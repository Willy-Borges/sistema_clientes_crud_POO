<?php
require_once __DIR__ . "/../app/Database/Conexao.php";
require_once __DIR__ . "/../app/DAO/ClienteDAO.php";
require_once __DIR__ . "/../app/Models/Cliente.php";
require_once __DIR__ . "/../app/Controllers/ClienteController.php";

use App\Controllers\ClienteController;

header("Content-Type: application/json");

$raw = file_get_contents('php://input');
$dados = json_decode($raw, true);

if (!$dados) {
    echo json_encode(["status" => "erro", "mensagem" => "Dados inválidos."]);
    exit;
}

// mapeia codigo -> id somente uma vez
if (!empty($dados['codigo'])) {
    $dados['id'] = (int)$dados['codigo'];
}

$ctrl = new ClienteController();
$ret = $ctrl->editar($dados);

echo json_encode($ret);
