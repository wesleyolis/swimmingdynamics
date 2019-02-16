<?php
//smae database or seperate database
// $result = db_query("show tables from ".$tm4db." like 'meet\_____'");

$query = "show tables like '".$tm4db."meet\_____'";
$result = db_query($query);
?>