<?php

require('../../main_include.php'); 

		$curr_param = '';
		foreach($_GET as $key => $val)
		{
			if($key != 'ss' && $key != 'db')
			$curr_param.= (($curr_param=='')?'':'&').$key.'='.$val;
		}

		$query = "select SQL_CACHE m.MName, m.AgeUp from ".$db_name."mtevent as e inner join ".$db_name."meet as m on (e.Meet=m.Meet) WHERE e.Meet=".inj_int($_GET['m']);
		  $result = db_query($query);
		  if(!mysql_error())
		  $object = mysql_fetch_object($result);
		  
		  switch($_GET['gen'])
		    {
		     case 'female': $Gen= "Female";
		       break;
		     case 'male': $Gen="Male";
		       break;
		     case 'mixed': $Gen="Mixed";
		       break;
		    }
		  

		  $Where = ' ';
		   $Where = "r.meet='".inj_int($_GET['m'])."' ";
		   
		  switch($_GET['gen'])
		    {
		     case 'female': $Where.= " and a.Sex='F'";
		       break;
		     case 'male': $Where.= " and a.Sex='M'";
		       break;
		    }
		 
		       $Where.= " and r.Age >=".inj_int($_GET['lo'])." and r.Age <=".inj_int($_GET['hi']);
		    
		  
		       drupal_set_title($object->MName.' Meet Fina '.$running_config['fina_points_rank_year'].' Points Ranking Results'.' '.$_GET['ss'].'-'.($_GET['ss']+1).'<br><small>'.$Gen.' '.Age(($_GET['lo']*100)+$_GET['hi']).' '.(($_GET['str']=='All' & $_GET['dis']=='All')?'Overall':((($_GET['dis']=='All')?'':$_GET['dis'].'m ').(($_GET['str']=='All')?'':Stroke($_GET['str'])))).', Age as of '.get_date($object->AgeUp).'</small>');
		       $breadcumb[] = l2('Meets',null,'index.php');
		       $breadcumb[] = l2('Age & Gender',substr($curr_param,0,strrpos($curr_param,'&gen')),'meets_fina_age.php');
		       $breadcumb[] = l2('Stroke & Dis',substr($curr_param,0,strrpos($curr_param,'&str')),'meets_fina_str_dis.php');
		       setseasons_breadcrumb($breadcumb);
		  
		  
		  
	  if($_GET['str']!='All')
		    $Where.= " and r.Stroke=".inj_int($_GET['str'])." ";
		  if($_GET['dis']!='All')
		    $Where.= " and r.distance=".inj_int($_GET['dis'])." ";
		
		  
		    
   $sort = (isset($_GET['s'])==false)?0:$_GET['s'];
   
   if($sort!=0)
   $curr_param = substr($curr_param,0,strrpos($curr_param,'&s'));
   
   $header[] = array('data' => ($sort==0)?'Max':l2('Max',$curr_param,'meets_fina.php'), 'style' => 'width:2em;');
   $header[] = array('data' => ($sort==1)?'Avg':l2('Avg',$curr_param.'&s=1','meets_fina.php'), 'style' => 'width:2em;');
   $header[] = array('data' => ($sort==2)?'Total':l2('Total',$curr_param.'&s=2','meets_fina.php'), 'style' => 'width:2em;');
   $header[] = array('data' => ($sort==3)?'Min':l2('Min',$curr_param.'&s=3','meets_fina.php'), 'style' => 'width:2em;');
   $header[] = array('data' => ($sort==4)?'STD':l2('STD',$curr_param.'&s=4','meets_fina.php'), 'style' => 'width:2em;');
   

   $headers[] = array('data' => t('#'), 'style' => 'width:2em;');
   $headers[] = $header[$sort];
   $headers[] = array('data' => t('Athlete Name'), 'style' => 'width:20em;');
   $headers[] = array('data' => t('M/F'), 'style' => 'width:1.5em;');
   $headers[] = array('data' => t('Age'), 'style' => 'width:1.5em;');
   $headers[] = array('data' => t('Team'), 'style' => 'width:7em;');
   $headers[] = array('data' => t('Events'), 'style' => 'width:2em;');
   for($i=0;$i<5;$i++)
     if($i!=$sort)
       {
	  $headers[] = $header[$i];
	  $fields[] = 'f'.$i;
       }

      
       
   $query = "SELECT SQL_CACHE Round(std(r.fina)) as f4,min(r.fina) as f3,max(r.fina) as f0,Round(avg(r.fina)) as f1, Count(*) as events, Sum(r.fina) as f2,";
   $query.= "a.Athlete, a.Last, a.First, a.Sex, r.age, t.TCode, t.LSC FROM (((".$db_name."result as r left join ".$db_name."athlete as a on (r.ATHLETE=a.Athlete)) left JOIN ".$db_name."team as t on (a.team1=t.TEAM) )) WHERE r.fina!=0 and ".$Where;
    $query.= " group by r.Athlete order by f".$sort." desc  limit ".$limit_results_query;

   $result = db_query($query);
   
   $pos = 0;
   $pos2 = -1;
   $point=NULL;
   //Grouping
   $First = false;
   if(!mysql_error())
   while ($object = mysql_fetch_array($result))
     {

	if($point != $object['f'.$sort])
	  {
	     $point = $object['f'.$sort];
	     $pos++;
	  }
	$link='ath='.$object['Athlete'];
	$rows[] = array((($pos ==$pos2)?'-':$pos),$object['f'.$sort],l2($object['Last'].",&nbsp;".$object['First'], $link,'../athlete/times/top_times.php'), $object['Sex'], $object['age'], $object['TCode'].'-'.$object['LSC'],$object['events'],$object[$fields[0]],$object[$fields[1]],$object[$fields[2]],$object[$fields[3]]);
	if($pos != $pos2)
	  $pos2 = $pos;
     }

   if (!$rows)
     {
	$rows[] = array(array('data' => 'There are no athletes results found matching your criteria.', 'colspan' => 11));
     }
      $output.='Change the Fina Rankings order by clicking on the column title, Avg, Total, Min, STD, Max';
   $output.= theme_table($headers, $rows,null,null);
   
   render_page();
?>