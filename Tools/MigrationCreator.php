<?php

namespace App\Component\Tools;

use App\Component\Extensions\MakeCommandExtension;

class MigrationCreator extends \Illuminate\Database\Migrations\MigrationCreator
{
    use MakeCommandExtension;
}