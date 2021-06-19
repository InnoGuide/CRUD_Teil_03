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
                                <input class="form-control" name="first_name" placeholder="Vorname" value="<?=$this->first_name?>">
                            </div>
                            <div class="col-5">
                                <input class="form-control " name="last_name" placeholder="Name" value="<?=$this->last_name?>">
                            </div>
                            <div class="col-2 overflow-hidden ">
                                <button type="submit" class="btn btn-info ml-4 text-white" value="<?=$this->save_update?>" name="button"><?=$this->save_update?></button>
                            </div>
                            <input type="hidden" name="ID" value="<?=$this->ID?>"  >
                        </div>
                    </form>    
                </div>
            </div>    
            <!--INFO-->
            <h5 class="text-white"><?=$this->info?></h5>
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
                        <?php foreach ($this->rows as $row) : ?>
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
    <script type="module" src="/App/classes/user_mvc_class/template.js""></script>