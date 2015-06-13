<?php
try {


require('Reflib_Protocol_Redirector.php');

$pr = new Reflib_Protocol_Redirector();
$pr->redirect();




} catch (Exception $e) {
    var_dump($e);
}


?>