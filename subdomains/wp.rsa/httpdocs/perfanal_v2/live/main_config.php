<?php
//Version 0.1
///var/www/vhosts/swimdynamics.co.za
$app_dir='/var/www/vhosts/swimdynamics.co.za/httpdocs/beta/perfanal_v2';
$app_domain='www.swimdynamics.co.za';
$app_domain_dir='/beta/perfanal_v2/';

//mysql main database to encapsulate all sub swimming sites in
$mysql_db = null;

$exp='/';
$site_title="Swimming Dynamics";


//initialization of varibles
$sites=null;

//RSA - Republic of South Africa
//WP - Western Province
//League

$ranking_timeout = 10;
$limit_results_query = 500;

/*structure of site settings*/
$site_settings=null;
$site_settings['ranking_update'] = 'W';		//'W' or 'M'
$site_settings['ranking_dd']=1;
$site_settings['ranking_mm']=5;
$site_settings['perfanal_ranking_period']=1;
$site_settings['ranking_days_before']=0;
$site_settings['rank_from']='n';		//'n' or 'y' include times this season only
$site_settings['rank_team']='T1';		//R,t1,t2(1&2),t3(1&2&3)
$site_settings['rank_fina_multi_seasons']='';	//kinda removed from supportFina Rankings - Age over two seasons.,Meets scantion as 'FNA' will be include in the rankings. Athelte's age will be calculated as the age that pretains to 1 full year of times over a 2 seasons period, current and previius season.The previous best times of the last season must also be include in the sanctioning and this data base.
$site_settings['default_seas']='L';		//"L' Latest or seaons key
$site_settings['display_breakers']=60;		//Days ago records will be consider broken
$site_settings['display_min_display']=20;	//minum number of (results/events) to display on page before grouping
$site_settings['cntry']='RSA';			//Country Filter Code
$site_settings['cntry_desc']='Republic of South Africa';		//Full description of the country
$site_settings['lsc']='WP';			//Sub Team Fiter

/*structure of site seas settings*/
$site_seas_settings['rank_time_meet_filter']='RSA';
$site_seas_settings['rank_fina_meet_filter']='FNA';



$sites['www.swimdynamics.co.za']['league'] = array(
'title'=>'League Offical',
'desc'=>'League Swimmers that have been registered with Swimming South Africa.',
'url'=>'www.swimdynamics.co.za/beta',
'dylayout'=>'http://www.swimdynamics.co.za/beta/?q=perfanal/league/layout',
'dybody'=>'page_contents0123456789',
'dybreadcrumb'=>'breadcrumb0123456789',
'settings'=>$site_settings,
'seas'=>array('2010'=>array('db_type'=>true,'db_name'=>'wp_rsa_perfanal','db_prefix'=>'wp_rsa_perfanal_2010','db_url'=>'cmysql5-1.glodns.net:3306','db_user'=>'wp_rsa_perfanal','db_pass'=>'1986drupal0311','version'=>null,'running_config'=>$seas_running_config,'settings'=>$site_seas_settings),
'2009'=>array('db_type'=>true,'db_name'=>'wp_rsa_perfanal','db_prefix'=>'wp_rsa_perfanal_league_2009','db_url'=>'cmysql5-1.glodns.net:3306','db_user'=>'wp_rsa_perfanal','db_pass'=>'1986drupal0311','version'=>null,'running_config'=>$seas_running_config,'settings'=>$site_seas_settings),
'2008'=>array('db_type'=>true,'db_name'=>'wp_rsa_perfanal','db_prefix'=>'wp_rsa_perfanal_league_2008','db_url'=>'cmysql5-1.glodns.net:3306','db_user'=>'wp_rsa_perfanal','db_pass'=>'1986drupal0311','version'=>null,'running_config'=>$seas_running_config,'settings'=>$site_seas_settings),
'2007'=>array('db_type'=>true,'db_name'=>'wp_rsa_perfanal','db_prefix'=>'wp_rsa_perfanal_league_2007','db_url'=>'cmysql5-1.glodns.net:3306','db_user'=>'wp_rsa_perfanal','db_pass'=>'1986drupal0311','version'=>null,'running_config'=>$seas_running_config,'settings'=>$site_seas_settings)

)
);
$site_seas_settings['rank_time_meet_filter']='ALL';	//none=>'',Use Lsc Filter=>'LSC',ALL=>'ALL'
$site_seas_settings['rank_fina_meet_filter']='ALL';

$sites['www.swimdynamics.co.za']['series'] = array(
'title'=>'Club Series',
'desc'=>'Club Series swimmers unregistered to Swimsa and non offical times.',
'url'=>'www.swimdynamics.co.za/beta',
'dylayout'=>'http://www.swimdynamics.co.za/beta/?q=perfanal/series/layout',
'dybody'=>'page_contents0123456789',
'dybreadcrumb'=>'breadcrumb0123456789',
'settings'=>$site_settings,
'seas'=>array('2010'=>array('db_type'=>true,'db_name'=>'wp_rsa_perfanal','db_prefix'=>'wp_rsa_perfanal_series_2010','db_url'=>'cmysql5-1.glodns.net:3306','db_user'=>'wp_rsa_perfanal','db_pass'=>'1986drupal0311','version'=>null,'settings'=>$site_seas_settings))
);

/*Clear Site Settings to prevent futher propigations*/
$site_settings=null;
$site_seas_settings=null;


?>