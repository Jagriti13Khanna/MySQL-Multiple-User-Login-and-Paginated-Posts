<?php

require ("includes/connect.php");

include ("includes/header.php");

session_start();

if(isset($_SESSION['minnislongrandomstring-1']))
{
    include ("change.php");
}

?>
