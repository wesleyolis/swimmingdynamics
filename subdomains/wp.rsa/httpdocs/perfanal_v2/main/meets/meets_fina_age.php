<?php 

require('../../main_include.php'); 
$display_link=0;
 $query = "select SQL_CACHE m.MName, m.AgeUp from ".$db_name."mtevent as e inner join ".$db_name."meet as m on (e.Meet=m.Meet) WHERE e.Meet=".inj_int($_GET['m'])." ";
		  $result = db_query($query);
		  if(!mysql_error())
		  $object = mysql_fetch_object($result);
	  
		  drupal_set_title($object->MName.' Meet Results'.' '.$_GET['ss'].'-'.($_GET['ss']+1).' - Fina Points');
	
		  
		  	$breadcumb[] = l2('Meets',null,'index.php');
		       setseasons_breadcrumb($breadcumb);
		        
		       	$menu_option = 'fina';
			require('meets_menu.php');
		  
		  
			$headers[] = array('data' => t('Female'), 'style' => 'width:6.5em;');
			$headers[] = array('data' => t('Male'), 'style' => 'width:6.5em;');
			$headers[] = array('data' => t('Mixed'), 'style' => 'width:6.5em;');

			$page_link='meets_fina_str_dis.php';
			
			   $curr_param = '';
			foreach($_GET as $key => $val)
			{
				if($key != 'ss' && $key != 'db')
				$curr_param.= (($curr_param=='')?'':'&').$key.'='.$val;
			}
			
		  $query = "SELECt Distinct Lo_Hi from ".$db_name."mtevent Where I_R='I' and  Meet=".inj_int($_GET['m'])."  union select '99' as Lo_Hi order by Lo_Hi desc";
		  $result = db_query($query);
		  if(!mysql_error())
		  while ($object = mysql_fetch_object($result))
		    {
		       $rows[] = array(l2(Age($object->Lo_Hi),$curr_param.'&gen=female&lo='.floor($object->Lo_Hi/100).'&hi='.($object->Lo_Hi%100),$page_link),l2(Age($object->Lo_Hi),$curr_param.'&gen=male&lo='.floor($object->Lo_Hi/100).'&hi='.($object->Lo_Hi%100),$page_link),l2(Age($object->Lo_Hi),$curr_param.'&gen=mixed&lo='.floor($object->Lo_Hi/100).'&hi='.($object->Lo_Hi%100),$page_link));
		    }
		  $output.= theme_table($headers, $rows,null,null);
		  
		  
		  render_page();
		  
?>