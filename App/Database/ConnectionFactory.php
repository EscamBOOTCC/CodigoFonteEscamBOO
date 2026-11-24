<?php 

namespace App\Database;

use Exception;
use PDO;

class ConnectionFactory 
{
    private static ?PDO $Connection = null; //guarda a instancia da conexão com o banco de dados

    public static function getConnection()
    {
        if (self::$Connection === null)  //verifica se a conexão já foi criada
        {
            try 
            {//tenta criar a conexão com o banco de dados
                
                $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
                self::$Connection = self::createConnection($dsn);
                
            }catch (Exception $e) 
            { //verifica se ocorreu algum erro ao tentar criar a conexão
                
                $dsnWithoutDb = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";charset=utf8mb4";
                
                try 
                {  //tenta criar a conexão sem especificar o banco de dados, caso o banco de dados não exista ainda
                    $initConnection = self::createConnection($dsnWithoutDb); //cria a conexão sem especificar o banco de dados

                    $databaseInit = new DatabaseInitializer(); //cria uma instância da classe DatabaseInitializer para inicializar o banco de dados
                    $databaseInit->init($initConnection); //inicializa o banco de dados, criando o banco de dados e as tabelas necessárias

                    
                    $dsnWithDb = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4"; //cria o DSN com o banco de dados especificado
                    self::$Connection = self::createConnection($dsnWithDb); //após a inicialização do banco de dados

                }catch (Exception $initError) 
                {
                    die("Falha crítica ao tentar criar e inicializar o banco de dados: " . $initError->getMessage());
                }
            }
        }

        return self::$Connection;
    }

    private static function createConnection(string $dsn) //cria a conexão com o banco de dados usando PDO
    {
        $options = [
            PDO::ATTR_ERRMODE                => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE     => PDO::FETCH_ASSOC,
        ];

        return new PDO($dsn, DB_USER, DB_PASS, $options); //retorna a instância da conexão com o banco de dados
    }
}