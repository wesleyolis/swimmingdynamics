<?php

require('../../main_include.php'); 

 $query = "select SQL_CACHE  distinct m.QTSS,m.QTS,m.QTLS,m.QTL,m.QTYS,m.QTY, m.MName FROM ".$db_name."meet as m  where m.Meet=".inj_int($_GET['m']);
		      

			$result = db_query($query);
		       if(!mysql_error())
		       {
		       $object = mysql_fetch_object($result);
		       drupal_set_title($object->MName.' Meet Events info'.' '.$_GET['ss'].'-'.($_GET['ss']+1));
		       setseasons_breadcrumb(array(l2('Meets',null,'index.php')));

		       	 $display_link = 1;
		         $menu_option = 'event';
			 require('meets_menu.php');

		       $headers[] = array('data' => t('Sess'));
		       $headers[] = array('data' => t('Event'));
		       $headers[] = array('data' => t('Gender'));
		       $headers[] = array('data' => t('Age'));
		       $headers[] = array('data' => t('Distance'));
		       $headers[] = array('data' => t('Stroke'));
		       $headers[] = array('data' => t('I/R'));
		       $headers[] = array('data' => t('Fee'));
		       $headers[] = array('data' => t('Course'));
		       $headers[] = array('data' => t('Div'));
		       if(($QTL=$object->QTL)>0)
			 $headers[] = array('data' => t('LC Slower'));
		       if(($QTLS=$object->QTLS)>0)
			 $headers[] = array('data' => t('LC Faster'));
		       if(($QTS=$object->QTS)>0)
			 $headers[] = array('data' => t('SC Slower'));
		       if(($QTSS=$object->QTSS)>0)
			 $headers[] = array('data' => t('SC Faster'));
			if(($QTY=$object->QTY)>0)
			 $headers[] = array('data' => t('Yard Slower'));
		       if(($QTYS=$object->QTYS)>0)
			 $headers[] = array('data' => t('Yard Faster'));

			 $query = "Select FastCut,SlowCut,Fast_L,Slow_L,Fast_Y,Slow_Y, Round(Fee,2) as Fee,Session, MtEvent,MtEvX, IF(Count(MtEvX)=1,Lo_Hi,'*') as Lo_Hi ,MtEv, IF(Count(Distinct Sex)=1,Sex,'X') As Sex, Distance, Stroke, I_R,If(Count(*)>0,Count(*),1) as Events, Course,Division FROM ".$db_name."mtevente as e WHERE e.MtEv>0 and  Meet=".inj_int($_GET['m'])." ".($_GET['age']!='All'?(' and ( e.Lo_Hi='.inj_int($_GET['age']).((isset($_GET['op'])==true)?' or e.Lo_Hi=99':'').')'):'')." ".($_GET['gen']!='All'?(" and (e.Sex='X'or e.Sex='".inj_str($_GET['gen'])."')"):'')." ".(($_GET['age']!='All' || (isset($_GET['gp'])==true))?(' Group by MtEv,MtEvX order by Session, MtEv,MtEvX'):' Group by MtEv order by Session, MtEv')." ";
		
			 $result = db_query($query);
		       if(!mysql_error())
		       while ($object = mysql_fetch_object($result))
			 {
			   $link = 'm='.$_GET['m'].'&ev='.$object->MtEv;
			    $temp[] = $object->Session;

			    $temp[] = ($object->Events == 1)?$object->MtEv.'&nbsp;'.$object->MtEvX:l2($object->MtEv, $link,'event_info.php');
			    $temp[] =Gender($object->Sex);
			    $temp[] = Age($object->Lo_Hi);
			    $temp[] =  $object->Distance."m";
			    $temp[] = Stroke($object->Stroke);
			    $temp[] =  IR($object->I_R);
			    $temp[] = ($object->Events==1)?$object->Fee:'';
			    $temp[] = Course(0,$object->Course);
			    $temp[] = $object->Division;
			    if($object->Events == 1)
			      {
				 if($QTL>0)
				   $temp[]=(get_time($object->Fast_L));
				 if($QTLS>0)
				   $temp[]=(get_time($object->Slow_L));
				 if($QTS>0)
				   $temp[]=(get_time($object->FastCut));
				 if($QTSS>0)
				   $temp[]=(get_time($object->SlowCut));
				   if($QTY>0)
				   $temp[]=(get_time($object->Fast_Y));
				 if($QTYS>0)
				   $temp[]=(get_time($object->Slow_Y));
			      }
			    else
			      {
				 if($QTL>0)
				   $temp[]='';
				 if($QTLS>0)
				   $temp[]='';
				 if($QTS>0)
				   $temp[]='';
				 if($QTSS>0)
				   $temp[]='';
				   if($QTY>0)
				   $temp[]='';
				 if($QTYS>0)
				   $temp[]='';
			      }
			    $rows[] = $temp;
			    $temp=null;
			 }
		
		       
			 	$query = "Select distinct Lo_Hi FROM ".$db_name."mtevente as e WHERE Meet=".inj_int($_GET['m'])." and Lo_Hi!=0 order by Lo_Hi";
				$results = db_query($query);
				if(!mysql_error())
				if(mysql_num_rows($results) > 1)
				{

				$output.="<form action='events_info.php'  method='get'>";
				$output.="<input type='hidden' name='db' value='".$_GET['db']."'>";
				$output.="<input type='hidden' name='ss' value='".$_GET['ss']."'>";
				$output.="<input type='hidden' name='m' value='".$_GET['m']."'>";
				$output.="<select name='age'>";
				$output.="<option value='All' ".($_GET['age']==''?"selected='selected'":'').">All Age Groups</option>";
				
				$open = false;
				while($object = mysql_fetch_object($results))
				{
					if($object->Lo_Hi==99)
					$open = true;
					$output.="<option value='".$object->Lo_Hi."' ".($_GET['age']==$object->Lo_Hi?"selected='selected'":'')." >".Age($object->Lo_Hi)."</option>";
				
				}
				$output.="</select>";
				if($open)
				$output.="<span style='padding:0em 0em 0em 1em;'><input ".(((isset($_GET['op'])==true) && $_GET['age']!='All')?'checked':'')." type='checkbox' name='op'>Include Open Events</span>";
				
				
				
				$output.="<span style='padding:0em 2em 0em 2em;'>Gender&nbsp;<select name='gen'>";
				$output.="<option value='All' ".($_GET['gen']=='All'?"selected='selected'":'').">All</option>";
				$output.="<option value='F' ".($_GET['gen']=='F'?"selected='selected'":'').">Female</option>";
				$output.="<option value='M' ".($_GET['gen']=='M'?"selected='selected'":'').">Male</option>";
				$output.="</select></span>";
				$output.="&nbsp;&nbsp;<input type='submit' value='Filter'/>";
				if($_GET['age']=='All')
				$output.="<br><input ".(((isset($_GET['gp'])==true))?'checked':'')." type='checkbox' name='gp'>Show Sub Age Groups";
				
				
				}
			 
			 $output.="<table width='100%' border='0' cellspacing='0' cellpadding='0' style='border-style: solid; border-width: 0px'><tr valign='top'><td>".theme_table($headers, $rows,array('id'=>'hyper','class'=>'hyper','align'=>'left'),null)."</td><td width='170px'>".$banner160_600."</td></tr></table>";
		       }
		       
		       render_page();
		    
?>