<?php
require_once __DIR__ . "/../app/Database/Conexao.php";
require_once __DIR__ . "/../app/DAO/ClienteDAO.php";
require_once __DIR__ . "/../app/Controllers/ClienteController.php";

use App\Controllers\ClienteController;

header("Content-Type: application/json");

// aceitar POST form-data com campos: codigo, nome, cpf, cel, email
$codigo = $_POST['codigo'] ?? null;
$nome = $_POST['nome'] ?? null;
$cpf = $_POST['cpf'] ?? null;
$celular = $_POST['celular'] ?? null;
$email = $_POST['email'] ?? null;

$ctrl = new ClienteController();

// se codigo informado -> buscar único
if (!empty($codigo)) {
    $cliente = $ctrl->buscar((int)$codigo);
    if (!$cliente) {
        echo json_encode(["status" => "erro", "msg" => "Cliente não encontrado."]);
        exit;
    }
    echo json_encode(["status" => "ok", "cliente" => $cliente]);
    exit;
}

// montar filtros para pesquisa
$filtros = [
    'nome' => $nome,
    'cpf'  => $cpf,
    'celular'  => $celular,
    'email'=> $email
];

$resultado = $ctrl->pesquisar($filtros);

if (!$resultado || count($resultado) === 0) {
    echo json_encode(["status" => "erro", "msg" => "Nenhum cliente encontrado."]);
    exit;
}

if (count($resultado) > 1) {
    echo json_encode(["status" => "lista", "clientes" => $resultado]);
    exit;
}

// exatamente 1 registro (though pesquisar returns rows of id,nome,cpf)
echo json_encode(["status" => "ok", "cliente" => $resultado[0]]);
