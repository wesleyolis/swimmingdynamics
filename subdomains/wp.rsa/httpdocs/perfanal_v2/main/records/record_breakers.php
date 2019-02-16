<?php

require('../../main_include.php');

$breakers_days = $config['display_breakers'];

if($_GET['set'] != null && $_GET['size'] != null)
{
	$results = db_query("SELECT SQL_CACHE n.*, r.*,((r.Lo_Age*100)+r.Hi_Age) as Age  from ".$db_name."recname as n inner join ".$db_name."records as r on (n.record = r.Record) Where r.I_R='I' and  DATEDIFF(CURDATE(),r.RecDate)<=".$breakers_days." Order by r.recdate desc limit ".(($_GET['set']*8)+1).','.$_GET['size']); 
		//echo "SELECT SQL_CACHE n.*, r.*,((r.Lo_Age*100)+r.Hi_Age) as Age  from ".$db_name."recname as n inner join ".$db_name."records as r on (n.record = r.Record) Where r.I_R='I' and  DATEDIFF(CURDATE(),r.RecDate)<=".$breakers_days." Order by r.recdate desc limit ".(($_GET['set']*8)+1).','.$_GET['size'];
	if(!mysql_error())
	if(mysql_num_rows($results) != 0)
	{
		    $rec='';
		     while($object = mysql_fetch_object($results))
		       {
			       $rec.= "<div style='padding:1em 0em 0em 0em;'>";
			       $rec.= "<div  style='float: left;padding:0 0.5em 0 0'><b>".LO_HI($object->Lo_Age,$object->Hi_Age).'<br>'.get_time($object->RecTime).'</b></div>';
			       $rec.= "<b>".$object->Distance.'m '.Stroke($object->Stroke).'</b> '.$object->RecFile.' Record '.$object->Year.', <b>'.l($object->RecText,'age/'.$object->Record.'/'.$object->Age,$_GET['loc'].'main/records/index.php').'</b>&nbsp;'.$object->RecLSC.($object->RecLSC!=null?'-':'').$object->RecTeam.' </div>';
		      
		       //<div style='float:left;padding-left:0.1em;width:120px'>
		       //</div>
		       }
		       echo $rec;
	}
	
	
}
?>