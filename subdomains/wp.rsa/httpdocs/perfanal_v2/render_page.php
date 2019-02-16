<?php
$layout_url=$app_dir.'/layout_caching/'.$domain_reg.'/'.$config ['db_ident'].'_layout.php';

	if($config['dylayout']!=null)
	{
		
			$update=FALSE;
			
			if(file_exists ($layout_url))
			{
				
				$file_time =  filemtime($layout_url);
				
				if((time()-7200)>$file_time)
				$update=TRUE;
				

			}
			else
			{
				//echo $domain_reg;
				if(!chdir($app_dir.'/layout_caching/'.$domain_reg))
				{
					$path = dirname('/'.$domain_reg); 
					if(mkdir( $path, 0777))
					echo 'true';
					else
					echo'false';
				}
				$file_time=0;
				$update=TRUE;
				
			}
			
			if($update==TRUE && false)
			{
				
				//echo date ("F d Y H:i:s.",$file_time);
				
				$r = new HttpRequest($config['dylayout'].'/'.$seas.'/'.serialize($file_time), HttpRequest::METH_GET);
				if($file_time!=0)
				$r->setOptions(array('lastmodified' => $file_time));
				try {
				    $r->send();
				    if ($r->getResponseCode() == 200) {
					   $page_content123=$r->getResponseBody();
					   //minipulation of file for rendering
					   
					   $patten = Array('/<head>/','/<title>.*<\/title>/','/'.$config['dybody'].'/','/'.$config['dybreadcrumb'].'<\/div>/','/<\/head>/','/<\/body>/','/index.php\?db='.$config ['db_ident'].'&amp;ss='.$seas.'/','/>'.$config['title'].'<\/a>/');
					   $replace = Array('<?php echo $meta_tags ?><head>','<title><?php echo $page_title ?></title>','<h2><?php echo $page_title_inline ?></h2><?php echo $output ?>','<div style="float:left;"><?php echo $breadcrumb ?></div><?php echo $breadcrumb_right ?></div>','<?php echo $js_script ?><?php echo $styles ?></head>','<?php echo $js_include ?></body>','index.php?db='.$config ['db_ident'].'&amp;ss=<?php echo $config[\'seas_curr\'] ?>','><?php echo $config[\'seas_curr\'] ?> <?php echo $config[\'title\'] ?></a>');
					   $page = preg_replace($patten,$replace,$page_content123);
					   
					file_put_contents($layout_url, $page);
				    }
				    else
				    {
					//echo 'failed: '.$r->getResponseCode();    
				    }
				    
				} catch (HttpException $ex) {
				  //  echo 'error: '.$ex;
				}
				
			}
	}
	
	function render_page()
	{
		global $config;
		global $page_url,$page_file;
		global $output;
		global $page_title,$page_title_inline;
		global $breadcrumb;
		global $layout_url;
		global $app_relative;
		global $breadcrumb;
		global $app_domain_dir;
		global $js_include;
		global $render_start;
		global $latency_start;
		global $meta_tags;
		global $js_script;
		
		meta_tags();
		
		//fixed heading formating
		$patten = Array('/<small>/','/<\/small>/','/<br>/','/<br\/>/');
		$replace = Array('','',' ',' ');
		$page_title = preg_replace($patten,$replace,$page_title);
		
		$current_seas = $config['seas_curr'];
		
		require('advert.php'); 
		
		//Render content
		$js_include.= "<script defer async type='text/javascript' defer='defer' src='".$app_relative."js/rowlink.js'></script>";
		$js_include = preg_replace('/<\/script>/',"</script>\n",$js_include);

		//$js_include = "<script type='text/javascript' defer='defer' src='".$app_relative."js/rowlink.js'></script>";
		$styles="<style type='text/css' media='all' defer async>@import '".$app_relative."css/perfanal.css';</style>";
		$styles = preg_replace('/<\/style>/',"</style>\n",$styles);
		//Render breadcrumb.
	
		
		$main_pos = strpos($page_url,'main/')+5;
		
		if($main_pos!=5)
		{$main_dir_pos = strpos($page_url,'/',$main_pos);
		$page_seas_url = $app_domain_dir.'main/'.substr($page_url,$main_pos,$main_dir_pos-$main_pos).'/index.php';
		}
		else
		{
			$main_pos = strpos($page_url,$app_domain_dir)+strlen($app_domain_dir);
			$main_dir_pos = strpos($page_url,'/',$main_pos);
			$page_seas_url = $app_domain_dir.''.substr($page_url,$main_pos,$main_dir_pos-$main_pos).'/index.php';
		}
		
		$options='';
		   foreach($config['seas'] as $season=>$val)   
		     $options.= '<option '.(($config['seas_curr']==$season)?'selected':'').' value="'.$season.'">'.$season.'-'.($season+1).'</option>';
		   $Sea = l('Change Season: ',null,$app_domain_dir.'main/seasons/index.php').'<select onchange="document.location=\''.$page_seas_url.'?db='.$config['db_ident'].'&ss=\'+this.value;" size="1" name="Season">'.$options.'</select>';
		
		   
		  if($breadcrumb!=null)
		   foreach($breadcrumb as $crumb => $val)
		   $Sea.='&nbsp;<small>></small>&nbsp;'.$val;
		   
		   $breadcrumb=$Sea;
		  
		   $breadcrumb_right='&nbsp;&nbsp;<div style="color:#000000;padding:0em 0em 0em 2em;float:right;width:5em;" align="left">Page&nbsp;in&nbsp;'.round((microtime_float()-$render_start),3).'s</div>';
		   
		   
		  
		   
		require($layout_url);
		
		
		
		ob_end_flush();
	}
	
	/*if($db_link_unic != FALSE)
	{
		mysql_close($db_link_unic);
	}*/
	
?>