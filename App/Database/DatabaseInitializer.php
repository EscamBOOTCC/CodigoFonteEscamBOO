<?php

namespace App\Database;

use PDO;
use Exception;

class DatabaseInitializer
{
    public function init(PDO $connection): void //inicializa o banco de dados, criando o banco de dados e as tabelas necessárias
    {
        
        $connection->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        
        
        $connection->exec("USE " . DB_NAME);

        $scriptPath = __DIR__ . '/Scripts/ScriptBD.sql';

        if (file_exists($scriptPath)) {
            $sql = file_get_contents($scriptPath);

            try {
                
                
                $connection->exec($sql);
            } catch (Exception $e) {
                error_log("Erro ao inicializar banco de dados: " . $e->getMessage());
                throw new Exception("Erro ao executar o script SQL: " . $e->getMessage());
            }
        } else {
            throw new Exception("O arquivo de script em '{$scriptPath}' não foi encontrado.");
        }

        $this->criarADMs($connection);
    }

    public function criarADMs(PDO $conn): void //adms pre definidos pelo sistema, para que o sistema tenha pelo menos um adm cadastrado
    {
        
        $conn->exec("USE " . DB_NAME);

        $administradores = [
            [
                'nome'  => 'admin',
                'cpf'   => '00000000009',
                'email' => 'admin@admin',
                'senha' => password_hash("admin", PASSWORD_BCRYPT) //hash da senha para que não seja armazenada em texto puro no banco de dados
            ],
            [
                'nome'  => 'Luiza',
                'cpf'   => '00000000001',
                'email' => 'Luiza@admin',
                'senha' => password_hash("Luiza", PASSWORD_BCRYPT)
            ],
            [
                'nome'  => 'Rafa',
                'cpf'   => '00000000002',
                'email' => 'Rafa@admin',
                'senha' => password_hash("Rafa", PASSWORD_BCRYPT)
            ],
            [
                'nome'  => 'Evillyn',
                'cpf'   => '00000000003',
                'email' => 'Evillyn@admin',
                'senha' => password_hash("Evillyn", PASSWORD_BCRYPT)
            ],
            [
                'nome'  => 'Sarah',
                'cpf'   => '00000000004',
                'email' => 'Sarah@admin',
                'senha' => password_hash("Sarah", PASSWORD_BCRYPT)
            ]
        ];

        $sqlAdm = "INSERT INTO ADM (Nome, CPF, Genero, Email, Senha, DataNascimento, FotoPerfil, Endereco, Ativo) 
                   VALUES (:nome, :cpf, NULL, :email, :senha, NULL, NULL, NULL, 'ATIVO')
                   ON DUPLICATE KEY UPDATE Email = VALUES(Email)";

        $stmtAdm = $conn->prepare($sqlAdm);

        foreach ($administradores as $adm) {
            $stmtAdm->execute([
                ':nome'  => $adm['nome'],
                ':cpf'   => $adm['cpf'],
                ':email' => $adm['email'],
                ':senha' => $adm['senha']
            ]);
        }
    }
}