<?php

namespace App\Component\Extensions;

trait MakeCommandExtension
{
    /**
     * Get the migration stub file.
     *
     * @param string|null $table
     * @param bool $create
     * @return string
     */
    protected function getStub($table = null, $create = null)
    {
        // Check if command is a migration command or not.
        if (basename(__CLASS__) != 'MigrationCreator') {
            // It's not a migration command
            return components('COMPONENTS_DIR') . '/stubs/' . mb_strtolower($this->type) . '.stub';
        }
        // It's a migration command
        if (is_null($table)) {
            return $this->files->get(components('COMPONENTS_DIR') . 'stubs/blank.stub');
        }

        // We also have stubs for creating new tables and modifying existing tables
        // to save the developer some typing when they are creating a new tables
        // or modifying existing tables. We'll grab the appropriate stub here.
        $stub = $create ? 'create.stub' : 'update.stub';

        return $this->files->get(components('COMPONENTS_DIR') . "stubs/{$stub}");
    }
}