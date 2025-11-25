<?php
namespace App\Models;

class Cliente
{
    public $id;
    public $nome;
    public $cpf;
    public $sexo;
    public $data_nascimento;
    public $profissao;
    public $logradouro;
    public $numero;
    public $bairro;
    public $cidade;
    public $uf;
    public $cep;
    public $celular;
    public $email;

    public function __construct(array $dados = [])
    {
        foreach ($dados as $campo => $valor) {
            if (property_exists($this, $campo)) {
                $this->$campo = $valor;
            }
        }
    }
}
