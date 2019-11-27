<?php

    function getConnection(){

        $URL     = "mysql:host=localhost;dbname=dbredetex;port=3306";
        $USUARIO = "root";
        $SENHA   = "";

        try {
            $PDO = new PDO($URL, $USUARIO, $SENHA);
            return $PDO;
        } catch(PDOException $error) {
            echo "ERRO! CONTATE O ADMINISTRADOR DO SISTEMA <br>";
            echo "Detalhes do Erro:" . $error->getMessage();
        }
    }

?>