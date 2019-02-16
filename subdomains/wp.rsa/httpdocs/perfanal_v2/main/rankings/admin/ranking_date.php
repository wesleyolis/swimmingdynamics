<?php

$Sd = $config['ranking_dd'];
$Sm = $config['ranking_mm'];

echo 'Rank Data';

$cur = getdate();
echo $cur;

   if(($cur['mday'] <= $Sd && $cur['mon'] <= $Sm ) || $cur['mon'] < $Sm)
     {//beging year
     	echo "begin";
	if($get_seas < ($cur['year']-1))
	  {
	  	echo '111111archive:'.$rank['date'];
	     //archive date
	     $rank['date'] = ($get_seas +1).'-'.$Sm.'-'.$Sd;
	     $rank['type'] = 'archive';
	  }
	else
	  {
		  $rank['type'] = 'current';
		  if($ranking_manual_update == 'manual')
		  {
			  $result = db_query("SELECT SQL_CACHE CURDATE() as dt");
			  if(!mysql_error())
			  $object = mysql_fetch_object($result);
			  $rank['date'] = $object->dt;
		  }
		  else
		  {
			
		     $update = $config['ranking_update'];
		     $period = $config['ranking_period'];
		     
		     if($update=='W')
		       {
			  $result = db_query("SELECT SQL_CACHE SUBDATE(CURDATE(),((WEEK(CURDATE(),1)%".$period.")*7)+ DAYOFWEEK(CURDATE())-1+IF(DAYOFWEEK(CURDATE())=1,7,0)) as dt ");
			  if(!mysql_error())
			  $object = mysql_fetch_object($result);
			  $rank['date'] = $object->dt;
		       }
		     else
		       {
			  $result = db_query("SELECT SQL_CACHE LAST_DAY(SUBDATE(CURDATE(),INTERVAL 1 MONTH)) as dt");
			  if(!mysql_error())
			  $object = mysql_fetch_object($result);
			  $rank['date'] = $object->dt;
		       }
		  }
	  }
     }
   else
     {//end year

echo $get_seas.'-'.$cur['year'];

	if($get_seas < ($cur['year']))
	  {
	  	echo 'sdfsdf';
	     //archive date
	     $rank['date'] = ($get_seas+1).'-'.$Sm.'-'.$Sd;
	     $rank['type'] = 'archive';
	  }
	else
	  {
	     //current date.
	      $rank['type'] = 'current';
		  if($ranking_manual_update == 'manual')
		  {
			  $result = db_query("SELECT SQL_CACHE CURDATE() as dt");
			  if(!mysql_error())
			  $object = mysql_fetch_object($result);
			  $rank['date'] = $object->dt;
		  }
		  else
		  {
			
		     $update = $config['ranking_update'];
		     $period = $config['ranking_period'];  
			  
		     if($update=='W')
		       {
			  echo "SELECT SQL_CACHE SUBDATE(CURDATE(),((WEEK(CURDATE(),1)%".$period.")*7)+ DAYOFWEEK(CURDATE())-1+IF(DAYOFWEEK(CURDATE())=1,7,0)) as dt ";
			  $result = db_query("SELECT SQL_CACHE SUBDATE(CURDATE(),((WEEK(CURDATE(),1)%".$period.")*7)+ DAYOFWEEK(CURDATE())-1+IF(DAYOFWEEK(CURDATE())=1,7,0)) as dt ");
			 if(!mysql_error())
			  $object = mysql_fetch_object($result);
			  $rank['date'] = $object->dt;
		       }
		     else
		       {
			  $result = db_query("SELECT SQL_CACHE LAST_DAY(SUBDATE(CURDATE(),INTERVAL 1 MONTH)) as dt");
			  if(!mysql_error())
			  $object = msql_fetch_object($result);
			  $rank['date'] = $object->dt;
		       }
		  }
	  }
     }
?>