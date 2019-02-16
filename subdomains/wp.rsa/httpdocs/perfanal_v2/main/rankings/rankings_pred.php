<?php
	require('ranking_config.php');
	
	
	if($_GET['yy'] != null && $_GET['mm'] != null && $_GET['dd'] != null)
	{
		$ranking_form = $_GET['yy'].'-'.$_GET['mm'].'-'.$_GET['dd'];
		$ranking_on_date_d = mktime(0, 0, 0, $_GET['mm'], $_GET['dd'], $_GET['yy']);
		$B_date = explode('-',$running_config['ranking_last_update']);
		$ranking_last_update_d = mktime(0, 0, 0, $B_date[1], $B_date[2], $B_date[0]);
		if($ranking_on_date_d <= $ranking_last_update_d)
		{
			$output.="<div style='padding:1em 0em 0em 0em;color:#FF0000'>Error date is before current rankings date.</div>";
		}
		else
		{
			unset($_GET['yy'],$_GET['mm'],$_GET['dd']);
			$_GET['d']= $ranking_form;
			$param = '';
			foreach($_GET as $key => $val)
			{
				$param.= (($param=='')?'':'&').$key.'='.$val;
			}
			drupal_goto('index.php',$param,false);
		}
		
	}
	
	$meta_tags.='<meta name="robots" content="noindex, nofollow">';
	drupal_set_title($pref_head." Predicative Rankings - Date Selection");
	setseasons_breadcrumb(array(l2('Ranking Catagories','','index.php')));
	
	
	$headers[] = array('data' => t('Meet'), 'width' => '300px');
	$headers[] = array('data' => t('Type'), 'width' => '20px');
	$headers[] = array('data' => t('Sanc'), 'width' => '20px');
	$headers[] = array('data' => t('Start date'), 'width' => '130px');
	$headers[] = array('data' => t('End date'), 'width' => '100px');
	$headers[] = array('data' => t('Course'), 'width' => '40px');
	$headers[] = array('data' => t('Location'));
	
	$query = "select SQL_CACHE DISTINCT m.Meet, m.MName, m.Start, m.End,m.type,m.Sanction, m.Course, m.Location  from ".$db_name."meet m where m.Start>'".$ranking_on_date."' order by m.Start desc";
	
	$result = db_query($query);
	if(!mysql_error())
	while ($object = mysql_fetch_object($result))
	{
	$start_date = explode("-", $object->Start);	
	 $link = 'd='.Date('Y-m-d', mktime(0, 0, 0, $start_date[1], $start_date[2], $start_date[0]));
	
	
	 $rows[] = array('data' => array(l2($object->MName, $link,'index.php'),$object->type,get_sanc($object->Sanction),get_date($object->Start), get_date($object->End), $object->Course, $object->Location));
	}
	
	$output.='<br>Please select a meet of the start date of which must be used for the predictive ranking age calculation or enter your own custom date.<br>';
	
	$output.="<form style='padding:0.5em 0em 0em 0em;' action='rankings_pred.php'  method='get'>";
	$output.="<input type='hidden' name='db' value='".$_GET['db']."'>";
	$output.="<input type='hidden' name='ss' value='".$_GET['ss']."'>";
	$output.="Custom date must be after ".$ranking_on_date.".<br><span style='padding:0em 0.5em 0em 0em;'>Year</span><select name='yy'><option value='".$_GET['ss']."'>".$_GET['ss']."</option><option value='".($_GET['ss']+1)."'>".($_GET['ss']+1)."</option></select>";
	$output.="<span style='padding:0em 0.5em 0em 0.5em;'>Month</span><select name='mm'>";
	for($i=1;$i<=12;$i++)
		$output.="<option value='".$i."'>".Date('M',mktime(0,0,0,$i,1,2010))."</option>";
	$output.="</select>";
	
	$output.="<span style='padding:0em 0.5em 0em 0.5em;'>Day</span><select name='dd'>";
	for($i=1;$i<=31;$i++)
		$output.="<option value='".$i."'>".$i."</option>";
	$output.="</select>";
	$output.="&nbsp;&nbsp;<input type='submit' value='Proceed..'/></form>";
	
	$output.= theme_table($headers, $rows,array('id'=>'hyper','class'=>'hyper'),null);
	
	render_page();
	?>
