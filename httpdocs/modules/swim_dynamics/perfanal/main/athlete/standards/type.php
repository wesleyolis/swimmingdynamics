<?php
$output.= athlete_heading($tm4db,$season,arg(4));


					$query = "Select SQL_CACHE *,extract(YEAR FROM from_days(datediff(CURDATE(), Birth))) as age from ".$tm4db."athlete_".$season." where Athlete= %d";
					$result= db_query($query,arg(4));
					$object = db_fetch_object($result);
					$c_age = $object->age;
					$birth = $object->Birth;
					
					$col[]='Short Course';
					$link = 'athlete/'.arg(1).'/standards/perf_events/'.arg(4).'/'.arg(5).'/'.$c_age.'/S';
					$col[]=(l('Current Age '.$c_age,$link));
					//$col[]='Current Age '.$c_age;
					for($i = ($c_age+1);$i<$c_age+5;$i++)
					{
						$link = 'athlete/'.arg(1).'/standards/perf_events/'.arg(4).'/'.arg(5).'/'.$i.'/S';
						$col[]= l('Age of '.$i,$link);
					}
					$rows[]=$col;
					$col=null;
					$col[]='Long Course';
					$link = 'athlete/'.arg(1).'/standards/perf_events/'.arg(4).'/'.arg(5).'/'.$c_age.'/L';
					$col[]=(l('Current Age '.$c_age,$link));
					//$col[]='Current Age '.$c_age;
					for($i = ($c_age+1);$i<$c_age+5;$i++)
					{
						$link = 'athlete/'.arg(1).'/standards/perf_events/'.arg(4).'/'.arg(5).'/'.$i.'/L';
						$col[]= l('Age of '.$i,$link);
					}
					$rows[]=$col;
					$output.=t('<b>Performance</b><br>Please select your age to apply qualifying times at.<br> Note: use this to judge an athlete\'s performance and goal times.');
					$output.=theme_table(null,$rows,null,null);
					$rows=null;

					if($season == get_cur_seasons())
					{
					$headers[] = array('data' => t('Meet'), 'width' => '300px');
					$headers[] = array('data' => t('Type'), 'width' => '20px');
					$headers[] = array('data' => t('Start date'), 'width' => '100px');
					$headers[] = array('data' => t('End date'), 'width' => '100px');
					$headers[] = array('data' => t('Course'), 'width' => '40px');
					$headers[] = array('data' => t('Location'));
					// order by m.Start DESC

					$result = db_query("select SQL_CACHE extract(YEAR FROM from_days(datediff(IF(m.AgeUp is null,m.start,m.AgeUp), '".$birth."')))  as athlete_age,if(m.restrictbest=0,0,m.type) as best_time, m.Meet, m.MName, m.Start, m.End,m.Type, m.Course, m.Location,m.since,m.minage,m.restrictbest,m.enteratqtime from ".$tm4db."meet_".$season." as m  where m.Start >CURDATE() order by athlete_age asc,m.course,m.minage,best_time,m.enteratqtime,m.start desc");
					$groupby = array('athlete_age'=>null,'Course'=>null,'minage'=>'','best_time'=>'','enteratqtime'=>'');
					while ($object = db_fetch_array($result))
					{
						foreach($groupby as $key =>$value)
						if($object[$key]!=$value)
						{//add group heading
							//set groupfields
							foreach($groupby as $key2=>$v)
							{$groupby[$key2]=$object[$key2];}
							$link = 'athlete/'.arg(1).'/standards/qt/'.arg(4).'/'.arg(5).'/'.$object['Meet'];
							$rows[] = array(array('data'=>'<b>'.l('Age '.$object['athlete_age'].' Meet Group',$link).'</b>','colspan'=>6,'class'=>'green'));
							break;
						}
						  $link = 'meets/'.arg(1).'/info/'.$object['Meet'];
						  $rows[] = array('data' => array(l(t($object['MName']), $link),$object['Type'],get_date($object['Start']), get_date($object['End']), $object['Course'], $object['Location']),'class'=>'onlythis');
					}
					$output.=t('<br/><b>Meets Events Qualify - Entries</b><br/>Please select a meet group <span style=\'background-color:#CCFF99\'>(Rows in Green)</span> for which a set of qualifying times will apply.<br/>Note: use this when considering qualifying times for specific meets as it take\'s into account your age and rules of the meet.');
					$output.= theme_table($headers, $rows,array('id'=>'hyper','class'=>'hyper'),null);
					}
?>