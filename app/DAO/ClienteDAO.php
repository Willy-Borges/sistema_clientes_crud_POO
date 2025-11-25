<?php
namespace App\DAO;

use App\Database\Conexao;
use App\Models\Cliente;
use PDO;
use Exception;

class ClienteDAO
{
    private $db;

    public function __construct()
    {
        $this->db = Conexao::getConexao();
    }

    /* salvar (insert) - retorna array com status, id inserido e mensagem */
    public function salvar(Cliente $c)
    {
        $sql = "INSERT INTO clientes (nome, cpf, sexo, data_nascimento, profissao,
                logradouro, numero, bairro, cidade, uf, cep, celular, email)
                VALUES (:nome, :cpf, :sexo, :data_nascimento, :profissao,
                        :logradouro, :numero, :bairro, :cidade, :uf, :cep, :celular, :email)";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':nome', $c->nome);
        $stmt->bindValue(':cpf', $c->cpf);
        $stmt->bindValue(':sexo', $c->sexo);
        $stmt->bindValue(':data_nascimento', $c->data_nascimento);
        $stmt->bindValue(':profissao', $c->profissao);
        $stmt->bindValue(':logradouro', $c->logradouro);
        $stmt->bindValue(':numero', $c->numero);
        $stmt->bindValue(':bairro', $c->bairro);
        $stmt->bindValue(':cidade', $c->cidade);
        $stmt->bindValue(':uf', $c->uf);
        $stmt->bindValue(':cep', $c->cep);
        $stmt->bindValue(':celular', $c->celular);
        $stmt->bindValue(':email', $c->email);

        if ($stmt->execute()) {
            return [
                'status' => 'sucesso',
                'id' => (int)$this->db->lastInsertId(),
                'mensagem' => 'Cliente cadastrado com sucesso!'
            ];
        }

        // tentar obter mensagem mais descritiva do PDO
        $err = $stmt->errorInfo();
        $mensagem = isset($err[2]) && $err[2] ? $err[2] : 'Erro ao inserir cliente.';

        return ['status' => 'erro', 'msg' => $mensagem, 'mensagem' => $mensagem];
    }

    /* editar (update) - usa id */
    public function editar(Cliente $c)
    {
        if (empty($c->id)) {
            $mensagem = 'Código (id) obrigatório para editar.';
            return ['status' => 'erro', 'msg' => $mensagem, 'mensagem' => $mensagem];
        }

        $sql = "UPDATE clientes SET
                    nome = :nome,
                    cpf = :cpf,
                    sexo = :sexo,
                    data_nascimento = :data_nascimento,
                    profissao = :profissao,
                    logradouro = :logradouro,
                    numero = :numero,
                    bairro = :bairro,
                    cidade = :cidade,
                    uf = :uf,
                    cep = :cep,
                    celular = :celular,
                    email = :email
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':nome', $c->nome);
        $stmt->bindValue(':cpf', $c->cpf);
        $stmt->bindValue(':sexo', $c->sexo);
        $stmt->bindValue(':data_nascimento', $c->data_nascimento);
        $stmt->bindValue(':profissao', $c->profissao);
        $stmt->bindValue(':logradouro', $c->logradouro);
        $stmt->bindValue(':numero', $c->numero);
        $stmt->bindValue(':bairro', $c->bairro);
        $stmt->bindValue(':cidade', $c->cidade);
        $stmt->bindValue(':uf', $c->uf);
        $stmt->bindValue(':cep', $c->cep);
        $stmt->bindValue(':celular', $c->celular);
        $stmt->bindValue(':email', $c->email);
        $stmt->bindValue(':id', $c->id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return ['status' => 'sucesso', 'mensagem' => 'Cliente atualizado com sucesso.'];
        }

        $err = $stmt->errorInfo();
        $mensagem = isset($err[2]) && $err[2] ? $err[2] : 'Erro ao atualizar cliente.';

        return ['status' => 'erro', 'msg' => $mensagem, 'mensagem' => $mensagem];
    }

    /* excluir por codigo/id */
    public function excluir($codigo)
    {
        $sql = "DELETE FROM clientes WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $codigo, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return ['status' => 'sucesso', 'mensagem' => 'Cliente excluído com sucesso.'];
        }

        $err = $stmt->errorInfo();
        $mensagem = isset($err[2]) && $err[2] ? $err[2] : 'Erro ao excluir cliente.';
        return ['status' => 'erro', 'msg' => $mensagem, 'mensagem' => $mensagem];
    }

    /* buscar por id */
    public function buscar($codigo)
    {
        $sql = "SELECT id, nome, cpf, sexo, data_nascimento, profissao,
                       logradouro, numero, bairro, cidade, uf, cep, celular, email
                FROM clientes WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $codigo, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row : null;
    }

    /* pesquisar por múltiplos campos (campos opcionais em array) */
    public function pesquisar(array $filtros)
    {
        // montar cláusula dinâmica
        $where = [];
        $params = [];

        if (!empty($filtros['codigo'])) {
            $where[] = "id = :id";
            $params[':id'] = (int)$filtros['codigo'];
        }
        if (!empty($filtros['nome'])) {
            $where[] = "nome LIKE :nome";
            $params[':nome'] = '%' . $filtros['nome'] . '%';
        }
        if (!empty($filtros['cpf'])) {
            $where[] = "cpf LIKE :cpf";
            $params[':cpf'] = '%' . $filtros['cpf'] . '%';
        }
        if (!empty($filtros['cel'])) {
            $where[] = "celular LIKE :celular";
            $params[':celular'] = '%' . preg_replace('/\D/', '', $filtros['cel']) . '%';
        }
        if (!empty($filtros['email'])) {
            $where[] = "email LIKE :email";
            $params[':email'] = '%' . $filtros['email'] . '%';
        }

        if (empty($where)) {
            return []; // sem critérios
        }

        $sql = "SELECT id, nome, cpf FROM clientes WHERE " . implode(" OR ", $where) . " ORDER BY id ASC";
        $stmt = $this->db->prepare($sql);

        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* listar todos (retorna array de assoc) */
    public function listarTodos()
    {
        $sql = "SELECT id, nome, cpf FROM clientes ORDER BY id ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
