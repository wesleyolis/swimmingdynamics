<?php
$last_ranking = variable_get('perfanal_'.$season.'_ranking_last_update', getDate());

	   $rankdate = ranking_date($season);
	   if($last_ranking < $rankdate['date'])
	   {
		   echo 'updating';
	variable_set('perfanal_'.$season.'_ranking_start_time',date('Y-m-d', time()));
	
	$timeout = variable_get('perfanal_ranking_timeout',10);
	set_time_limit(60*$timeout);
	Ignore_User_Abort(True);
	$tm4db = variable_get('perfanal_database', 'perfanal');
	variable_set('perfanal_'.$season.'_ranking_updating',true);
	
	{
	$last_ranking = variable_get('perfanal_'.$season.'_ranking_last_update', getdate());
	$lsc = variable_get('perfanal_lsc', '');
	$cntry = variable_get('perfanal_cntry', '');
	$confirm = variable_get('perfanal_rankcon', '');
	$rankdate = ranking_date($season);

	
	     $ranktype = $rankdate['type'];
	     $rankdate = $rankdate['date'];
	     
	     $rank_from_option = variable_get('perfanal_rank_from','y');
	     $rank_days_before = variable_get('perfanal_ranking_days_before','2');
	     if($ranktype=='archive')
	     $rank_days_before=0;
	     
	      $Sd = variable_get('perfanal_ranking_dd', '01');
	      $Sm = variable_get('perfanal_ranking_mm', '01');
	     $from_date = $season.'-'.$Sm.'-'.$Sd;
	     $max_sanctions = variable_get('perfanal_'.$season.'_max_sanctions', 1);

	     $ranking_stage = variable_get('perfanal_'.$season.'_ranking_stage', 0);
	     
	     $rank_fina = variable_get('perfanal_rank_fina_multi_seasons', '');
	     
	     //Updates Athelte Ages
	     if($ranking_stage <1)
	       {
		  variable_set('perfanal_'.$season.'_ranking_type', $ranktype);
		  $query.="UPDATE ".$tm4db."athlete_".$season." as a Set a.Age = extract(YEAR FROM from_days(datediff('".$rankdate."', a.Birth)))";
		  db_query($query);
		
		
		
		if($rank_fina=='y')
		{
			$query="UPDATE ".$tm4db."athlete_".$season." as a Set a.fina_age = extract(YEAR FROM from_days(datediff('".$from_date."', a.Birth)))";
			db_query($query);
		}
		
		  $ranking_stage+=3;
		  variable_set('perfanal_'.$season.'_ranking_stage', 3);
	       }

	     //Set up team filters for all times match lsc and cntry settings.
	    
	     $filter='';
	     
	     if($cntry!='' || $lsc!='')
	     {
		      	$team_option = variable_get('perfanal_rank_team','R');
			
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
	     
	     if($ranking_stage <4)
	       {
		  //All Meet Type's
		  db_query("Update ".$tm4db."result_".$season." as r Set r.RAll = if(r.NT=0,0,-3)");
		  
		  //Exclude meets
		  $query="UPDATE ".$tm4db."result_".$season." as r inner JOIN ".$tm4db."meet_".$season." as m on (r.meet=m.meet) set r.RAll=-1 ";
		  $query.="Where not ((DATEDIFF(m.END,'".$rankdate."') <=-".$rank_days_before.")  ".(($rank_from_option=='y')?(" and (DATEDIFF(m.END,'".$from_date."') >=0) "):'')." ".(($confirm !='' & $cntry!='')?" and (isnull(m.sanction) or INSTR(m.sanction,'".$cntry."!')=0) ":'').");";
		 
		   db_query( $query);

		   //Exclude Athelte's
		   if($filter!='')
		   {
			   $query=" UPDATE ".$tm4db."result_".$season." as u,(";
			   $query.=" SELECT r.result from ".$tm4db."result_".$season." as r  inner JOIN  ".$tm4db."athlete_".$season." as a on (r.athlete = a.athlete) ";
			   $query.=" inner join ".$tm4db."team_".$season." as t on  (".$filter_team.") ";
			   $query.=" WHERE not (".$filter.") ) as r2";
			   $query.=" set u.RALL=-2";
			   $query.=" where u.result=r2.result and u.RALL!=-1";
			   
			   db_query( $query);
		   }

		  
		  variable_set('perfanal_'.$season.'_ranking_stage', 4);
		 $ranking_stage++;
	       }
		     

	     if($ranking_stage <5)
	       {
		    $grp='grp'.rand(0,500);
		    
		    
		   db_query("set @".$grp."='-1'");
		  $query=" update ".$tm4db."result_".$season." as r";
		  $query.=" set r.RAll = if((@".$grp." != (@".$grp.":=concat(r.athlete,r.COURSE,r.STROKE,r.distance))),1,if(r.points!=0,2,0))";
		  $query.=" Where r.RAll>=0 and r.NT=0 and r.I_R = 'I'";
		  $query.=" order by r.Athlete,r.Course,r.Stroke,r.Distance,r.score;";
		 //$output.=$query;
		// echo $query;
		
		  db_query($query);
		  variable_set('perfanal_'.$season.'_ranking_stage', 5);
		$ranking_stage++;
		
	       }
	      $meet_type=variable_get('perfanal_'.arg(1).'_type', '');
	      $meet_type_fina=variable_get('perfanal_'.arg(1).'_type_fina', '');
	     
	    // if(!($meet_type =='ALL' & $meet_type =='LSC' & $meet_type_fina =='ALL' & $meet_type_fina =='LSC'))//$meet_type !='' 
	     if(!($meet_type =='ALL' & $meet_type_fina =='ALL'))//$meet_type !='' 
	       {
		       
		  //updating Meet type rankings
		  if($ranking_stage <6)
		    {
			 
			  //  echo $max_sanctions;
			  $query='';
			  for($i2=2;$i2<=$max_sanctions;$i2++)
			  {
				  $query.=", r.Rtype".$i2."=0";
			  }
		       db_query("Update ".$tm4db."result_".$season." as r Set r.RType = 0".$query);
		       
		    //   $output.="Update ".$tm4db."result_".$season." as r Set r.RType = 0".$query;
		       
		       for($i=1;$i<=$max_sanctions;$i++)
		       {
		    	$query="update ".$tm4db."result_".$season."  as r inner JOIN ";
			$query.=" (".$tm4db."meet_sanctions_".$season."  as m left join ".$tm4db."code_".$season."  as c ON (m.abbr=c.abbr)) on (r.meet=m.meet)";
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
			$query_rev=" r.Rtype".$i2."=if(r.Rtype".(($i2==2)?'':($i2-1))."<=0,0,c.tindex), ".$query_rev;
			}
			$query.=' Set '.$query_rev;
			$query.=" Where c.type=3 ".(($confirm=='')?'':" and m.include=1")." and m.c=".($i+1);
			
			//$query.=" Where ((DATEDIFF(m.END,'".$rankdate."') <=-".$rank_days_before.")  ".(($rank_from_option=='y')?(" and (DATEDIFF(m.END,'".$from_date."') >=0) "):'')." "."  and  r.RAll>=0 and c.type=3 )";
			//$output.=$query;
			
			db_query($query);
			       //.(($confirm !='' & $cntry!='')?" and (isnull(m.sanction) or INSTR(m.sanction,c.abbr)=0) ":'')
		       }
		       variable_set('perfanal_'.$season.'_ranking_stage', 6);
		    $ranking_stage++;
		    
		    }
		   

		  $results = db_query("Select distinct c.tindex,c.abbr from ".$tm4db."code_".$season." as c inner join ".$tm4db."meet_sanctions_".$season." as m ON (m.abbr=c.abbr) Where m.c >1 and c.type=3 ".(($confirm=='')?'':" and m.include=1")." and c.tindex >".($ranking_stage-4)." order by tindex asc");
		  
			
		 while($object = db_fetch_object($results))
		  {$grp='grp'.rand(0,500);
			 // db_query('CREATE TABLE IF NOT EXISTS '.$tm4db.'rankings_'.$object->tindex.'_'.$season."result INT, sub smallint)  ENGINE=MYISAM");
			//  db_query('INSERT INTO TargetTable (result,sub) ');
		        db_query("set @".$grp."='0'");
		       $query=" update ".$tm4db."result_".$season." as r";
		       $query.=" set r.RType = if(r.RType!=".$object->tindex.",r.RType,if((@".$grp." != (@".$grp.":=concat(r.athlete,r.COURSE,r.STROKE,r.distance))),".$object->tindex.",if(r.POINTS!=0,".($object->tindex+1).",0)))";
		      
		       $col_Rtype='';
		       for($i=2;$i<=$max_sanctions;$i++)
		       {
			       $query.=", r.RType".$i." = if(r.RType".$i."!=".$object->tindex.",r.RType".$i.",if((@".$grp." != (@".$grp.":=concat(r.athlete,r.COURSE,r.STROKE,r.distance))),".$object->tindex.",if(r.POINTS!=0,".($object->tindex+1).",0)))";
		      
			       $col_Rtype.= " or r.RType".$i."=".$object->tindex;
		       }
		
		       $query.=" Where  r.NT=0 and r.I_R = 'I' and ( r.RType=".$object->tindex." ".$col_Rtype.")";
		       $query.=" order by r.Athlete,r.Course,r.Stroke,r.Distance,r.score;";
		    //  $output.=$query;
		       
		       db_query($query);
		       $output.="Meet Type:".$object->abbr."<br/>";

		       variable_set('perfanal_'.$season.'_ranking_stage', ($object->tindex+6));
		      $ranking_stage=$object->tindex+7;

		    }
		 
	       }
	       
	    
		  variable_set('perfanal_'.$season.'_ranking_updating',false);
		  variable_set('perfanal_'.$season.'_ranking_last_update', $rankdate);
		  variable_set('perfanal_'.$season.'_ranking_stage', 0);
	
	       

	     //update fina points
	     	$output.='Rankings '.$season.' Have been updated'; 
	   }}else
	   {
		   $output='Rankings '.$season.' are up to date';
		   $output.='<br/><br/>'.l('Rest current Rankings','updaterankings/'.arg(1).'/reset');
	   }
	   cache_clear_all('*', 'cache_page', TRUE);
?>