<?php
$db_server = "localhost:3306";
$db_user   = "s24104013_cebuplantdepot";
$db_pass   = "CebuDepot2025";
$db_name   = "s24104013_cebuplantdepot";
$con = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

if (!$con) {
   
    die("<script>alert('Unable to connect to the database!');</script>");
}
?>
