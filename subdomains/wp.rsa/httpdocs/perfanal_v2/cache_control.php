<?php
$url = $_SERVER['REQUEST_URI'];
if(strpos($url,'admin')>0 || strpos($url,'optimize')>0 || strpos($url,'updating')>0)
{
	header("Cache-Control: max-age=-1, no-store"); 
}
else
{
	$Sd = $config['ranking_dd'];
	$Sm = $config['ranking_mm'];
	
	$cur = getdate();
	   if(($cur['mday'] < $Sd && $cur['mon'] <= $Sm ) || $cur['mon'] < $Sm)
	     {//beging year
		if($_GET['ss'] < ($cur['year']-1))
		  {
		     //archive date
		     $content_archived=true;
		  }
		  else
		   $content_archived=false;
		
	     }
	   else
	     {//end year
		if($_GET['ss'] < ($cur['year']))
		  {
		     //archive date
		      $content_archived=true;
		  }
		  else
		   $content_archived=false;
		
	     }


	     if($content_archived==true)
	     {
		     header("Cache-Control: max-age=31536000, public");
	     }
	     else
	     {
		     if(strpos($url,'main/meets/js_power_results_v2')>0)
		     {
			     header("Cache-Control: max-age=43200, public"); 
		     }
		     else
		     if(strpos($url,'main/meets')>0 || strpos($url,'main/rankings')>0 || strpos($url,'main/athlete')>0 || strpos($url,'main/standards')>0 || strpos($url,',main/records')>0||strpos($url,'main/fina')>0)
			{
				header("Cache-Control: max-age=1800, public"); 
			}
			else
			{
				header("Cache-Control: max-age=-1, no-store"); 
			}
	     }



	
}
?>
