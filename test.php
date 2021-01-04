<?php
    $var = "Ü";
    var_dump($var);
    $var = urlencode($var);
    $var = urldecode($var);
    var_dump($var);
?>