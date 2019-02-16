<?php


$age = $object->Age;
				  $since = $object->Since;
				  $restrictbest = $object->RestrictBest;
				  $restrictbest = ($restrictbest ==null || $restrictbest==0)?false:true;
				  $sex = $object->Sex;
				  $type = $obejct->Type;
				  $min_age = $object->MinAge;
				  $min_age = ($min_age==null)?0:$min_age;
				  $athlete = arg(4);?

	$output.="Tesing page<br><br><br>";
	$query='';
	$query=" SELECT r.I_R,e.F".$std_qt.",e.S".$std_qt.",e.F".($std_qt+12).",e.S".($std_qt+12).",((e.Lo_Age*100)+e.Hi_Age) as Lo_Hi,e.Sex,e.Stroke,e.Distance,r.SCORE,r.Course,r.Score as conv,1 as pref,If((e.S".($std_qt+12).">=0),If(e.S".($std_qt+12).">=r.Score,11,12),11) + If((e.F".($std_qt+12).">=0),IF((e.F".($std_qt+12).">=0),If(e.F".($std_qt+12)."<r.Score,0,-10),10),0) as qt ";
    $query.=" From (".$tm4db.".result_".$season." as r inner JOIN ".$tm4db.".".$std."_".$LSeason." as e on (e.I_R='I'".(($age< $minage)?'and e.Hi_Age!=99':'')." and ((r.DISTANCE = e.DISTANCE and r.STROKE=e.STROKE)) and (e.Sex='".$sex."') and e.Lo_Age <= ".$age." AND e.Hi_Age >= ".$age."))";
   $query.=" inner join ".$tm4db.".meet_".$season." as m on ((r.Meet = m.Meet".(($since ==null || $since=='0000-00-00 00:00:00')?'':" and m.Start >= '".$since."'").(($restrictbest==false)?'':" and m.Type='".$type."'")."))";
    $query.=" Where r.NT=0 and r.I_R!='R' and r.Course='L'  and r.ATHLETE =".arg(4);
    
    
    $output.= '<br><br>'.$query.'<br><br>';
 $res = db_query($query);
				       
       while ($obj = db_fetch_array($res))
	 {
		$output.= (implode(',',$obj).'<br>'.'<br>');
	 }

?>