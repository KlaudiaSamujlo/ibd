<?php
ini_set('display_errors',1);
error_reporting(E_ALL|E_STRICT);
session_start();
require_once 'vendor/autoload.php';

use Ibd\Koszyk;

$koszyk = new Koszyk();

$id = (int)$_GET['id'];
$koszyk->zmienLiczbeSztuk([$id => 0]);
echo 'ok';