<?php

$query = "select SQL_CACHE m.MName, m.AgeUp from ".$tm4db."mtevent_".$season." e, ".$tm4db."meet_".$season." m WHERE e.Meet=%d and e.Meet=m.Meet";
		  $result = db_query($query,arg(4));
		  $object = db_fetch_object($result);
		  
		  switch(arg(3))
		    {
		     case 'female': $Gen= "Female";
		       break;
		     case 'male': $Gen="Male";
		       break;
		     case 'mixed': $Gen="Mixed";
		       break;
		    }
		  

		  $Where = ' ';
		   $Where = "r.meet='".arg(4)."' ";
		  switch(arg(3))
		    {
		     case 'female': $Where.= " and a.Sex='F'";
		       break;
		     case 'male': $Where.= " and a.Sex='M'";
		       break;
		    }
		  if(arg(5) !=NULL)
		    {
		       $Where.= " and r.Age >=".floor(arg(5)/100)." and r.Age <=".(arg(5)%100);
		    }
		  
		  drupal_set_title($object->MName.' Meet Results'.' '.arg(1).'-'.(arg(1)+1).' - Fina Points '.(floor((arg(1)/4))*4).' <br><small>'.$Gen.' '.Age(arg(5)).', Age as of '.get_date($object->AgeUp).'</small>');//."&nbsp;&nbsp;&nbsp;Points Rankings<br><br>");
		  setseasons_breadcrumb(array(l('Meets','meets/'.arg(1))));
		  $output.="<div class='tabs'><ul class='tabs primary'>";
		  $output.="<li>".l('Info','meets/'.arg(1).'/meets_info/'.arg(4))."</li>";
		  $output.="<li>".l('Events','meets/'.arg(1).'/events/'.arg(4))."</li>";
		  $output.="<li>".l('Individual Points','meets/'.arg(1).'/points/'.arg(4))."</li>";
		  $output.="<li>".l('Team Points','meets/'.arg(1).'/team_points/'.arg(4))."</li>";
		  $output.="<li class='active'>".l('Fina Points','meets/'.arg(1).'/fina_points/'.arg(4))."</li></div>";
		  

 $sort = (arg(6)==null)?0:arg(6);
   $url = 'meets/'.arg(1).'/fina_points/'.arg(3).'/'.arg(4).'/'.arg(5);

   $header[] = array('data' => ($sort==0)?t('Avg'):l(t('Avg'),$url), 'width' => '40px');
   $header[] = array('data' => ($sort==1)?t('Total'):l(t('Total'),$url.'/1'), 'width' => '40px');
   $header[] = array('data' => ($sort==2)?t('Max'):l(t('Max'),$url.'/2'), 'width' => '40px');
   $header[] = array('data' => ($sort==3)?t('Min'):l(t('Min'),$url.'/3'), 'width' => '40px');

   $headers[] = array('data' => t('#'), 'width' => '40px');
   $headers[] = $header[$sort];
   $headers[] = array('data' => t('Athlete Name'), 'width' => '250px');
   $headers[] = array('data' => t('M/F'), 'width' => '30px');
   $headers[] = array('data' => t('Age'), 'width' => '30px');
   $headers[] = array('data' => t('Team'), 'width' => '80px');
   $headers[] = array('data' => t('Events'), 'width' => '40px');
   for($i=0;$i<4;$i++)
     if($i!=$sort)
       {
	  $headers[] = $header[$i];
	  $fields[] = 'f'.$i;
       }
       
      
       
   $query = "SELECT SQL_CACHE min(r.fina) as f3,max(r.fina) as f2,Round(avg(r.fina)) as f0, Count(*) as events, Sum(r.fina) as f1,";
   $query.= "a.Athlete, a.Last, a.First, a.Sex, r.age, t.TCode, t.LSC FROM (((".$tm4db."result_".$season." as r left join ".$tm4db."athlete_".$season." as a on (r.ATHLETE=a.Athlete)) left JOIN ".$tm4db."team_".$season." as t on (a.team1=t.TEAM) )) WHERE r.fina!=0 and a.Group='A' and ".$Where;
    $query.= " group by r.Athlete order by f".$sort." desc ".pages_limit(1);

   $result = db_query($query);
   //$output.=$query;
   $pos = 0;
   $pos2 = -1;
   $point=NULL;
   //Grouping
   $First = false;
   while ($object = db_fetch_array($result))
     {

	if($point != $object['f'.$sort])
	  {
	     $point = $object['f'.$sort];
	     $pos++;
	  }
	$link='athlete/'.arg(1).'/top_times/'.$object['Athlete'];
	$rows[] = array((($pos ==$pos2)?'-':$pos),$object['f'.$sort],( ($object['Last']==NULL)?'Athlete not found':l(t($object['Last'].", ".$object['First']), $link)), $object['Sex'], $object['age'], $object['TCode'].'-'.$object['LSC'],$object['events'],$object[$fields[0]],$object[$fields[1]],$object[$fields[2]]);
	if($pos != pos2)
	  $pos2 = $pos;
     }

   if (!$rows)
     {
	$rows[] = array(array('data' => t('There are no athletes results found matching your criteria, click '.l(t('here'), 'ranking/'.arg(1).'/'.arg(2).'/'.arg(3).'/'.arg(4).'/'.arg(5).'/'.arg(6)).' to go back.'), 'colspan' => 10));
     }
   $output.= theme('table', $headers, $rows);
?>