<?php
$option = 'std';
require('../../../main_include.php');
require('../heading.php');
	       

	$Sd = $config['ranking_dd'];
	$Sm = $config['ranking_mm'];
	$ss_begin = $_GET['ss'].'-'.$Sm.'-'.$Sd;
	
	$age = $_GET['age'];
	$course = substr($_GET['c'],0,1);
	$course_only = substr($_GET['c'],1,2);
		
	drupal_set_title($_GET['std'].' Performance Q/T\'s, '.Course(0,$course).' '.($course_only =='O'?'Only':'').' at age '.$age.',BT '.(isset($_GET['ex'])==true?'Excluded':'Included').' - '.$heading);
	
	$link = 'ath='.$_GET['ath'].'&std='.strtolower($_GET['std']).'&age='.$_GET['age'].'&c='.$_GET['c'];
	if(isset($_GET['ex'])==true)
		$output.=l2('Include Previous Seasons Results (Best times) ',$link,'perf_events.php');
	else
		$output.=l2('Exclude Previous Seasons Results (Best times) ',$link.'&ex=','perf_events.php');
	
	$output.='<br>'.Course(0,$course).' '.($course_only =='O'?'Only':'')." Performance Rating as of age ".$age;
	
	
	
	$results = db_query("Select SQL_CACHE * From ".$db_name."stdname Where StdFile='".strtolower(inj_str($_GET['std']))."'");
	if(!mysql_error())
	{
		$Object = mysql_fetch_array($results);
	
	 $query='';
	 
	 $shift=0;
	 $qt_count=0;
	for($i=0;$i<12;$i++)
	{
		$t = trim($Object['D'.($i+1)]);
		if($t=='')
		break;
		$dsec[]= $t;
		$qt_count++;
		if($course=='S')
			$query.='S'.$i.',F'.$i.',';
		if($course=='L')
		{
			$query.='S'.($i+12).',F'.($i+12).',';
			$shift=12;
		}
		if($course=='Y')
		{
			$query.='S'.($i+24).',F'.($i+24).',';
			$shift=24;
		}
	}
	$query_main="Select SQL_CACHE ".$query." s.distance,s.stroke,((s.Lo_age*100)+s.Hi_Age) as age_group,m.Start,m.MName,r.I_R,".($course_only=='O'?'r.score':course_conversion($course))." as score, r.score as score_orig,r.course From ".$db_name.strtolower(inj_str($_GET['std']))." as s inner join ".$db_name."result as r on (r.distance=s.distance and r.stroke=s.stroke) inner join ".$db_name."athlete as a on (a.athlete=r.athlete) inner join ".$db_name."meet as m on (m.meet=r.meet) Where s.I_R='I' and r.I_R != 'R' ".($course_only=='O'?"and r.course='".inj_str($course)."' ":'')." and r.NT=0 and a.sex=s.sex and a.athlete=".inj_int($_GET['ath'])." and (s.Lo_Age<=".inj_int($age)." and s.Hi_age>=".inj_int($age).")  ".(isset($_GET['ex'])==true?' AND DATEDIFF(m.Start,\''.$ss_begin.'\')>0 ':' and r.RBest=1')." order by s.Lo_age,s.Hi_age,s.stroke,s.distance,score ";
	//echo $query_main;
	$results = db_query($query_main);
	//print_r($dsec);
	//$output.=$query;
       $headers[] = array('data' => t(''));
       $headers[] = array('data' => t('Time'));
       $headers[] = array('data' => t('Age-Group'));
       $headers[] = array('data' => t('QT'),);
       $headers[] = array('data' => t('QT <small>time</small>'), 'width' => '80px');
       $headers[] = array('data' => t('I/L'));
       $headers[] = array('data' => t('Date'));
       $headers[] = array('data' => t('Meet'));
       // order by m.Start DESC
       $prev_dis=null;
       $prev_stroke=null;
       $prev_course=null;
       $prev_agegroup=null;
       
       if(!mysql_error())
       while ($object = mysql_fetch_array($results))
		{
			if(!($object['distance'] == $prev_dis && $object['stroke'] == $prev_stroke && $object['course'] == $prev_course && $object['age_group'] == $prev_agegroup))
			{
					
			$sub_head=null;
			$cols=null;
			$sub_head[] = array('data'=>'<b>'.$object['distance'].' '.stroke($object['stroke']).'</b>'.($course_only=='O' || $object['course']==$course?'':' - '.get_time($object['score_orig']).'<small>'.$object['course'].'</small>'),'colspan'=>3);
			 
			$cols[]=''; 
			
			$cols[] = get_time($object['score']).($course != $object['course']?'*':'');
			$cols[] = Age($object['age_group']);	
			$i=$shift;
			$i2=$shift;
			$qt_present=false;
			
			for(;$i2<$qt_count+$shift;$i2++)
			{
				if($object['S'.$i2]!=0 || !is_null( $object['S'.$i2]))
				$qt_present = true;
			}
			
			for(;$i<$qt_count+$shift;$i++)
			{
				if($object['S'.$i]>=$object['score'] || ($qt_present ==false &($object['S'.$i]==0 || is_null( $object['S'.$i]))))//|| (($object['S'.$i]==0 || is_null( $object['S'.$i])) & $qt_present==false))
				break;
			}
			if($i!=$qt_count+$shift)
			{
				$cols[]=$dsec[$i-$shift];
				$cols[]=get_time($object['S'.$i]);
			}
			else
			{
				$cols[]=' - - ';
				$cols[]='';
			}
			//echo $i;
			if($i-$shift-1>=0)
			{        
				for($i3=$i-1;$i3-$shift>=0;$i3--)
				{
					if($object['S'.($i3)]!=0)
					{
						$i=$i3+1;
						break;
						
					}
				}
				
				
					
					if($object['S'.($i-1)]==0)
					{
						$sub_head[]=array('data'=>'<small><b>Next Level:</b></small><b>','colspan'=>2,'align'=>'right');
					
						$sub_head[]=array('data'=>'<b><small>'.$dsec[$i-$shift-1].'  - Refer to '.$dsec[$i-$shift-1].' Rules for applicability</small></b>','colspan'=>3);
					}
					else
					{
						$sub_head[]=array('data'=>'<b><small>Next Level: </small><b>','colspan'=>2,'align'=>'right');
						$sub_head[]=array('data'=>'<b><small>'.$dsec[$i-$shift-1].' - '.get_time($object['S'.($i-1)]).' improve by '.(($object['score']-$object['S'.($i-1)])/100).'s</small></b>','colspan'=>3);
					}
				
				
				
			}
			else
			{
				$sub_head[]=array('data'=>'','colspan'=>5);
				
			}

							
			$cols[]=$object['I_R'];
			$cols[]=get_date($object['Start']);
			$cols[]=$object['MName'];
			//$sub_head[]=array('data'=>'','colspan'=>4);
			$rows[]=$sub_head;
			
			$rows[]=$cols;
			}
			{
				$prev_dis = $object['distance'];
				$prev_stroke = $object['stroke'];
				$prev_course = $object['course'];
				$prev_agegroup = $object['age_group'];
			}
		}
				       
				       
				 
	}			 
			       if (!$rows)
				 $rows[] = array(array('data' => t('There are no events results that are applicable'), 'colspan' => 11));
				 $table =  theme_table($headers, $rows,null,null);
				    $output.=$table;
				    $output.="</td></tr></table><br><br><br><br>";
				    
				    render_page();
?>