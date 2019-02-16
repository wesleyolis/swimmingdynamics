<?php
{
	     $LSeason = get_cur_seasons();

	     if(arg(3)== 'events' || arg(3)== 'mail' || arg(3)== 'confirm')
	       {

		  $result = db_query("select SQL_CACHE if(m.RestrictBest=1,True,False) as RestrictBest2,if(m.EnforceQualifying=1,True,False) as EnforceQualifying2,m.*,a.*, extract(YEAR FROM from_days(datediff(IF(m.AgeUp is null,m.Start,m.AgeUp), Birth))) as Age from ".$tm4db."meet_".$LSeason." as m, ".$tm4db."athlete_".$season." as a Where m.meet=%d and a.Athlete=%d",arg(5),arg(4));

		  $object = db_fetch_object($result);
		  if($object ==null)
		    {
		       drupal_set_message('meet not Found');
		       drupal_goto('athlete/entries/'.arg(4));
		    }

		  $enforce = (int) $object->EnforceQualifying2;
		  $enterqt = (int) $object->EnterAtQTime;
		  $coursepref = substr($object->Course, 0, 1);
		  $multi = (($object->Course == 'L' || $object->Course == 'LO')?0:(($object->Course == 'S'||$object->Course == 'SO')?1:(3+(($coursepref=='L')?0:1))));
		  $meet = arg(5);
		   $age = $object->Age;
		  $since = $object->Since;
		  $since = ($since ==null || $since=='0000-00-00 00:00:00')?null:$since;
		  $restrictbest = (int) $object->RestrictBest;

		  $sex = $object->Sex;
		  $type = $obejct->Type;
		  $min_age = $object->MinAge;
		  $min_age = ($min_age==null)?0:$min_age;
		  $athlete = arg(4);
		  //$output.=$restrictbest;
		  if(arg(3)== 'events') //entry page
		    {
		       $output.= athlete_heading($tm4db,$season,arg(4));

		        $output.="<table border='0' width='100%'><tr><td valign='top'>";

		       $output.='<br>'.l(t($object->MName),'meets/'.$LSeason.'/info/'.arg(5)).'<div id="noprint"><br><br>';
		       $output.="Age Update date: ".get_date((($ageUp !=null)?$ageUp:$object->Start))." (".$age."yrs)<br><br>";
		       $output.="Qualifying Times Enforced: ".yesno($object->EnforceQualifying2);
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
		    if(arg(3)== 'mail' || arg(3)== 'confirm'  || $_POST['submit']=='Printable Summary') //mail page
		      {

			 if($_POST["meet"]==$meet & ( $_POST["email"]!=null || $_POST["cemail"]!=null) || arg(3)=='mail'  || $_POST['submit']=='Printable Summary')
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

			 if((arg(3)== 'confirm' || $_POST["email"]!=null) & $_POST['submit']!='Printable Summary')
			   {
			      $output.='<span class="no_print"><br>Please double check and confirm your entries are to be sent to \''.$_POST['email'].'\'.<br>If the email address is wrong, please enter the correct e-mail adress in the box below, else leave blank and click the button email<br>Note is the time to print to have a hard copy<br></span>';
			   }

			 drupal_set_title($object1->First.' '.(($object1->Initial==null)?'':$object1->Initial.'. ').$object1->Last.'&nbsp; '.Gender($object1->Sex).'&nbsp; '.$object1->Age.'yrs &nbsp;('.get_date($object1->Birth).') &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$object1->TName.' - '.$object1->LSC.'');
			 $output.='<br>'.l(t($object->MName),'meets/'.$LSeason.'/info/'.arg(5)).'<br><br>';
			 if($_POST['submit']!='Printable Summary')
			 $output.= '<span class="no_print">'.$object1->First.' '.(($object1->Initial==null)?'':$object1->Initial.'. ').$object1->Last.'&nbsp; '.Gender($object1->Sex).'&nbsp; '.$object1->Age.'yrs &nbsp;('.get_date($object1->Birth).') <br>'.$object1->TName.' - '.$object1->LSC.'<br><br></span>';

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
		       $query.="SELECT SQL_CACHE f.*, min(f.qt) as qt, min(f.pref) as pref, min(f.conv) From ("; //select final information
		       $query.="SELECT h.* From (";// orders envents for preferance selection

		       $query.="SELECT f.*, min(f.SCORE) as SCORE2 From ("; //limits times to top time in each course
		       $query.="SELECT t.* From ("; //test all times
		        if($object->Course=='LO')
			 {
				 $qtset=' (e.Fast_L>0) Or (e.Slow_L>0)';
			    //long course only query
			    $query.=" SELECT r.I_R,e.Session,e.FastCut,e.SlowCut,e.Fast_L,e.Slow_L, e.MtEv,e.MtEvX,e.Lo_Hi,e.Sex,e.Stroke,e.Distance,e.Fee,r.SCORE,r.Course,m.MName,m.Start,r.Score as conv,1 as pref,If((e.Slow_L>0),If(e.Slow_L>=r.Score,11,12),11) + If((e.Fast_L>0),If(e.Fast_L<=r.Score,0,-10),0) as qt ";
			    $query.=" From (".$tm4db."result_".$season." as r inner JOIN ".$tm4db."mtevente_".$LSeason." as e on (e.Meet=".arg(5)." and e.I_R='I'".(($age < $min_age)?'and e.Lo_Hi!=99':'')." and ((r.DISTANCE = e.DISTANCE and r.STROKE=e.STROKE)) and (e.Sex='X' Or e.Sex='".$sex."') and floor(e.Lo_Hi/100) <= ".$age." AND (e.Lo_Hi%100) >= ".$age."))";
			    $query.=" inner join ".$tm4db."meet_".$season." as m on (r.Meet = m.Meet ".(($since ==null)?'':"and m.Start >= '".$since."'").((!$restrictbest)?'':" and m.Type='".$type."'").")";
			    $query.=" Where r.NT=0 and r.I_R!='R' and r.Course='L'  and r.ATHLETE =".$athlete;
			 }

		       if($object->Course=='LS') //Considers Muti course cut meets
			 {
				 $qtset='(e.FastCut>0) Or (e.Fast_L>0) Or (e.Slow_L>0) Or (e.SlowCut>0)';
				
			    //long course query
			 
			    $query.=" SELECT r.I_R,e.Session,e.FastCut,e.SlowCut,e.Fast_L,e.Slow_L,e.MtEv,e.MtEvX,e.Lo_Hi,e.Sex,e.Stroke,e.Distance,e.Fee,r.SCORE,r.Course,m.MName,m.Start,r.Score as conv,1 as pref,If((e.Slow_L>0) Or (e.SlowCut>0),If(e.Slow_L>0,If(e.Slow_L>=r.Score,11,12),13),11) + If((e.FastCut>0) Or (e.Fast_L>0),If((e.Fast_L>0),If(e.Fast_L<=r.Score,0,-10),10),0) as qt ";
			    $query.=" From (".$tm4db."result_".$season." as r inner JOIN ".$tm4db."mtevente_".$LSeason." as e on (e.Meet=".arg(5)." and e.I_R='I'".(($age < $min_age)?'and e.Lo_Hi!=99':'')." and ((r.DISTANCE = e.DISTANCE and r.STROKE=e.STROKE)) and (e.Sex='X' Or e.Sex='".$sex."') and floor(e.Lo_Hi/100) <= ".$age." AND (e.Lo_Hi%100) >= ".$age."))";
			    $query.=" inner join ".$tm4db."meet_".$season." as m on (r.Meet = m.Meet ".(($since ==null)?'':"and m.Start >= '".$since."'").((!$restrictbest)?'':" and m.Type='".$type."'").")";
			    $query.=" Where r.NT=0 and r.I_R!='R' and r.Course='L'  and r.ATHLETE =".$athlete;
			    
			    //Short course query
			    $query.=" union SELECT r.I_R,e.Session,e.FastCut,e.SlowCut,e.Fast_L,e.Slow_L,e.MtEv,e.MtEvX,e.Lo_Hi,e.Sex,e.Stroke,e.Distance,e.Fee,r.SCORE,r.Course,m.MName,m.Start,".course_conversion($tm4db,$season)." as conv,2 as pref,If((e.Slow_L>0) Or (e.SlowCut>0),If(e.SlowCut>0,If(e.SlowCut>=r.Score,11,12),13),11) + If((e.FastCut>0) Or (e.Fast_L>0),If((e.FastCut>0),If(e.FastCut<=r.Score,0,-10),10),0) as qt ";
			    $query.=" From (".$tm4db."result_".$season." as r inner JOIN ".$tm4db."mtevente_".$LSeason." as e on (e.Meet=".arg(5)." and e.I_R='I'".(($age < $min_age)?'and e.Lo_Hi!=99':'')." and ((r.DISTANCE = e.DISTANCE and r.STROKE=e.STROKE)) and (e.Sex='X' Or e.Sex='".$sex."') and floor(e.Lo_Hi/100) <= ".$age." AND (e.Lo_Hi%100) >= ".$age."))";
			    $query.=" inner join ".$tm4db."meet_".$season." as m on (r.Meet = m.Meet ".(($since ==null)?'':"and m.Start >= '".$since."'").((!$restrictbest)?'':" and m.Type='".$type."'").")";
			    $query.=" Where r.NT=0 and r.I_R='I' and r.Course='S'  and r.ATHLETE =".$athlete;

			 }

		       if($object->Course=='L') //Covert all times to long course then Consider them, note preferance (pref is equal here =1)
			 {
				  $qtset=' (e.Fast_L>0) Or (e.Slow_L>0)';
			    //long course query
			    $query.=" SELECT r.I_R,e.Session,e.FastCut,e.SlowCut,e.Fast_L,e.Slow_L,e.MtEv,e.MtEvX,e.Lo_Hi,e.Sex,e.Stroke,e.Distance,e.Fee,r.SCORE as SCORE,r.Course,m.MName,m.Start,r.Score as conv,1 as pref,If((e.Slow_L>0),If(e.Slow_L>=r.Score,11,12),11) + If((e.Fast_L>0),If(e.Fast_L<=r.Score,0,-10),0) as qt  ";
			    $query.=" From (".$tm4db."result_".$season." as r inner JOIN ".$tm4db."mtevente_".$LSeason." as e on (e.Meet=".arg(5)." and e.I_R='I'".(($age < $min_age)?'and e.Lo_Hi!=99':'')." and ((r.DISTANCE = e.DISTANCE and r.STROKE=e.STROKE)) and (e.Sex='X' Or e.Sex='".$sex."') and floor(e.Lo_Hi/100) <= ".$age." AND (e.Lo_Hi%100) >= ".$age."))";
			    $query.=" inner join ".$tm4db."meet_".$season." as m on (r.Meet = m.Meet ".(($since ==null)?'':"and m.Start >= '".$since."'").((!$restrictbest)?'':" and m.Type='".$type."'").")";
			    $query.=" Where r.NT=0 and r.I_R!='R' and r.Course='L'  and r.ATHLETE =".$athlete;

			    //Short course query
			    $query.=" union SELECT r.I_R,e.Session,e.FastCut,e.SlowCut,e.Fast_L,e.Slow_L,e.MtEv,e.MtEvX,e.Lo_Hi,e.Sex,e.Stroke,e.Distance,e.Fee,".course_conversion($tm4db,$season)." as SCORE,r.Course,m.MName,m.Start,".course_conversion($tm4db,$season)." as conv,1 as pref,If((e.Slow_L>0),If(e.Slow_L>=".course_conversion($tm4db,$season).",11,12),11) + If((e.Fast_L>0),If(e.Fast_L<=".course_conversion($tm4db,$season).",0,-10),0) as qt  ";
			    $query.=" From (".$tm4db."result_".$season." as r inner JOIN ".$tm4db."mtevente_".$LSeason." as e on (e.Meet=".arg(5)." and e.I_R='I'".(($age < $min_age)?'and e.Lo_Hi!=99':'')." and ((r.DISTANCE = e.DISTANCE and r.STROKE=e.STROKE)) and (e.Sex='X' Or e.Sex='".$sex."') and floor(e.Lo_Hi/100) <= ".$age." AND (e.Lo_Hi%100) >= ".$age."))";
			    $query.=" inner join ".$tm4db."meet_".$season." as m on (r.Meet = m.Meet ".(($since ==null)?'':"and m.Start >= '".$since."'").((!$restrictbest)?'':" and m.Type='".$type."'").")";
			    $query.=" Where r.NT=0 and r.I_R='I' and r.Course='S'  and r.ATHLETE =".$athlete;

			 }

		       if($object->Course=='SO')
			 {
				 $qtset='(e.FastCut>0) Or (e.SlowCut>0)';
			    //Short course only query
			    $query.=" SELECT r.I_R,e.Session,e.FastCut,e.SlowCut,e.Fast_L,e.Slow_L, e.MtEv,e.MtEvX,e.Lo_Hi,e.Sex,e.Stroke,e.Distance,e.Fee,r.SCORE,r.Course,m.MName,m.Start,r.Score as conv,1 as pref,If((e.SlowCut>0),If(e.SlowCut>=r.Score,11,12),11) + If((e.FastCut>0),If(e.FastCut<=r.Score,0,-10),0) as qt ";
			    $query.=" From (".$tm4db."result_".$season." as r inner JOIN ".$tm4db."mtevente_".$LSeason." as e on (e.Meet=".arg(5)." and e.I_R='I'".(($age < $min_age)?'and e.Lo_Hi!=99':'')." and ((r.DISTANCE = e.DISTANCE and r.STROKE=e.STROKE)) and (e.Sex='X' Or e.Sex='".$sex."') and floor(e.Lo_Hi/100) <= ".$age." AND (e.Lo_Hi%100) >= ".$age."))";
			    $query.=" inner join ".$tm4db."meet_".$season." as m on (r.Meet = m.Meet ".(($since ==null)?'':"and m.Start >= '".$since."'").((!$restrictbest)?'':" and m.Type='".$type."'").")";
			    $query.=" Where r.NT=0 and r.I_R!='L' and r.Course='S'  and r.ATHLETE =".$athlete;
			 }

		       if($object->Course=='SL')  //Considers Muti course cut meets
			 {	
				 $qtset='(e.FastCut>0) Or (e.Fast_L>0) Or (e.Slow_L>0) Or (e.SlowCut>0)';
				 //Short course query
			    $query.=" SELECT r.I_R,e.Session,e.FastCut,e.SlowCut,e.Fast_L,e.Slow_L,e.MtEv,e.MtEvX,e.Lo_Hi,e.Sex,e.Stroke,e.Distance,e.Fee,r.SCORE,r.Course,m.MName,m.Start,r.SCORE as conv,1 as pref,If((e.Slow_L>0) Or (e.SlowCut>0),If(e.SlowCut>0,If(e.SlowCut>=r.Score,11,12),13),11) + If((e.FastCut>0) Or (e.Fast_L>0),If((e.FastCut>0),If(e.FastCut<=r.Score,0,-10),10),0) as qt ";
			    $query.=" From (".$tm4db."result_".$season." as r inner JOIN ".$tm4db."mtevente_".$LSeason." as e on (e.Meet=".arg(5)." and e.I_R='I'".(($age < $min_age)?'and e.Lo_Hi!=99':'')." and ((r.DISTANCE = e.DISTANCE and r.STROKE=e.STROKE)) and (e.Sex='X' Or e.Sex='".$sex."') and floor(e.Lo_Hi/100) <= ".$age." AND (e.Lo_Hi%100) >= ".$age."))";
			    $query.=" inner join ".$tm4db."meet_".$season." as m on (r.Meet = m.Meet ".(($since ==null)?'':"and m.Start >= '".$since."'").((!$restrictbest)?'':" and m.Type='".$type."'").")";
			    $query.=" Where r.NT=0 and r.I_R!='R' and r.Course='S'  and r.ATHLETE =".$athlete;

			    //long course query
			    $query.=" union SELECT r.I_R,e.Session,e.FastCut,e.SlowCut,e.Fast_L,e.Slow_L,e.MtEv,e.MtEvX,e.Lo_Hi,e.Sex,e.Stroke,e.Distance,e.Fee,r.SCORE,r.Course,m.MName,m.Start,".course_conversion($tm4db,$season)." as conv,2 as pref,If((e.Slow_L>0) Or (e.SlowCut>0),If(e.Slow_L>0,If(e.Slow_L>=r.Score,11,12),13),11) + If((e.FastCut>0) Or (e.Fast_L>0),If((e.Fast_L>0),If(e.Fast_L<=r.Score,0,-10),10),0) as qt ";
			    $query.=" From (".$tm4db."result_".$season." as r inner JOIN ".$tm4db."mtevente_".$LSeason." as e on (e.Meet=".arg(5)." and e.I_R='I'".(($age < $min_age)?'and e.Lo_Hi!=99':'')." and ((r.DISTANCE = e.DISTANCE and r.STROKE=e.STROKE)) and (e.Sex='X' Or e.Sex='".$sex."') and floor(e.Lo_Hi/100) <= ".$age." AND (e.Lo_Hi%100) >= ".$age."))";
			    $query.=" inner join ".$tm4db."meet_".$season." as m on (r.Meet = m.Meet ".(($since ==null)?'':"and m.Start >= '".$since."'").((!$restrictbest)?'':" and m.Type='".$type."'").")";
			    $query.=" Where r.NT=0 and r.I_R='I' and r.Course='L'  and r.ATHLETE =".$athlete;

			 }

		       if($object->Course=='S')  //Covert all times to shrt course then Consider them, note preferance (pref is equal here =1)
			 {
				 $qtset='(e.FastCut>0) Or (e.SlowCut>0)';
			    //Short course query
			    $query.=" SELECT r.I_R,e.Session,e.FastCut,e.SlowCut,e.Fast_L,e.Slow_L,e.MtEv,e.MtEvX,e.Lo_Hi,e.Sex,e.Stroke,e.Distance,e.Fee,r.SCORE as SCORE,r.Course,m.MName,m.Start,r.SCORE as conv,1 as pref,If((e.SlowCut>0),If(e.SlowCut>=r.Score,11,12),11) + If((e.FastCut>0),If(e.FastCut<=r.Score,0,-10),0) as qt ";
			    $query.=" From (".$tm4db."result_".$season." as r inner JOIN ".$tm4db."mtevente_".$LSeason." as e on (e.Meet=".arg(5)." and e.I_R='I'".(($age < $min_age)?'and e.Lo_Hi!=99':'')." and ((r.DISTANCE = e.DISTANCE and r.STROKE=e.STROKE)) and (e.Sex='X' Or e.Sex='".$sex."') and floor(e.Lo_Hi/100) <= ".$age." AND (e.Lo_Hi%100) >= ".$age."))";
			    $query.=" inner join ".$tm4db."meet_".$season." as m on (r.Meet = m.Meet ".(($since ==null)?'':"and m.Start >= '".$since."'").((!$restrictbest)?'':" and m.Type='".$type."'").")";
			    $query.=" Where r.NT=0 and r.I_R!='R' and r.Course='S'  and r.ATHLETE =".$athlete;

			    //long course query
			    $query.=" union SELECT r.I_R,e.Session,e.FastCut,e.SlowCut,e.Fast_L,e.Slow_L,e.MtEv,e.MtEvX,e.Lo_Hi,e.Sex,e.Stroke,e.Distance,e.Fee,".course_conversion($tm4db,$season)." as SCORE,r.Course,m.MName,m.Start,".course_conversion($tm4db,$season)." as conv,1 as pref,If((e.SlowCut>0),If(e.SlowCut>=".course_conversion($tm4db,$season).",11,12),11) + If((e.FastCut>0),If(e.FastCut<=".course_conversion($tm4db,$season).",0,-10),0) as qt ";
			    $query.=" From (".$tm4db."result_".$season." as r inner JOIN ".$tm4db."mtevente_".$LSeason." as e on (e.Meet=".arg(5)." and e.I_R='I'".(($age < $min_age)?'and e.Lo_Hi!=99':'')." and ((r.DISTANCE = e.DISTANCE and r.STROKE=e.STROKE)) and (e.Sex='X' Or e.Sex='".$sex."') and floor(e.Lo_Hi/100) <= ".$age." AND (e.Lo_Hi%100) >= ".$age."))";
			    $query.=" inner join ".$tm4db."meet_".$season." as m on (r.Meet = m.Meet ".(($since ==null)?'':"and m.Start >= '".$since."'").((!$restrictbest)?'':" and m.Type='".$type."'").")";
			    $query.=" Where r.NT=0 and r.I_R='I' and r.Course='L'  and r.ATHLETE =".$athlete;

			 }

		       $query.=" ) as t order by MtEv,MtEvX,pref,SCORE, Start desc";

		       
		       
		       $query.=") as f Group by MtEv,MtEvX,pref";

		       $query.=" union SELECT null as I_R,e.Session,e.FastCut,e.SlowCut,e.Fast_L,e.Slow_L,e.MtEv,e.MtEvX,e.Lo_Hi,e.Sex,e.Stroke,e.Distance,e.Fee, null as SCORE, null as Course, null as MName, '' as Start, null as conv,13 as pref,If( ".$qtset." ,33,11) as qt, null as SCORE2 ";
		       $query.=" From (".$tm4db."mtevente_".$LSeason." as e) Where (e.Meet=".arg(5)." and e.I_R='I'".(($age < $min_age)?'and e.Lo_Hi!=99':'')."  and (e.Sex='X' Or e.Sex='".$sex."') and floor(e.Lo_Hi/100) <= ".$age." AND (e.Lo_Hi%100) >= ".$age.")";

		       $query.=" ) as h  order by Session, MtEv,MtEvX,qt,pref,conv";

		       $query.=" ) as f Group by f.MtEv,f.MtEvX order by Session,MtEv,MtEvX,qt,pref,conv, Start desc";
		       
		       //$output.=$query;
		       $headers[] = array('data' => t('Ses'), 'width' => '30px');
		       $headers[] = array('data' => t('Event'), 'width' => '40px');
		       $headers[] = array('data' => t('Gen'), 'width' => '20px');
		       $headers[] = array('data' => t('Dis'), 'width' => '40px');
		       $headers[] = array('data' => t('Stroke'), 'width' => '60px');
		       $headers[] = array('data' => t('Age'), 'width' => '80px');
		       $headers[] = array('data' => t('Fee'), 'width' => '30px');
		       $headers[] = array('data' => t('En'), 'width' => '10px');
		       $headers[] = array('data' => t('Time'), 'width' => '80px');
		       $headers[] = array('data' => t('I/L'), 'width' => '20px');
		       $headers[] = array('data' => t('Date'), 'width' => '80px');
		       $headers[] = array('data' => t('Meet'), 'width' => '320px');
		       // order by m.Start DESC
		       if(arg(3)=='mail' & ($_POST["email"]==null || $_POST["cemail"]!=null))
			 $email=true;

		       $result = db_query($query);
		       $i=0;
		       $total = 0;
		       while ($object = db_fetch_object($result))
			 {
			    if(arg(3)!='events')
			      $selected = (($_POST["enter_".$object->MtEv.$object->MtEvX]==($object->MtEv.$object->MtEvX))?true:false);
			    else
			      $selected = false;

			    if($selected==true || (arg(3)!='mail' & arg(3)!='confirm' & arg(3)!= 'print'))
			      {
				 $timebox = get_time($object->conv);
				 if(($object->qt%10==1 & $object->conv !=null) || ($enforce==0 & $object->qt%10!=3 )|| $object->qt==11)
				   {
				      $total +=$object->Fee;

				      $enter = '<input id="enter_'.$object->Session.'" '.(($selected==true)?'checked':'').' type="checkbox" name="enter_'.$object->MtEv.$object->MtEvX.'" value="'.$object->MtEv.$object->MtEvX.'">';
				      if($object->conv ==null)// $enterqt
					if($email == false)
					  $timebox = '<input type="text" name="time_'.$object->MtEv.$object->MtEvX.'" size="10">';
				      else
					{
					   $timebox = $_POST["time_".$object->MtEv.$object->MtEvX].' c';
					}
				   }
				 else
				   $enter = '';

				 //$link = 'athlete/entries/events/'.arg(3).'/'.$object->Meet;
				 $rows[] = array('onmouseover'=>"dis_qt(".$i.",".js_null($object->SCORE2).",".js_null((($object->Course=='L')?0:1)).",".js_null($object->SlowCut).",".js_null($object->FastCut).",".js_null($object->Slow_L).",".js_null($object->Fast_L).",".$multi.")",'data' => array($object->Session,$object->MtEv.$object->MtEvX,$object->Sex,$object->Distance,Stroke($object->Stroke),Age($object->Lo_Hi),$object->Fee,$enter,array('data'=>$timebox.' <small>'.$object->Course.'</small>'."<div class='cellrel'><div class='cellinfodis' id='s".$i."'></div></div>",'class'=>(($object->qt%10==1)?'green':(($object->qt%10==2)?(($enforce==1)?'red':'yellow'):(($object->qt%10==3)?'red':''))).(($object->qt<10)?(($enforce==1)?' min_red':' min_yellow'):(($object->qt>30)?' red ':(($object->qt>20)?' min_red':'')))),$object->I_R,get_date($object->Start),$object->MName));
				 $i++;
			      }
			 }
		       if (!$rows)
			 $rows[] = array(array('data' => t('There are no events matching you criteria (Age,Sex)'), 'colspan' => 11));

		       $rows[] = array(array('data'=>'Total: ','colspan'=>'6','align'=>'right'),array('class'=>'total','data'=>'<div id=\'total\'>'.(($email==true)?$total:'').'</div>','colspan'=>'2'),array('colspan'=>'4'));

		       //if(arg(6)=='wesley')
			 {

			    $body = $output;
			    $table =  theme_table($headers, $rows,array('id'=>'total_C7_A6_Dtotal','onmouseout'=>'hide_dis();'));
			    $body.=$table;
			    $body.='<link rel="stylesheet" type="text/css" href="'.path().'/style.css">';
			    //$body.='<script type="text/javascript" defer="defer" src="'.path().'/js/athlete.js"></script>';

			    $ok=true;
			    if(arg(3)== 'mail' & $_POST["email"]==null)//entry page
			      {
				 $ok=false;
				 $ok = mimemail(null, $_POST['cemail'], $subject.' Entries',$body);
				 if($ok == true)
				   {
				      drupal_set_message('Entries email sent sucesfully!');
				      drupal_goto('athlete/'.arg(1).'/entries/'.arg(4));

				   }
				 else
				   {
				      drupal_set_message("Email failed to send to '".$_POST['cemail']."' , if this email address is correct click email");
				      drupal_goto('athlete/'.arg(1).'/entries/'.arg(4));

				   }

			      }

			    $output.="<form action='".url('athlete/').arg(1).'/entries/'.((arg(3)== 'events' || (arg(3)=='mail' & $_POST['email'] !=null)|| !$ok)?'confirm':'mail').'/'.arg(4).'/'.$meet."' method='post'><input type='hidden' name='meet' value='".$meet."'>";
			    $output.=$table;
			    if($_POST['submit']!='Printable Summary')
			    $output.="<span class='no_print'><br>Recipient Email Address &nbsp;&nbsp;<input type='text' name='email' value='' size='30'><input type='hidden' name='cemail' value='".$_POST['email']."'> &nbsp;<input type='submit' value='Email".(arg(3)!= 'confirm'?' & Printable Version':'')."' name='submit'>&nbsp;&nbsp;<input type='submit' value='Printable Summary' name='submit'><br><br>I would advise you to send the email to your self first, the forward it on, so that you have proof of your entries<br></span>";
			    $output.= "</form>";
			    $output.="</td></tr></table>";
			 }
		       //".((arg(3)== 'confirm')?'':$_POST['email'])."

		    }
		  else
		    {
		       $output.="<p aglin='center'>Sorry this meet Courses is not supoprted: ".$object->Course."</p>";
		    }

		 $output;
		 //return
	       }
	     else
	       {
		  $output.= athlete_heading($tm4db,$season,arg(3));
		  $output.="Please select a meet to see events list.<br>";
		  $output.="<br>";
		  $headers[] = array('data' => t('Meet'), 'width' => '300px','field' => 'm.MName');
		  $headers[] = array('data' => t('Type'), 'width' => '20px');
		  $headers[] = array('data' => t('Start date'), 'width' => '100px','sort' => 'asc','field' => 'm.Start');
		  $headers[] = array('data' => t('End date'), 'width' => '100px');
		  $headers[] = array('data' => t('Course'), 'width' => '40px');
		  $headers[] = array('data' => t('Location'));
		  // order by m.Start DESC
		  //
		  $result = db_query("select SQL_CACHE DISTINCT IF(e.Meet Is Null,1,0) as results, m.Meet, m.MName, m.Start, m.End,m.Type, m.Course, m.Location  from ".$tm4db."meet_".$LSeason." m left join ".$tm4db."mtevent_".$LSeason." e on (m.Meet=e.Meet) where m.Start >CURDATE()".tablesort_sql($headers));

		  while ($object = db_fetch_object($result))
		    {
		       $link = 'athlete/'.arg(1).'/entries/events/'.arg(3).'/'.$object->Meet;
		       $rows[] = array('data' => array(l(t($object->MName), $link),$object->Type,get_date($object->Start), get_date($object->End), $object->Course, $object->Location),'class'=>'onlythis');
		    }
		  $output.= theme_table($headers, $rows,array('id'=>'hyper','class'=>'hyper'),null);

		  $output;
		  //return
	       }

	  }
?>