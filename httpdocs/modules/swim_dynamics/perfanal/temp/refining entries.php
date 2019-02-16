				      <?php
				      
				      if($object->Course=='LO')
					 {
					    //long course only query
					    $query.=" SELECT r.I_R,e.FastCut,e.SlowCut,e.Fast_L,e.Slow_L,((e.Lo_Age*100)+e.Hi_Age) as Lo_Hi,e.Sex,e.Stroke,e.Distance,r.SCORE,r.Course,m.MName,m.Start,r.Score as conv,1 as pref,If((e.Slow_L>=0),If(e.Slow_L>=r.Score,11,12),11) + If((e.Fast_L>=0),IF((e.Fast_L>=0),If(e.Fast_L<r.Score,0,-10),10),0) as qt ";
					    $query.=" From (".$tm4db."result_".$season." as r inner JOIN ".$tm4db."mtevente_".$LSeason." as e on (e.I_R='I'".(($object->Age < $object->MinAge)?'and e.Lo_Hi!=99':'')." and ((r.DISTANCE = e.DISTANCE and r.STROKE=e.STROKE)) and (e.Sex='".$object->Sex."') and e.Lo_Age <= ".$object->Age." AND e.Hi_Age >= ".$object->Age."))";
					    $query.=" inner join ".$tm4db."meet_".$season." as m on (r.Meet = m.Meet ".(($object->Since ==null)?'':"and m.Start >= '".$object->Since."'").(($object->RestrictBest==False)?'':" and m.Type='".$object->Type."'").")";
					    $query.=" Where r.NT=0 and r.I_R!='R' and r.Course='L'  and r.ATHLETE =".arg(4);
					 }
		
				       if($object->Course=='LS') //Considers Muti course cut meets
					 {
					    //long course query
					    $query.=" SELECT r.I_R,e.FastCut,e.SlowCut,e.Fast_L,e.Slow_L,((e.Lo_Age*100)+e.Hi_Age) as Lo_Hi,e.Sex,e.Stroke,e.Distance,r.SCORE,r.Course,m.MName,m.Start,r.Score as conv,1 as pref,If((e.Slow_L>=0) Or (e.SlowCut>=0),If(e.Slow_L>=0,If(e.Slow_L>=r.Score,11,12),13),11) + If((e.FastCut>=0) Or (e.Fast_L>=0),If((e.Fast_L>=0),If(e.Fast_L<r.Score,0,-10),10),0) as qt ";
					    $query.=" From (".$tm4db."result_".$season." as r inner JOIN ".$tm4db."mtevente_".$LSeason." as e on (e.I_R='I'".(($object->Age < $object->MinAge)?'and e.Lo_Hi!=99':'')." and ((r.DISTANCE = e.DISTANCE and r.STROKE=e.STROKE)) and (e.Sex='".$object->Sex."') and e.Lo_Age <= ".$object->Age." AND e.Hi_Age >= ".$object->Age."))";
					    $query.=" inner join ".$tm4db."meet_".$season." as m on (r.Meet = m.Meet ".(($object->Since ==null)?'':"and m.Start >= '".$object->Since."'").(($object->RestrictBest==False)?'':" and m.Type='".$object->Type."'").")";
					    $query.=" Where r.NT=0 and r.I_R!='R' and r.Course='L'  and r.ATHLETE =".arg(4);
		
					    //Short course query
					    $query.=" union SELECT r.I_R,e.FastCut,e.SlowCut,e.Fast_L,e.Slow_L,((e.Lo_Age*100)+e.Hi_Age) as Lo_Hi,e.Sex,e.Stroke,e.Distance,r.SCORE,r.Course,m.MName,m.Start,floor( r.Score + IF(r.Course='L',(r.Distance/50)-(r.Distance/25),(r.Distance/25)-(r.Distance/50))*IF(r.STROKE=1 OR r.STROKE=5,80,IF(r.STROKE=2,60,IF(r.STROKE=3,100,IF(r.STROKE=4,70,0))))) as conv,2 as pref,If((e.Slow_L>=0) Or (e.SlowCut>=0),If(e.SlowCut>=0,If(e.SlowCut>=r.Score,11,12),13),11) + If((e.FastCut>=0) Or (e.Fast_L>=0),If((e.FastCut>=0),If(e.FastCut<r.Score,0,-10),10),0) as qt ";
					    $query.=" From (".$tm4db."result_".$season." as r inner JOIN ".$tm4db."mtevente_".$LSeason." as e on (e.I_R='I'".(($object->Age < $object->MinAge)?'and e.Lo_Hi!=99':'')." and ((r.DISTANCE = e.DISTANCE and r.STROKE=e.STROKE)) and (e.Sex='".$object->Sex."') and e.Lo_Age <= ".$object->Age." AND e.Hi_Age >= ".$object->Age."))";
					    $query.=" inner join ".$tm4db."meet_".$season." as m on (r.Meet = m.Meet ".(($object->Since ==null)?'':"and m.Start >= '".$object->Since."'").(($object->RestrictBest==False)?'':" and m.Type='".$object->Type."'").")";
					    $query.=" Where r.NT=0 and r.I_R='I' and r.Course='S'  and r.ATHLETE =".arg(4);
		
					 }
		
				       if($object->Course=='L') //Covert all times to long course then Consider them, note preferance (pref is equal here =1)
					 {
					    //long course query
					    $query.=" SELECT r.I_R,e.FastCut,e.SlowCut,e.Fast_L,e.Slow_L,((e.Lo_Age*100)+e.Hi_Age) as Lo_Hi,e.Sex,e.Stroke,e.Distance,r.SCORE as SCORE,r.Course,m.MName,m.Start,r.Score as conv,1 as pref,If((e.Slow_L>=0),If(e.Slow_L>=r.Score,11,12),11) + If((e.Fast_L>=0),IF((e.Fast_L>=0),If(e.Fast_L<r.Score,0,-10),10),0) as qt  ";
					    $query.=" From (".$tm4db."result_".$season." as r inner JOIN ".$tm4db."mtevente_".$LSeason." as e on (e.I_R='I'".(($object->Age < $object->MinAge)?'and e.Lo_Hi!=99':'')." and ((r.DISTANCE = e.DISTANCE and r.STROKE=e.STROKE)) and (e.Sex='".$object->Sex."') and e.Lo_Age <= ".$object->Age." AND e.Hi_Age >= ".$object->Age."))";
					    $query.=" inner join ".$tm4db."meet_".$season." as m on (r.Meet = m.Meet ".(($object->Since ==null)?'':"and m.Start >= '".$object->Since."'").(($object->RestrictBest==False)?'':" and m.Type='".$object->Type."'").")";
					    $query.=" Where r.NT=0 and r.I_R!='R' and r.Course='L'  and r.ATHLETE =".arg(4);
		
					    //Short course query
					    $query.=" union SELECT r.I_R,e.FastCut,e.SlowCut,e.Fast_L,e.Slow_L,((e.Lo_Age*100)+e.Hi_Age) as Lo_Hi,e.Sex,e.Stroke,e.Distance,floor( r.Score + IF(r.Course='L',(r.Distance/50)-(r.Distance/25),(r.Distance/25)-(r.Distance/50))*IF(r.STROKE=1 OR r.STROKE=5,80,IF(r.STROKE=2,60,IF(r.STROKE=3,100,IF(r.STROKE=4,70,0))))) as SCORE,r.Course,m.MName,m.Start,floor( r.Score + IF(r.Course='L',(r.Distance/50)-(r.Distance/25),(r.Distance/25)-(r.Distance/50))*IF(r.STROKE=1 OR r.STROKE=5,80,IF(r.STROKE=2,60,IF(r.STROKE=3,100,IF(r.STROKE=4,70,0))))) as conv,1 as pref,If((e.Slow_L>=0),If(e.Slow_L>=floor( r.Score + IF(r.Course='L',(r.Distance/50)-(r.Distance/25),(r.Distance/25)-(r.Distance/50))*IF(r.STROKE=1 OR r.STROKE=5,80,IF(r.STROKE=2,60,IF(r.STROKE=3,100,IF(r.STROKE=4,70,0))))),11,12),11) + If((e.Fast_L>=0),IF((e.Fast_L>=0),If(e.Fast_L<floor( r.Score + IF(r.Course='L',(r.Distance/50)-(r.Distance/25),(r.Distance/25)-(r.Distance/50))*IF(r.STROKE=1 OR r.STROKE=5,80,IF(r.STROKE=2,60,IF(r.STROKE=3,100,IF(r.STROKE=4,70,0))))),0,-10),10),0) as qt  ";
					    $query.=" From (".$tm4db."result_".$season." as r inner JOIN ".$tm4db."mtevente_".$LSeason." as e on (e.I_R='I'".(($object->Age < $object->MinAge)?'and e.Lo_Hi!=99':'')." and ((r.DISTANCE = e.DISTANCE and r.STROKE=e.STROKE)) and (e.Sex='".$object->Sex."') and e.Lo_Age <= ".$object->Age." AND e.Hi_Age >= ".$object->Age."))";
					    $query.=" inner join ".$tm4db."meet_".$season." as m on (r.Meet = m.Meet ".(($object->Since ==null)?'':"and m.Start >= '".$object->Since."'").(($object->RestrictBest==False)?'':" and m.Type='".$object->Type."'").")";
					    $query.=" Where r.NT=0 and r.I_R='I' and r.Course='S'  and r.ATHLETE =".arg(4);
		
					 }
		
				       if($object->Course=='SO')
					 {
					    //Short course only query
					    $query.=" SELECT r.I_R,e.FastCut,e.SlowCut,e.Fast_L,e.Slow_L,((e.Lo_Age*100)+e.Hi_Age) as Lo_Hi,e.Sex,e.Stroke,e.Distance,r.SCORE,r.Course,m.MName,m.Start,r.Score as conv,1 as pref,If((e.SlowCut>=0),If(e.SlowCut>=r.Score,11,12),11) + If((e.FastCut>=0),IF((e.FastCut>=0),If(e.FastCut<r.Score,0,-10),10),0) as qt ";
					    $query.=" From (".$tm4db."result_".$season." as r inner JOIN ".$tm4db."mtevente_".$LSeason." as e on (e.I_R='I'".(($object->Age < $object->MinAge)?'and e.Lo_Hi!=99':'')." and ((r.DISTANCE = e.DISTANCE and r.STROKE=e.STROKE)) and (e.Sex='".$object->Sex."') and e.Lo_Age <= ".$object->Age." AND e.Hi_Age >= ".$object->Age."))";
					    $query.=" inner join ".$tm4db."meet_".$season." as m on (r.Meet = m.Meet ".(($object->Since ==null)?'':"and m.Start >= '".$object->Since."'").(($object->RestrictBest==False)?'':" and m.Type='".$object->Type."'").")";
					    $query.=" Where r.NT=0 and r.I_R!='L' and r.Course='S'  and r.ATHLETE =".arg(4);
					 }
		
				       if($object->Course=='SL')  //Considers Muti course cut meets
					 {	//Short course query
					    $query.=" SELECT r.I_R,e.FastCut,e.SlowCut,e.Fast_L,e.Slow_L,((e.Lo_Age*100)+e.Hi_Age) as Lo_Hi,e.Sex,e.Stroke,e.Distance,r.SCORE,r.Course,m.MName,m.Start,r.SCORE as conv,1 as pref,If((e.Slow_L>=0) Or (e.SlowCut>=0),If(e.SlowCut>=0,If(e.SlowCut>=r.Score,11,12),13),11) + If((e.FastCut>=0) Or (e.Fast_L>=0),If((e.FastCut>=0),If(e.FastCut<r.Score,0,-10),10),0) as qt ";
					    $query.=" From (".$tm4db."result_".$season." as r inner JOIN ".$tm4db."mtevente_".$LSeason." as e on (e.I_R='I'".(($object->Age < $object->MinAge)?'and e.Lo_Hi!=99':'')." and ((r.DISTANCE = e.DISTANCE and r.STROKE=e.STROKE)) and (e.Sex='".$object->Sex."') and e.Lo_Age <= ".$object->Age." AND e.Hi_Age >= ".$object->Age."))";
					    $query.=" inner join ".$tm4db."meet_".$season." as m on (r.Meet = m.Meet ".(($object->Since ==null)?'':"and m.Start >= '".$object->Since."'").(($object->RestrictBest==False)?'':" and m.Type='".$object->Type."'").")";
					    $query.=" Where r.NT=0 and r.I_R!='R' and r.Course='S'  and r.ATHLETE =".arg(4);
		
					    //long course query
					    $query.=" union SELECT r.I_R,e.FastCut,e.SlowCut,e.Fast_L,e.Slow_L,((e.Lo_Age*100)+e.Hi_Age) as Lo_Hi,e.Sex,e.Stroke,e.Distance,r.SCORE,r.Course,m.MName,m.Start,floor( r.Score + IF(r.Course='L',(r.Distance/50)-(r.Distance/25),(r.Distance/25)-(r.Distance/50))*IF(r.STROKE=1 OR r.STROKE=5,80,IF(r.STROKE=2,60,IF(r.STROKE=3,100,IF(r.STROKE=4,70,0))))) as conv,2 as pref,If((e.Slow_L>=0) Or (e.SlowCut>=0),If(e.Slow_L>=0,If(e.Slow_L>=r.Score,11,12),13),11) + If((e.FastCut>=0) Or (e.Fast_L>=0),If((e.Fast_L>=0),If(e.Fast_L<r.Score,0,-10),10),0) as qt ";
					    $query.=" From (".$tm4db."result_".$season." as r inner JOIN ".$tm4db."mtevente_".$LSeason." as e on (e.I_R='I'".(($object->Age < $object->MinAge)?'and e.Lo_Hi!=99':'')." and ((r.DISTANCE = e.DISTANCE and r.STROKE=e.STROKE)) and (e.Sex='".$object->Sex."') and e.Lo_Age <= ".$object->Age." AND e.Hi_Age >= ".$object->Age."))";
					    $query.=" inner join ".$tm4db."meet_".$season." as m on (r.Meet = m.Meet ".(($object->Since ==null)?'':"and m.Start >= '".$object->Since."'").(($object->RestrictBest==False)?'':" and m.Type='".$object->Type."'").")";
					    $query.=" Where r.NT=0 and r.I_R='I' and r.Course='L'  and r.ATHLETE =".arg(4);
		
					 }
		
				       if($object->Course=='S')  //Covert all times to shrt course then Consider them, note preferance (pref is equal here =1)
					 {
		
					    //Short course query
					    $query.=" SELECT r.I_R,e.FastCut,e.SlowCut,e.Fast_L,e.Slow_L,e.Lo_Hi,e.Sex,e.Stroke,e.Distance,r.SCORE as SCORE,r.Course,m.MName,m.Start,r.SCORE as conv,1 as pref,If((e.SlowCut>=0),If(e.SlowCut>=r.Score,11,12),11) + If((e.FastCut>=0),IF((e.FastCut>=0),If(e.FastCut<r.Score,0,-10),10),0) as qt ";
					    $query.=" From (".$tm4db."result_".$season." as r inner JOIN ".$tm4db."mtevente_".$LSeason." as e on (e.I_R='I'".(($object->Age < $object->MinAge)?'and e.Lo_Hi!=99':'')." and ((r.DISTANCE = e.DISTANCE and r.STROKE=e.STROKE)) and (e.Sex='".$object->Sex."') and e.Lo_Age <= ".$object->Age." AND e.Hi_Age >= ".$object->Age."))";
					    $query.=" inner join ".$tm4db."meet_".$season." as m on (r.Meet = m.Meet ".(($object->Since ==null)?'':"and m.Start >= '".$object->Since."'").(($object->RestrictBest==False)?'':" and m.Type='".$object->Type."'").")";
					    $query.=" Where r.NT=0 and r.I_R!='R' and r.Course='S'  and r.ATHLETE =".arg(4);
		
					    //long course query
					    $query.=" union SELECT r.I_R,e.FastCut,e.SlowCut,e.Fast_L,e.Slow_L,e.Lo_Hi,e.Sex,e.Stroke,e.Distance,floor( r.Score + IF(r.Course='L',(r.Distance/50)-(r.Distance/25),(r.Distance/25)-(r.Distance/50))*IF(r.STROKE=1 OR r.STROKE=5,80,IF(r.STROKE=2,60,IF(r.STROKE=3,100,IF(r.STROKE=4,70,0))))) as SCORE,r.Course,m.MName,m.Start,floor( r.Score + IF(r.Course='L',(r.Distance/50)-(r.Distance/25),(r.Distance/25)-(r.Distance/50))*IF(r.STROKE=1 OR r.STROKE=5,80,IF(r.STROKE=2,60,IF(r.STROKE=3,100,IF(r.STROKE=4,70,0))))) as conv,1 as pref,If((e.SlowCut>=0),If(e.SlowCut>=floor( r.Score + IF(r.Course='L',(r.Distance/50)-(r.Distance/25),(r.Distance/25)-(r.Distance/50))*IF(r.STROKE=1 OR r.STROKE=5,80,IF(r.STROKE=2,60,IF(r.STROKE=3,100,IF(r.STROKE=4,70,0))))),11,12),11) + If((e.FastCut>=0),IF((e.FastCut>=0),If(e.FastCut<floor( r.Score + IF(r.Course='L',(r.Distance/50)-(r.Distance/25),(r.Distance/25)-(r.Distance/50))*IF(r.STROKE=1 OR r.STROKE=5,80,IF(r.STROKE=2,60,IF(r.STROKE=3,100,IF(r.STROKE=4,70,0))))),0,-10),10),0) as qt ";
					    $query.=" From (".$tm4db."result_".$season." as r inner JOIN ".$tm4db."mtevente_".$LSeason." as e on (e.I_R='I'".(($object->Age < $object->MinAge)?'and e.Lo_Hi!=99':'')." and ((r.DISTANCE = e.DISTANCE and r.STROKE=e.STROKE)) and (e.Sex='".$object->Sex."') and e.Lo_Age <= ".$object->Age." AND e.Hi_Age >= ".$object->Age."))";
					    $query.=" inner join ".$tm4db."meet_".$season." as m on (r.Meet = m.Meet ".(($object->Since ==null)?'':"and m.Start >= '".$object->Since."'").(($object->RestrictBest==False)?'':" and m.Type='".$object->Type."'").")";
					    $query.=" Where r.NT=0 and r.I_R='I' and r.Course='L'  and r.ATHLETE =".arg(4);
		
					 }
