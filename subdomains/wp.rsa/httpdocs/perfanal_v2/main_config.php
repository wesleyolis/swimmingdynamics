<?php
//Version 0.1
///var/www/vhosts/swimdynamics.co.za
///home/swimdy16/subdomains/wp.rsa/httpdocs/perfanal_v2/render_page.php
//$app_dir='/var/www/vhosts/swimdynamics.co.za/subdomains/wp.rsa/httpdocs/perfanal_v2';
$app_dir='/home/swimdy16/subdomains/wp.rsa/httpdocs/perfanal_v2';
$app_domain='wp.rsa.swimdynamics.co.za';
$app_domain_dir='/perfanal_v2/';

//mysql main database to encapsulate all sub swimming sites in
$mysql_db = null;

$exp='/';
$site_title="Western Province - Swimming Dynamics RSA";


//initialization of varibles
$sites=null;

//RSA - Republic of South Africa
//WP - Western Province
//League

$ranking_timeout = 20;
$limit_results_query = 500;

/*structure of site settings*/
$site_settings=null;
$site_settings['ranking_update'] = 'W';		//'W' or 'M'
$site_settings['ranking_dd']=1;
$site_settings['ranking_mm']=5;
$site_settings['ranking_period']=1;
$site_settings['ranking_days_before']=0;
$site_settings['rankcon']='';			//yes for confirmation of country,'' for no
$site_settings['rank_from']='y';		//'n' or 'y' include times this season only
$site_settings['rank_team']='T1';		//R,t1,t2(1&2),t3(1&2&3)
$site_settings['rank_fina_multi_seasons']='';	//kinda removed from supportFina Rankings - Age over two seasons.,Meets scantion as 'FNA' will be include in the rankings. Athelte's age will be calculated as the age that pretains to 1 full year of times over a 2 seasons period, current and previius season.The previous best times of the last season must also be include in the sanctioning and this data base.
$site_settings['default_seas']='L';		//"L' Latest or seaons key
$site_settings['display_breakers']=120;		//Days ago records will be consider broken
$site_settings['display_min_display']=20;	//minum number of (results/events) to display on page before grouping
$site_settings['cntry']='RSA';			//Country Filter Code
$site_settings['cntry_desc']='Republic of South Africa';		//Full description of the country
$site_settings['lsc']='WP';			//Sub Team Fiter

/*structure of site seas settings*/
$site_seas_settings['rank_time_meet_filter']='RSA';
$site_seas_settings['rank_fina_meet_filter']='FNA';

//,'db_name'=>'wp_rsa_perfanal','db_prefix'=>'wp_rsa_perfanal_league_2012','db_url'=>'cmysql5-3.wadns.net:3306'

//'db_name'=>'wp_rsa_perfanal_cur','db_prefix'=>'wp_rsa_perfanal_league_2012','db_url'=>'cmysql5-5.wadns.net:3306

$sites['wp.rsa.swimdynamics.co.za']['league'] = array(
'title'=>'League',
'desc'=>'League Swimmers that have been registered with Swimming South Africa.',
'searchengine_desc'=>'Swimming Western Province Aquatics (WPA), South Africa, Cape Town',
'searchengine_keywords'=>'Western Province Aquatics,WPA,Western Province,Western, Province, South Africa, Cape Town',
'url'=>'wp.rsa.swimdynamics.co.za',
'dylayout'=>'http://wp.rsa.swimdynamics.co.za/league/layout',
'dybody'=>'page_contents0123456789',
'dybreadcrumb'=>'breadcrumb0123456789',
'settings'=>$site_settings,
'seas'=>array(	
'2018'=>array('db_type'=>true,'db_name'=>'wp_rsa_perfanal','db_prefix'=>'wp_rsa_perfanal_league_2018','db_url'=>'cmysql5-3.wadns.net:3306','db_user'=>'wp_rsa_perfanal','db_pass'=>'password','version'=>'01','running_config'=>null,'lsc_alias' => array('CPT', 'CED','WCD','OBD','ROND','EDD'),'settings'=>array('rank_time_meet_filter'=>'','rank_fina_meet_filter'=>'')),	
'2017'=>array('db_type'=>true,'db_name'=>'wp_rsa_perfanal','db_prefix'=>'wp_rsa_perfanal_league_2017','db_url'=>'cmysql5-3.wadns.net:3306','db_user'=>'wp_rsa_perfanal','db_pass'=>'password','version'=>'15','running_config'=>null,'lsc_alias' => array('CPT', 'CED','WCD','OBD','ROND','EDD'),'settings'=>array('rank_time_meet_filter'=>'','rank_fina_meet_filter'=>'')),	
'2016'=>array('db_type'=>true,'db_name'=>'wp_rsa_perfanal','db_prefix'=>'wp_rsa_perfanal_league_2016','db_url'=>'cmysql5-3.wadns.net:3306','db_user'=>'wp_rsa_perfanal','db_pass'=>'password','version'=>'15','running_config'=>null,'lsc_alias' => array('CPT', 'CED','WCD','OBD','ROND'),'settings'=>array('rank_time_meet_filter'=>'','rank_fina_meet_filter'=>'')),	
'2015'=>array('db_type'=>true,'db_name'=>'wp_rsa_perfanal','db_prefix'=>'wp_rsa_perfanal_league_2015','db_url'=>'cmysql5-3.wadns.net:3306','db_user'=>'wp_rsa_perfanal','db_pass'=>'password','version'=>'01','running_config'=>null,'lsc_alias' => array('CPT', 'CED','WCD','OBD','ROND'),'settings'=>array('rank_time_meet_filter'=>'','rank_fina_meet_filter'=>'')),	
'2014'=>array('db_type'=>true,'db_name'=>'wp_rsa_perfanal','db_prefix'=>'wp_rsa_perfanal_league_2014','db_url'=>'cmysql5-3.wadns.net:3306','db_user'=>'wp_rsa_perfanal','db_pass'=>'password','version'=>'03','running_config'=>null,'settings'=>array('rank_time_meet_filter'=>'','rank_fina_meet_filter'=>'')),
'2013'=>array('db_type'=>true,'db_name'=>'wp_rsa_perfanal','db_prefix'=>'wp_rsa_perfanal_league_2013','db_url'=>'cmysql5-3.wadns.net:3306','db_user'=>'wp_rsa_perfanal','db_pass'=>'password','version'=>'01','running_config'=>null,'settings'=>array('rank_time_meet_filter'=>'','rank_fina_meet_filter'=>'')),
'2012'=>array('db_type'=>true,'db_name'=>'wp_rsa_perfanal','db_prefix'=>'wp_rsa_perfanal_league_2012','db_url'=>'cmysql5-3.wadns.net:3306','db_user'=>'wp_rsa_perfanal','db_pass'=>'password','version'=>null,'running_config'=>null,'settings'=>array('rank_time_meet_filter'=>'','rank_fina_meet_filter'=>'')),
'2011'=>array('db_type'=>true,'db_name'=>'wp_rsa_perfanal','db_prefix'=>'wp_rsa_perfanal_league_2011','db_url'=>'cmysql5-3.wadns.net:3306','db_user'=>'wp_rsa_perfanal','db_pass'=>'password','version'=>null,'running_config'=>null,'settings'=>array('rank_time_meet_filter'=>'ALL','rank_fina_meet_filter'=>'ALL')),
'2010'=>array('db_type'=>true,'db_name'=>'wp_rsa_perfanal','db_prefix'=>'wp_rsa_perfanal_league_2010','db_url'=>'cmysql5-3.wadns.net:3306','db_user'=>'wp_rsa_perfanal','db_pass'=>'password','version'=>null,'running_config'=>null,'settings'=>$site_seas_settings),
'2009'=>array('db_type'=>true,'db_name'=>'wp_rsa_perfanal','db_prefix'=>'wp_rsa_perfanal_league_2009','db_url'=>'cmysql5-3.wadns.net:3306','db_user'=>'wp_rsa_perfanal','db_pass'=>'password','version'=>null,'running_config'=>null,'settings'=>$site_seas_settings),
'2008'=>array('db_type'=>true,'db_name'=>'wp_rsa_perfanal','db_prefix'=>'wp_rsa_perfanal_league_2008','db_url'=>'cmysql5-3.wadns.net:3306','db_user'=>'wp_rsa_perfanal','db_pass'=>'password','version'=>null,'running_config'=>null,'settings'=>$site_seas_settings),
'2007'=>array('db_type'=>true,'db_name'=>'wp_rsa_perfanal','db_prefix'=>'wp_rsa_perfanal_league_2007','db_url'=>'cmysql5-3.wadns.net:3306','db_user'=>'wp_rsa_perfanal','db_pass'=>'password','version'=>null,'running_config'=>null,'settings'=>$site_seas_settings)

)
);
$site_seas_settings['rank_time_meet_filter']='ALL';	//none=>'',Use Lsc Filter=>'LSC',ALL=>'ALL'
$site_seas_settings['rank_fina_meet_filter']='ALL';

$sites['wp.rsa.swimdynamics.co.za']['series'] = array(
'title'=>'Club Series',
'desc'=>'Club Series swimmers unregistered to Swimsa and non offical times.',
'searchengine_desc'=>'Swimming Western Province Aquatics (WPA), South Africa, Cape Town',
'searchengine_keywords'=>'Western Province Aquatics,WPA,Western Province,Western, Province, South Africa, Cape Town',
'url'=>'wp.rsa.swimdynamics.co.za',
'dylayout'=>'http://wp.rsa.swimdynamics.co.za/series/layout',
'dybody'=>'page_contents0123456789',
'dybreadcrumb'=>'breadcrumb0123456789',
'settings'=>$site_settings,
'seas'=>array(
'2016'=>array('db_type'=>true,'db_name'=>'wp_rsa_perfanal','db_prefix'=>'wp_rsa_perfanal_series_2016','db_url'=>'cmysql5-3.wadns.net:3306','db_user'=>'wp_rsa_perfanal','db_pass'=>'password','version'=>'11','running_config'=>null,'lsc_alias' => array('CPT', 'CED','WCD','OBD','ROND'),'settings'=>$site_seas_settings),
'2015'=>array('db_type'=>true,'db_name'=>'wp_rsa_perfanal','db_prefix'=>'wp_rsa_perfanal_series_2015','db_url'=>'cmysql5-3.wadns.net:3306','db_user'=>'wp_rsa_perfanal','db_pass'=>'password','version'=>'01','running_config'=>null,'lsc_alias' => array('CPT', 'CED','WCD','OBD','ROND'),'settings'=>$site_seas_settings),
'2014'=>array('db_type'=>true,'db_name'=>'wp_rsa_perfanal','db_prefix'=>'wp_rsa_perfanal_series_2014','db_url'=>'cmysql5-3.wadns.net:3306','db_user'=>'wp_rsa_perfanal','db_pass'=>'password','version'=>'01','running_config'=>null,'settings'=>$site_seas_settings),
'2013'=>array('db_type'=>true,'db_name'=>'wp_rsa_perfanal','db_prefix'=>'wp_rsa_perfanal_series_2013','db_url'=>'cmysql5-3.wadns.net:3306','db_user'=>'wp_rsa_perfanal','db_pass'=>'password','version'=>'01','running_config'=>null,'settings'=>$site_seas_settings),
'2012'=>array('db_type'=>true,'db_name'=>'wp_rsa_perfanal','db_prefix'=>'wp_rsa_perfanal_series_2012','db_url'=>'cmysql5-3.wadns.net:3306','db_user'=>'wp_rsa_perfanal','db_pass'=>'password','version'=>null,'running_config'=>null,'settings'=>$site_seas_settings),
'2011'=>array('db_type'=>true,'db_name'=>'wp_rsa_perfanal','db_prefix'=>'wp_rsa_perfanal_series_2011','db_url'=>'cmysql5-3.wadns.net:3306','db_user'=>'wp_rsa_perfanal','db_pass'=>'password','version'=>null,'running_config'=>null,'settings'=>$site_seas_settings)
)
);

/*Clear Site Settings to prevent futher propigations*/
$site_settings=null;
$site_seas_settings=null;

?>