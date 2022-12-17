<?php

namespace Exist404\DatatableCruds\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class NewDatatableCrudsCommand extends Command
{
    protected $name;

    protected $filePath;

    protected $signature = 'datatablecruds:for {name}';

    protected $description = 'Preparing datatablecruds';

    public function handle()
    {
        $this->name = strtolower(str_replace('/', '\\', $this->argument('name')));

        $this->filePath = $this->getDir() . '\\' . $this->getClass()  . 'DatatableCruds.php';

        if (! file_exists($this->filePath)) {
            $this->info('Preparing DatatableCruds for ' . $this->getClass() . ' Model...');
            $this->line('----------------------------------------------------');
            $this->createDatatableCrudsFile();
            $this->info('DatatableCruds created successfully: ' . $this->getClass() . 'DatatableCruds 🏁');
        } else {
            $this->info('DatatableCruds for ' . $this->getClass() . ' Model already exists!');
        }
    }

    protected function createDatatableCrudsFile(): void
    {
        if (!is_dir($this->getDir())) {
            mkdir($this->getDir(), 0777, true);
        }

        $content = str_replace(
            ['{{namespace}}', '{{modelNameSpace}}', '{{class}}'],
            [$this->getNamespace('Datatables'), $this->getModelNameSpace(), $this->getClass()],
            file_get_contents(__DIR__ . '/../../../stubs/DatatableCruds.stub')
        );

        File::put($this->filePath, $content);
    }

    protected function getNameSpace(string $for): string
    {
        return rtrim("App\\$for\\" . str_ireplace($this->getClass(), '', $this->name), '\\');
    }

    protected function getDir(): string
    {
        return app_path(str_ireplace('App\\', '', $this->getNameSpace('Datatables')));
    }

    protected function getClass(): string
    {
        $name = $this->name;
        $name = explode('\\', $name)[count(explode('\\', $name)) - 1];
        return ucwords($name);
    }

    protected function getModelNameSpace(): string
    {
        return $this->getNameSpace('Models') . '\\' . $this->getClass();
    }
}
