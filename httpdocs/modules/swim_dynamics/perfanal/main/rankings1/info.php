<?php
drupal_set_title("Rankings Information");

	$update = variable_get('perfanal_ranking_update', 'W');
	$period = variable_get('perfanal_ranking_period', '2');
	$rank_days_before = variable_get('perfanal_ranking_days_before','2');
	   $rank_from_option = variable_get('perfanal_rank_from','y');
	     
        $Sd = variable_get('perfanal_ranking_dd', '01');
        $Sm = variable_get('perfanal_ranking_mm', '01');
        $from_date = $season.'-'.$Sm.'-'.$Sd;
	
	$output.="<br/>The current rankings as of ".$last_ranking."<br/><br/>";
	if($rank_from_option=='y')
	$output.='Results from '.$from_date.'.<br/><br/>';
	$type = variable_get('perfanal_'.arg(1).'_ranking_type', '');
	if($type=='archive')
	  {
	     $output.="These are Archived Ranks and will not be updated.<br/><br/>";
	  }
	else
	  {
	     If($update=='M')
	       $output.="They are updated on a Monthly basis.<br/><br/>";
	     else
	       $output.="They are updated every ".$period." Weeks.<br/><br/>";
	  }

	$output.="Athlete's age's are determined as at the current rankings date. (".$last_ranking.")<br/>";
	$output.="Age group filters look at the athlete's age as of the current rankings date.<br/><br/>";
	$output.="Meet Results that are consider for rankings must have ended ".$rank_days_before." days prior to the rankings date. (".$last_ranking.")<br/>";
	$output.="To view list of meets including in this rankings set click the Meets link on rankings page.<br/>";

	$output =  t($output);
?>