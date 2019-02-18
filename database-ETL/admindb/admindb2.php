<?php
set_time_limit(60*10);
$pre = $_GET['pre'];
$ss = $_GET['ss'];
$db = $_GET['db'];
$ver = $_GET['ver'];

if(!($db==null || $pre==null|| $ver==null))
{
	echo "db:".$db.'<br/>';
	echo "season:".$ss.'<br/>';
	
	// we connect to localhost at port 3307
	
	$link = mysql_connect('127.0.0.1:3306', 'root', 'WeZley');
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


/*            

Update wp_rsa_perfanal_league_2012_4_meet as up set `Type` = 'LG';

Update wp_rsa_perfanal_league_2012_4_meet as up set `Type` = 'IN' where LOCATION NOT IN (
SELECT "Long Street" as LOC UNION
SELECT "KINGS PARK SWIMMING POOL" UNION
SELECT "Strand" UNION
SELECT "Newton Park" UNION
SELECT "GC Joliffe Swimming Pool" UNION
SELECT "Newton Park Pool"  UNION
SELECT "UWC" UNION
SELECT "Various" UNION
SELECT "Wellness World" UNION
SELECT "UWC Pool" UNION
SELECT "UWC" UNION
SELECT "Various" UNION
SELECT "N.A. Smit Swimming Pool"
);


Update wp_rsa_perfanal_league_2012_4_meet set `Type` = IF ( INSTR( LCASE(MName),'League Best Times') > 0, 'BTP',IF ( INSTR( LCASE(MName),'Series Best Times') > 0, 'BTC',IF ( INSTR( LCASE(MName),'OPEN WATER') >= 1 ,'OWS', IF ( INSTR( LCASE(MName),'Club Series') >= 1,'CS',IF ( INSTR( LCASE(MName),'International') >= 2, 'IN',`Type`)))));

Update wp_rsa_perfanal_league_2012_4_meet set Sanction = concat( IF ( INSTR( LCASE(MName),'WP') = 1 ,'WP,',','), IF ( `Type` != 'CS' AND `Type` != 'BTP' AND `Type` != 'BTC', 'RSA,', ',') , IF ( `Type` != 'CS' AND `Type` != 'BTC', 'IBT,', ','), IF ( `Type` != 'BTP' AND `Type` != 'BTC', 'ICS,', ','))


*/

            $insert_query = " INSERT INTO `code` ( `ABBR`, `desc`, `TYPE` ) VALUES ";
            $insert_query.= ' ("ICS","Un-Offical Includes Club Series",3), ("IBT","Un-Offical Includes Best Times",3),("NW","North West",3), ("OWS","Open Water Swimming",3), ("SW","South Western Districts",3), ("RSA","Republic of South Africa",3), ("WP","Western Province",3), ("USA","United States America",3), ("VT","Vaal Triangle",3), ("NT","Northern Tigers Swimming",3), ("NN","Northern Kwa-Zulu Natal",3), ("GW","Griquas",3), ("IN","International",3), ("BO","Border",3), ("BTP","LG Best Times From Previous Season",3),("BTC","CS Best Times From Previous Season",3), ("CG","Central Gauteng",3), ("CS","Club Series",3), ("EP","Eastern Province",3), ("FNA","FINA Rankings",3), ("FS","Free State",3), ("KZN","Kwa Zulu Natal",3), ("LG","League",3), ("AG","Meet",3), ("NC","Northern Cape",3), ("MP","Mpumalanga",3), ("KZ","KwaZulu-Natal",3), ("AUS","Austrialia",3), ("LP","Limpopo",3), ("NF","Northern Free State",3), ("ES","Eastern Gauteng",3)';

            mysql_query(" alter table `code` modify column `CODE` int(10) auto_increment ");
            mysql_query($sinsert_query);

/*
            mysql_query("Update meet as up set `Type` = 'LG';");

            mysql_query("Update meet as up set `Type` = 'IN' where  INSTR(LCASE(MName),'Best Times') = 0 AND LOCATION NOT IN ( SELECT 'Long Street' as LOC UNION SELECT 'KINGS PARK SWIMMING POOL' UNION SELECT 'Strand' UNION SELECT 'Newton Park' UNION SELECT 'GC Joliffe Swimming Pool' UNION SELECT 'Newton Park Pool'  UNION SELECT 'UWC' UNION SELECT 'Various' UNION SELECT 'Wellness World' UNION SELECT 'UWC Pool' UNION SELECT 'UWC' UNION SELECT 'Various' UNION SELECT 'N.A. Smit Swimming Pool' UNION SELECT 'Bellville Pool' UNION SELECT 'Newton Park Swimming Pool' UNION SELECT 'Oudsthoorn' UNION SELECT 'UWC Swimming Pool' UNION SELECT 'Kings Park Pool' UNION SELECT 'UWC' UNION SELECT 'Eikenhof Dam' UNION SELECT 'Theewaters Dam' UNION SELECT 'N.A. Swimming Pool' UNION SELECT 'Hillcrest Pool' UNION SELECT 'Hillcrest Pool' UNION SELECT 'Ashanti Dam, Agter Paarl' UNION SELECT 'Newton Park' UNION SELECT 'Hillcrest Swimming Pool' UNION SELECT 'Polokwane Municipal Swimming Pool' UNION SELECT 'Ellis Park' UNION SELECT 'Pietersburg High School' UNION SELECT 'Uitenhage Swimming Pool' UNION SELECT 'Hamdan Sports Complex' UNION SELECT 'Pietersburg High School' UNION SELECT 'Uitenhage Swimming Pool' UNION SELECT 'Dolphins' UNION SELECT 'Kings Park Pool SC' UNION SELECT 'Delville' UNION SELECT 's Kings Park LC Pool, Durban' UNION SELECT 'Wahoo Aquatic Centre'
UNION SELECT 'Long Street / Stellenbosch'
UNION SELECT  'Metro / Winelands / West Coast'
UNION SELECT  'Long Street / Stellenbosch'
);");

            mysql_query("Update meet set `Type` = IF ( INSTR( LCASE(MName),'League Best Times') > 0, 'BTP',IF ( INSTR( LCASE(MName),'Series Best Times') > 0, 'BTC',IF ( INSTR( LCASE(MName),'OPEN WATER') >= 1 ,'OWS', IF ( INSTR( LCASE(MName),'Club Series') >= 1,'CS', IF ( INSTR( LCASE(MName),'WP Junior') >= 1,'CS',IF ( INSTR( LCASE(MName),'International') >= 2, 'IN',`Type`))))));");



            mysql_query("Update meet set Sanction = concat( IF ( `Type` != 'IN' AND ( INSTR( LCASE(MName),'WP') = 1 OR ( `Type` = 'LG' and LOCATION IN ( SELECT 'Long Street' as LOC UNION SELECT 'KINGS PARK SWIMMING POOL' UNION SELECT 'Strand' UNION SELECT 'Newton Park' UNION SELECT 'GC Joliffe Swimming Pool' UNION SELECT 'Newton Park Pool'  UNION SELECT 'UWC' UNION SELECT 'Various' UNION SELECT 'Wellness World' UNION SELECT 'UWC Pool' UNION SELECT 'UWC' UNION SELECT 'Various' UNION SELECT 'N.A. Smit Swimming Pool' UNION SELECT 'Bellville Pool')) ),'WP,',','), IF ( `Type` != 'IN' AND `Type` != 'CS' AND `Type` != 'BTP' AND `Type` != 'BTC', 'RSA,', ',') , IF ( `Type` != 'IN' AND `Type` != 'CS' AND `Type` != 'BTC', 'IBT,', ','), IF ( `Type` != 'IN' AND `Type` != 'BTP' AND `Type` != 'BTC', 'ICS,', ','))");
*/

			//now query the tables out and update the names.
			$check = Array();
			$result = mysql_query("SHOW TABLES");
			
			// See also mysql_result(), mysql_fetch_array(), mysql_fetch_row()), etc.
			
				echo 'Tables No matched<br/>';
				while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
				$val = $row[0];
				
				$pos = strpos($val,$pre.'_');
				//echo $val.':'.$pos.'<br>';
				if($pos===false)
				{
					$check[$pre.'_'.$val."_".$ss] = $val ;
					mysql_query("RENAME TABLE ".$val." TO ".$pre.'_'.$ss.'_'.$ver.'_'.$val."");
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
			//MYSQL_ASSOC
				echo '<br/>Fixing Naming of standa tables<br/><br/>';
				echo "Select * From ".$pre.'_'.$ss.'_'.$ver.'_'."stdname";
				
				$result = mysql_query("Select * From ".$pre.'_'.$ss.'_'.$ver.'_'."stdname");
				while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
				$std = $row['StdFile'];
				echo $std."<br>";
				$sql='';
					
					for($i=0;$i<36;$i++)
					{
						mysql_query("ALTER TABLE ".$pre.'_'.$ss.'_'.$ver.'_'.$std." CHANGE COLUMN `F(".$i.")` F".$i."  int(10)");
						mysql_query("ALTER TABLE ".$pre.'_'.$ss.'_'.$ver.'_'.$std." CHANGE COLUMN `S(".$i.")` S".$i."  int(10)");
					}
					
					
					
				}
				
				
				echo '<br/>Fixing Naming of Relay tables<br/><br/>';
				
					$std = 'relay';
					for($i=1;$i<9;$i++)
					{
						mysql_query("ALTER TABLE ".$pre.'_'.$ss.'_'.$ver.'_'.$std." CHANGE COLUMN `ATH(".$i.")` ATH".$i."  int(10)");
						
					}
					
					
				echo '<br/>Droping Unessary Columns in Result Table<br/><br/>';

                mysql_query("ALTER TABLE ".$pre.'_'.$ss.'_'.$ver."_result DROP COLUMN `DQCODE`");
                mysql_query("ALTER TABLE ".$pre.'_'.$ss.'_'.$ver."_result DROP COLUMN `DQDESCRIPT`");
                mysql_query("ALTER TABLE ".$pre.'_'.$ss.'_'.$ver."_result DROP COLUMN `DQCODESecondary`");
                mysql_query("ALTER TABLE ".$pre.'_'.$ss.'_'.$ver."_result DROP COLUMN `DQDESCRIPTSecondary`");

                echo '<br/>dropping orphaned results records.<br/><br/>';

                mysql_query("delete from  ".$pre.'_'.$ss.'_'.$ver."_result where I_R != `R` and athlete not in (select athlete from ".$pre.'_'.$ss.'_'.$ver."_athlete");

				echo '<br/>Droping Table index<br/><br/>';
				
				$result = mysql_query("SHOW TABLES");
				while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
					$val = $row[0];
					if(strrchr($val,$pre.'_'.$ss.'_'.$ver.'_')==true)
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
<form method="GET" name="admindb2.php" action="admindb2.php">
	<p>PREFIX <input type="text" name="pre" size="20"></p>
	<p>DB <input type="text" name="db" size="20"></p>
	<p>SS <input type="text" name="ss" size="8"></p>
	<p>Version <input type="text" name="ver" size="8"></p>
	<p><input type="submit" value="Submit" name="B1"><input type="reset" value="Reset" name="B2"></p>
</form>
<?php

}



?>