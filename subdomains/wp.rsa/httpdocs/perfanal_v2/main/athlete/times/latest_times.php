<?php
$option = 'latest_times';
require('../../../main_include.php');
require('../heading.php');



 $where = ".meet=(select m.meet from ".$db_name."meet as m inner join ".$db_name."result as r on (r.meet=m.meet) where r.ATHLETE=".inj_int($_GET['ath'])."  order by m.Start Desc limit 1)";

require('meet_common.php');

drupal_set_title('Latest '.$meet_name.'  Results Times - '.$heading);

 $output.= theme_table($header, $rowsL,array('onmouseout'=>'hide_dis();'),null);

	     $output.='</div><br><br><br><br><br><br><br>';
	   

render_page();

?>