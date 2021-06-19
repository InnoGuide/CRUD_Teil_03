<?php
namespace App\classes;
use PDO;
use PDOException;

class PDOconn extends Config{

    public function conn() //"?" kann auch null sein
    {
        //echo $this->DSN."Server=".$this->DB_servername."Database=".$this->DB_dbname, $this->DB_username, $this->DB_password;
        try{
            switch($this->DSN){
               case 'sqlsrv':  $conn = new PDO("$this->DSN:Server=$this->DB_servername;Database=$this->DB_dbname", $this->DB_username, $this->DB_password);
                            break;
               case  'mysql':   $conn = new PDO("$this->DSN:host=$this->DB_servername;dbname=$this->DB_dbname", $this->DB_username, $this->DB_password);
                            break;
               default: echo "<h1 style='background-color:black; color: white'>Kein korrekter DSN!</h1>";
                        exit();

            }

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch(PDOException $e) {

            echo "<h1 style='background-color:black; color: white'>   Der Webserver konnte keine Verbindung zur DB herstellen!</h1>";

            exit();
        }
    }
}
