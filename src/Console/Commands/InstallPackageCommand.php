<?php

namespace Exist404\DatatableCruds\Console\Commands;

use Illuminate\Console\Command;

class InstallPackageCommand extends Command
{
    protected $signature = 'datatablecruds:install';

    protected $description = 'Install datatablecruds package';

    public function handle()
    {
        $this->info('Installing DatatableCruds...');
        $this->call('vendor:publish', [
            '--provider' => 'Exist404\DatatableCruds\DatatableCrudsProvider',
            '--force' => true
        ]);
        $this->info('DatatableCruds Instalation finished successfully. 🏁');
    }
}
