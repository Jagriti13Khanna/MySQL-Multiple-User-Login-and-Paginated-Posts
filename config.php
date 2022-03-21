<?php

if (!defined("BASE_URL")) {
    define("BASE_URL", "https://jkhanna1.dmitstudent.ca/dmit2025/labs/lab6/");
}

if (!defined("THIS_PAGE")) {
    define("THIS_PAGE", $SERVER['PHP_SELF']);
}

//This stops SQL Injection in POST vars
foreach ($_POST as $key => $value) {
        $_POST[$key] = mysqli_real_escape_string($conn, $value);
}

//This stops SQL Injection in GET vars
foreach ($_GET as $key => $value) {
    if(!is_array($_GET)) {
        $_GET[$key] = mysqli_real_escape_string($conn, $value);
    }
}

?>