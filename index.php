<?php

require_once("vendor/autoload.php");

use PDO;
use Aspecto\Model\Contato;
use Aspecto\Repository\ContatoRepository;

$conn = new PDO("mysql:host=localhost;dbname=contatos_goaop", "root", "123456");
$repository = new ContatoRepository($conn);

$contato = new Contato();
$contato->setNome("ricardo");

$repository->persist($contato);