<?php
namespace App\classes\product_mvc_class;
class Control extends View{
    function __construct()
    {
        //parent::__construct();
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