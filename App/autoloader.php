<?php

spl_autoload_register("myAutoload");
function myAutoload($Name)
{
    $path = $_SERVER["DOCUMENT_ROOT"] . "/";
    $fullname = $path . str_replace('\\', '/', $Name);

    $ext = [".class.php", ".interface.php", ".trait.php"];
    for ($i = 0; $i < count($ext); $i++)
        if (file_exists($fullname . $ext[$i])) {
            require_once $fullname . $ext[$i];
            return;
        }
}

