<?php

namespace App\Component\Extensions;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Seeder;

trait SeederExtension
{
    /**
     * Find classes in given $dir
     *
     * @param string $dir
     * @return array
     */
    protected function getClasses($dir)
    {
        $preClasses = get_declared_classes();
        $files = scandir($dir);
        sort($files);
        foreach ($files as $file) {
            if ($file == '.' || $file == '..') continue;
            require_once "{$dir}/{$file}";
        }
        return array_values(array_diff(get_declared_classes(), $preClasses));
    }

    /**
     * Run seeder files in given $dir
     *
     * @param string $dir
     * @throws BindingResolutionException
     */
    protected function runSeeders($dir = './database/seeds')
    {
        $seeders = $this->getClasses($dir);
        foreach ($seeders as $seeder) {
            if (method_exists($seeder, 'run')) {
                $this->checkEnvironment($seeder);
            }
        }
    }

    /**
     * Seed the given connection from the given path.
     *
     * @param array|string $class
     * @param bool $silent
     * @return Seeder
     * @throws BindingResolutionException
     */
    public function call($class, $silent = false)
    {
        $seeder = app()->make($class);
        if ($seeder->model && (app()->make($seeder->model))::query()->count()) {
            echo "$seeder->model currently seeded.\n";
            return;
        }
        return parent::call($class, $silent = false);
    }

    /**
     * @param string $seeder
     * @throws BindingResolutionException
     */
    public function checkEnvironment($seeder)
    {
        if (property_exists($seeder, 'production')) {
            if (app()->environment() == 'production') {
                if ((app()->make($seeder))->production === true) {
                    $this->call($seeder);
                } else {
                    echo "$seeder shouldn't run on production.\n";
                }
            } else {
                $this->call($seeder);
            }
        } else {
            $this->call($seeder);
        }
    }
}