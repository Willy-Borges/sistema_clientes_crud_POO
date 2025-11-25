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
$celular = trim($_POST['cel'] ?? "");
$celLimpo = preg_replace('/[^0-9]/', '', $celular);

if ($nome === "" || $cpf === "" || $celLimpo === "" || $email === "") {
    echo json_encode([
        "status" => "erro",
        "mensagem" => "Nome, CPF, Celular e Email são obrigatórios."
    ]);
    exit;
}


if (!preg_match("/^[A-Za-zÀ-ÖØ-öø-ÿ\s'-]+$/u", $nome)) {
    echo json_encode([
        "status" => "erro",
        "mensagem" => "Nome inválido. Use apenas letras e espaços."
    ]);
    exit;
}

// validar CPF

$cpfLimpo = preg_replace('/[^0-9]/', '', $cpf);

// precisa ter 11 dígitos
if (strlen($cpfLimpo) !== 11) {
    echo json_encode([
        "status" => "erro",
        "mensagem" => "CPF inválido. Deve conter 11 números."
    ]);
    exit;
}

if (preg_match('/^(\\d)\\1{10}$/', $cpfLimpo)) {
    echo json_encode([
        "status" => "erro",
        "mensagem" => "CPF inválido."
    ]);
    exit;
}


function validarCPF($cpf) {
    for ($t = 9; $t < 11; $t++) {
        $d = 0;
        for ($c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$t] != $d) {
            return false;
        }
    }
    return true;
}

if (!validarCPF($cpfLimpo)) {
    echo json_encode([
        "status" => "erro",
        "mensagem" => "CPF inválido."
    ]);
    exit;
}
//--------------------------
// validar cel

$celLimpo = preg_replace('/[^0-9]/', '', $celular);

if ($celLimpo === "") {
    echo json_encode([
        "status" => "erro",
        "mensagem" => "Celular é obrigatório."
    ]);
    exit;
}

if (strlen($celLimpo) !== 11) {
    echo json_encode([
        "status" => "erro",
        "mensagem" => "Celular inválido. Deve conter 11 números."
    ]);
    exit;
}
if (preg_match('/^(\d)\1{10}$/', $celLimpo)) {
    echo json_encode([
        "status" => "erro",
        "mensagem" => "Celular inválido."
    ]);
    exit;
}
$celular = $celLimpo;

if ($celLimpo === "01234567890") {
    echo json_encode([
        "status" => "erro",
        "mensagem" => "Celular inválido."
    ]);
    exit;
}

$celular = $celLimpo;
// -------------------------
// ===== VALIDAR CEP =====
$cepLimpo = preg_replace('/[^0-9]/', '', $cep);

if ($cep !== "" && strlen($cepLimpo) !== 8) {
    echo json_encode([
        "status" => "erro",
        "mensagem" => "CEP inválido. Deve conter 8 números."
    ]);
    exit;
}
//----------------------------
// validar email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        "status" => "erro",
        "mensagem" => "Email inválido. Informe um endereço válido."
    ]);
    exit;
}


if ($data_nascimento !== "" && preg_match("/^\d{2}\/\d{2}\/\d{4}$/", $data_nascimento)) {
    $p = explode("/", $data_nascimento);
    $data_nascimento = $p[2] . "-" . $p[1] . "-" . $p[0];
}

//----------------------------


// converter data dd/mm/yyyy → yyyy-mm-dd
if ($data_nascimento !== "" && preg_match("/^\d{2}\/\d{2}\/\d{4}$/", $data_nascimento)) {
    $p = explode("/", $data_nascimento);
    $data_nascimento = $p[2] . "-" . $p[1] . "-" . $p[0];
}


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
