<?php

//Not to self change date formatting to corisponed with drupal date formate.
function get_date($v){
$start_date=explode("-", $v);
return ($v==null)?'-':(($v=='0000-00-00 00:00:00')?'--': Date('d/m/Y', mktime(0, 0, 0, $start_date[1], $start_date[2], $start_date[0])));
}

echo "Loaded";
?>