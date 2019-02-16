<?php
require('../../main_include.php');
$meta_tags='<meta name="robots" content="index, follow">';

	 drupal_set_title("Conversion Factors used for this Season.");

     $output.="</br><b> Conversion from LCM to SCM Factors</b></br>";

    $output.="<br> Uses the difference in number of turns.</br></br>";

    for( $i = 1; $i < 6; $i++ )
        $output.= Stroke($i).' : '.($Factors[ $i - 1 ]).'s</br>';

    $output.="</br><b>Conversion from yards to SCM</b></br>";

    $output.="<br>Use straight multiple</br></br>";

    $output.= ' < 500m : '.$Factors_yards[0].'</br>';
    $output.= ' < 1650m : '.$Factors_yards[1].'</br>';
    $output.= ' >= 1650m : '.$Factors_yards[2].'</br>';

     
     render_page();
     
     ?>

