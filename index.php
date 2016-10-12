<?php

require_once('vendor/autoload.php');

use Aspect\ApplicationAspectKernel;
use Aspect\ClasseA;
use Aspect\ClasseB;

$applicationAspectKernel = ApplicationAspectKernel::getInstance();
$applicationAspectKernel->init(array(
		'cacheDir' => '/tmp/',
        'includePaths' => array(
            __DIR__ . '/src/'
        )
));

$a = new ClasseA();
$a->executa();

$b = new ClasseB();
$b->executa();