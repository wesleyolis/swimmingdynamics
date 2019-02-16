<?php
 $output.= athlete_heading($tm4db,$season,arg(3));
		       
		       
		       $headers[] = array('data' => t('Name'), 'width' => '100px');
		       $headers[] = array('data' => t('Description'), 'width' => '150px');
		       $headers[] = array('data' => t('Year'), 'width' => '40px');

		       $results = db_query("Select SQL_CACHE StdFile,Descript,Year From ".$tm4db.".stdname_".$season." Order BY Year desc,StdFile");

		       while($object = db_fetch_object($results))
			$rows[] = array(l($object->StdFile,'athlete/'.arg(1).'/standards/type/'.arg(3).'/'.$object->StdFile),$object->Descript,$object->Year);

		       $output.='<br/>Please select the standard times that you would like to apply.<br/><br/>';
		       $output.=theme_table($headers, $rows,array('id'=>'hyper','class'=>'hyper'),null);
?>