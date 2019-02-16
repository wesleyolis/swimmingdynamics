<?php

$Sd = variable_get('perfanal_ranking_dd', '01');
   $Sm = variable_get('perfanal_ranking_mm', '01');

   $cur = getdate();
   if(($cur['mday'] < $Sd & $cur['mon'] <= $Sm ) || $cur['mon'] < $Sm)
     {//beging year
	if($season < ($cur['year']-1))
	  {
	     //archive date
	     $rank['date'] = (arg(1) +1).'-'.$Sm.'-'.$Sd;
	     $rank['type'] = 'archive';
	  }
	else
	  {
		  $rank['type'] = 'current';
		  if(arg(2)=='manual')
		  {
			  $result = db_query("SELECT SQL_CACHE CURDATE() as dt");
			  $object = db_fetch_object($result);
			  $rank['date'] = $object->dt;
		  }
		  else
		  {
		     //current date.
		     
		     $update = variable_get('perfanal_ranking_update', 'W');
		     $period = variable_get('perfanal_ranking_period', '2');
		     if($update=='W')
		       {
			  $result = db_query("SELECT SQL_CACHE SUBDATE(CURDATE(),((WEEK(CURDATE(),1)%".$period.")*7)+ DAYOFWEEK(CURDATE())-1+IF(DAYOFWEEK(CURDATE())=1,7,0)) as dt ");
			  $object = db_fetch_object($result);
			  $rank['date'] = $object->dt;
		       }
		     else
		       {
			  $result = db_query("SELECT SQL_CACHE LAST_DAY(SUBDATE(CURDATE(),INTERVAL 1 MONTH)) as dt");
			  $object = db_fetch_object($result);
			  $rank['date'] = $object->dt;
		       }
		  }
	  }
     }
   else
     {//end year
	if($season < ($cur['year']))
	  {
	     //archive date
	     $rank['date'] = ($season+1).'-'.$Sm.'-'.$Sd;
	     $rank['type'] = 'archive';
	  }
	else
	  {
	     //current date.
	      $rank['type'] = 'current';
		  if(arg(2)=='manual')
		  {
			  $result = db_query("SELECT SQL_CACHE CURDATE() as dt");
			  $object = db_fetch_object($result);
			  $rank['date'] = $object->dt;
		  }
		  else
		  {
		     $update = variable_get('perfanal_ranking_update', 'W');
		     $period = variable_get('perfanal_ranking_period', '2');
		     if($update=='W')
		       {
			  $result = db_query("SELECT SQL_CACHE SUBDATE(CURDATE(),((WEEK(CURDATE(),1)%".$period.")*7)+ DAYOFWEEK(CURDATE())-1+IF(DAYOFWEEK(CURDATE())=1,7,0)) as dt ");
			  $object = db_fetch_object($result);
			  $rank['date'] = $object->dt;
		       }
		     else
		       {
			  $result = db_query("SELECT SQL_CACHE LAST_DAY(SUBDATE(CURDATE(),INTERVAL 1 MONTH)) as dt");
			  $object = db_fetch_object($result);
			  $rank['date'] = $object->dt;
		       }
		  }
	  }
     }
?>