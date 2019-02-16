<?php

$server = "localhost";
$user = "swimdy16_wp_rsa_perfanal1";
$pass = "1986drupal0311";
$db_name = "wp_rsa_perfanal";
$link = mysql_connect($server, $user, $pass);

mysql_select_db($db_name) or die(mysql_error());
$query = "LOAD DATA LOCAL INFILE '$file' INTO TABLE `T1` FIELDS TERMINATED BY ';' OPTIONALLY ENCLOSED BY '\"' ";
mysql_query($query) or die(mysql_error());
?>