<?php
/*structure of the sites and setting templets

subsite.mainurl.com/page.php?q=urlidentifier/seasons/
or
subsite.mainurl.com/urlidentifier/seasons/page.php?q=
&f_key = fitler keys

*/
$sites['subsites']['url_identifier'] = array(
'title'=>'',
'settings'=>null,
'seas'=>array('season key filter'=>
array('db_type'=>false, /*true=mysql,false=acess*/
'db_name'=>null,
'db_url'=>null,
'db_user'=>null
'db_pass'=>null
'version'=>null,
'settings'=>null)
));

/*structure of site settings*/
$site_settings=null;
$site_settings['ranking_update'] = 'W';		//'W' or 'M'
$site_settings['ranking_dd']=1;
$site_settings['ranking_mm']=5;
$site_settings['perfanal_ranking_period']=1;
$site_settings['ranking_days_before']=0;
$site_settings['rank_from']='n';		//'n' or 'y' include times this season only
$site_settings['rank_team']='T3';		//R,t1,t2(1&2),t3(1&2&3)
$site_settings['default_seas']='l';		//"l' Latest or seaons key
$site_settings['display_breakers']=20;		//Days ago records will be consider broken
$site_settings['display_min_display']=20;	//minum number of (results/events) to display on page before grouping
$site_settings['cntry']=null;			//Country Filter Code
$site_settings['cntry_desc']=null;		//Full description of the country
$site_settings['lsc']=null;			//Sub Team Fiter

$site_settings=null;

/*structure of site seas settings*/
$site_seas_settings['rank_time_meet_filter']='All';
$site_seas_settings['rank_fina_meet_filter']='All';
$site_seas_settings=null;

?>