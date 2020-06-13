<?php

namespace App\Component\Commands;

use App\Component\Tools\MigrationCreator;
use Illuminate\Support\Composer;

class MigrateMakeCommand extends \Illuminate\Database\Console\Migrations\MigrateMakeCommand
{
    /**
     * Create a new migration install command instance.
     *
     * @param MigrationCreator $creator
     * @param Composer $composer
     */
    public function __construct(MigrationCreator $creator, Composer $composer)
    {
        parent::__construct($creator, $composer);
    }
}