<?php
require_once __DIR__ . "/../app/Database/Conexao.php";
require_once __DIR__ . "/../app/DAO/ClienteDAO.php";
require_once __DIR__ . "/../app/Controllers/ClienteController.php";

use App\Controllers\ClienteController;

header("Content-Type: application/json");

$ctrl = new ClienteController();
$lista = $ctrl->listar();

if (!$lista || count($lista) === 0) {
    echo json_encode(["status" => "vazio", "msg" => "Nenhum cliente cadastrado."]);
    exit;
}

echo json_encode(["status" => "ok", "clientes" => $lista]);
