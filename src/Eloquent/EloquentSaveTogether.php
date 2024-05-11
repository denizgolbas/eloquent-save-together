<?php

namespace Denizgolbas\EloquentSaveTogether\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

trait EloquentSaveTogether
{
    protected array $togetherRequestKeys;
    protected array $relatedArray;

    public function initializeHasTogether(): void
    {
        if (isset($this->together) && is_array($this->together))
        {
            foreach ($this->together as $key => $relation)
            {
                if (is_bool($relation))
                {
                    $this->togetherRequestKeys[Str::snake($key)] = $relation;
                }
                else
                {
                    $this->togetherRequestKeys[Str::snake($relation)] = false;
                }
            }
        }
    }

    public function fillTogether(array $datas): self
    {
        $this->fill(Arr::except($datas, array_keys($this->togetherRequestKeys)));

        foreach ($this->together ?? [] as $key => $relation)
        {
            if (is_bool($relation))
            {
                $rel = $key;
            }
            else
            {
                $rel = $relation;
            }

            if (method_exists(self::class, $rel))
            {
                $requestKey = Str::snake($rel);
                match (get_class($this->{$rel}()))
                {
                    HasOne::class,
                    MorphTo::class => $this->fillOneRecord($datas, $requestKey, $rel),
                    default        => $this->fillMultiRecord($datas, $requestKey, $rel)
                };
            }
        }

        return $this;
    }

    public function saveTogether(): Collection
    {
        $this->save();

        foreach ($this->relatedArray as $key => $relation)
        {
            if ($relation instanceof Collection)
            {
                $this->saveMultiRecord($relation, $key);
            }
            elseif ($relation instanceof Model)
            {
                $this->saveOneRecord($relation, $key);
            }
        }

        return new Collection();
    }

    protected function shouldDelete(string $key)
    {
        return $this->togetherRequestKeys[Str::snake($key)] ?? false;
    }

    protected function fillMultiRecord($datas, $requestKey, $rel)
    {
        $collection = new Collection();
        $modelClass = $this->{$rel}()->getRelated();

        foreach ($datas[$requestKey] ?? [] as $data)
        {
            $model = isset($data['id']) ? $modelClass::find($data['id']) ?? new $modelClass() : new $modelClass();

            if (in_array(self::class, class_uses($modelClass), true))
            {
                $model->fillTogether($data);
            }
            else
            {
                $model->fill($data);
            }
            $collection->push($model);
        }
        $this->relatedArray[$rel] = $collection;
    }

    protected function fillOneRecord($datas, $requestKey, $rel)
    {
        if (isset($datas[$requestKey]))
        {
            $modelClass = $this->{$rel}()->getRelated();
            $model = isset($datas[$requestKey]['id']) ? $modelClass::find($datas[$requestKey]['id']) ?? new $modelClass() : new $modelClass();
            $model->fill($datas[$requestKey]);

            if (in_array(self::class, class_uses($modelClass), true))
            {
                $model->fillTogether($datas[$requestKey]);
            }
            else
            {
                $model->fill($datas[$requestKey]);
            }
            $this->relatedArray[$rel] = $model;
        }
    }


    protected function saveMultiRecord($relation, $key)
    {
        if ($this->{$key}() instanceof MorphToMany)
        {
            if ($this->shouldDelete($key))
            {
                $this->{$key}()->detach();
            }
        }
        else
        {
            if ($this->shouldDelete($key))
            {
                $updateNeedNoDeleteOnes = array_filter($relation->pluck('id')->toArray() ?? []);
                $this->{$key}()->whereNotIn('id', $updateNeedNoDeleteOnes)->delete();
            }
        }

        foreach ($relation as $item)
        {
            $modelClass = $this->{$key}()->getRelated();

            if (in_array(self::class, class_uses($modelClass), true))
            {
                $sub_item = $this->{$key}()->save($item);
                $sub_item->saveTogether();
            }
            else
            {
                $this->{$key}()->save($item);
            }
        }
    }

    protected function saveOneRecord($relation, $key)
    {
        $modelClass = $this->{$key}()->getRelated();

        if (in_array(self::class, class_uses($modelClass), true))
        {
            $sub_item = $this->{$key}()->save($relation);
            $sub_item->saveTogether();
        }
        else
        {
            if ($this->shouldDelete($key))
            {
                $this->{$key}()->whereNot('id', $relation->id ?? null)->delete();
            }
            $this->{$key}()->save($relation);
        }
    }
}
