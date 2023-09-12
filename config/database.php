<?php

//realizamos con programacion orientada a objetos
//definimos la clase database
//definimos los atributos privados
// si se va a enlazar el host en otra maquina
// se agrega la direccion ip en localhost
// se agrega la variable database con el nombre de la 
//base de datos que hicimos en phpmyadmin
class Database{
    private $hostname ="localhost";
    private $database ="nepetacat_database";
    private $username ="root";
    private $password ="Administrador1#";
    private $charset ="utf8";

    //declaramos la funcion conectar
    //declaramos una variable conectar
    //usamos this para indicar de que variable llamaremos
    //agregamos opciones con un arreglo
    //hacemos la configuracion para evitar que las preparaciones sean emuladas
    function conectar()
    {
        try{
        $conexion = "mysql:host=".$this->hostname . "; dbname=" .$this->database ."; charset=" .$this->charset;
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false
        ];

        //definimos una variable y asociamos la cadena de conexion
        //retornamos la variable
        //para evitar errores usamos try y catch
        $pdo = new PDO($conexion, $this->username, $this->password, $options);

        return $pdo;

    //se define variable e de error
    }catch(PDOException $e){
        echo 'Error de concexión: ' . $e->getMessage();
        exit;
    }
    }


}

?>