<?php
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . "/../app/Database/Conexao.php";
require_once __DIR__ . "/../app/DAO/ClienteDAO.php";
require_once __DIR__ . "/../app/Models/Cliente.php";
require_once __DIR__ . "/../app/Controllers/ClienteController.php";

use App\Controllers\ClienteController;

$id              = trim($_POST['id'] ?? "");
$nome            = trim($_POST['nome'] ?? "");
$cpf             = trim($_POST['cpf'] ?? "");
$sexo            = trim($_POST['sexo'] ?? "");
$data_nascimento = trim($_POST['data_nascimento'] ?? "");
$profissao       = trim($_POST['profissao'] ?? "");
$logradouro      = trim($_POST['logradouro'] ?? "");
$numero          = trim($_POST['numero'] ?? "");
$bairro          = trim($_POST['bairro'] ?? "");
$cidade          = trim($_POST['cidade'] ?? "");
$uf              = trim($_POST['uf'] ?? "");
$cep             = trim($_POST['cep'] ?? "");
$celular         = trim($_POST['cel'] ?? "");
$email           = trim($_POST['email'] ?? "");

// validações
if ($nome === "" || $cpf === "" || $celular === "" || $email === "") {
    echo json_encode([
        "status" => "erro",
        "mensagem" => "Nome, CPF, Celular e Email são obrigatórios."
    ]);
    exit;
}

// converter data dd/mm/yyyy → yyyy-mm-dd
if ($data_nascimento !== "" && preg_match("/^\d{2}\/\d{2}\/\d{4}$/", $data_nascimento)) {
    $p = explode("/", $data_nascimento);
    $data_nascimento = $p[2] . "-" . $p[1] . "-" . $p[0];
}

// montar array igual ao controller espera
$dados = [
    "id" => $id,
    "nome" => $nome,
    "cpf" => $cpf,
    "sexo" => $sexo,
    "data_nascimento" => $data_nascimento,
    "profissao" => $profissao,
    "logradouro" => $logradouro,
    "numero" => $numero,
    "bairro" => $bairro,
    "cidade" => $cidade,
    "cep" => $cep,
    "celular" => $celular,
    "email" => $email,
    "uf" => $uf
];

$ctrl = new ClienteController();
$ret = $ctrl->salvar($dados);

echo json_encode($ret);
