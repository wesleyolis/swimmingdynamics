	<?php
	
	
	
	$last_ranking =  $running_config['ranking_last_update'];
	require('ranking_date.php');
	
	
	$rankdate = $rank;
	
	echo 'rankingdates:'.$last_ranking.':'.$rankdate['date'];
	if($last_ranking < $rankdate['date'] || $last_ranking == null)
	{
		echo 'updating<br>';
		set_time_limit(60*$ranking_timeout);
		Ignore_User_Abort(True);
		$running_config['ranking_updating'] = true;
		require('../../../running_options.php');
	{
	$last_ranking = $running_config['ranking_last_update'];
	$lsc = $config['lsc'];
	$cntry = $config['cntry'];
	$confirm = $config['rankcon'];
	
	//$rankdate = ranking_date($season);
	
		
	
	     $ranktype = $rankdate['type'];
	     $rankdate = $rankdate['date'];
	     
	     $rank_from_option = $config['rank_from'];
	     $rank_days_before = $config['ranking_days_before'];
	     
	     if($ranktype=='archive')
	     $rank_days_before=0;


         $Sm = $config['ranking_mm'];
         $Sd = $config['ranking_dd'];

	     $from_date = $get_seas.'-'.$Sm.'-'.$Sd;
	     
	     $max_sanctions = $running_config['max_sanctions'];
	     $rank_fina = $config['rank_fina_multi_seasons'];
	     
	     //Updates Athelte Ages

	     	$running_config['ranking_type'] = $ranktype;
		
		  $query.="UPDATE ".$db_name."athlete as a Set a.Age = extract(YEAR FROM from_days(datediff('".$rankdate."', a.Birth)))";
		  db_query($query);
		
		if($rank_fina=='y')
		{
			$query="UPDATE ".$db_name."athlete as a Set a.fina_age = extract(YEAR FROM from_days(datediff('".$from_date."', a.Birth)))";
			db_query($query);
		}
		
	     //Set up team filters for all times match lsc and cntry settings.
	    
	     $filter='';
	     
	     if($cntry!='' || $lsc!='')
	     {
		     $team_option = $config['rank_team'];
			
		       if($cntry!='')
		       $filter.=" t.TCntry='".$cntry."' ";
		       if($cntry!='' & $lsc!='')
		       $filter.=" and ";
		       if($lsc!='')
		       $filter.=" t.lsc='".$lsc."' ";	
		       
		       if($team_option=='R')
		       $filter_team= " r.team=t.team ";
		       
		       if( strpos($team_option,'T')!==false)
		       {$team = strpos($team_option,'T');
			       if($team_option=='T1' || $team_option=='T2' || $team_option=='T3')
			       $filter_team=' a.Team1=t.TEAM ';
			       if($team_option=='T2' || $team_option=='T3')
			       $filter_team.=' or a.Team2=t.TEAM ';
			       if($team_option=='T3')
			       $filter_team.=' or a.Team3=t.TEAM ';
		       }
	
	     }
	     /*
	     if($ranking_stage <2)
	     {
	     if($rank_fina=='y')
	       {
		       //fina 2 seasons rankigns calculated
		  //All Meet Type's
		  db_query("Update ".$tm4db."result_".$season." as r Set r.RFINA = -1");
		  
		  //Exclude meets - use a diffrent method
		  $query="UPDATE ".$tm4db."result_".$season." as r inner JOIN ".$tm4db."meet_".$season." as m on (r.meet=m.meet) inner join ".$tm4db."meet_sanctions_".$season."  as sm on (m.meet=sm.meet) set r.RFINA=0 ";
		  $query.="Where ((DATEDIFF(m.END,'".$rankdate."') <=-".$rank_days_before.")  ".(($rank_from_option=='y')?(" and (DATEDIFF(m.END,'".(($season-1).'-'.$Sm.'-'.$Sd)."') >=0) "):'')." ) and sm.abbr='FNA';";
		
		   db_query( $query);
	
		
		   
		   //Exclude Athelte's
		   if($filter!='')
		   {
			   $query=" UPDATE ".$tm4db."result_".$season." as u,(";
			   $query.=" SELECT r.result from ".$tm4db."result_".$season." as r  inner JOIN  ".$tm4db."athlete_".$season." as a on (r.athlete = a.athlete) ";
			   $query.=" inner join ".$tm4db."team_".$season." as t on  (".$filter_team.") ";
			   $query.=" WHERE not (".$filter.") or not (r.age!=a.FINA_age) ) as r2";
			   $query.=" set u.RFINA=-2";
			   $query.=" where u.result=r2.result and u.RFINA=0";
			   
			   db_query( $query);
		   }
	       }
		  
		  variable_set('perfanal_'.$season.'_ranking_stage', 2);
		 $ranking_stage++;
	       }
	       
	       if($ranking_stage <3)
	       {
		if($rank_fina=='y')
		       {
			       //fina 2 seasons rankigns calculated
			    $grp='grp'.rand(0,500);
			    
			    
			   db_query("set @".$grp."='-1'");
			  $query=" update ".$tm4db."result_".$season." as r";
			  $query.=" set r.RFINA = if((@".$grp." != (@".$grp.":=concat(r.athlete,r.COURSE,r.STROKE,r.distance,r.NT,r.I_R)) and r.NT=0 and r.I_R = 'I'),1,if(r.points!=0,2,0))";
			  $query.=" Where r.RFINA>=0";
			  $query.=" order by r.Athlete,r.Course,r.Stroke,r.Distance,r.score;";
			 //$output.=$query;
			// echo $query;
			
			  db_query($query);
		       }
			  variable_set('perfanal_'.$season.'_ranking_stage', 3);
		$ranking_stage++;
	       }
	     */
	     
	       //All Meet Type's
		  db_query("Update ".$db_name."result as r Set r.RAll = if(r.NT=0,0,-3)");
		  
		  //Exclude meets
		  $query="UPDATE ".$db_name."result as r inner JOIN ".$db_name."meet as m on (r.meet=m.meet) set r.RAll=-1 ";
		  $query.="Where not ((DATEDIFF(m.END,'".$rankdate."') <=-".$rank_days_before.")  ".(($rank_from_option=='y')?(" and (DATEDIFF(m.END,'".$from_date."') >=0) "):'')." ".(($confirm !='' & $cntry!='')?" and (isnull(m.sanction) or INSTR(m.sanction,'".$cntry."!')=0) ":'').");";
echo $query;
		 
		   db_query( $query);
	
		   //Exclude Athelte's
		   if($filter!='')
		   {
			   $query=" UPDATE ".$db_name."result as u,(";
			   $query.=" SELECT r.result from ".$db_name."result as r  inner JOIN  ".$db_name."athlete as a on (r.athlete = a.athlete) ";
			   $query.=" inner join ".$db_name."team as t on  (".$filter_team.") ";
			   $query.=" WHERE not (".$filter.") ) as r2";
			   $query.=" set u.RALL=-2";
			   $query.=" where u.result=r2.result and u.RALL!=-1";
			   
			   db_query( $query);
		   }
		    $grp='grp'.rand(0,500);
		    
		    
		   db_query("set @".$grp."='-1'");
		  $query=" update ".$db_name."result as r";
		  $query.=" set r.RAll = if((@".$grp." != (@".$grp.":=concat(r.athlete,r.COURSE,r.STROKE,r.distance))),1,if(r.points!=0,2,0))";
		  $query.=" Where r.RAll>=0 and r.NT=0 and r.I_R = 'I'";
		  $query.=" order by r.Athlete,r.Course,r.Stroke,r.Distance,r.score;";
		 //$output.=$query;
		// echo $query;
		
		  db_query($query);
		
		  $meet_type = $config['rank_time_meet_filter'];
		  $meet_type_fina = $config['rank_fina_meet_filter'];

	     
	    // if(!($meet_type =='ALL' & $meet_type =='LSC' & $meet_type_fina =='ALL' & $meet_type_fina =='LSC'))//$meet_type !='' 
	     if(!($meet_type =='ALL' && $meet_type_fina =='ALL') )//$meet_type !='' 
	       {
		       
		  //updating Meet type rankings
		
			
			  //  echo $max_sanctions;
			  $query='';
			  for($i2=2;$i2<=$max_sanctions;$i2++)
			  {
				  $query.=", r.Rtype".$i2."=0";
			  }
		       db_query("Update ".$db_name."result as r Set r.RType = 0".$query);
		      
		       
		    //   $output.="Update ".$tm4db."result_".$season." as r Set r.RType = 0".$query;
		       
		       for($i=1;$i<=$max_sanctions;$i++)
		       {
			$query="update ".$db_name."result  as r inner JOIN ";
			$query.=" (".$db_name."meet_sanctions  as m left join ".$db_name."code as c ON (m.abbr=c.abbr)) on (r.meet=m.meet)";
			$query_rev='';
		    if($i==1)
			{
			$query_rev.="r.Rtype=if(r.RAll<0 or   r.NT!=0 or r.I_R != 'I',-1,c.tindex)";
			}
			else
			{
			$query_rev.="r.Rtype=if(r.Rtype=0,c.tindex,r.Rtype)";
			}

			for($i2=2;$i2<=$i;$i2++)
			{

                			//$query_rev=" r.Rtype".$i2."=if( r.Rtype".$i2." = 0 and r.Rtype".(($i2==2)?'':($i2-1))." > 0, c.tindex, r.Rtype".$i2.") ".$query_rev;

			$query_rev=", r.Rtype".$i2."=if(r.Rtype".$i2." = 0 and r.Rtype".(($i2==2)?'':($i2-1))." > 0, c.tindex, r.Rtype".$i2."), ".$query_rev;
			}
			$query.=' Set '.$query_rev;
			$query.=" Where c.type=3 ".(($confirm=='')?'':" and m.include=1")." and m.c=".($i+1);
   if($i==1)
			{
            $query.=" and ( r.POINTS != 0 or r.SCORE != 0 ); ";
}

			//$query.=" Where ((DATEDIFF(m.END,'".$rankdate."') <=-".$rank_days_before.")  ".(($rank_from_option=='y')?(" and (DATEDIFF(m.END,'".$from_date."') >=0) "):'')." "."  and  r.RAll>=0 and c.type=3 )";
			$output.=$query;
			
			db_query($query);
			       //.(($confirm !='' & $cntry!='')?" and (isnull(m.sanction) or INSTR(m.sanction,c.abbr)=0) ":'')
		       }
		
		    
		   
	
		  $results = db_query("Select distinct c.tindex,c.abbr from ".$db_name."code as c inner join ".$db_name."meet_sanctions as m ON (m.abbr=c.abbr) Where m.c >1 and c.type=3 ".(($confirm=='')?'':" and m.include=1")." and c.tindex >0 order by tindex asc");
		  
		if(!mysql_error())
		 while($object = mysql_fetch_object($results))
		  {
			  $grp='grp'.rand(0,500);
			 // db_query('CREATE TABLE IF NOT EXISTS '.$tm4db.'rankings_'.$object->tindex.'_'.$season."result INT, sub smallint)  ENGINE=MYISAM");
			//  db_query('INSERT INTO TargetTable (result,sub) ');
			db_query("set @".$grp."='0'");
		       $query=" update ".$db_name."result as r";
		       $query.=" set r.RType = if(r.RType!=".$object->tindex.",r.RType,if((@".$grp." != (@".$grp.":=concat(r.athlete,r.COURSE,r.STROKE,r.distance))),".$object->tindex.",if(r.POINTS!=0,".($object->tindex+1).",0)))";
		      
		       $col_Rtype='';
		       for($i=2;$i<=$max_sanctions;$i++)
		       {
			       $query.=", r.RType".$i." = if(r.RType".$i."!=".$object->tindex.",r.RType".$i.",if((@".$grp." != (@".$grp.":=concat(r.athlete,r.COURSE,r.STROKE,r.distance))),".$object->tindex.",if(r.POINTS!=0,".($object->tindex+1).",0)))";
		      
			       $col_Rtype.= " or r.RType".$i."=".$object->tindex;
		       }
		// r.NT=0 and r.I_R = 'I' and
		       $query.=" Where  ( r.RType=".$object->tindex." ".$col_Rtype.")";
		       $query.=" order by r.Athlete,r.Course,r.Stroke,r.Distance,r.score;";
		      $output.=$query;
		       
		       db_query($query);
		       $output.="Meet Type:".$object->abbr."<br/>";
	
		     
	
		    }
		 
	       }
	       
	    
	       $running_config['ranking_updating'] = false;
	       $running_config['ranking_last_update'] = $rankdate;
	     
	       require('../../../running_options.php');
	
	     //update fina points
		$output.='<br>'. $rankdate['type'].' Rankings '.$get_seas.' Version '.$version.' Have been updated as of '.$rankdate.'<br>'; 
		$output.='<br>'.l2('Go back to version menu ','','../../optimize/version.php');
	   }}else
	   {
		   $output='<br>'. $rankdate['type'].' Rankings '.$get_seas.' are up to date ('.$running_config['ranking_last_update'].')';
		   $output.='<br/><br/>'.l2('Rest current Rankings','&version='.$version,'reset_rankings.php');
		   $output.='<br>'.l2('Go back to version menu ','','../../optimize/version.php');
	   }
	 
	?>