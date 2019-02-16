<?php
function()
{
$FC_S='';
$SC_S='';
$FC_L='';
$SC_L='';

$qry=['qtset'] = ' (e.Fast_L>0) Or (e.Slow_L>0)';
$qry=['query'] = '';

			    //long course only query
			    
			SELECT r.I_R,e.Session,
			e.FastCut,e.SlowCut,e.Fast_L,e.Slow_L,
			e.MtEv,e.MtEvX,e.Lo_Hi,e.Sex,e.Stroke,e.Distance,e.Fee,r.SCORE,r.Course,m.MName,m.Start,
			r.Score as conv,
			1 as pref,
			If((e.Slow_L>0),If(e.Slow_L>=r.Score,11,12),11) + If((e.Fast_L>0),If(e.Fast_L<=r.Score,0,-10),0) as qt 
			
			    From (".$tm4db."result_".$season." as r inner JOIN ".$tm4db."mtevente_".$LSeason." as e on (e.Meet=".arg(5)." and e.I_R='I'".(($age < $min_age)?'and e.Lo_Hi!=99':'')." and ((r.DISTANCE = e.DISTANCE and r.STROKE=e.STROKE)) and (e.Sex='X' Or e.Sex='".$sex."') and floor(e.Lo_Hi/100) <= ".$age." AND (e.Lo_Hi%100) >= ".$age."))
			    inner join ".$tm4db."meet_".$season." as m on (r.Meet = m.Meet ".(($since ==null)?'':"and m.Start >= '".$since."'").(($restrictbest==False)?'':" and m.Type='".$type."'").")
			    Where r.NT=0 and r.I_R!='R' 
			    
			    and r.Course='L' 
			    and r.ATHLETE =".$athlete;
			    
			    
			      SELECT r.I_R,
			      e.F".$std_qt.",e.S".$std_qt.",e.F".($std_qt+12).",e.S".($std_qt+12).",
			      ((e.Lo_Age*100)+e.Hi_Age) as Lo_Hi,e.Sex,e.Stroke,e.Distance,r.SCORE,r.Course,m.MName,m.Start,
			      r.Score as conv,
			      1 as pref,
			      If((e.S".($std_qt+12).">0),If(e.S".($std_qt+12).">=r.Score,11,12),11) + If((e.F".($std_qt+12).">0),If(e.F".($std_qt+12)."<=r.Score,0,-10),0) as qt 
					  
			      From (".$tm4db."result_".$season." as r inner JOIN ".$tm4db."".$std."_".$LSeason." as e on (e.I_R='I'".(($age < $min_age)?'and e.Hi_Age!=99':'')." and ((r.DISTANCE = e.DISTANCE and r.STROKE=e.STROKE)) and (e.Sex='".$sex."') and e.Lo_Age <= ".$age." AND e.Hi_Age >= ".$age."))
					    inner join ".$tm4db."meet_".$season." as m on (r.Meet = m.Meet ".(($since ==null)?'':"and m.Start >= '".$since."'").(($restrictbest==False)?'':" and m.Type='".$type."'").")
					    Where r.NT=0 and r.I_R!='R'
					    and r.Course='L' 
					    and r.ATHLETE =".$athlete;
			    
			  
}
			    ?>
