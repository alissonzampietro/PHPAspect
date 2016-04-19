<?php

require_once("vendor/autoload.php");

use Aspecto\Aspect\ApplicationAspectKernel;
use Aspecto\Model\Contato;
use Aspecto\Repository\ConnectionFactory;
use Aspecto\Repository\ContatoRepository;

$applicationAspectKernel = ApplicationAspectKernel::getInstance();
$applicationAspectKernel->init(array(
        'debug' => true, // use 'false' for production mode
        // Cache directory
        'cacheDir'  => __DIR__ . 'tmp/',
        // Include paths restricts the directories where aspects should be applied, or empty for all source files
        'includePaths' => array(
            __DIR__ . '/src/'
        )
));

$conn = ConnectionFactory::getConnection();
$repository = new ContatoRepository($conn);

$contato = new Contato();
$contato->setNome("ricardo");

$repository->persist($contato);