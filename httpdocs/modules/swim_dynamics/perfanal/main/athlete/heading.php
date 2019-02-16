<?php
drupal_add_js(path().'/js/athlete.js',null,'header',true,TRUE);

   $option = arg(2);
  
		  $query = "Select SQL_CACHE a.*,t.*,c.*, extract(YEAR FROM from_days(datediff(CURDATE(), a.Birth))) as Age from ".$tm4db."athlete_".$season." as a left join ".$tm4db."team_".$season." as t on (a.team1=t.team)  left join ".$tm4db."code_".$season." as c on (a.Group = c.ABBR)  where Athlete= %d";
		   //$output.=$query;
		   $result= db_query($query,$athlete);
		   $object = db_fetch_object($result);
		   if($object ==null)
		     {
			drupal_set_message("Error Athlete was not found!");
			drupal_goto('athlete');
		     }
		   $my_data= $object->First.''.(($object->First==$object->Pref)?'':', '.$object->Pref).' '.$object->Last.'&nbsp; '.Gender($object->Sex).'&nbsp; '.$object->Age.'yrs &nbsp;('.get_date($object->Birth).') <br/>'.$object->TName.' - '.$object->LSC.' : '.$object->_desc.'';
	    
    

   drupal_set_title($my_data);
   $output.="<br><div class='tabs'><ul class='tabs primary'>";
   $output.="<li ".(($option =='top_times')?"class='active'":'').">".l('Top','athlete/'.arg(1).'/top_times/'.$athlete)."</li>";
   $output.="<li ".(($option =='all_times')?"class='active'":'').">".l('All & Splits','athlete/'.arg(1).'/all_times/'.$athlete)."</li>";
   $output.="<li ".(($option =='latest_times')?"class='active'":'').">".l('Lastest','athlete/'.arg(1).'/latest_times/'.$athlete)."</li>";
   $output.="<li ".(($option =='entries')?"class='active'":'').">".l('Entries','athlete/'.arg(1).'/entries/'.$athlete)."</li>";
   $output.="<li ".(($option =='standards')?"class='active'":'').">".l('Apply Qualifying Times','athlete/'.arg(1).'/standards/'.$athlete)."</li>";
   $output.="<li ".(($option =='graphs')?"class='active'":'').">".l('Perfomance Graphs','athlete/'.arg(1).'/graphs/'.$athlete)."</li>";
   $output.="<li ".(($option =='my_records')?"class='active'":'').">".l('My Records','athlete/'.arg(1).'/my_records/'.$athlete)."</li>";
   //$output.="<li ".(($option =='compare')?"class='active'":'').">".l('Comparisions','athlete/'.arg(1).'/compare/'.$athlete)."</li>";
   $output.="</ul></div>";
?>