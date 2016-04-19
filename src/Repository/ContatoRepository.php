<?php

namespace Aspecto\Repository;

use PDO;
use Aspecto\Model\Contato;

class ContatoRepository {

	protected $conn;

	public function __construct(PDO $conn) {
		$this->conn = $conn;
	}

	public function persist(Contato $contato) {
		$sql = "INSERT INTO contatos (nome) VALUES (:nome)";
		$nomeContato = $contato->getNome();

		$stmt = $this->conn->prepare($sql);
		$stmt->bindParam(":nome", $nomeContato, PDO::PARAM_STR);
		$stmt->execute();
	}

}