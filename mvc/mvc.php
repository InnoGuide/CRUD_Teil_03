<?php
class Model{
    private $conn;
    protected $rows;
    protected $first_name=null;
    protected $last_name=null;
    protected $ID=null;
    protected $list_sql="select * from users";
    protected $param=[];
    protected $info = "";
    protected $save_update="save";
    protected $action="none";

    function __construct(){
        try{
            $this->conn = new PDO("mysql:host=127.0.0.1; dbname=mydb", "root", null);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch (PDOException $e){
            echo $e->getMessage();
        exit("Keine DB Verbindung!");
        }
    }
    
    protected function get_rows(){
        $stmt= $this->conn->prepare($this->list_sql);
        $stmt->execute($this->param);
        $this->rows=$stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function save(){
        if($_POST['first_name']=="" or $_POST['last_name']=="") {
            $info = "Füllen Sie bitte alle Felder aus!";
            return;
        }    
        $sql = "INSERT INTO users (first_name, last_name) VALUES (:first_name, :last_name)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['first_name' => $_POST['first_name'], 'last_name' => $_POST['last_name']]);   
        if($stmt->rowCount()>0) $info=$stmt->rowCount()." Datensatz wurde gespeichert!";
        else $info="Kein Datenzatz gespeichert!";
    }

    protected function edit(){
        $this->save_update="update";
        $sql = "SELECT * FROM users WHERE ID=:id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id'=>$_POST['ID']]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->first_name = $row['first_name'];
        $this->last_name = $row['last_name'];
        $this->ID=$row['ID'];
    }

    protected function update(){
        $sql = "UPDATE users SET first_name=:first_name, last_name=:last_name where ID=:id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['first_name' => $_POST['first_name'], 'last_name' => $_POST['last_name'], 'id' => $_POST['ID']]);
        if ($stmt->rowCount() > 0) $this->info = $stmt->rowCount() . " Datensatz verändert!";
        else $this->info = "Kein Datensatz verändert/gespeichert!";
    }

    protected function delete(){
        $sql = "DELETE FROM users WHERE ID=:id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id'=>$_POST['ID']]);
        if ($stmt->rowCount() > 0) $this->info = $stmt->rowCount() . " Datensatz gelöscht!";
        else $this->info = "Der Datensatz wurde nicht gelöscht!";
    }

    protected function search(){
        $this->list_sql = "select * from users where (last_name like :search_string) or (first_name like :search_string)";
        $this->param = ['search_string' => '%' . $_POST['search'] . '%'];
    }

}
class View extends Model{
    function render(){
        include "template.php";
    }
}
class Control extends View{
    function __construct()
    {
        parent::__construct();
        if(isset($_POST['button'])) {
            $action = $_POST['button'];
            switch ($action) {
                case 'save':
                    $this->save();
                    break;
                case 'edit':
                    $this->edit();
                    break;
                case 'update':
                    $this->update();
                    break;
                case 'delete':
                    $this->delete();
                    break;
                case 'search':
                    $this->search();
            }
        }
        $this->get_rows();
        $this->render();
    }
}