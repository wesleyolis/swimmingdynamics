<?php
$option = 'std';
require('../../../main_include.php');
require('../heading.php');
	       
	drupal_set_title('Apply '.strtolower($_GET['std']).' Q/T Standard, Type Menu - '.$heading);
	


					$query = "Select SQL_CACHE *,extract(YEAR FROM from_days(datediff(CURDATE(), Birth))) as age from ".$db_name."athlete where Athlete=".inj_int($_GET['ath']);
					$result= db_query($query);
					if(!mysql_error())
					$object = mysql_fetch_object($result);
					$c_age = $object->age;
					$birth = $object->Birth;
					
					$header[]=array('data'=>'Meet Course');
					$header[]=array('data'=>'description');
					$col[]='SO';
					$col[]='Short Course times only to be consider';
					$link = 'ath='.$_GET['ath'].'&std='.strtolower($_GET['std']).'&age='.$c_age.'&c=SO&ex=';
					$col[]=(l2('Current Age '.$c_age,$link,'perf_events.php'));
					//$col[]='Current Age '.$c_age;
					for($i = ($c_age+1);$i<$c_age+5;$i++)
					{
						if($i<18)
						{
						$link = 'ath='.$_GET['ath'].'&std='.strtolower($_GET['std']).'&age='.$i.'&c=SO&ex=';
						$col[]=(l2('at Age '.$i,$link,'perf_events.php'));
						}
						else
						{
							break;
						}
					}
					$rows[]=$col;
					$col=null;
					$col[]='LO';
					$col[]='Long Course times only to be consider';
						$link = 'ath='.$_GET['ath'].'&std='.strtolower($_GET['std']).'&age='.$c_age.'&c=LO&ex=';
						$col[]=(l2('Current Age '.$c_age,$link,'perf_events.php'));
					
					for($i = ($c_age+1);$i<$c_age+5;$i++)
					{
						if($i<18)
						{
						$link = 'ath='.$_GET['ath'].'&std='.strtolower($_GET['std']).'&age='.$i.'&c=LO&ex=';
						$col[]=(l2('at Age '.$i,$link,'perf_events.php'));
						}
						else
						{
							break;
						}
					}
					$rows[]=$col;
					$col=null;
					$col[]='YO';
					$col[]='Yards Course times only to be consider';
						$link = 'ath='.$_GET['ath'].'&std='.strtolower($_GET['std']).'&age='.$c_age.'&c=YO&ex=';
						$col[]=(l2('Current Age '.$c_age,$link,'perf_events.php'));
					
					for($i = ($c_age+1);$i<$c_age+5;$i++)
					{
						if($i<18)
						{
						$link = 'ath='.$_GET['ath'].'&std='.strtolower($_GET['std']).'&age='.$i.'&c=YO&ex=';
						$col[]=(l2('at Age '.$i,$link,'perf_events.php'));
						}
						else
						{
							break;
						}
					}
					$rows[]=$col;
					$col=null;
					$col[]='S';
					$col[]='All times Converted Short Course';
					$link = 'ath='.$_GET['ath'].'&std='.strtolower($_GET['std']).'&age='.$c_age.'&c=S&ex=';
					$col[]=(l2('Current Age '.$c_age,$link,'perf_events.php'));
					//$col[]='Current Age '.$c_age;
					for($i = ($c_age+1);$i<$c_age+5;$i++)
					{
						if($i<18)
						{
						$link = 'ath='.$_GET['ath'].'&std='.strtolower($_GET['std']).'&age='.$i.'&c=S&ex=';
						$col[]=(l2('at Age '.$i,$link,'perf_events.php'));
						}
						else
						{
							break;
						}
					}
					$rows[]=$col;
					$col=null;
					$col[]='L';
					$col[]='All times Converted Long Course';
						$link = 'ath='.$_GET['ath'].'&std='.strtolower($_GET['std']).'&age='.$c_age.'&c=L&ex=';
						$col[]=(l2('Current Age '.$c_age,$link,'perf_events.php'));
					
					for($i = ($c_age+1);$i<$c_age+5;$i++)
					{
						if($i<18)
						{
						$link = 'ath='.$_GET['ath'].'&std='.strtolower($_GET['std']).'&age='.$i.'&c=L&ex=';
						$col[]=(l2('at Age '.$i,$link,'perf_events.php'));
						}
						else
						{
							break;
						}
					}
					$rows[]=$col;
					$col=null;
					$col[]='Y';
					$col[]='All times Converted Yards Course';
						$link = 'ath='.$_GET['ath'].'&std='.strtolower($_GET['std']).'&age='.$c_age.'&c=Y&ex=';
						$col[]=(l2('Current Age '.$c_age,$link,'perf_events.php'));
					
					for($i = ($c_age+1);$i<$c_age+5;$i++)
					{
						if($i<18)
						{
						$link = 'ath='.$_GET['ath'].'&std='.strtolower($_GET['std']).'&age='.$i.'&c=Y&ex=';
						$col[]=(l2('at Age '.$i,$link,'perf_events.php'));
						}
						else
						{
							break;
						}
					}
					$rows[]=$col;
					
					
					
					$output.=t('<b>Performance</b><br>Please select your age to apply qualifying times at.<br> Note: use this to judge an athlete\'s performance and goal times.<br>Select the age that corrisponeds to the same gala meet type so that you may see what you qualify for in meet');
					$output.=theme_table($header,$rows,null,null);
					$rows=null;
/*
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
					*/
					render_page();
?>