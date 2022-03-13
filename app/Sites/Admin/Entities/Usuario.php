<?php

namespace App\Sites\Admin\Entities;

class Usuario
{
    private ?int $id;
    private ?string $nome;
    private ?string $usuario;
    private ?string $senha;
    private ?int $status;
    private ?int $permissao;
    private ?string $data;

    public function __construct(
        int $id = null,
        string $nome = null,
        string $usuario = null,
        string $senha = null,
        ?int $status = 2,
        ?int $permissao = 2,
        string $data = null
    ) {
        $this->id           = $id;
        $this->nome         = $nome;
        $this->usuario      = $usuario;
        $this->senha        = $senha;
        $this->status       = $status;
        $this->permissao    = $permissao;
        $this->data         = $data;
    }

    /**
     * Get the value of id
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get the value of nome
     */
    public function getNome(): ?string
    {
        return $this->nome;
    }

    /**
     * Get the value of usuario
     */
    public function getUsuario(): ?string
    {
        return strtolower($this->usuario);
    }

    /**
     * Get the value of senha
     */
    public function getSenha(): ?string
    {
        return $this->senha;
    }

     /**
     * Set the value of senha
     */
    public function setSenha(string $senha)
    {
        $this->senha = $senha;
    }

    /**
     * Get the value of status
     */
    public function getStatus(): ?int
    {
        if ($this->status < 1 || $this->status > 2) {
            return 2;
        }

        return $this->status;
    }

    /**
     * Get the value of permissao
     */
    public function getPermissao(): ?int
    {
        if ($this->permissao < 1 || $this->permissao > 2) {
            return 2;
        }

        return $this->permissao;
    }

    /**
     * Get the value of data
     */
    public function getData(): ?string
    {
        return $this->data;
    }

    /**
     * Recebe uma senha crua e retorna um valor em hash
     *
     * @param   string  $senha  Senha original
     * @return  string          Retorna o hash
     */
    public static function hashPassword(string $senha): ?string
    {
        return password_hash($senha, PASSWORD_BCRYPT);
    }
}
