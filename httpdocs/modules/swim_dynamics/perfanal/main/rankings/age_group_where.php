<?php
// Set up where conditioning
	if(arg(3)=='indi_points' || arg(3)=='team_points' || arg(3)=='time' || arg(3)=='fina')
	  {
	     $Where = "";
	     $Where_rank= null;
	     $B_date = str_replace('-','/',arg(2));
	     if(arg(8)!=99)
	     if(arg(3)=='team_points') //|| arg(3)=='fina')
	     {
	       $Where.=" r.AGE >=".floor(arg(8)/100)." and r.AGE <=".(arg(8)%100)." ";
	
	     }
	     else
	     {
		     if(arg(2)=='curr')
		     {
		       $Where.=" a.age >=".floor(arg(8)/100)." and a.age <=".(arg(8)%100)." ";
		       
		     }
	       else  
	       {
		       
		       $Where.=" extract(YEAR FROM from_days(datediff('".$B_date."', a.Birth))) >=".floor(arg(8)/100)." and extract(YEAR FROM from_days(datediff('".$B_date."', a.Birth))) <=".(arg(8)%100)." ";
		       
	       }
	     }
	       else
	       {$Where.=" (1=1) ";
	       }
	       
	       if(arg(2)=='curr')
		     $ath_age=" a.age ";
		     else
		     {
			     
			     $ath_age=" extract(YEAR FROM from_days(datediff('".$B_date."', a.Birth))) as age ";
		     }
		     
	       
	       
	        $max_sanctions = variable_get('perfanal_'.$season.'_max_sanctions', 1);
		
	     if(arg(3)=='team_points' || arg(3)=='indi_points')
	       {
		       
		      $t=0;
		  if(arg(5) != 'ALL')
		    {
			for($t=0;$t<($rank_pre_seasons?2:1);$t++)
			{      
				$Where_rank[$t]='';
			       $Where_rank[$t].= " and ( r.RType = ".$tindex[$t]." Or r.RType = ".($tindex[$t]+1)." ";
			       for($i=2;$i<=$max_sanctions;$i++)
			       {
				       $Where_rank[$t].=  "Or r.RType".$i." = ".$tindex[$t]." Or r.RType".$i." = ".($tindex[$t]+1)." ";
			       }
			       $Where_rank[$t].= ")";
			}


		    }
		  else
		  {
		    $Where_rank[$t]= " and r.RAll > 0";
		  }
	       }
	     else
	       {
		      
		       $t=0;
		  if(arg(5) != 'ALL')
		    {
			   
			 //  echo 'max snactions: '.$max_sanctions;
			 for($t=0;$t<($rank_pre_seasons?2:1);$t++)
			{
				
				$Where_rank[$t]='';
			       $Where_rank[$t].= " and (r.RType = ".$tindex[$t]." ";
			       for($i=2;$i<=$max_sanctions;$i++)
			       {
				       $Where_rank[$t].= " or r.RType".$i." = ".$tindex[$t]." ";
			       }
			       $Where_rank[$t].= ")";
			}
		    }
		  else
		  {
			  
		    $Where_rank[$t]= " and r.RAll=1";
		  }
		   
		   
	       }
	       
	     if(arg(6) != 'ALL')
	       $Where.= " and r.COURSE='".arg(6)."' ";
	     switch(arg(7))
	       {
		case 'female': $Where.= " and a.Sex='F' ";
		  break;
		case 'male': $Where.= " and a.Sex='M' ";
		  break;
	       }
	       			$team_option = variable_get('perfanal_rank_team','R');
			      $filter_team=' left JOIN '.$tm4db.'team_'.$season.' as t on (a.team1=t.TEAM) ';
			      $filter_team_pre=' left JOIN '.$tm4db.'team_'.($season-1).' as t on (a.team1=t.TEAM) ';
			      
			     if($team_option=='R')
			     $filter_team=' left JOIN '.$tm4db.'team_'.$season.' as t on (r.team=t.team) ';
			     
	     if($cntry != arg(4) & arg(4) !='All')
	     {
		     		
			     
			       
			       
			       
		    // $lsc = variable_get('perfanal_lsc', '');
		     if($lsc=='')
		     {
			     	$Where.= " and (t.LSC='".arg(4)."' ";
				if(arg(3)!='team_points')
				{
					if( strpos($team_option,'T')!==false)
				       {$team = strpos($team_option,'T');
					       if($team_option=='T1' || $team_option=='T2' || $team_option=='T3')
					       $filter_team=' left JOIN '.$tm4db.'team_'.$season.' as t on (a.team1=t.TEAM) ';
					       if($team_option=='T2' || $team_option=='T3')
					       {
						       $filter_team.=' left JOIN '.$tm4db.'team_'.$season.' as t2 on (a.team2=t.TEAM ) ';
						       $Where.= " or t2.LSC='".arg(4)."' ";
					       }
					       if($team_option=='T3')
					       {
						       $filter_team.=' left JOIN '.$tm4db.'team_'.$season.' as t3 on (a.team3=t.TEAM ) ';
						       $Where.= " or t3.LSC='".arg(4)."' ";
	
					       }
					
				       }
				}
			       $Where.= ")";
			       
		     }
	     }
	     
	  }
	
?>