<?php

require __DIR__ . '/vendor/autoload.php';

use TeamGenerator\CLI;
use TeamGenerator\Generator\Generator;

(new CLI(new Generator))->run();