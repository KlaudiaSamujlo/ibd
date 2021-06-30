<?php
ini_set('display_errors',1);
error_reporting(E_ALL|E_STRICT);
session_start();
require_once 'vendor/autoload.php';

use Ibd\Koszyk;

if(isset($_POST)) {
    $koszyk = new Koszyk();
    if ($koszyk->usun($_GET['id'])) {
        echo 'ok';
    }
}