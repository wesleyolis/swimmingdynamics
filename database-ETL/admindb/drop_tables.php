<?php

$pre = $_GET['pre'];
$ss = $_GET['ss'];

if(!($ss==null || $pre==null))
{
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
				while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
				$val = $row[0];
				
				
				if(strrchr($val,$pre.'_')==false)
				{
					$check[$pre.'_'.$val."_".$ss] = $val ;
					mysql_query("RENAME TABLE ".$val." TO ".$pre.'_'.$val."_".$ss);
				}
				else
				{
				echo $val;
			
				echo '<br/>';
				}
				
				}
				echo '<br/>';
				//Show renamed tables
				echo 'Tables Renamed<br/>';
				$result = mysql_query("SHOW TABLES");
				while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
				$val = $row[0];
				if($check[$val]!=null)
					echo $check[$val].': renamed<br/>';
				}
			
				echo '<br/>';
				}
			
				echo '<br/>Fixing Naming of standa tables<br/><br/>';
				$result = mysql_query("Select * From ".$pre."_stdname_".$ss);
				while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
				$std = $row['StdFile'];
				echo $std."<br>";
				$sql='';
					
					for($i=0;$i<36;$i++)
					{
						mysql_query("ALTER TABLE ".$pre.'_'.$std."_".$ss." CHANGE COLUMN `F(".$i.")` F".$i."  int(10)");
						mysql_query("ALTER TABLE ".$pre.'_'.$std."_".$ss." CHANGE COLUMN `S(".$i.")` S".$i."  int(10)");
					}
					
					
					
				}
				
				
				echo '<br/>Fixing Naming of Relay tables<br/><br/>';
				
					$std = 'relay';
					for($i=1;$i<9;$i++)
					{
						mysql_query("ALTER TABLE ".$pre.'_'.$std."_".$ss." CHANGE COLUMN `ATH(".$i.")` ATH".$i."  int(10)");
						
					}
					
					
					
				
				
				
				echo '<br/>Droping Table index<br/><br/>';
				
				$result = mysql_query("SHOW TABLES");
				while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
					$val = $row[0];
					if(strrchr($val,'_'.$ss)==true)
					{
						//Get index's of each table
						
						$result2 = mysql_query("SHOW INDEX FROM ".$val);
						echo "SHOW INDEX FROM ".$val."<br/>";
						while ($row2 = mysql_fetch_array($result2, MYSQL_NUM))
						{
							$index = $row2[2];
							//DROP [ONLINE|OFFLINE] INDEX index_name ON tbl_name
							mysql_query("ALTER TABLE ".$val." DROP ONLINE INDEX `".$index."`");
							echo "ALTER TABLE ".$val." DROP ONLINE INDEX `".$index."`<br/>";
						}
							
							mysql_query("ALTER TABLE ".$val." DROP PRIMARY KEY;");
							echo "ALTER TABLE ".$val." DROP PRIMARY KEY<br/>";
							
							mysql_query("ALTER TABLE ".$val." DROP ONLINE PRIMARY KEY;");
							echo "ALTER TABLE ".$val." DROP ONLINE PRIMARY KEY<br/>";
							
							mysql_query("ALTER TABLE ".$val." DROP ONLINE INDEX `ZID`");
							echo "ALTER TABLE ".$val." DROP ONLINE INDEX `ZID`<br/>";
							
						
					}
				
				
				}
				
				
				/*
				$tm4db = $db;
				$seasons = $ss;
				require('optimize.php');
				echo $output;
	*/
	
	    
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
<form method="GET" name="admindb.php" action="admindb.php">
	<p>PREFIX <input type="text" name="pre" size="20"></p>
	<p>DB <input type="text" name="db" size="20"></p>
	<p>SS <input type="text" name="ss" size="8"></p>
	<p><input type="submit" value="Submit" name="B1"><input type="reset" value="Reset" name="B2"></p>
</form>
<?php

}



?>