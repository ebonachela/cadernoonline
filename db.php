<?php

class dbClass{

    private $host = 'localhost';
    private $user = 'root';
    private $pass = '';
    private $database = 'gerador_simulados';

    public function conectar(){
        $con = mysqli_connect($this->host, $this->user, $this->pass, $this->database);
        mysqli_set_charset($con, 'utf8');

        //Erro de conexão
        if (mysqli_connect_errno()){
            echo 'Erro na conexão com o banco de dados';
        }

        $table_usuarios = "CREATE TABLE IF NOT EXISTS `usuarios` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `usuario` varchar(32) NOT NULL,
            `email` varchar(100) NOT NULL,
            `senha` varchar(32) NOT NULL,
            `cargo` varchar(50) DEFAULT 'user',
            `vip` varchar(30) DEFAULT 'normal',
            `status` char(1) NOT NULL DEFAULT '1',
            `motivo` varchar(100) DEFAULT NULL,
            `ban_time` int(11) DEFAULT NULL,
            PRIMARY KEY (`id`)
        );";

        $table_pendentes = "CREATE TABLE IF NOT EXISTS `pendentes` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `usuario` varchar(20) NOT NULL,
            `body` text NOT NULL,
            `resa` text NOT NULL,
            `resb` text NOT NULL,
            `resc` text NOT NULL,
            `resd` text NOT NULL,
            `rese` text NOT NULL,
            `disciplina` varchar(15) NOT NULL,
            `disciplina_tipo` varchar(100) DEFAULT NULL,
            `resposta` char(1) DEFAULT NULL,
            PRIMARY KEY (`id`)
        );";

        mysqli_query($con, $table_usuarios);
        mysqli_query($con, $table_pendentes);

        return $con;
    }

    public function escape($inp) { 
        if(is_array($inp)) 
            return array_map(__METHOD__, $inp); 
    
        if(!empty($inp) && is_string($inp)) { 
            return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $inp); 
        } 
    
        return $inp; 
    }

}