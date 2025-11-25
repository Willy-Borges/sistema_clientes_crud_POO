<?php
require_once __DIR__ . "/../app/Database/Conexao.php";
require_once __DIR__ . "/../app/DAO/ClienteDAO.php";
require_once __DIR__ . "/../app/Controllers/ClienteController.php";

use App\Controllers\ClienteController;

header("Content-Type: application/json; charset=utf-8");

// aceita 'codigo' ou 'id'
$codigo = $_POST['codigo'] ?? $_POST['id'] ?? null;

if (empty($codigo)) {
    echo json_encode([
        "status" => "erro",
        "mensagem" => "Código não informado."
    ]);
    exit;
}

$ctrl = new ClienteController();
$ret = $ctrl->excluir((int)$codigo);

// garante sempre um array com status e mensagem
if (!is_array($ret)) {
    $ret = [
        "status" => "erro",
        "mensagem" => "Retorno inválido do controller."
    ];
}

// renomeia a chave 'msg' para 'mensagem', caso exista
if (isset($ret["msg"])) {
    $ret["mensagem"] = $ret["msg"];
    unset($ret["msg"]);
}

echo json_encode($ret);
