<?php
if ( $option != 'graphs')
{
$js_include.="<script async type='text/javascript' defer='defer' src='".$app_relative."js/athlete.js'></script>";
$js_include.="<script async type='text/javascript' defer='defer' src='".$app_relative."js/rgraph_v2/RGraph.common.core.js'></script>";
$js_include.="<script async type='text/javascript' defer='defer' src='".$app_relative."js/rgraph_v2/RGraph.line.js'></script>";
$js_include.="<script async type='text/javascript' defer='defer' src='".$app_relative."js/rgraph_v2/RGraph.common.zoom.js'></script>";
$js_include.="<script async type='text/javascript' defer='defer' src='".$app_relative."js/rgrapher_v2.js'></script>";

}

   
  
		  $query = "Select SQL_CACHE a.*,t.*,c.*, extract(YEAR FROM from_days(datediff(CURDATE(), a.Birth))) as Age from ".$db_name."athlete as a left join ".$db_name."team as t on (a.team1=t.team)  left join ".$db_name."code as c on (a.Group = c.ABBR)  where Athlete=".inj_int($_GET['ath']);
		   //$output.=$query;
		   
		   $result= db_query($query);
		   if(!mysql_error())
		   $object = mysql_fetch_object($result);
		   if($object ==null)
		     {
			drupal_set_message("Error Athlete was not found!");
			drupal_goto('error for the mean time');
		     }
		     $heading = $object->First.''.(($object->First==$object->Pref)?'':', '.$object->Pref).' '.$object->Last.'&nbsp; '.Gender($object->Sex).' ('.get_date($object->Birth).')';
		   $output=$my_data= $object->Age.' years old, '.$object->TName.' - '.$object->LSC.' : '.$object->_desc.'<br>';
		
		   if(isset($option)==false)
		   $option='';
		   
		   
   $output.="<br><div class='tabs'><ul class='tabs primary'>";
   $output.="<li ".(($option =='top_times.php')?"class='active'":'').">".l2('Top','ath='.$_GET['ath'],'../times/top_times.php')."</li>";
   $output.="<li ".(($option =='all_times.php')?"class='active'":'').">".l2('All & Splits','ath='.$_GET['ath'],'../times/all_times.php')."</li>";
   
   $output.="<li ".(($option =='latest_times')?"class='active'":'').">".l2('Lastest','ath='.$_GET['ath'],'../times/latest_times.php')."</li>";
  /* $output.="<li ".(($option =='entries.php')?"class='active'":'').">".l('Entries','athlete/'.arg(1).'/entries/'.$athlete)."</li>";
  */ $output.="<li ".(($option =='std')?"class='active'":'').">".l2('Apply Qualifying Times','ath='.$_GET['ath'],'../standards/index.php')."</li>";
   $output.="<li ".(($option =='graphs')?"class='active'":'').">".l2('Perfomance Graphs','ath='.$_GET['ath'],'../graphs/index.php')."</li>";
  
   $output.="<li ".(($option =='my_records')?"class='active'":'').">".l2('My Records','ath='.$_GET['ath'],'../my_records/my_records.php')."</li>";
   //$output.="<li ".(($option =='compare')?"class='active'":'').">".l('Comparisions','athlete/'.arg(1).'/compare/'.$athlete)."</li>";
  
   $output.="</ul></div><br><br>";
   if($option =='top_times.php' || $option == 'all_times.php')
   $meta_tags.='<meta name="robots" content="index, follow">';
   else
   if($option == 'my_records')
   	$meta_tags.='<meta name="robots" content="index, nofollow">';
   else
    	$meta_tags.='<meta name="robots" content="noindex, nofollow">';
?>