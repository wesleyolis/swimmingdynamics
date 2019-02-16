<?php			














				





//meet entries








      $output.="<table border='0' width='100%'><tr><td valign='top'>";

		       $output.='<br>'.l(t($object->MName),'meets/'.$LSeason.'/info/'.arg(5)).'<div id="noprint"><br><br>';
		       $output.="Age Update date: ".get_date((($ageUp !=null)?$ageUp:$object->Start))." (".$age."yrs)<br><br>";
		       $output.="Qualifying Times Enforced: ".yesno($object->EnforceQualifying);
		       $output.="<br><br>Min Open Age: ".hasvalue('',$min_age);

		       $output.="</div></td><td width='250px' valign='top'><div id='noprint'><br>".l("Course: ".$object->Course,'meets/'.$LSeason.'/info/'.arg(5))."<br><br>N.B Border Colors Apply to Slower than qt times.<br><br><br>Maxmium Entries: ".hasvalue('',$object->MaxIndEnt)."</td></div><td align='right'>";

		       $output.="<div id='noprint'><table class='entries' border='0' cellpadding='4px' width='359px'>";

		       $output.="<tr><td colspan='2'><b>Colors Key:</b></td></tr>";
		       $output.="<tr><td width='101' class='green'>Green</td><td><b>May enter with pleasure</b></td></tr>";
		       $output.="<tr><td width='101' class='yellow min_yellow'>Yellow/ Orange-border</td><td><b>Possible entry</b>, depends on  meet rules. haven't met qt times, but qt times aren't being enforced! </td></tr>";
		       $output.="<tr><td width='101' class='red min_red'>Red/ Brown-border</td><td><b>May not enter</b> as one does not meet pre-requisites for event.</td></tr>";
		       $output.="</table></div>";

		       $output.="</td></tr><tr><td colspan='3'>";
		    }
		  else
		    if(arg(3)== 'mail' || arg(3)== 'confirm') //mail page
		      {

			 if($_POST["meet"]==$meet & ( $_POST["email"]!=null || $_POST["cemail"]!=null) || arg(3)=='mail')
			   {}
			 else
			   {
			      drupal_set_message('Please Enter an email address');
			      drupal_goto('athlete/'.arg(1).'/entries/events/'.arg(4).'/'.arg(5));
			   }
			 drupal_add_js(path().'/js/athlete.js',null,'header',true,TRUE);

			 $query1 = "Select a.*,t.*, extract(YEAR FROM from_days(datediff(CURDATE(), a.Birth))) as Age from ".$tm4db."athlete_".$season." as a left join ".$tm4db."team_".$season." as t on (a.team1=t.team) where Athlete= %d";
			 $result1= db_query($query1,arg(4));
			 $object1 = db_fetch_object($result1);
			 if($object1 ==null)
			   {
			      drupal_set_message("Error Athlete was not found!");
			      drupal_goto('athlete');
			   }

			 if(arg(3)== 'confirm' || $_POST["email"]!=null)
			   {
			      $output.='<br>Please double check and confirm your entries are to be sent to \''.$_POST['email'].'\'.<br>if the email address is wrong, please enter the correct e-mail adress in the box below, else leave blank<br>';
			   }

			 drupal_set_title($object1->First.' '.(($object1->Initial==null)?'':$object1->Initial.'. ').$object1->Last.'&nbsp; '.Gender($object1->Sex).'&nbsp; '.$object1->Age.'yrs &nbsp;('.get_date($object1->Birth).') &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$object1->TName.' - '.$object1->LSC.'');
			 $output.='<br>'.l(t($object->MName),'meets/'.$LSeason.'/info/'.arg(5)).'<br><br>';
			 $output.= $object1->First.' '.(($object1->Initial==null)?'':$object1->Initial.'. ').$object1->Last.'&nbsp; '.Gender($object1->Sex).'&nbsp; '.$object1->Age.'yrs &nbsp;('.get_date($object1->Birth).') <br>'.$object1->TName.' - '.$object1->LSC.'<br><br>';

			 $subject = $object->MName .' Entries '.$object1->First.' - '.(($object1->Initial==null)?'':$object1->Initial.'. ').$object1->Last;

		      }

		  $output.='';
					/*
					Pref is the used for order of selection, of which course comes first.
					qt if 1 means time qualifies for entry, if 2 then he does not qulify but it is depened on weather qt times are enforced.
					qt 3 means time does not qualify, because there are qt times avalible just not for that course.
					qt of less than 10 means he does not meet the slower than qt time, but these times are not enforced.
					qt larger than 20 means does no qualify.
					*/
		  if($object->Course=='LO' ||$object->Course=='LS' ||$object->Course=='SL' ||$object->Course=='SO' || $object->Course=='S' || $object->Course=='L')
		    {
		       $query="";
		       $query.="SELECT f.*, min(f.qt) as qt, min(f.pref) as pref, min(f.conv) From ("; //select final information
		       $query.="SELECT h.* From (";// orders envents for preferance selection

		       $query.="SELECT f.*, min(f.SCORE) as SCORE2 From ("; //limits times to top time in each course
		       $query.="SELECT t.* From ("; //test all times
		       if($object->Course=='LO')
			 {
			    //long course only query
			    $query.=" SELECT r.I_R,e.Session,e.FastCut,e.SlowCut,e.Fast_L,e.Slow_L, e.MtEv,e.MtEvX,e.Lo_Hi,e.Sex,e.Stroke,e.Distance,e.Fee,r.SCORE,r.Course,m.MName,m.Start,r.Score as conv,1 as pref,If((e.Slow_L>=0),If(e.Slow_L>=r.Score,11,12),11) + If((e.Fast_L>=0),IF((e.Fast_L>=0),If(e.Fast_L<r.Score,0,-10),10),0) as qt ";
			    $query.=" From (".$tm4db."result_".$season." as r inner JOIN ".$tm4db."mtevente_".$LSeason." as e on (e.Meet=".arg(5)." and e.I_R='I'".(($age < $min_age)?'and e.Lo_Hi!=99':'')." and ((r.DISTANCE = e.DISTANCE and r.STROKE=e.STROKE)) and (e.Sex='X' Or e.Sex='".$sex."') and floor(e.Lo_Hi/100) <= ".$age." AND (e.Lo_Hi%100) >= ".$age."))";
			    $query.=" inner join ".$tm4db."meet_".$season." as m on (r.Meet = m.Meet ".(($since ==null)?'':"and m.Start >= '".$since."'").(($restrictbest==False)?'':" and m.Type='".$type."'").")";
			    $query.=" Where r.NT=0 and r.I_R!='R' and r.Course='L'  and r.ATHLETE =".$athlete;
			 }

		       if($object->Course=='LS') //Considers Muti course cut meets
			 {
			    //long course query
			 
			    $query.=" SELECT r.I_R,e.Session,e.FastCut,e.SlowCut,e.Fast_L,e.Slow_L,e.MtEv,e.MtEvX,e.Lo_Hi,e.Sex,e.Stroke,e.Distance,e.Fee,r.SCORE,r.Course,m.MName,m.Start,r.Score as conv,1 as pref,If((e.Slow_L>=0) Or (e.SlowCut>=0),If(e.Slow_L>=0,If(e.Slow_L>=r.Score,11,12),13),11) + If((e.FastCut>=0) Or (e.Fast_L>=0),If((e.Fast_L>=0),If(e.Fast_L<r.Score,0,-10),10),0) as qt ";
			    $query.=" From (".$tm4db."result_".$season." as r inner JOIN ".$tm4db."mtevente_".$LSeason." as e on (e.Meet=".arg(5)." and e.I_R='I'".(($age < $min_age)?'and e.Lo_Hi!=99':'')." and ((r.DISTANCE = e.DISTANCE and r.STROKE=e.STROKE)) and (e.Sex='X' Or e.Sex='".$sex."') and floor(e.Lo_Hi/100) <= ".$age." AND (e.Lo_Hi%100) >= ".$age."))";
			    $query.=" inner join ".$tm4db."meet_".$season." as m on (r.Meet = m.Meet ".(($since ==null)?'':"and m.Start >= '".$since."'").(($restrictbest==False)?'':" and m.Type='".$type."'").")";
			    $query.=" Where r.NT=0 and r.I_R!='R' and r.Course='L'  and r.ATHLETE =".$athlete;
			    
			    //Short course query
			    $query.=" union SELECT r.I_R,e.Session,e.FastCut,e.SlowCut,e.Fast_L,e.Slow_L,e.MtEv,e.MtEvX,e.Lo_Hi,e.Sex,e.Stroke,e.Distance,e.Fee,r.SCORE,r.Course,m.MName,m.Start,floor( r.Score + IF(r.Course='L',(r.Distance/50)-(r.Distance/25),(r.Distance/25)-(r.Distance/50))*IF(r.STROKE=1 OR r.STROKE=5,80,IF(r.STROKE=2,60,IF(r.STROKE=3,100,IF(r.STROKE=4,70,0))))) as conv,2 as pref,If((e.Slow_L>=0) Or (e.SlowCut>=0),If(e.SlowCut>=0,If(e.SlowCut>=r.Score,11,12),13),11) + If((e.FastCut>=0) Or (e.Fast_L>=0),If((e.FastCut>=0),If(e.FastCut<r.Score,0,-10),10),0) as qt ";
			    $query.=" From (".$tm4db."result_".$season." as r inner JOIN ".$tm4db."mtevente_".$LSeason." as e on (e.Meet=".arg(5)." and e.I_R='I'".(($age < $min_age)?'and e.Lo_Hi!=99':'')." and ((r.DISTANCE = e.DISTANCE and r.STROKE=e.STROKE)) and (e.Sex='X' Or e.Sex='".$sex."') and floor(e.Lo_Hi/100) <= ".$age." AND (e.Lo_Hi%100) >= ".$age."))";
			    $query.=" inner join ".$tm4db."meet_".$season." as m on (r.Meet = m.Meet ".(($since ==null)?'':"and m.Start >= '".$since."'").(($restrictbest==False)?'':" and m.Type='".$type."'").")";
			    $query.=" Where r.NT=0 and r.I_R='I' and r.Course='S'  and r.ATHLETE =".$athlete;

			 }

		       if($object->Course=='L') //Covert all times to long course then Consider them, note preferance (pref is equal here =1)
			 {
			    //long course query
			    $query.=" SELECT r.I_R,e.Session,e.FastCut,e.SlowCut,e.Fast_L,e.Slow_L,e.MtEv,e.MtEvX,e.Lo_Hi,e.Sex,e.Stroke,e.Distance,e.Fee,r.SCORE as SCORE,r.Course,m.MName,m.Start,r.Score as conv,1 as pref,If((e.Slow_L>=0),If(e.Slow_L>=r.Score,11,12),11) + If((e.Fast_L>=0),IF((e.Fast_L>=0),If(e.Fast_L<r.Score,0,-10),10),0) as qt  ";
			    $query.=" From (".$tm4db."result_".$season." as r inner JOIN ".$tm4db."mtevente_".$LSeason." as e on (e.Meet=".arg(5)." and e.I_R='I'".(($age < $min_age)?'and e.Lo_Hi!=99':'')." and ((r.DISTANCE = e.DISTANCE and r.STROKE=e.STROKE)) and (e.Sex='X' Or e.Sex='".$sex."') and floor(e.Lo_Hi/100) <= ".$age." AND (e.Lo_Hi%100) >= ".$age."))";
			    $query.=" inner join ".$tm4db."meet_".$season." as m on (r.Meet = m.Meet ".(($since ==null)?'':"and m.Start >= '".$since."'").(($restrictbest==False)?'':" and m.Type='".$type."'").")";
			    $query.=" Where r.NT=0 and r.I_R!='R' and r.Course='L'  and r.ATHLETE =".$athlete;

			    //Short course query
			    $query.=" union SELECT r.I_R,e.Session,e.FastCut,e.SlowCut,e.Fast_L,e.Slow_L,e.MtEv,e.MtEvX,e.Lo_Hi,e.Sex,e.Stroke,e.Distance,e.Fee,floor( r.Score + IF(r.Course='L',(r.Distance/50)-(r.Distance/25),(r.Distance/25)-(r.Distance/50))*IF(r.STROKE=1 OR r.STROKE=5,80,IF(r.STROKE=2,60,IF(r.STROKE=3,100,IF(r.STROKE=4,70,0))))) as SCORE,r.Course,m.MName,m.Start,floor( r.Score + IF(r.Course='L',(r.Distance/50)-(r.Distance/25),(r.Distance/25)-(r.Distance/50))*IF(r.STROKE=1 OR r.STROKE=5,80,IF(r.STROKE=2,60,IF(r.STROKE=3,100,IF(r.STROKE=4,70,0))))) as conv,1 as pref,If((e.Slow_L>=0),If(e.Slow_L>=floor( r.Score + IF(r.Course='L',(r.Distance/50)-(r.Distance/25),(r.Distance/25)-(r.Distance/50))*IF(r.STROKE=1 OR r.STROKE=5,80,IF(r.STROKE=2,60,IF(r.STROKE=3,100,IF(r.STROKE=4,70,0))))),11,12),11) + If((e.Fast_L>=0),IF((e.Fast_L>=0),If(e.Fast_L<floor( r.Score + IF(r.Course='L',(r.Distance/50)-(r.Distance/25),(r.Distance/25)-(r.Distance/50))*IF(r.STROKE=1 OR r.STROKE=5,80,IF(r.STROKE=2,60,IF(r.STROKE=3,100,IF(r.STROKE=4,70,0))))),0,-10),10),0) as qt  ";
			    $query.=" From (".$tm4db."result_".$season." as r inner JOIN ".$tm4db."mtevente_".$LSeason." as e on (e.Meet=".arg(5)." and e.I_R='I'".(($age < $min_age)?'and e.Lo_Hi!=99':'')." and ((r.DISTANCE = e.DISTANCE and r.STROKE=e.STROKE)) and (e.Sex='X' Or e.Sex='".$sex."') and floor(e.Lo_Hi/100) <= ".$age." AND (e.Lo_Hi%100) >= ".$age."))";
			    $query.=" inner join ".$tm4db."meet_".$season." as m on (r.Meet = m.Meet ".(($since ==null)?'':"and m.Start >= '".$since."'").(($restrictbest==False)?'':" and m.Type='".$type."'").")";
			    $query.=" Where r.NT=0 and r.I_R='I' and r.Course='S'  and r.ATHLETE =".$athlete;

			 }

		       if($object->Course=='SO')
			 {
			    //Short course only query
			    $query.=" SELECT r.I_R,e.Session,e.FastCut,e.SlowCut,e.Fast_L,e.Slow_L, e.MtEv,e.MtEvX,e.Lo_Hi,e.Sex,e.Stroke,e.Distance,e.Fee,r.SCORE,r.Course,m.MName,m.Start,r.Score as conv,1 as pref,If((e.SlowCut>=0),If(e.SlowCut>=r.Score,11,12),11) + If((e.FastCut>=0),IF((e.FastCut>=0),If(e.FastCut<r.Score,0,-10),10),0) as qt ";
			    $query.=" From (".$tm4db."result_".$season." as r inner JOIN ".$tm4db."mtevente_".$LSeason." as e on (e.Meet=".arg(5)." and e.I_R='I'".(($age < $min_age)?'and e.Lo_Hi!=99':'')." and ((r.DISTANCE = e.DISTANCE and r.STROKE=e.STROKE)) and (e.Sex='X' Or e.Sex='".$sex."') and floor(e.Lo_Hi/100) <= ".$age." AND (e.Lo_Hi%100) >= ".$age."))";
			    $query.=" inner join ".$tm4db."meet_".$season." as m on (r.Meet = m.Meet ".(($since ==null)?'':"and m.Start >= '".$since."'").(($restrictbest==False)?'':" and m.Type='".$type."'").")";
			    $query.=" Where r.NT=0 and r.I_R!='L' and r.Course='S'  and r.ATHLETE =".$athlete;
			 }

		       if($object->Course=='SL')  //Considers Muti course cut meets
			 {	//Short course query
			    $query.=" SELECT r.I_R,e.Session,e.FastCut,e.SlowCut,e.Fast_L,e.Slow_L,e.MtEv,e.MtEvX,e.Lo_Hi,e.Sex,e.Stroke,e.Distance,e.Fee,r.SCORE,r.Course,m.MName,m.Start,r.SCORE as conv,1 as pref,If((e.Slow_L>=0) Or (e.SlowCut>=0),If(e.SlowCut>=0,If(e.SlowCut>=r.Score,11,12),13),11) + If((e.FastCut>=0) Or (e.Fast_L>=0),If((e.FastCut>=0),If(e.FastCut<r.Score,0,-10),10),0) as qt ";
			    $query.=" From (".$tm4db."result_".$season." as r inner JOIN ".$tm4db."mtevente_".$LSeason." as e on (e.Meet=".arg(5)." and e.I_R='I'".(($age < $min_age)?'and e.Lo_Hi!=99':'')." and ((r.DISTANCE = e.DISTANCE and r.STROKE=e.STROKE)) and (e.Sex='X' Or e.Sex='".$sex."') and floor(e.Lo_Hi/100) <= ".$age." AND (e.Lo_Hi%100) >= ".$age."))";
			    $query.=" inner join ".$tm4db."meet_".$season." as m on (r.Meet = m.Meet ".(($since ==null)?'':"and m.Start >= '".$since."'").(($restrictbest==False)?'':" and m.Type='".$type."'").")";
			    $query.=" Where r.NT=0 and r.I_R!='R' and r.Course='S'  and r.ATHLETE =".$athlete;

			    //long course query
			    $query.=" union SELECT r.I_R,e.Session,e.FastCut,e.SlowCut,e.Fast_L,e.Slow_L,e.MtEv,e.MtEvX,e.Lo_Hi,e.Sex,e.Stroke,e.Distance,e.Fee,r.SCORE,r.Course,m.MName,m.Start,floor( r.Score + IF(r.Course='L',(r.Distance/50)-(r.Distance/25),(r.Distance/25)-(r.Distance/50))*IF(r.STROKE=1 OR r.STROKE=5,80,IF(r.STROKE=2,60,IF(r.STROKE=3,100,IF(r.STROKE=4,70,0))))) as conv,2 as pref,If((e.Slow_L>=0) Or (e.SlowCut>=0),If(e.Slow_L>=0,If(e.Slow_L>=r.Score,11,12),13),11) + If((e.FastCut>=0) Or (e.Fast_L>=0),If((e.Fast_L>=0),If(e.Fast_L<r.Score,0,-10),10),0) as qt ";
			    $query.=" From (".$tm4db."result_".$season." as r inner JOIN ".$tm4db."mtevente_".$LSeason." as e on (e.Meet=".arg(5)." and e.I_R='I'".(($age < $min_age)?'and e.Lo_Hi!=99':'')." and ((r.DISTANCE = e.DISTANCE and r.STROKE=e.STROKE)) and (e.Sex='X' Or e.Sex='".$sex."') and floor(e.Lo_Hi/100) <= ".$age." AND (e.Lo_Hi%100) >= ".$age."))";
			    $query.=" inner join ".$tm4db."meet_".$season." as m on (r.Meet = m.Meet ".(($since ==null)?'':"and m.Start >= '".$since."'").(($restrictbest==False)?'':" and m.Type='".$type."'").")";
			    $query.=" Where r.NT=0 and r.I_R='I' and r.Course='L'  and r.ATHLETE =".$athlete;

			 }

		       if($object->Course=='S')  //Covert all times to shrt course then Consider them, note preferance (pref is equal here =1)
			 {

			    //Short course query
			    $query.=" SELECT r.I_R,e.Session,e.FastCut,e.SlowCut,e.Fast_L,e.Slow_L,e.MtEv,e.MtEvX,e.Lo_Hi,e.Sex,e.Stroke,e.Distance,e.Fee,r.SCORE as SCORE,r.Course,m.MName,m.Start,r.SCORE as conv,1 as pref,If((e.SlowCut>=0),If(e.SlowCut>=r.Score,11,12),11) + If((e.FastCut>=0),IF((e.FastCut>=0),If(e.FastCut<r.Score,0,-10),10),0) as qt ";
			    $query.=" From (".$tm4db."result_".$season." as r inner JOIN ".$tm4db."mtevente_".$LSeason." as e on (e.Meet=".arg(5)." and e.I_R='I'".(($age < $min_age)?'and e.Lo_Hi!=99':'')." and ((r.DISTANCE = e.DISTANCE and r.STROKE=e.STROKE)) and (e.Sex='X' Or e.Sex='".$sex."') and floor(e.Lo_Hi/100) <= ".$age." AND (e.Lo_Hi%100) >= ".$age."))";
			    $query.=" inner join ".$tm4db."meet_".$season." as m on (r.Meet = m.Meet ".(($since ==null)?'':"and m.Start >= '".$since."'").(($restrictbest==False)?'':" and m.Type='".$type."'").")";
			    $query.=" Where r.NT=0 and r.I_R!='R' and r.Course='S'  and r.ATHLETE =".$athlete;

			    //long course query
			    $query.=" union SELECT r.I_R,e.Session,e.FastCut,e.SlowCut,e.Fast_L,e.Slow_L,e.MtEv,e.MtEvX,e.Lo_Hi,e.Sex,e.Stroke,e.Distance,e.Fee,floor( r.Score + IF(r.Course='L',(r.Distance/50)-(r.Distance/25),(r.Distance/25)-(r.Distance/50))*IF(r.STROKE=1 OR r.STROKE=5,80,IF(r.STROKE=2,60,IF(r.STROKE=3,100,IF(r.STROKE=4,70,0))))) as SCORE,r.Course,m.MName,m.Start,floor( r.Score + IF(r.Course='L',(r.Distance/50)-(r.Distance/25),(r.Distance/25)-(r.Distance/50))*IF(r.STROKE=1 OR r.STROKE=5,80,IF(r.STROKE=2,60,IF(r.STROKE=3,100,IF(r.STROKE=4,70,0))))) as conv,1 as pref,If((e.SlowCut>=0),If(e.SlowCut>=floor( r.Score + IF(r.Course='L',(r.Distance/50)-(r.Distance/25),(r.Distance/25)-(r.Distance/50))*IF(r.STROKE=1 OR r.STROKE=5,80,IF(r.STROKE=2,60,IF(r.STROKE=3,100,IF(r.STROKE=4,70,0))))),11,12),11) + If((e.FastCut>=0),IF((e.FastCut>=0),If(e.FastCut<floor( r.Score + IF(r.Course='L',(r.Distance/50)-(r.Distance/25),(r.Distance/25)-(r.Distance/50))*IF(r.STROKE=1 OR r.STROKE=5,80,IF(r.STROKE=2,60,IF(r.STROKE=3,100,IF(r.STROKE=4,70,0))))),0,-10),10),0) as qt ";
			    $query.=" From (".$tm4db."result_".$season." as r inner JOIN ".$tm4db."mtevente_".$LSeason." as e on (e.Meet=".arg(5)." and e.I_R='I'".(($age < $min_age)?'and e.Lo_Hi!=99':'')." and ((r.DISTANCE = e.DISTANCE and r.STROKE=e.STROKE)) and (e.Sex='X' Or e.Sex='".$sex."') and floor(e.Lo_Hi/100) <= ".$age." AND (e.Lo_Hi%100) >= ".$age."))";
			    $query.=" inner join ".$tm4db."meet_".$season." as m on (r.Meet = m.Meet ".(($since ==null)?'':"and m.Start >= '".$since."'").(($restrictbest==False)?'':" and m.Type='".$type."'").")";
			    $query.=" Where r.NT=0 and r.I_R='I' and r.Course='L'  and r.ATHLETE =".$athlete;

			 }

		       $query.=" ) as t order by MtEv,MtEvX,pref,SCORE, Start desc";

		       $query.=") as f Group by MtEv,MtEvX,pref";

		       $query.=" union SELECT null as I_R,e.Session,e.FastCut,e.SlowCut,e.Fast_L,e.Slow_L,e.MtEv,e.MtEvX,e.Lo_Hi,e.Sex,e.Stroke,e.Distance,e.Fee, null as SCORE, null as Course, null as MName, '' as Start, null as conv,13 as pref,If((e.FastCut>=0) Or (e.Fast_L>=0) Or (e.Slow_L>=0) Or (e.SlowCut>=0),13,11) as qt, null as SCORE ";
		       $query.=" From (".$tm4db."mtevente_".$LSeason." as e) Where (e.Meet=".arg(5)." and e.I_R='I'".(($age < $min_age)?'and e.Lo_Hi!=99':'')."  and (e.Sex='X' Or e.Sex='".$sex."') and floor(e.Lo_Hi/100) <= ".$age." AND (e.Lo_Hi%100) >= ".$age.")";

		       $query.=" ) as h  order by Session, MtEv,MtEvX,qt,pref,conv";

		       $query.=" ) as f Group by f.MtEv,f.MtEvX order by Session,MtEv,MtEvX,qt,pref,conv, Start desc";
				  
				  
				  
				  
				  
				  
				  
				  
				  //standars




				       
