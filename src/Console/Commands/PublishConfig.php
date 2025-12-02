<?php

namespace Denizgolbas\EloquentSaveTogether\Console\Commands;

use Illuminate\Console\Command;

class PublishConfig extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eloquent-save-together:publish-config';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish the Eloquent Save Together configuration file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->call('vendor:publish', [
            '--tag' => 'eloquent-save-together-config',
        ]);

        $this->info('Eloquent Save Together configuration published successfully!');
        $this->info('You can now customize the relation mappings in config/eloquent-save-together.php');
    }
}