<?php
$db_server = "localhost";
$db_user   = "root";
$db_pass   = "";
$db_name   = "cebu_plant_depot";
$con = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

if (!$con) {
   
    die("<script>alert('Unable to connect to the database!');</script>");
}
?>
