<?php

$pre = $_GET['pre'];
$ss = $_GET['ss'];
$db = $_GET['db'];

if(!($ss==null || $db==null))
{
	echo "db:".$db.'<br/>';
	echo "season:".$ss.'<br/>';
	
	// we connect to localhost at port 3307
	
	$link = mysql_connect('127.0.0.1:3306', 'root', 'Oliver');
	if ($link) {
	echo 'Connected successfully<br/>';
	
	// make foo the current db
	$db_selected = mysql_select_db($db, $link);
		if (!$db_selected) {
		    die ('Can\'t use '.$db.': ' . mysql_error());
		}
		else
		{
			echo "'".$db."' Selected<br/>";
			
			//now query the tables out and update the names.
			$check = Array();
			$result = mysql_query("SHOW TABLES");
			
			// See also mysql_result(), mysql_fetch_array(), mysql_fetch_row()), etc.
			
				echo 'Tables No matched<br/>';
				while ($row = mysql_fetch_array($result, MYSQL_NUM)) 
				{
					$val = $row[0];
					
					
					if(strrchr($val,'_'.$ss)!=false)
					{
						
						mysql_query("RENAME TABLE ".$val." TO ".$pre.'_'.$val);
					}
					else
					{
					echo $val;
				
					echo '<br/>';
					}
					
				}
		}
				
			
	    
	}
	else
	{
		die('Could not connect: ' . mysql_error());
	}
	
	mysql_close($link);

}
else
{
?>
<form method="GET" name="admindb.php" action="retables.php">
	<p>PREFIX <input type="text" name="pre" size="20"></p>
	<p>DB <input type="text" name="db" size="20"></p>
	<p>SS <input type="text" name="ss" size="8"></p>
	<p><input type="submit" value="Submit" name="B1"><input type="reset" value="Reset" name="B2"></p>
</form>
<?php

}



?>