<?php
require('../../../main_include.php'); 

function array_position($arr,$key,$inc,$i)
{

foreach($arr as $k=>$val)
{
if($k==$key)
{
	
return $i;

}else
$i=$i+$inc;	
}
return 0;

}

$output="";
?>
<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<head>
<title>
<?php

  $query = "select SQL_CACHE m.* FROM ".$db_name."meet as m where m.Meet=".inj_int($_GET['m']);
	 $result = db_query($query);
	     if(!mysql_error())
	     {
	     $object = mysql_fetch_object($result);
	     }
	     else
	     {
		exit();     
	     }
	     $str=$object->MName.' JS Powered Results'.' '.$_GET['ss'].'-'.($_GET['ss']+1);
	     $page_title_inline = ucfirst($config['title']).' '.$str;
	     $page_title = $seas_reg.' '.$page_title_inline.' - '.$site_title;
	     echo $page_title;


?></title>
<link rel="stylesheet" type="text/css" href="styles.css">
<script src="filters.js" language="javascript" type="text/javascript"></script>
<script language="javascript" type="text/javascript"><!--
window.status="Downloading.... List of Clubs";
document.write("<frameset cols='1,*' framespacing='0' frameborder='0' scrolling='no' noresize><frame name='l' target='_top' src='' scrolling='no' noresize><frameset rows='150,*' framespacing='0' frameborder='0' noresize><frame name='h'  scrolling='no'><frame name='m'></frameset></frameset>");

type = new Array("Finals","Prelims","Semi-Final");

clubs = new Array(<?php

$teams = null;

$query = "select t.Team,t.TName, t.TCode,t.LSC from ".$db_name."result as r inner join ".$db_name."team as t on (r.team = t.team) where r.meet = ".inj_int($_GET['m'])."  group by r.team order by t.TName;";


$result = db_query($query);
	if(mysql_error())
		exit();
	else
	{
		while($object = mysql_fetch_object($result))
		{
		$teams[$object->Team] = null;//array($object->TName,$object->TCode.'-'.$object->LSC);	
		echo '"'.$object->TName.'","'.($object->TCode.'-'.$object->LSC).'",';
		}
	}
?>"","");

events = new Array(<?php
$events=null;
$query = "select e.MtEvent,e.Session,e.MtEv,e.MtEvX,e.Sex,e.Lo_Hi,e.Distance,e.Stroke,e.I_R from ".$db_name."mtevent as e where e.I_R='I' and meet=".inj_int($_GET['m'])." order by  e.Session,e.MtEv,e.MtEvX";

$result = db_query($query);
	if(mysql_error())
		exit();
	else
	{
		while($object = mysql_fetch_object($result))
		{
			$events[$object->MtEvent]=null;
		echo ''.$object->Session.',"'.$object->MtEv.''.$object->MtEvX.'","'.$object->Sex.'",'.$object->Lo_Hi.','.$object->Distance.','.$object->Stroke.',"'.$object->I_R.'",';
		}
	}
?>"","");

window.status = "Proccessing... setting up Filters";
fil ();
window.status = "Dowmloading List of athletes";
ath = new Array("-","-","-","-","-",<?php

$ath = null;

$query = "select SQL_CACHE a.Athlete, a.Last,a.First,r.Team,a.Sex,min(r.age) as age from ".$db_name."result as r inner join ".$db_name."athlete as a on (r.athlete=a.athlete) where r.meet=".inj_int($_GET['m'])." group by a.athlete order by a.athlete";
$result = db_query($query);
	
if(mysql_error())
		exit();
	else
	{
		while($object = mysql_fetch_object($result))
		{
			$ath[$object->Athlete]=null;
			
		echo '"'.$object->Last.'","'.$object->First.'",'.array_position($teams,$object->Team,2,0).',"'.$object->Sex.'",'.$object->age.',';
		}
	}
	
?>"","");
window.status = "Dowmloading Results";
relay = new Array("",""<?php

//"1","Tygerberg Aquatics","A","F","02:29.04",1080,1285,1070,1250,
?>);

res = new Array(<?php

$query = ("SELECT sql_cache r.athlete,r.score,r.MTEVENT,r.F_P,r.NT, r.place from ".$db_name."result as r inner join ".$db_name."mtevent as e on(r.mtevent = e.mtevent) where e.meet = ".inj_int($_GET['m'])." and r.I_R='I' order by e.Session,e.MtEv,e.MtEvX,r.F_P,r.nt, r.score");

$loc='';
$F_P_S = array('F'=>0,'P'=>1,'S'=>2,'T'=>0,'O'=>0,'I'=>0);
$f_P = null;
$Ev = null;
$pos = 0;
$rcount=0;
$first = true;
$result = db_query($query);

	if(mysql_error())
		exit();
	else
	{
		while($object = mysql_fetch_object($result))
		{            
			echo (($object->NT==0)?$object->place:'"'.NT($object->NT).'"').','.array_position($ath,$object->athlete,5,5).',"'.get_time($object->score).'",';
			//echo $object->place.','.$object->ath_pos.',"'.get_time($object->score).'",';
			
			
			
			
			if($f_P != $object->F_P && $Ev != $object->MTEVENT)
			{
				
				if(!$first)
				$loc.=''.array_position($events,$Ev,1,0).','.$F_P_S[$f_p].','.$rcount.',';
				$first=false;
				$f_p = $object->F_P;
				$Ev = $object->MTEVENT;
				$rcount=0;
			}
			
			$rcount+=1;
		}
		$loc.=''.array_position($events,$Ev,1,0).','.$F_P_S[$f_p].','.$rcount.',';
	}
	
	

?>"","");

loc = new Array(<?php echo $loc;
?>"","");

setTimeout("init();",1000);
alert("Click on the athlete's surname to see summary of times and jumped window.");
//-->
</script></head><body>Strand aquatic's makes heavy use of JavaScript!<br>You don't appear to have a java compatible browser!<br>Please visit one of the following sites depending<br>on your browser to update it.<br><a href="http://microsoft.com/windows/ie/">Internet Explorer 6</a> or <a href="http://home.netscape.com/download/">Netscape 6</a></body></html>


