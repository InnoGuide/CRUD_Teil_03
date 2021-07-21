<?php
namespace App\classes\product_mvc_class;
use App\classes\abstractClasses\AbstractCrud;
use PDO;
class Model extends AbstractCrud {
    //private $conn;
    protected $rows;
    protected $first_name=null;
    protected $last_name=null;
    protected $ID=null;
    protected $list_sql="select * from produkt";
    protected $param=[];
    protected $info = "";
    protected $save_update="save";
    protected $action="none";

   // function __construct(){
   //     $this->conn();
   // }
    
    protected function get_rows(){
        $stmt= $this->conn()->prepare($this->list_sql);
        $stmt->execute($this->param);
        $this->rows=$stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function save(){
        if($_POST['first_name']=="" or $_POST['last_name']=="") {
            $info = "Füllen Sie bitte alle Felder aus!";
            return;
        }    
        $sql = "INSERT INTO produkt (first_name, last_name) VALUES (:first_name, :last_name)";
        $stmt = $this->conn()->prepare($sql);
        $stmt->execute(['first_name' => $_POST['first_name'], 'last_name' => $_POST['last_name']]);   
        if($stmt->rowCount()>0) $this->info=$stmt->rowCount()." Datensatz wurde gespeichert!";
        else $this->info="Kein Datenzatz gespeichert!";
    }

    protected function edit(){
        $this->save_update="update";
        $sql = "SELECT * FROM produkt WHERE ID=:id";
        $stmt = $this->conn()->prepare($sql);
        $stmt->execute(['id'=>$_POST['ID']]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->first_name = $row['first_name'];
        $this->last_name = $row['last_name'];
        $this->ID=$row['ID'];
    }

    protected function update(){
        $sql = "UPDATE produkt SET first_name=:first_name, last_name=:last_name where ID=:id";
        $stmt = $this->conn()->prepare($sql);
        $stmt->execute(['first_name' => $_POST['first_name'], 'last_name' => $_POST['last_name'], 'id' => $_POST['ID']]);
        if ($stmt->rowCount() > 0) $this->info = $stmt->rowCount() . " Datensatz verändert!";
        else $this->info = "Kein Datensatz verändert/gespeichert!";
    }

    protected function delete(){
        $sql = "DELETE FROM produkt WHERE ID=:id";
        $stmt = $this->conn()->prepare($sql);
        $stmt->execute(['id'=>$_POST['ID']]);
        if ($stmt->rowCount() > 0) $this->info = $stmt->rowCount() . " Datensatz gelöscht!";
        else $this->info = "Der Datensatz wurde nicht gelöscht!";
    }

    protected function search(){
        $this->list_sql = "select * from produkt where (last_name like :search_string) or (first_name like :search_string)";
        $this->param = ['search_string' => '%' . $_POST['search'] . '%'];
    }

}
