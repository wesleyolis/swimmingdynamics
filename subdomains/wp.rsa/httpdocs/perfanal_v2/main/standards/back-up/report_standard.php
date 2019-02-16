<?php
	$season = get_cur_seasons();	
	//$season = get_seasons();
   $tm4db = variable_get('perfanal_database', 'perfanal');

  
   if(arg(1)==null)
     {
	drupal_set_title('National Gala Standards Statistics');
	$output.="Please select a meet from which its rules may be used, with the standard.";

	$headers[] = array('data' => t('Meet'), 'width' => '300px','field' => 'm.MName');
	$headers[] = array('data' => t('Type'), 'width' => '20px');
	$headers[] = array('data' => t('Start date'), 'width' => '100px','sort' => 'desc','field' => 'm.Start');
	$headers[] = array('data' => t('End date'), 'width' => '100px');
	$headers[] = array('data' => t('Course'), 'width' => '40px');
	$headers[] = array('data' => t('Location'));
	// order by m.Start DESC
	//
	$result = db_query("select SQL_CACHE DISTINCT IF(e.Meet Is Null,1,0) as results, m.Meet, m.MName, m.Start, m.End,m.Type, m.Course, m.Location  from ".$tm4db."meet_".$season." m left join ".$tm4db."mtevent_".$season." e on (m.Meet=e.Meet) where m.Start >CURDATE()".tablesort_sql($headers));

	while ($object = db_fetch_object($result))
	  {
	     $link = 'report_standard/'.$object->Meet;
	     $rows[] = array('data' => array(l(t($object->MName), $link),$object->Type,get_date($object->Start), get_date($object->End), $object->Course, $object->Location),'class'=>'onlythis');
	  }
	$output.= theme_table($headers, $rows,array('id'=>'hyper','class'=>'hyper'),NULL);

	$output;
     }
   else
     {
	if(arg(2)==null)
	  {
	     drupal_set_title("National Gala Standards Statistics");

	     $output.="Please select a standard.";

	     $headers[] = array('data' => t('Name'), 'width' => '100px');
	     $headers[] = array('data' => t('Description'), 'width' => '150px');
	     $headers[] = array('data' => t('Year'), 'width' => '40px');

	     $results = db_query("Select StdFile,Descript,Year From ".$tm4db."stdname_".$season." Order BY Year desc,StdFile");

	     while($object = db_fetch_object($results))
	       $rows[] = array(l($object->StdFile,'report_standard/'.arg(1).'/'.$object->StdFile),$object->Descript,$object->Year);

	    $output.=theme_table($headers, $rows,array('id'=>'hyper','class'=>'hyper'),NULL);
	  }
	else
	  {

	     $result = db_query("Select * From ".$tm4db."stdname_".$season." Where StdFile='%s' ",arg(2));
	     $object = db_fetch_array($result);
	     $sc_pre='';
	     $sc_suf='';
	     $lc_pre='';
	     $lc_suf='';
	     $qt='';
	     for($i=0;$i<12;$i++)
	       {
		  if($object['D'.($i+1).''] != null)
		    {
		       $des[] = $object[('D'.($i+1))];
		       $qt.='S'.$i.',F'.$i.',S'.($i+12).',F'.($i+12).',';
		       $sc_pre.= "IF(IF((S".$i.">0)||(F".$i.">0)||(S".($i+12).">0)||(F".($i+12).">0),IF(S".$i.">0, If(S".$i.">=Score,1,0),1) && IF(F".$i.">0, If(F".$i."<=Score,1,0),1),1),".$i.",";
		       $sc_suf.=")";
		       $lc_pre.= "IF(IF((S".$i.">0)||(F".$i.">0)||(S".($i+12).">0)||(F".($i+12).">0),IF(S".($i+12).">0, If(S".($i+12).">=Score,1,0),1) && IF(F".($i+12).">0, If(F".($i+12)."<=Score,1,0),1),1),".($i).",";
		       $lc_suf.=")";

		    }

	       }

	     $STD = $object['StdFile'];
	     if(db_num_rows($result)>0)
	       {
		  $result = db_query("select m.*,IF(m.AgeUp=null,m.Start,m.AgeUp) as AgeUp from ".$tm4db."meet_".$season." as m Where m.meet=%d",arg(1));
		  $object = db_fetch_object($result);

		  drupal_set_title($object->MName.", ".arg(2)." - National Gala Standards Statistics");

		  $sel='';
		  if($object->Course=='L' || $object->Course=='S')
		    {
		       $sel="If(Course='L',".$lc_pre.' 99'.$lc_suf.','.$sc_pre.' 99'.$sc_suf.') as qt ';
		       $sel.='';
		    }
		  else
		    if($object->Course=='LO')
		      $sel=$lc_pre.' 99'.$lc_suf.' as qt ';
		  else
		    $sel=$sc_pre.' 99'.$sc_suf.' as qt ';

		  $query = "Select r.*, e.Lo_Age,e.Hi_Age,".$qt.' '.$sel." From ".$tm4db."".strtolower($STD)."_".$season." as e left JOIN ";
		  $query.= " (Select r.*, MIN(score2) as Score, extract(YEAR FROM from_days(datediff('".$object->AgeUp."', Birth))) as Ages FROM (select m.MName,m.Start,r.Meet,r.I_R,r.Score as score2,r.Stroke,r.Distance,r.Course,";
		  $query.=" a.* FROM (".$tm4db."result_".$season." as r inner JOIN ".$tm4db."meet_".$season." as m on (r.meet=m.meet )) inner JOIN ".$tm4db."athlete_".$season." as a on (r.athlete=a.athlete) ";
		  $query.=" WHERE r.NT=0 and r.I_R !='R'".(($object->Since ==null)?'':"and m.Start >= '".$object->Since."'").(($object->RestrictBest==False)?'':" and m.Type='".$object->Type."'")."  and ".(($object->Course=='L'||$object->Course=='S'?"r.Course!='Y'":"r.Course='".substr($object->Course,0,1)."'"))."  order by a.athlete,stroke,distance,course,score) as r group BY athlete,stroke,distance,course) as r on ((r.stroke=e.STROKE) and (r.distance=e.DISTANCE) and (r.sex=e.sex))";
		  $query.=" where r.Ages>=e.Lo_Age and r.Ages <=e.Hi_Age ".(($object->Age < $object->MinAge)?'and Not(e.Lo_Age=0 and e.Hi_Age=99)':'')." order by athlete,stroke,distance,qt,course desc";

		  $query = "SELECT c._desc,Sex,mqt, Count(athlete) as ath from (Select Athlete,SubGr,Sex,Min(qt) as mqt From(".$query.") as re Where re.qt<99 group by athlete ) as re left join ".$tm4db."code_".$season." as c on (re.SubGr=c.ABBR ) Where c.TYPE=1 GROUP by SubGr,Sex,mqt with ROLLUP";

		  $headers[] = array('data' => t('Races'), 'width' => '100px');
		  $headers[] = array('data' => t('Gender'), 'width' => '60px');
		  $headers[] = array('data' => t('STD'), 'width' => '60px');
		  $headers[] = array('data' => t('Amount'), 'width' => '60px');

		  $results = db_query($query);

		  while($object = db_fetch_object($results))
		    $rows[] = array($object->_desc,($object->Sex==null)?(array('data'=>'Total','class'=>'green')):Gender($object->Sex),($object->mqt==null)?(($object->Sex==null)?array('data'=>'','class'=>'green'):(array('data'=>'Total','class'=>'green'))):$des[$object->mqt],($object->mqt==null)?array('class'=>'green','data'=>$object->ath):$object->ath);
	       }
	    $output =  theme('table', $headers, $rows);
	  }
     }
?>