<?php

//$db_link_unic = mysqli_connect('localhost:3306', 'swimdy16_wp_rsa_perfanal1','password1');
$db_link_unic = mysql_connect('localhost:3307', 'swimdy16_wp_rsa_perfanal1','passwrod');


if ($db_link_unic != FALSE)
{
	mysql_error();
	echo 'Failed to Connect';
}
else
{
	echo "Connected";

}

echo phpinfo();


?>