<?php
$LSeason = $season;

	$output.= athlete_heading($tm4db,$season,arg(4));

	
	$age = arg(6);
	$course = arg(7);
	
	$output.=Course(1,$course)." Performance Rating as of age ".$age;
	
	 $results = db_query("Select SQL_CACHE * From ".$tm4db."stdname_".$season." Where StdFile='%s'",arg(5));
	 $Object = db_fetch_array($results);
	
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
	$query_main="Select SQL_CACHE ".$query." s.distance,s.stroke,((s.Lo_age*100)+s.Hi_Age) as age_group,m.Start,m.MName,r.I_R,r.score From ".$tm4db."%s_".$season." as s inner join ".$tm4db."result_".$season." as r on (r.distance=s.distance and r.stroke=s.stroke) inner join ".$tm4db."athlete_".$season." as a on (a.athlete=r.athlete) inner join ".$tm4db."meet_".$season." as m on (m.meet=r.meet) Where s.I_R='I' and r.course='".$course."' and r.NT=0 and a.sex=s.sex and a.athlete=".arg(4)." and (s.Lo_Age<=".$age." and s.Hi_age>=".$age.") and r.RBest=1 order by s.Lo_age,s.Hi_age,s.stroke,s.distance ";
	//echo $query_main;
	$results = db_query($query_main,strtolower(arg(5)));
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

       while ($object = db_fetch_array($results))
		{
			$sub_head=null;
			$cols=null;
			$sub_head[]=array('data'=>'<b>'.$object['distance'].' '.stroke($object['stroke']).'</b>','colspan'=>3);
			 
			$cols[]=''; 
			
			$cols[]=get_time($object['score']);
			$cols[]=Age($object['age_group']);	
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
				$sub_head[]=array('data'=>'<b><small>Next Level: </small><b>','colspan'=>2,'align'=>'right');
				$sub_head[]=array('data'=>'<b><small>'.$dsec[$i-$shift-1].' - '.get_time($object['S'.($i-1)]).' improve by '.(($object['score']-$object['S'.($i-1)])/100).'s</small></b>','colspan'=>3);
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
				       
				       
				 
				 
			       if (!$rows)
				 $rows[] = array(array('data' => t('There are no events matching you criteria (Age,Sex)'), 'colspan' => 11));
				 $table =  theme_table($headers, $rows,null);
				    $output.=$table;
				    $output.="</td></tr></table><br><br><br><br>";
?>