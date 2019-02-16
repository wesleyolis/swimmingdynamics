<?php

require('../../../main_include.php');
require('../heading.php');
		       
		      $output.='<br><br><b>Peak Ranking</b><br><br>Round down to lowest whole number for ranking position.<br>';
		      $output.='To be consider 100% accurate for event, your position in that event must remain below 50<br>';
		       $result = db_query("select a.birth,a.age,a.sex from ".$db_name."athlete as a where a.ATHLETE=".inj_int($_GET['ath']));
		       if(!mysql_error())
		       if($object = mysql_fetch_object($result))
		     {
			       $ath_birth = $object->birth;
			       $ath_age = $object->age;
			       $ath_sex = $object->sex;
			      
			     
			       
			      $result = db_query("select distinct r.course, r.distance, r.stroke from ".$db_name."result as r where r.ATHLETE=%d and r.I_R <> 'R' and r.NT =0 and r.SCORE !=0 ".((arg(4)=='All')?'':'and r.stroke='.arg(4)).((arg(5)=='All')?'':' and r.distance='.arg(5))." group by r.STROKE, r.DISTANCE order by r.Course,r.STROKE, r.DISTANCE",arg(3));
			      
			    $begin_date = '-'.date("m-d").' 00:00:00';//substr($ath_birth,0,4).
			      $pos_total=0;
			      $event_count=0;
			      $event=array();
			      while ($object = db_fetch_object($result))
				 {
					//query rankings
					$res_rank = db_query(" set @pos = 0,@pre = -1,@pos2 = 0,@pre2 = -1,@ath_pos=-1;");
					
					$stroke=$object->stroke;
					$distance=$object->distance;
					$course=$object->course;
					$event_count+=1;
					/*
					Select * FROM(
					Select if(@pre=(@pre:=p.score) ,@pos,@pos:=(@pos+1)) AS r_pos2,if(p.age<=14,0,if(@pre2=(@pre2:=p.score) ,@pos2,@pos2:=(@pos2+1))) AS r_pos,
					if(p.Athlete=612,@ath_pos:=@pos,0) AS ath_pos, p.* from (
					Select SQL_CACHE r.Athlete,r.score,a.age,a.Birth as org_birth,if(a.Birth>'1994-03-07 00:00:00',+1,-1) as pos_mod,if(a.Birth>'1994-03-07 00:00:00',DATE_ADD(a.Birth, INTERVAL -2 YEAR),a.Birth) as Birth FROM (((wp_rsa_perfanal_result_2009 as r left join wp_rsa_perfanal_athlete_2009 as a on (r.ATHLETE=a.Athlete))) ) WHERE r.RAll=1 and (a.age >=13 and a.age <=16) and r.nt=0 and r.COURSE='L' and a.Sex='F' and r.Stroke=1 and r.Distance=50 and ( DATE_ADD(a.birth, INTERVAL -2 YEAR) <='1994-03-07 00:00:00')   order by r.Score) as p ) as p2 where p2.r_pos2<=@ath_pos and p2.r_pos2>=@ath_pos-50  order by p2.Birth desc
					*/	
					$query="Select * FROM(Select if(@pre=(@pre:=p.score),@pos,@pos:=(@pos+1)) AS r_pos2,if(p.age<=".($ath_age-1).",0,if(@pre2=(@pre2:=p.score),@pos2,@pos2:=(@pos2+1))) AS r_pos,if(p.Athlete=".arg(3).",@ath_pos:=@pos,0) AS ath_pos2,if(p.Athlete=".arg(3).",@pos2,0) AS ath_pos, p.* from (";
					$query.="Select SQL_CACHE r.Athlete,r.score,a.age,a.Birth as B,if(a.athlete=".arg(3).",0,if(a.Birth>'".$ath_birth."',+1,-1)) as pos_mod,if(a.Birth>'".$ath_birth."',DATE_ADD(a.Birth, INTERVAL -2 YEAR),a.Birth) as Birth";
					 $query.=" FROM (((".$tm4db."result_".$season." as r left join ".$tm4db."athlete_".$season." as a on (r.ATHLETE=a.Athlete))) )";
					 $query.=" WHERE r.RAll=1 and (a.age >=".($ath_age-2)." and a.age <=".($ath_age+1).") and r.nt=0 and r.COURSE='".$course."' and a.Sex='".$ath_sex."' and r.Stroke=".$stroke." and r.Distance=".$distance."  order by r.Score";
					$query.=") as p ) as p2 where ((p2.r_pos=0 and p2.pos_mod=1) or p2.pos_mod=-1 or p2.pos_mod=0) and p2.r_pos2<=@ath_pos and p2.r_pos2>=@ath_pos-50 and p2.B >= DATE_ADD('".$ath_birth."', INTERVAL DATEDIFF(CURDATE(), DATE_ADD('".$ath_birth."', INTERVAL +17 YEAR))  DAY) and p2.B <= DATE_ADD('".$ath_birth."',INTERVAL 2  YEAR)  order by p2.Birth asc";
					echo $query.'<br><br>';
					//and (p2.age=16 or p2.athlete=612)
					// and  DATE_ADD(a.Birth, INTERVAL -2 YEAR) <='".$ath_birth."'
					// and p2.B >= DATE_ADD('1994-03-07 00:00:00', INTERVAL DATEDIFF(CURDATE(), DATE_ADD('1994-03-07 00:00:00', INTERVAL +17 YEAR))  DAY) and p2.B <= DATE_ADD('1994-03-07 00:00:00', INTERVAL DATEDIFF(DATE_ADD('1994-03-07 00:00:00', INTERVAL +17 YEAR),CURDATE() )  DAY)
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
						$ath_pos+=$event_res[$i]['pos_mod'];
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
					
					
					//echo print_r($event);
					
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
		     render_page();

?>