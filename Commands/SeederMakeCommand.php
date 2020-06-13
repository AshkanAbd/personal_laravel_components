<?php

namespace App\Component\Commands;

use App\Component\Extensions\MakeCommandExtension;

class SeederMakeCommand extends \Illuminate\Database\Console\Seeds\SeederMakeCommand
{
    use MakeCommandExtension;

    private $model;

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameInput()
    {
        $trimName = trim($this->argument('name'));
        if (ends_with($trimName, 'Seeder')) {
            $this->model = str_replace('Seeder', '', $trimName);
        } else {
            $this->model = $trimName;
            $trimName .= 'Seeder';
        }
        return $trimName;
    }

    /**
     * Get the destination class path.
     *
     * @param string $name
     * @return string
     */
    protected function getPath($name)
    {
        return "{$this->laravel->databasePath()}/seeds/" . now()->timestamp . "_{$name}.php";
    }

    /**
     * Build the class with the given name.
     *
     * @param string $name
     * @return string
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        return $this->replaceModel($stub, $name)->replaceNamespace($stub, $name)->replaceClass($stub, $name);
    }

    /**
     * Replace the model for the given stub.
     *
     * @param string $stub
     * @param string $name
     * @return $this
     */
    protected function replaceModel(&$stub, $name)
    {
        $stub = str_replace('DummyModel', $this->model, $stub);
        return $this;
    }
}
