<?php
    function reset_vars(){
        global $first_name, $last_name, $ID, $save_update, $info, $action, $param, $list_sql;
        $first_name=null;
        $last_name=null;
        $ID=null;
        $list_sql="select * from users";
        $param=[];
        $info = "";
        $save_update="save";
        $action="none";
        

    }
        try{
            $conn = new PDO("mysql:host=127.0.0.1; dbname=mydb", "root", null);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch (PDOException $e){
            echo $e->getMessage();
        exit("Keine DB Verbindung!");
        }
    //echo "Verbindung steht!";
    reset_vars();
    if(isset($_POST['button'])) {
        $action = $_POST['button'];
        switch ($action) {
            case 'save':
                if($_POST['first_name']=="" or $_POST['last_name']=="") {
                    $info = "Füllen Sie bitte alle Felder aus!";
                    break;
                }    
                $sql = "INSERT INTO users (first_name, last_name) VALUES (:first_name, :last_name)";
                $stmt = $conn->prepare($sql);
                $stmt->execute(['first_name' => $_POST['first_name'], 'last_name' => $_POST['last_name']]);   
                if($stmt->rowCount()>0) $info=$stmt->rowCount()." Datensatz wurde gespeichert!";
                else $info="Kein Datenzatz gespeichert!";
                
                break;
            case 'edit':
                $save_update="update";
                $sql = "SELECT * FROM users WHERE ID=:id";
                $stmt = $conn->prepare($sql);
                $stmt->execute(['id'=>$_POST['ID']]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_name = $row['first_name'];
                $last_name = $row['last_name'];
                $ID=$row['ID'];
                break;
            case 'update':
                $sql = "UPDATE users SET first_name=:first_name, last_name=:last_name where ID=:id";
                $stmt = $conn->prepare($sql);
                $stmt->execute(['first_name' => $_POST['first_name'], 'last_name' => $_POST['last_name'], 'id' => $_POST['ID']]);
                reset_vars();//$info wird danach geschrieben
                if ($stmt->rowCount() > 0) $info = $stmt->rowCount() . " Datensatz verändert!";
                else $info = "Kein Datensatz verändert/gespeichert!";
                break;
            case 'search':
                $list_sql = "select * from users where (last_name like :search_string) or (first_name like :search_string)";
                $param = ['search_string' => '%' . $_POST['search'] . '%'];
                break;
            case 'delete':
                $sql = "DELETE FROM users WHERE ID=:id";
                $stmt = $conn->prepare($sql);
                $stmt->execute(['id'=>$_POST['ID']]);
                if ($stmt->rowCount() > 0) $info = $stmt->rowCount() . " Datensatz gelöscht!";
                else $info = "Der Datensatz wurde nicht gelöscht!";
        }
    
    }
    $stmt= $conn->prepare($list_sql);
    $stmt->execute($param);
    $rows=$stmt->fetchAll(PDO::FETCH_ASSOC);
    //echo '<pre>' , var_dump($rows) , '</pre>';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/font-awesome/css/font-awesome.min.css">
    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.js"></script>
</head>
<body class="bg-primary">
    <div class="container-fluid  w-75">
        <div class="jumbotron">
            <div class="card mt-5">
                <div class="card-header text-light bg-info">
                    <h4>PHP CRUD</h4>
                </div>
                <div class="card-body ">
                    <form method="POST">
                        <div class="row ">
                            <div class="col-5">
                                <input class="form-control" name="first_name" placeholder="Vorname" value="<?=$first_name?>">
                            </div>
                            <div class="col-5">
                                <input class="form-control " name="last_name" placeholder="Name" value="<?=$last_name?>">
                            </div>
                            <div class="col-2 overflow-hidden ">
                                <button type="submit" class="btn btn-info ml-4 text-white" value="<?=$save_update?>" name="button"><?=$save_update?></button>
                            </div>
                            <input type="hidden" name="ID" value="<?=$ID?>"  >
                        </div>
                    </form>    
                </div>
            </div>    
            <!--INFO-->
            <h5 class="text-white"><?=$info?></h5>
            <div class="card mt-1">
                <div class="card-header">
                    <form method="post">
                        <div class="input-group">
                            <input type="text" class="form-control" name="search" placeholder="Search">
                            <div class="input-group-btn">
                                <button class="btn btn-info" type="submit" name="button" value="search">
                                    <i class="fa fa-search text-white"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>  
                <div class="card-body">
                    <table class="table table-striped ">
                        <thead class="bg-secondary text-light">
                        <tr>
                            <th scope="col">first_name</th>
                            <th scope="col">last_name</th>
                            <th scope="col" class=" d-flex justify-content-end pe-3">action</th>
                        </tr>
                        </thead>
                        <?php foreach ($rows as $row) : ?>
                        <tr class="t-row">
                            <td><?=htmlspecialchars($row['first_name'])?></td>
                            <td><?=htmlspecialchars($row['last_name'])?></td>
                            <td class="d-flex justify-content-end">
                                <form method="POST" >
                                    <input type="hidden" name="ID" value="<?=$row['ID']?>" >
                                    <button type="submit" class="btn btn-info text-white" value="edit" name="button">edit</button>
                                    <button type="submit" class="btn btn-danger ml-2" value="delete" name="button">delete</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach ?>
                    </table>
                </div>    
            </div>      
        </div>
    </div>    
    <div style="height:800px ; background: green"></div>
    <script>
    let table= document.querySelector(".table");
table.rows[1].classList.add("bg-primary","text-white");
console.log(table.rows);
document.querySelectorAll(".t-row").forEach(elem=>{
    elem.addEventListener('click', row=>{
       const rowSelect=document.querySelector(".t-row.bg-primary.text-white");
       rowSelect.classList.remove("bg-primary","text-white");
       row.currentTarget.classList.add("bg-primary","text-white");
    })
})
document.addEventListener("keydown", event=>{
    if(event.key!=="ArrowDown" && event.key!=="ArrowUp") return;
    const rowSelect=document.querySelector(".t-row.bg-primary.text-white");
    switch(event.key){
        case "ArrowDown":
             if(rowSelect.rowIndex+1<table.rows.length){
                event.preventDefault();
                rowSelect.classList.remove("bg-primary","text-white");
                table.rows[rowSelect.rowIndex+1].classList.add("bg-primary","text-white");
                if (!isElementInViewport(table.rows[rowSelect.rowIndex+2])){
                    table.rows[rowSelect.rowIndex+1].scrollIntoView({block: "end"}); 
                }
             }
        break;
        case "ArrowUp":
            if(rowSelect.rowIndex-1>0){
                event.preventDefault();
                rowSelect.classList.remove("bg-primary","text-white");
                table.rows[rowSelect.rowIndex-1].classList.add("bg-primary","text-white");
                if (!isElementInViewport(table.rows[rowSelect.rowIndex-1])){
                    table.rows[rowSelect.rowIndex-1].scrollIntoView(); 
                }
             }
    }

} )
 //Element.scrollIntoViewIfNeeded()
 function isElementInViewport(el) {
    let rect = el.getBoundingClientRect();
    return (
         rect.top >= 0 &&
         rect.left >= 0 &&
         rect.bottom <= (window.innerHeight || document. documentElement.clientHeight) &&
         rect.right <= (window.innerWidth || document. documentElement.clientWidth)
       );
 }
    </script>
</body>
</html>