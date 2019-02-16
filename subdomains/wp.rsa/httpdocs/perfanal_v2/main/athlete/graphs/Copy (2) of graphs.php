<?php
 $output.= athlete_heading($tm4db,$season,arg(3));

	     if(arg(4) ==null||arg(5) ==null)
	       {
		  $header[] = array('data' => t('Distance - Time'), 'width' => '110px');
		  //$header[] = array('data' => t('Stroke'), 'width' => '80px');

		  $result = db_query("select r.DISTANCE, r.STROKE from ".$tm4db."result_".$season." r where r.ATHLETE=%d and r.I_R <> 'R' and r.NT =0 group by r.STROKE, r.DISTANCE order by r.STROKE, r.DISTANCE",arg(3));
		  $output.='Please select an option.<br>';
		  $rows[] = array(l('ALL'.stroke($object->STROKE),'athlete/'.arg(1).'/graphs/'.arg(3).'/ALL/ALL'));
		  while ($object = db_fetch_object($result))
		    {
		       $rows[] = array(l($object->DISTANCE.' '.stroke($object->STROKE),'athlete/'.arg(1).'/graphs/'.arg(3).'/'.$object->DISTANCE.'/'.$object->STROKE));
		    }
		  $output.="<div style='padding:20px;' width='200px' align='left'>";
		    $output.= theme_table($header, $rows,array('id'=>'hyper','class'=>'hyper','align'=>'left'),null).'<br></div>';
		  $output.="<div align='center'>";
		  require('stroke_distance.php');
		  $output.="</div>";
	       }
	     else
	     if(arg(2)!='graphs_fina')
	       {


		  $header[] = array('data' => t('Time'), 'width' => '70px');
		  $header[] = array('data' => t('Distance'), 'width' => '70px');
		  $header[] = array('data' => t('Stroke'), 'width' => '80px');
		  $header[] = array('data' => t('Rounds'), 'width' => '50px');
		  $header[] = array('data' => t('Date'), 'width' => '90px');
		  $header[] = array('data' => t('Meet - Results'), 'width' => '300px');

		  
		  
		  $key_lookup = array();
		  if(arg(4)=='ALL' & arg(5)=='ALL')
		    $result = db_query("select r.COURSE, r.SCORE, r.DISTANCE, r.STROKE, m.MName, m.Start, r.F_P, r.MtEvent, m.Meet from ".$tm4db."result_".$season." r, ".$tm4db."meet_".$season." m where r.ATHLETE=%d and r.MEET=m.Meet and r.I_R <> 'R' and r.NT =0 and r.SCORE !=0 group by r.STROKE, r.DISTANCE order by r.STROKE, r.DISTANCE, r.SCORE",arg(3));
		  else
		    $result = db_query("select r.COURSE, r.SCORE, r.DISTANCE, r.STROKE,r.I_R, m.MName, m.Start, r.F_P, r.MtEvent, m.Meet from ".$tm4db."result_".$season." r, ".$tm4db."meet_".$season." m where r.ATHLETE=%d and r.distance= %d and r.stroke=%d and r.MEET=m.Meet and r.I_R <> 'R' and r.NT =0 and r.SCORE !=0 group by r.STROKE, r.DISTANCE order by r.STROKE, r.DISTANCE, m.Start,r.COURSE,r.F_P desc,r.Score",arg(3),arg(4),arg(5));
		  while ($object = db_fetch_object($result))
		    {
		       $key_lookup[] = array($object->COURSE, $object->STROKE, $object->DISTANCE);
		    }

		  for ($i=0; $i<count($key_lookup); $i++)
		    {
		       $query = "select r.COURSE, r.NT, r.SCORE, r.DISTANCE, r.STROKE,r.I_R, m.MName, m.Start, r.F_P, r.MtEvent, m.Meet from ".$tm4db."result_".$season." as r left join ".$tm4db."meet_".$season." as m on (r.MEET=m.Meet ) where r.SCORE !=0 AND r.NT=0 AND r.STROKE=".$key_lookup[$i][1]." and r.DISTANCE=".$key_lookup[$i][2]." and  r.ATHLETE=".arg(3)." order by r.STROKE,r.Distance, m.Start,r.COURSE,r.F_P desc,r.Score";

		       $result = db_query($query);
		       //			drupal_set_message($query);

		       $last_stroke = NULL;
		       $last_distance = NULL;
		       $rowsL = array();
		       $rowsS = array();
		       $graph_hdrs = array();
		       $graph_dataL = array();
		       $graph_dataS = array();

		       while ($object = db_fetch_object($result))
			 {
			    //				print_r($object);

			    $time = Score(0,$object->SCORE);
			    $f_p = (strtolower($object->F_P) == 'f') ? 'Final' : 'Prelim';

			    $start_date = explode("-", $object->Start);
			    $date = Date('d/m/Y', mktime(0, 0, 0, $start_date[1], $start_date[2], $start_date[0]));
			    $graph_hdrs[$date.$object->F_P] = $date.'|';
			    //$link = 'meets/'.arg(1).'/event/results/'.$object->MtEvent;
			    $link = 'athlete/'.arg(1).'/meet_times/'.arg(3).'/'.$object->Meet;

			    if (strtolower($object->COURSE) == "l")
			      {
				 $rowsL[] = array($time, $object->DISTANCE.'m', Stroke($object->STROKE), $f_p, $date, l(t($object->MName), $link));
				 $graph_dataL[$date.$object->F_P]=($object->SCORE).'|';

			      }
			    else
			      {
				 if($graph_hdrs[$date.$object->F_P] != null)
				   if($graph_dataS[$date.$object->F_P] != null)
				     $graph_dataL[$date.$object->F_P]='-|';
			      }

			    if (strtolower($object->COURSE) == "s")
			      {
				 $rowsS[] = array($time, $object->DISTANCE.'m', Stroke($object->STROKE), $f_p, $date, l(t($object->MName), $link));
				 $graph_dataS[$date.$object->F_P]=($object->SCORE).'|';
			      }
			    else
			      {
				 if($graph_hdrs[$date.$object->F_P] != null)
				   if($graph_dataL[$date.$object->F_P] != null)
				     $graph_dataS[$date.$object->F_P]='-|';
			      }

			    if (strtolower($object->COURSE) == "s")
			      {
				 if($graph_hdrs[$date.$object->F_P] != null)
				   if($graph_dataS[$date.$object->F_P] != null)
				     $graph_dataL[$date.$object->F_P]='-|';
			      }

			    $last_stroke = $object->STROKE;
			    $last_distance = $object->DISTANCE;
			 }
			
		       $splitterL = array();
		       $splitterS = array();

		       $splitterL[] = array(array('data' => '<div style="font-weight: bold; color: blue;">Long Course</div>', 'colspan' => 6));
		       $splitterS[] = array(array('data' => '<div style="font-weight: bold; color: orange;">Short Course</div>', 'colspan' => 6));

		       $rows = array_merge($splitterL, $rowsL);
		       $rowsL = array_merge($rows, $splitterS);
		       $rows = array_merge($rowsL, $rowsS);

		       $graph_hdrs_data = NULL;
		       foreach($graph_hdrs as $key => $value)
			 {
			    $graph_hdrs_data.= $value;
			 }
		       $graph_dataL_data = NULL;
		       foreach($graph_dataL as $key => $value)
			 {
			    $graph_dataL_data.= $value;
			 }
		       $graph_dataS_data = NULL;
		       foreach($graph_dataS as $key => $value)
			 {
			    $graph_dataS_data.= $value;
			 }
	
		       $graph_data = '?headers='.$graph_hdrs_data;
		       $graph_data.= (($graph_dataL_data) ? '&data1='.$graph_dataL_data : '');
		       $graph_data.= (($graph_dataS_data) ? '&data2='.$graph_dataS_data : '');
		      

		       $gdataS[] = array('data' => "<table cellspacing='0' cellpadding='0'><tr><td style='padding:0px' width='700px'><div class='cellrel'><div class='graphsback'>".theme_image(path()."/images/swimming.jpg",null,null,array('id'=>'img_no_print','width'=>'615px','height'=>'150px'),false)."</div>".theme_image(path()."/grapher.php".$graph_data,null,null,null,false)."</div></td></tr></table>", 'colspan' => 6,'align'=>'center');

		       $rows[] = $gdataS;

		       $output.= '<br><div style="font-weight: bold;">'.$last_distance.'m '.Stroke($last_stroke).'</div><hr>';
		       $output.= theme('table', $header, $rows).'<br>';
		       $gdataS = NULL;
		       $rows   = NULL;
		       $rowsL  = NULL;
		       $rowsS  = NULL;
		    }
	       }
	       else
	       {
		  $header[] = array('data' => t('Avg-Fina'), 'width' => '70px');
		  $header[] = array('data' => t('Events'), 'width' => '70px');
		  $header[] = array('data' => t('Date'), 'width' => '90px');
		  		  		  
		  
		 
		  $query= "select round(avg(r.fina),0) as fina_avg, count(*) as events, m.Start from ".$tm4db."result_".$season." as r, ".$tm4db."meet_".$season." as m where r.ATHLETE=%d and r.MEET=m.Meet and r.I_R <> 'R' and r.NT =0 and r.fina !=0 ".(arg(4)=='All'?'':' and r.stroke='.arg(4))." ".(arg(5)=='All'?'':' and r.distance='.arg(5))." group by m.Start order by m.start";
		   
		
		  $result = db_query($query,arg(3));
		 
		    	
		       $graph_data ='';
		       $graph_hdrs='';
		       while ($object = db_fetch_object($result))
			 {
			    //				print_r($object);

			     
			    $start_date = explode("-", $object->Start);
			    $date = Date('d/m/Y', mktime(0, 0, 0, $start_date[1], $start_date[2], $start_date[0]));
			    
			    $rows[] = array($object->fina_avg, $object->events, $date);
			    $graph_hdrs.= $date.'|';
			    $graph_data.= $object->fina_avg.'|';

		       
		    }  
		 
		  if($graph_hdrs!='')
		   $rows[] = array(array('data' => "<table cellspacing='0' cellpadding='0'><tr><td style='padding:0px' width='700px'><div class='cellrel'><div class='graphsback'>".theme_image(path()."/images/swimming.jpg",null,null,array('id'=>'img_no_print','width'=>'615px','height'=>'150px'),false)."</div>".theme_image(path()."/grapher_fina.php".($graph_hdrs!=''?'?headers='.$graph_hdrs.'&data1='.$graph_data:'?headers=&data1=0|'),null,null,null,false)."</div></td></tr></table>", 'colspan' => 3,'align'=>'center'));
		    $output.= '<br><div style="font-weight: bold;">';
		   if(arg(5)!='All')
		    $output.= arg(5).'m ';
		   if(arg(4)!='All')
		    $output.=Stroke(arg(4));
		    $output.='</div><hr>';
		       $output.= theme('table', $header, $rows).'<br>';
		       
		       
		       
		      $output.='<br><br><b>Peak Ranking</b><br><br>Round down to lowest whole number for ranking position.<br>';
		      $output.='To be consider 100% accurate for event, your position in that event must remain below 50<br>';
		       $result = db_query("select a.birth,a.age,a.sex from ".$tm4db."athlete_".$season." a where a.ATHLETE=%d",arg(3));
		     if($object = db_fetch_object($result))
		     {
			       $ath_birth = $object->birth;
			       $ath_age = $object->age;
			       $ath_sex = $object->sex;
			      
			     
			       
			      $result = db_query("select distinct r.course, r.distance, r.stroke from ".$tm4db."result_".$season." r where r.ATHLETE=%d and r.I_R <> 'R' and r.NT =0 and r.SCORE !=0 ".((arg(4)=='All')?'':'and r.stroke='.arg(4)).((arg(5)=='All')?'':' and r.distance='.arg(5))." group by r.STROKE, r.DISTANCE order by r.Course,r.STROKE, r.DISTANCE",arg(3));
			      
			    $begin_date = '-'.date("m-d").' 00:00:00';//substr($ath_birth,0,4).
			      $pos_total=0;
			      $event_count=0;
			      $event=array();
			      while ($object = db_fetch_object($result))
				 {
					//query rankings
					$res_rank = db_query(" set @pos = 0,@pre = -1,@ath_pos=-1;");
					
					$stroke=$object->stroke;
					$distance=$object->distance;
					$course=$object->course;
					$event_count+=1;
					
					$query="Select * FROM(Select if(@pre=(@pre:=p.score),@pos,@pos:=(@pos+1)) AS r_pos,if(p.Athlete=".arg(3).",@ath_pos:=@pos,0) AS ath_pos, p.* from (";
					$query.="Select SQL_CACHE r.Athlete,r.score,a.age,if(a.Birth>'".$ath_birth."',+1,-1) as pos_mod,if(a.Birth>'".$ath_birth."',DATE_ADD(a.Birth, INTERVAL -2 YEAR),a.Birth) as Birth";
					 $query.=" FROM (((".$tm4db."result_".$season." as r left join ".$tm4db."athlete_".$season." as a on (r.ATHLETE=a.Athlete))) )";
					 $query.=" WHERE r.RAll=1 and (a.age >=".($ath_age-2)." and a.age <=".($ath_age+1).") and r.nt=0 and r.COURSE='".$course."' and a.Sex='".$ath_sex."' and r.Stroke=".$stroke." and r.Distance=".$distance."  and  DATE_ADD(a.Birth, INTERVAL -2 YEAR) <='".$ath_birth."' order by r.Score";
					$query.=") as p ) as p2 where p2.r_pos<=@ath_pos and p2.r_pos>=@ath_pos-50 and p2.Birth <='".$ath_birth."' and p2.Birth >=DATE_ADD(CURDATE(), INTERVAL -".($ath_age+2)." YEAR) and p2.Birth <='".$ath_birth."' order by p2.Birth desc";
					echo $query.'<br><br>';
					//and (p2.age=16 or p2.athlete=612)
					
					//and p2.r_pos>=@ath_pos-20
					//echo $query."<br>";
					$res_rank = db_query($query);
					$event_res = null;
					$event_res=array();
					$ath_pos=0;
					//
					$age_gap= (($ath_age+1)-$ath_age);
					//echo '<br>'.$ath_min_birth.'<br>';
					//echo $ath_birth;
					while ($object_r = db_fetch_object($res_rank))
					{//mktime(0,0,0,Date('m',strtotime($object_r->Birth)),Date('d',strtotime($object_r->Birth)),Date('Y',strtotime($object_r->Birth))+$ath_age)
							$event_res[]=array('course'=>$course,'stroke'=>$stroke,'dis'=>$distance,'date'=>(date('Y',strtotime($object_r->Birth))+$ath_age+1+$age_gap).'-'.date('m-d',strtotime($object_r->Birth)).' 00:00:00','pos'=>$object_r->r_pos,'pos_mod'=>$object_r->pos_mod);
							if($object_r->ath_pos>$ath_pos)
							$ath_pos=$object_r->ath_pos;
							//echo $object_r->ath_pos.':';
							//if(Date('Y',strtotime($object_r->Birth))<$ath_min_birth)
							//$ath_min_birth=Date('Y',strtotime($object_r->Birth));
					}
					//echo $ath_pos;
					//echo print_r($event_res);
					$ath_min_birth=Date('Y',strtotime($ath_birth))+$ath_age;
					$ath_s_pos=$ath_pos;
					$l=sizeof($event_res);
					$l2=$l-1;
					for($i=0;$i<$l;$i++)
					{
						$ath_pos-=$event_res[$i]['pos_mod'];
						if($i==-1)
						{
							//echo 'excute';
							//$event_res[$i]['pos']=$ath_s_pos;
							//$event_res[]= $event_res[$i];
							//$event_res[$i+1]['pos']=$ath_pos;
							$event_res[$i]['date']=$ath_min_birth.$begin_date;
							$pos_total+=$event_res[$i]['pos'];
						}
						else
							$event_res[$i]['pos']=$ath_pos;
	
					}
					$event = array_merge($event,$event_res);
					//echo print_r($event_res)."<br><br>";
					
				 }
				
				// echo '<br><br><br><br>'.print_r($event);
				 
				 	function cmp($a, $b)
					{
						$a_d = strtotime($a['date']);
						$b_d = strtotime($b['date']);
						if ($a_d == $b_d) {
							if ($a['course'] == $b['course']) {
								if ($a['stroke'] == $b['stroke']) {
									if ($a['dis'] == $b['dis']) {
										
										if ($a['pos'] == $b['pos']) {
										return 0;
										}
										else
										 return ($a['pos'] < $b['pos']) ? 1 : -1;
									    }
									    else
									    return($a['dis'] < $b['dis']) ? -1 : 1;
								}
									    else
									    return($a['stroke'] < $b['stroke']) ? -1 : 1;
							    }
							    else
							    return ($a['course'] < $b['course']) ? -1 : 1;
					    }
					    
					   
					    return ($a_d < $b_d) ? -1 : 1;
					}
					
					
					
					usort($event, "cmp");
					
					// echo '<br><br><br><br>'.print_r($event);
					
					//echo print_r($event);
				/*	foreach ($event as $key => $value) {
					   echo $value['course'].','.$value['stroke'].','.$value['dis'].','.$value['date'].','.$value['pos'].','."<br>";
					}
					*/
					//echo $pos_total;
					
					$graphs=array();
					
					$graph_header="";
					$header=array();
					$h_i=0;
					$l=sizeof($event);
					for($i=0;$i<$l;$i=$i+1)
					{
						
						if($header[$h_i-1]!=$event[$i]['date'])
						{
							$header[] = $event[$i]['date'];
							$graph_header .= date("d-m-Y",strtotime($event[$i]['date'])).'|';
							$h_i=$h_i+1;
						}
						
						

							$graphs[$event[$i]['course']][$event[$i]['stroke']][$event[$i]['dis']][]= $event[$i]['pos'];
					}
					
					$ath_cur_birth=(date('Y',strtotime($ath_birth))+$ath_age+1+$age_gap).'-'.date('m-d',strtotime($ath_birth)).' 00:00:00';
					
					$header[] = $ath_cur_birth;
					$graph_header .= date("d-m-Y",strtotime($ath_cur_birth)).'|';
					
					
					
					//echo $graph_header;
					//echo print_r($header);
					//echo
					$h_s=sizeof($header);
					
						foreach ($graphs as $key_c=>$value)
							foreach ($graphs[$key_c] as $key_s=>$value)
								foreach ($graphs[$key_c][$key_s] as $key_d=>$value)
								{
									$holder='-';
									for($h=0;$h<$h_s;$h++)
									{
										if(!isset($graphs[$key_c][$key_s][$key_d][$h]))
										{
											$graphs[$key_c][$key_s][$key_d][]=$holder;
										}
										else
										$holder=$graphs[$key_c][$key_s][$key_d][$h];
									}
									$graphs[$key_c][$key_s][$key_d][]=$holder;
								}
									
					$graph_data="";	
					$colors = array('blue','#CCCC00','red','green','orange','purple','brown');
							
					if(arg(5)=='All')
					{
						//echo ' distance all';
					foreach ($graphs as $key_c=>$value)
							foreach ($graphs[$key_c] as $key_s=>$value)
							{
								//generate graph
								$graph_d="";
								$graph_d_pos=1;
								$legend="";
								$rw=0;
								foreach ($graphs[$key_c][$key_s] as $key_d=>$value)
								{
									$legend.='<span style="color:'.$colors[$rw++].'">'.((arg(5)!='All')?stroke($key_s):$key_d.'m').'</span> ';
									$graph_d.= '&data'.($graph_d_pos).'=';
									for($h=0;$h<$h_s;$h++)
									{
										
										$graph_d.=(($graphs[$key_c][$key_s][$key_d][$h]=='-')?'-':$graphs[$key_c][$key_s][$key_d][$h]+(($graph_d_pos-1)/14)).'|';
											
									}
									$graph_d_pos++;
								}
									$output.= '<br><br>'.Course(0,$key_c).' '.((arg(4)!='All'  || (arg(5)=='All' && arg(4)=='All'))?stroke($key_s):$key_d.'m');
									$output.=theme_image(path()."/grapher_fina.php".($graph_header!=''?'?headers='.$graph_header.$graph_d.'&data='.$graph_data:'?headers=&data=0|'),null,null,null,false);
									$output.='<br>'.$legend;
									
							}
					}
					else
					{
						//echo ' stroke all';
						
						foreach ($graphs as $key_c=>$value)
						{
							$key_s=null;
						
							foreach ($graphs[$key_c] as $key_s2=>$value)
							if($key_s==null)
							$key_s=$key_s2;
							
							foreach ($graphs[$key_c][$key_s] as $key_d=>$value)
							{
								//generate graph
								$graph_d="";
								$graph_d_pos=1;
								$legend="";
								$rw=0;
								foreach ($graphs[$key_c] as $key_s=>$value)
								{
									$legend.='<span style="color:'.$colors[$rw++].'">'.(arg(5)!='All'?stroke($key_s):$key_d.'m').'</span> ';
									$graph_d.= '&data'.($graph_d_pos).'=';
									for($h=0;$h<$h_s;$h++)
									{
										
										$graph_d.=(($graphs[$key_c][$key_s][$key_d][$h]=='-')?'-':$graphs[$key_c][$key_s][$key_d][$h]+(($graph_d_pos-1)/14)).'|';
											
									}
									$graph_d_pos++;
								}
									$output.= '<br><br>'.Course(0,$key_c).' '.(arg(5)=='All'?stroke($key_s):$key_d.'m');
									$output.=theme_image(path()."/grapher_fina.php".($graph_header!=''?'?headers='.$graph_header.$graph_d.'&data='.$graph_data:'?headers=&data=0|'),null,null,null,false);
									$output.='<br>'.$legend;
							}
						}
					}
					
					
					
					//echo print_r($graphs);
					
					//echo print_r($header);
					
					
					//$output.=theme_image(path()."/grapher.php".($graph_hdrs!=''?'?headers='.$graph_hdrs.$graph_d.'&data='.$graph_data:'?headers=&data=0|'),null,null,null,false)				 
		     }
		      
		      
	       }
?>