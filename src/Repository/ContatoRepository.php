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
		$this->conn->beginTransaction();
		$stmt = $this->conn->prepare("INSERT INTO contato (nome) VALUES (:nome)");
		$stmt->bindParam(":nome", 'teste');
		$stmt->execute();
		$this->conn->commit();
	}

}