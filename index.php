<?php

try {

    require('BL_Protocol_Redirector.php');

    $pr = new BL_Protocol_Redirector();
    $pr->redirect();

} catch (Exception $e) {
    var_dump($e);
}

?>
