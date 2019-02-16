<?php
// Set up where conditioning
	if($_GET['type']=='indi_points' || $_GET['type']=='team_points' || $_GET['type']=='time' || $_GET['type']=='fina')
	  {
	     $Where = "";
	     $Where_rank= null;
	     $B_date = str_replace('-','/',$_GET['d']);
	     if(!($_GET['lo'] == 0 && $_GET['hi']==99))
	     if($_GET['type']=='team_points') //|| arg(3)=='fina')
	     {
	       $Where.=" r.AGE >=".inj_int($_GET['lo'])." and r.AGE <=".inj_int($_GET['hi'])." ";
	
	     }
	     else
	     {
		     if($rankings_predicative==false)
		     {
		       $Where.=" a.age >=".inj_int($_GET['lo'])." and a.age <=".inj_int($_GET['hi'])." ";
		       
		     }
		       else  
		       {
			       
			       $Where.=" extract(YEAR FROM from_days(datediff('".inj_str($B_date)."', a.Birth))) >=".inj_int($_GET['lo'])." and extract(YEAR FROM from_days(datediff('".inj_str($B_date)."', a.Birth))) <=".inj_int($_GET['hi'])." ";
			       
		       }
	     }
	       else
	       {$Where.=" (1=1) ";
	       }
	       
	       if($rankings_predicative == false)
		     $ath_age=" a.age ";
		     else
		     {
			     
			     $ath_age=" extract(YEAR FROM from_days(datediff('".inj_str($B_date)."', a.Birth))) as age ";
		     }
		     
	       
	       
	        $max_sanctions = $running_config['max_sanctions'];
		//echo 'max sanctions'.$running_config['max_sanctions'];
	     if($_GET['type'] == 'team_points' || $_GET['type'] == 'indi_points')
	       {
		       
		      $t=0;
		  if($_GET['sanc'] != 'ALL')
		    {
			   
				$Where_rank[$t]='';
			       $Where_rank[$t].= " and ( r.RType = ".$tindex[$t]." Or r.RType = ".($tindex[$t]+1)." ";
			       for($i=2;$i<=$max_sanctions;$i++)
			       {
				       $Where_rank[$t].=  "Or r.RType".$i." = ".$tindex[$t]." Or r.RType".$i." = ".($tindex[$t]+1)." ";
			       }
			       $Where_rank[$t].= ")";
			
			      

		    }
		  else
		  {
		    $Where_rank[$t]= " and r.RAll > 0";
		  }
	       }
	     else
	       {
		      
		       $t=0;
		  if($_GET['sanc'] != 'ALL')
		    {
			   
			 //  echo 'max snactions: '.$max_sanctions;
			 
				
				$Where_rank[$t]='';
			       $Where_rank[$t].= " and (r.RType = ".$tindex[$t]." ";
			       for($i=2;$i<=$max_sanctions;$i++)
			       {
				       $Where_rank[$t].= " or r.RType".$i." = ".$tindex[$t]." ";
			       }
			       $Where_rank[$t].= ")";
			
		    }
		  else
		  {
			  
		    $Where_rank[$t]= " and r.RAll=1";
		  }
		   
		   
	       }
	       
	     if($_GET['c']!= 'ALL')
	       $Where.= " and r.COURSE='".inj_str($_GET['c'])."' ";
	     switch($_GET['gen'])
	       {
		case 'female': $Where.= " and a.Sex='F' ";
		  break;
		case 'male': $Where.= " and a.Sex='M' ";
		  break;
	       }
	       			
	       $team_option = $config['rank_team'];
	      $filter_team=' left JOIN '.$db_name.'team as t on (a.team1=t.TEAM) ';
	      
	     if($team_option=='R')
	     $filter_team=' left JOIN '.$db_name.'team as t on (r.team=t.team) ';
	     
	     if($cntry != $_GET['lsc'] && $_GET['lsc'] !='All')
	     {
		    // $lsc = variable_get('perfanal_lsc', '');
		     if($lsc=='')
		     {
			     	$Where.= " and (t.LSC='".inj_str($_GET['lsc'])."' ";
				if($_GET['type'] != 'team_points')
				{
					if( strpos($team_option,'T')!==false)
				       {$team = strpos($team_option,'T');
					       if($team_option=='T1' || $team_option=='T2' || $team_option=='T3')
					       $filter_team=' left JOIN '.$db_name.'team as t on (a.team1=t.TEAM) ';
					       if($team_option=='T2' || $team_option=='T3')
					       {
						       $filter_team.=' left JOIN '.$db_name.'team as t2 on (a.team2=t.TEAM ) ';
						       $Where.= " or t2.LSC='".inj_str($_GET['lsc'])."' ";
					       }
					       if($team_option=='T3')
					       {
						       $filter_team.=' left JOIN '.$db_name.'team as t3 on (a.team3=t.TEAM ) ';
						       $Where.= " or t3.LSC='".inj_str($_GET['lsc'])."' ";
	
					       }
					
				       }
				}
			       $Where.= ")";
			       
		     }
	     }
	     
	  }
	
	
?>