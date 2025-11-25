<?php
namespace App\Controllers;

use App\DAO\ClienteDAO;
use App\Models\Cliente;

class ClienteController
{
    private $dao;

    public function __construct()
    {
        $this->dao = new ClienteDAO();
    }

    public function salvar(array $dados)
    {
        // garante id se vier como codigo
        if (!empty($dados['codigo'])) {
            $dados['id'] = (int)$dados['codigo'];
        }

        $cliente = new Cliente($dados);

        if (!empty($cliente->id)) {
            return $this->dao->editar($cliente);
        }

        return $this->dao->salvar($cliente);
    }

    public function editar(array $dados)
    {
        if (!empty($dados['codigo'])) {
            $dados['id'] = (int)$dados['codigo'];
        }

        $cliente = new Cliente($dados);
        return $this->dao->editar($cliente);
    }

    public function excluir($codigo)
    {
        return $this->dao->excluir($codigo);
    }

    public function buscar($codigo)
    {
        return $this->dao->buscar($codigo);
    }

    public function pesquisar($filtros)
    {
        return $this->dao->pesquisar($filtros);
    }

    public function listar()
    {
        return $this->dao->listarTodos();
    }
}
