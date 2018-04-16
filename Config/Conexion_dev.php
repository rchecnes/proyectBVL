<?php

function getConexion(){

	$name_ht = "localhost";
	$user_db = "";
	$pass_db = "";
	$name_db = "";

    $link = mysqli_connect($name_ht, $user_db, $pass_db, $name_db);

    return $link;
}
?>