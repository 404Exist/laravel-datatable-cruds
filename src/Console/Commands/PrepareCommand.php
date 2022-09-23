<?php

namespace Exist404\DatatableCruds\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class PrepareCommand extends Command
{
    protected $name;

    protected $signature = 'datatablecruds:prepare {name} {--M|middleware=*} {--P|prefix=}';

    protected $description = 'Preparing datatablecruds';

    public function handle()
    {
        $this->name = strtolower(str_replace('/', '\\', $this->argument('name')));
        $this->info('Preparing DatatableCruds for ' . $this->getClassName() . ' Model...');
        if (!File::exists($this->getModelPath())) {
            $this->call('make:model', [
                'name' => $this->argument('name'),
                '-m' => true,
                '-f' => true,
            ]);
        }
        $this->makeRoutes();
        $this->info('Routes created successfully: web.php');
        $this->makeController();
        $this->info('Controller created successfully: ' . $this->getClassName() . 'Controller');
        $this->info('Preparation finished successfully. 🏁');
        $this->line('----------------------------------------------------');
        if (!File::exists($this->getModelPath())) {
            $this->info("Now add table columns in migration file , run php artisan migrate.");
        }
        $this->info('Url : ' . $this->url());
        $this->line('----------------------------------------------------');
    }

    protected function makeController()
    {
        if (!is_dir($this->getDir())) {
            mkdir($this->getDir(), 0777, true);
        }
        $controller = str_replace(
            ['{{namespace}}', '{{modelNameSpace}}', '{{class}}'],
            [$this->getNamespace(), $this->getModelNameSpace(), $this->getClassName()],
            file_get_contents(__DIR__ . '/../../../stubs/controller.stub')
        );
        File::put($this->getDir() . '/' . $this->getClassName() . 'Controller.php', $controller);
    }

    protected function makeRoutes()
    {
        $web = str_replace(
            ['{{namespace}}', '{{routepath}}', '{{class}}', '{{routename}}', '{{middleware}}', '{{prefix}}'],
            [
                $this->getNamespace(),
                str_replace('\\', '/', $this->name),
                $this->getClassName(),
                str_replace('\\', '.', $this->name),
                $this->middleWare(),
                $this->prefix(),
            ],
            file_get_contents(__DIR__ . '/../../../stubs/web.stub')
        );
        File::append(base_path('routes/web.php'), $web);
    }

    protected function middleWare()
    {
        $middleware = "->middleware([";
        foreach ($this->option('middleware') as $value) {
            $middleware .= "'" . $value . "', ";
        }
        return $this->option('middleware') ? rtrim($middleware, ', ') . '])' : '';
    }

    protected function prefix()
    {
        return $this->option('prefix') ? '->prefix("' . $this->option('prefix') . '")' : '';
    }

    protected function url()
    {
        return url($this->option('prefix') . '/' . str_replace('\\', '/', $this->name));
    }

    protected function getNameSpace($for = 'Http\\Controllers')
    {
        return rtrim('App\\' . $for . '\\' . str_ireplace($this->getClassName(), '', $this->name), '\\');
    }

    protected function getDir($for = 'Http\\Controllers')
    {
        return str_replace('\\', '/', app_path(str_ireplace('App\\', '', $this->getNameSpace($for))));
    }

    protected function getClassName()
    {
        $name = explode('\\', $this->name)[count(explode('\\', $this->name)) - 1];
        return ucwords($name);
    }

    protected function getModelPath()
    {
        return $this->getDir('Models') . '\\'  . $this->getClassName() . '.php';
    }

    protected function getModelNameSpace()
    {
        return $this->getNameSpace('Models') . '\\' . $this->getClassName();
    }
}
