<?php
{
  $page_exe = true;
   $season = get_seasons();
      
   $fina_year = variable_get('perfanal_'.$season.'_ranking_fina_year', '');
      
   $tm4db = variable_get('perfanal_database', 'perfanal');
   $min_display = variable_get('perfanal_min_display', '20');
   $lsc = variable_get('perfanal_lsc', '');
   $cntry = variable_get('perfanal_cntry', '');
   $url_pref = (($cntry=='')?'All':$cntry);
   $confirm = variable_get('perfanal_rankcon', '');
   
   $pref_head='';
   if(arg(2)!='curr' & arg(2)!=null)
   $pref_head.='Predicative ';
   
   if($cntry!='')
     $pref_head.=$cntry.' ';
   if($lsc!='')
   {
   $pref_head.= (($pref_head !='')?' - ':'');
     $pref_head.=$lsc.' ';
   }
   if(arg(4)!=$cntry & arg(4)!=null & arg(4)!='All' & arg(4)!=$lsc)
   {
	   $pref_head.= (($pref_head !='')?' - ':'');
     $pref_head.=arg(4).' ';
   }
   
   $update = variable_get('perfanal_ranking_update', 'W');
   $period = variable_get('perfanal_ranking_period', '2');
   $rank_fina = variable_get('perfanal_rank_fina_multi_seasons', '');
   
   if($rank_fina =='y')
	{
		//cheack if there is a previous seasons
		$selected = (arg(1)-1);
		
		$rank_pre_seasons = false;
		  foreach (seasons() as $key => $value)
		     {
			if($key == $selected)
			{
			  $rank_pre_seasons = true;
			  break;
			}
		     }
		     
	}

   $type = Array('indi_points'=>'Individual Points','team_points'=>'Team Points','time'=>'Time','fina'=>'Fina');

   setseasons_breadcrumb(null);

   if(arg(2)=='updating')
     {
	      drupal_set_title($season.' '.$pref_head." Updating Rankings in processing");
	     $output='<br/>'.$season.' '.$pref_head." Updating Rankings in processing stage: ".variable_get('perfanal_'.$season.'_ranking_stage', '1');
	     $output.='<br/>This will only take a couple of seconds so please be patient.';
	     //return
	     
     }
   else
   {
	  $timeout = variable_get('perfanal_ranking_timeout',10)+1;
	  if(variable_get('perfanal_'.$season.'_ranking_updating',false) &  variable_set('perfanal_'.$season.'_ranking_start_time',time())> (date('Y-m-d',time())-($timeout*60)))
		drupal_goto('ranking/'.$season.'/updating');
	   
	     $last_ranking = variable_get('perfanal_'.$season.'_ranking_last_update', '');
	     
	     
	   perfanal_automated_rankings_Update($season);
	    //drupal_goto('ranking/'.$season.'/updating');
	
	    if(arg(2)=='meets')
	     {	     require('meets.php');
		     $page_exe=false;
	     }
	   else
	     if(arg(2)=='teams')
	       { require('teams.php');
		       $page_exe=false;
	       }
	       else
	     if(arg(2)=='pred')
	       { 
		       drupal_set_title($pref_head." Rankings based on ".arg(1)." Seasons");
		       require('predicative.php');
		       $page_exe=false;
	       }
	       else
	       if(arg(3)=='info')
		     {	     require('rank_info.php');
			     $page_exe=false;
		     }
		   else 
		     if(arg(2) ==null || arg(3) ==null) //Rankings type
		       {
			   $curr = (arg(2)==null)?'curr':arg(2);
			  drupal_set_title($pref_head." Rankings Categories");
			  $output.='<br>'.l('Individual Points','ranking/'.arg(1).'/'.$curr.'/indi_points').'<br><br>';
			  if(arg(2)==null)
			  $output.=l('Team Points','ranking/'.arg(1).'/curr/team_points').'<br><br>';
			  $output.=l('Times','ranking/'.arg(1).'/'.$curr.'/time').'<br><br>';
			  $output.=l('Fina - '.$fina_year ,'ranking/'.arg(1).'/'.$curr.'/fina').'<br>Allows performance comparison across strokes<br>and sprinters against long distance athletes.<br><br>';
			  if(arg(2)==null)
			  $output.=l('Predicative Rankings','ranking/'.arg(1).'/pred')."<br>Allows you the ability to select date at which athlete's ages are calculated at. To see how the age group rankings are influnce by your competitors age-out and age-into your age group.";
			  
			  //return
			   $page_exe=false;
		
		       }
   
       
       if($page_exe)
       {
   $breadcrumb[] = l('Categories','ranking/'.arg(1));
   $meettype=variable_get('perfanal_'.arg(1).'_type', '');
   $meettype_fina=variable_get('perfanal_'.arg(1).'_type_fina', '');
   if(arg(4)==null) //Country and LSC Filter
     {
	    
	   
	  if($lsc !='')
	  drupal_goto('ranking/'.arg(1).'/'.arg(2).'/'.arg(3).'/'.$lsc);
	

	drupal_set_title($pref_head.$type[arg(3)].' Rankings - Graphical location');
	setseasons_breadcrumb($breadcrumb);

	$headers[] = array('data' => t('LSC'), 'width' => '80px');
	$headers[] = array('data' => t('Description'), 'width' => '250px');

	$results = db_query("Select SQL_CACHE distinct t.lsc,c._desc From ".$tm4db."team_".$season." as t left join ".$tm4db."code_".$season." as c on (t.lsc = c.abbr and c.type=3) where t.lsc !='' ".(($cntry=='')?'':"and t.tcntry ='".$cntry."'" ).(($meettype=='LSC')?' and t.lsc=c.abbr':'').' order by LSC');
	  if($lsc=='' & $cntry!='')
	    $rows[] = array(l($url_pref,'ranking/'.arg(1).'/'.arg(2).'/'.arg(3).'/'.$url_pref),variable_get('perfanal_cntry_desc',''));

	while($object = db_fetch_object($results))
	  if($cntry!=$object->lsc)
	    $rows[] = array(l($object->lsc,'ranking/'.arg(1).'/'.arg(2).'/'.arg(3).'/'.$object->lsc),$object->_desc);

	$output.= theme_table($headers, $rows,array('id'=>'hyper','class'=>'hyper'),null);
	
	//return;
	$page_exe=false;

     }
     
       }
       
        if($page_exe)
       {
   if(($lsc =='' & $cntry=='') || ($cntry!='' & $lsc ==''))
      $breadcrumb[] = l('Graphical location','ranking/'.arg(1).'/'.arg(2).'/'.arg(3));
    

   if(arg(5)==null) //Meet type
     {
	if(arg(3)!='fina')
	{
	 if($meettype!='' & $meettype!='LSC')
	  drupal_goto('ranking/'.arg(1).'/'.arg(2).'/'.arg(3).'/'.arg(4).'/'.$meettype);
	}
	else
	{
	  if($meettype_fina!='' & $meettype_fina!='LSC')
	  drupal_goto('ranking/'.arg(1).'/'.arg(2).'/'.arg(3).'/'.arg(4).'/'.$meettype_fina);
	}
	
	  
	if(($meettype=='LSC' && arg(3)!='fina') || ($meettype_fina=='LSC' && arg(3)=='fina'))
	  {
		  if($cntry=='' || arg(4) != $cntry)
		  {
		     $results = db_query("Select SQL_CACHE  distinct c.ABBR,c._DESC  from ".$tm4db."code_".$season." as c inner join ".$tm4db."meet_sanctions_".$season." as m ON (c.abbr=m.abbr) Where c.abbr='%s' ".(($confirm=='')?'':" and m.include=1")." and type=3",arg(4));
		     $object = db_fetch_object($results);
		     if($object!=null)
		       {
			    drupal_goto('ranking/'.arg(1).'/'.arg(2).'/'.arg(3).'/'.arg(4).'/'.arg(4));
		       }
		     else
		       {
			  drupal_set_message("No Meet Type's found matching ".arg(4));
			  drupal_goto('ranking/'.arg(1).'/'.arg(2));
		       }
		  }
		  else
		  drupal_goto('ranking/'.arg(1).'/'.arg(2).'/'.arg(3).'/'.arg(4).'/ALL');
		  

	  }
	drupal_set_title($pref_head.$type[arg(3)].' Rankings - Meet Type');
	setseasons_breadcrumb($breadcrumb);
	$output.=  Meet_Type_Table('ranking/'.arg(1).'/'.arg(2).'/'.arg(3).'/'.arg(4),$tm4db,$season);
	//return
	$page_exe=false;
     }
     
       }
       
        if($page_exe)
       {
       

   if($meettype=='')
     $breadcrumb[] = l('Meet Type','ranking/'.arg(1).'/'.arg(2).'/'.arg(3).'/'.arg(4));

   if(arg(5)!='ALL')
     {
	$results = db_query("Select SQL_CACHE * from ".$tm4db."code_".$season." Where abbr='%s' and type=3",arg(5));
	$object = db_fetch_object($results);
	$tindex[0] = $object->tindex;
	$heading = (($object->_DESC=='')?$object->ABBR.' ':$object->_DESC).' Meets Only, ';
	//previus seasons match in need
	if($rank_pre_seasons)
	{
		$results = db_query("Select SQL_CACHE * from ".$tm4db."code_".($season-1)." Where abbr='%s' and type=3",arg(5));
		$object = db_fetch_object($results);
		$tindex[1] = $object->tindex;
		if($tindex[1]==null)
		$rank_pre_seasons = false;
	}
     }
   else
     $heading = 'All Meets, ';

   if(arg(6) ==null) // Course
     {
	drupal_set_title($pref_head.$type[arg(3)].' Rankings - Course'."<br/><small>".$heading.'</small>');
	setseasons_breadcrumb($breadcrumb);
	$output="<br>Please Select a course.<br><br>";
	if(arg(3) =='indi_points' || arg(3) =='team_points' || arg(3) =='fina')
	  $output.=l('All Courses','ranking/'.arg(1).'/'.arg(2).'/'.arg(3).'/'.arg(4).'/'.arg(5).'/ALL').'<br><br>';
	$output.=l('Short Course 25m','ranking/'.arg(1).'/'.arg(2).'/'.arg(3).'/'.arg(4).'/'.arg(5).'/S').'<br><br>';
	$output.=l('Long Course 50m','ranking/'.arg(1).'/'.arg(2).'/'.arg(3).'/'.arg(4).'/'.arg(5).'/L').'<br><br>';
	if(arg(3)!='fina')
	$output.=l('Open Water','ranking/'.arg(1).'/'.arg(2).'/'.arg(3).'/'.arg(4).'/'.arg(5).'/L/O').'<br><br>';
	//$output.=l('Yards','ranking/'.arg(1).'/'.arg(3).'/'.arg(4).'/'.arg(5).'/Y').'<br><br>';
	$output;
	//return;
	 $page_exe=false;
     }
    
       }
       
        if($page_exe)
       {
   $breadcrumb[] = l('Course','ranking/'.arg(1).'/'.arg(2).'/'.arg(3).'/'.arg(4).'/'.arg(5));

   if(arg(7) ==null || arg(8) ==null)//Age group and gender
     {
	     require('age_group.php');
	//return
	$page_exe=false;

     }
   else
     {
	require('age_group_where.php');  
       }
       
        if($page_exe)
	{
	  
	//Display Rankings Title
	$output.="<p class='no_print' style='padding:5px;' align='right'>".((arg(3)=='fina')?l('Base Times - '.$fina_year,'fina/base_times/'.$fina_year.'/'.arg(6)).' | ':'').'Included '.l('Meets','ranking/'.arg(1).'/meets/'.arg(5).'/'.$last_ranking.'/'.arg(6))." & ".l('Teams','ranking/'.arg(1).'/teams/include/'.(($cntry != arg(4) & arg(4)!='All')?arg(4):''))." | ".l('Detailed Information','ranking/'.arg(1).'/'.arg(2).'/info')."</p>";
	$breadcrumb[]=l('Age Group & Gender','ranking/'.arg(1).'/'.arg(2).'/'.arg(3).'/'.arg(4).'/'.arg(5).'/'.arg(6));
	setseasons_breadcrumb($breadcrumb);
	drupal_set_title($pref_head.' '.$type[arg(3)].' Rankings as of '.$last_ranking.'<br><small>'.$heading.' '.Gender(arg(7)).' '.Age(arg(8)).' '.Course(1,arg(6)).'</small> '.((arg(2)=='curr')?'':"<small><br>Athletes ages on ".arg(2).'</small>'));
	}
	if($page_exe)
	if(arg(3)=='indi_points') //Dsiplay Individual Points Rankings
	  {
		  
		  $Where.=$Where_rank[0];
	     require('indi_points.php');
	     //return
	  }
	else if(arg(3)=='team_points') // Display team Points Rankings
	  {
		  $Where.=$Where_rank[0];
	     require('team_points.php');
	     //return
	  }
	else if(arg(9)==null || arg(10) ==null) // Stroke Distance selection
	  {
	     if(arg(3)=='time')
	       {
		      
		       require('time/stroke_distance.php');
		 //return
	       }
	     else
	       if(arg(3)=='fina')
		 {
			 require('fina/stroke_distance.php');
		   
		    //return
		 }
	  }
	else
	  {
	     $breadcrumb[] = l('Distance & Stroke','ranking/'.arg(1).'/'.arg(2).'/'.arg(3).'/'.arg(4).'/'.arg(5).'/'.arg(6).'/'.arg(7).'/'.arg(8));
	     setseasons_breadcrumb($breadcrumb);

	     if(arg(3)=='time') // Display Times Rankings
	       {
		   $Where.=$Where_rank[0];
		   
		   require('time/score.php');
		   //return
	       }
	     else if(arg(3)=='fina') // Display Fina Rankings
	       {
		       //fina where is inside its own module for cusotmization
		  require('fina/fina.php');
		  //return
	       }

	  }
   }
   }

}
?>