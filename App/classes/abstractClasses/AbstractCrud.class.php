<?php
namespace App\classes\abstractClasses;
use App\classes\PDOconn;

abstract class AbstractCrud extends PDOconn {

    abstract protected function save();
    abstract protected function edit();
    abstract protected function update();
    abstract protected function delete();
    //abstract protected function neu();
}
