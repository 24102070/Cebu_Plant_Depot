<?php

    $db_server = "localhost";
    $db_user = "root";
    $db_pass = "";
    $db_name = "cebu_plant_depot_admin";
    $con = "";

    $con = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

    if(!$con){

        echo"<script>window.alert(`Unable to Connect`);</script>";

    }

?>