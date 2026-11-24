<?php

define('DEV_ENVIRONMENT', true); // habilita a exibição de erros no ambiente local

if (DEV_ENVIRONMENT == true) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} //verifica se está em ambiente de desenvolvimento e habilita a exibição de erros

if (session_status() === PHP_SESSION_NONE) {
    session_start();
} //verifica se a sessão já foi iniciada, caso não tenha sido, inicia a sessão

define('APP_NAME', 'EscamBOO'); //define banco de dados como EscamBOO, que é o nome do projeto
define('URL_BASE', 'http://localhost:8080'); //define a url base do projeto, seria o servidor local 

define('DB_HOST', 'localhost');
define('DB_PORT', 3306);
define('DB_NAME', 'EscamBOO'); //banco

define('DB_USER', 'euaquitlgd?'); //usuario do banco, obs: vcs precisam alterar pro usuario de vcs pra funcionar nas suas maquinas
define('DB_PASS', 'bancodedados'); //senha do banco de dados, obs: vcs precisam alterar pra senha de vcs pra funcionar nas suas maquinas

if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__, 2));
}

//esse é o arquivo de configuração do projeto, onde definimos as constantes que serão utilizadas em todo o projeto, como o nome do projeto, a url base, as informações do banco de dados e a configuração de exibição de erros.