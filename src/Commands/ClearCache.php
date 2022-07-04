<?php

namespace Chrisabey84\LaravelCachedOptionsList\Commands;

use Chrisabey84\LaravelCachedOptionsList\HasCachedOptionsList;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;

class ClearCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cached-options:clear {model : The model you wish to delete the cache for, including the fully qualified namespace}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear the cached options for the specified model';

    public function handle(): int
    {
        $model = $this->argument('model');

        if (class_exists($model) === false) {
            $this->error("Model '{$model}' does not exist. Please ensure you have included the fully qualified namespace.");

            return CommandAlias::FAILURE;
        }

        $traitClass = HasCachedOptionsList::class;

        if (in_array($traitClass, class_uses_recursive($model)) === false) {
            $this->error("Model '{$model}' is not using the '{$traitClass}' trait.");

            return CommandAlias::FAILURE;
        }

        $model::clearOptionsCache();

        $this->info("Options cache for model '{$model}' cleared successfully.");

        return CommandAlias::SUCCESS;
    }
}
