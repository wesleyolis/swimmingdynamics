<?php

   $edit = $_POST["edit"];

   $tm4db = variable_get('perfanal_database', 'perfanal');
   if(arg(1)==null)
     {
	$seasons = seasons();
	$options='';
	foreach($seasons as $season)
	  $options.= '<option '.((arg(1)==$season)?'selected':'').' value="'.$season.'">'.$season.'-'.($season+1).'</option>';
	$Sea = '<select size="1" id="Season">'.$options.'</select>&nbsp;'.$url;

	$output.='Please select the Season<br/>'.$Sea.'<br/><input onclick="document.location=(\''.url('updaterankings/').'\'+document.getElementById(\'Season\').value+\'/\');" type="button" value="Update Rankings">';
	$output.='&nbsp;<input onclick="document.location=(\''.url('updaterankings/').'\'+document.getElementById(\'Season\').value+\'/reset\');" type="button" value="Reset Rankings">';
     }
   else
     {
	     if(arg(2)=='reset')
	     {
		     variable_set('perfanal_'.arg(1).'_ranking_last_update', '');
		     $output.='Rankings '.$season.' have been reset.';
		     $output.='<br/><br/>'.l('Update Current Rankings','updaterankings/'.arg(1).'/');
	     }
	     else
	     {
		 $output.=perfanal_automated_rankings_Update(arg(1));
	     }
     }
?>