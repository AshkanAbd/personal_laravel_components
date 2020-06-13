<?php

namespace App\Component\Commands;

use App\Component\Extensions\MakeCommandExtension;

class ModelMakeCommand extends \Illuminate\Foundation\Console\ModelMakeCommand
{
    use MakeCommandExtension;
}