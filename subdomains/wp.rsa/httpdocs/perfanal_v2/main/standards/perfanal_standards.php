<?php
require('../../main_include.php');
$meta_tags='<meta name="robots" content="index, follow">';

switch($arg[1])
     {
	     
      case 'age':
	  {
	     $results = db_query('select SQL_CACHE * From '.$db_name.'stdname Where StdFile=\''.inj_str(strtolower($arg[0])).'\';');
	     if(!mysql_error())
	     $object = mysql_fetch_array($results);
	     drupal_set_title(Age($arg[2])." - ".course(1,$arg[3]).($arg[4] != ''?' => '.course(1,$arg[4]):'').' '.$object['StdFile'].(($object['Descript']==NULL)?'':' - '.$object['Descript'])." Standard");
	     setseasons_breadcrumb(array(l('Time Standards',null),l('Age Groups',$arg[0].'/ages')));
	     $headers[] = array('data' => t('Distance'), 'width' => '50px');
	     $headers[] = array('data' => t('Stroke'), 'width' => '70px');
	     $headers[] = array('data' => t('I/R'), 'width' => '20px');
	     $headers[] = array('data' => t('Div'), 'width' => '20px');

	     $count=0;
	     for($i=1;$i<13;$i++)
	       if($object['D'.$i]!=null & trim($object['D'.$i])!= '')
		 {
		    $headers[] = array('data' => t($object['D'.$i]),'width' =>'80px','title'=> $object['D'.$i.'Des']);
		    $count++;
		 }
	     //Set course
	    $coloms=($arg[3]=='L')?12:(($arg[3]=='S')?0:24);
         //set on the fly conversions
        $convert_from_course = $arg[3];
        $convert_to_course = ($arg[4] == ''?$arg[3]:$arg[4]);
          
	     $output.="<br><div class='tabs'><ul class='tabs primary'>";
	     $output.="<li ".(($arg[3]=='L')?"class='active'":'').">".l('Long Course 50m',$arg[0].'/age/'.$arg[2].'/L')."</li>";
	     $output.="<li ".(($arg[3]=='S')?"class='active'":'').">".l('Short Course 25m',$arg[0].'/age/'.$arg[2].'/S')."</li>";
	     $output.="<li ".(($arg[3]=='Y')?"class='active'":'').">".l('Yards',$arg[0].'/age/'.$arg[2].'/Y')."</li>";
	     $output.="</ul></div><br><br>";

          if ( ( $convert_from_course == 'L' || $convert_from_course == 'S' ) && $arg[4] == '' )
            $output.=l("Convert using conversion factors from ".Course(1,$arg[3])." to ".( $arg[3] == 'L' ? Course(1,'S') : Course(1,'L')),$arg[0].'/age/'.$arg[2].'/'.$arg[3].'/'.( $arg[3] == 'L'?'S':'L')).'</br>';
          if ( $arg[4] != '' )
              $output.=l2("View conversion factors",'','../conversions/index.php')."</br>";

	     $output.="<table><tr><td><div style='display:none;width:0px;height:0px;'>";
	     if(!mysql_error())
	     if(mysql_num_rows($results)>0)
	       {
		  $results = db_query('SELECT * from '.$db_name.inj_str(strtolower($object['StdFile'])).' Where Lo_Age ='.floor((inj_int($arg[2])/100)).' and Hi_Age ='.(inj_int($arg[2])%100).' Order by Sex,I_R,Stroke,Distance');

		  //Grouping
		  $Gender=null;
		  if(!mysql_error())
		  while($object = mysql_fetch_array($results))
		    {
		       if($Gender <> $object['Sex'])
			 {
			    $Gender = $object['Sex'];
			    if($rows !=NULL)
			      $output.= theme_table($headers, $rows,null,null).'<br>';
			    $output.= '</div><div align="center" style="position:relative;float:left;padding:2px"><b>'.t(Gender($object['Sex'])).'</b>';
			    $rows = NULL;
			 }
		       $temp[] = $object['Distance'];
		       $temp[] = Stroke($object['Stroke']);
		       $temp[] = $object['I_R'];
		       $temp[] = $object['Division'];
		       for($c=$coloms;$c<$count+$coloms;$c++)
               {
                   $temp[] = get_time(convert_time( $convert_from_course, $convert_to_course, $object['Stroke'], $object['Distance'], $object['S'.$c.''], $Factors, $Factors_yards ));
               }
		       $rows[]=$temp;
		       $temp=null;
		    }
	       }
	       $output.= theme_table($headers, $rows,null,null).'<br></div>';
	       
	     $output.="</tr></td></table>";
	
	  }
	break;

      case 'ages':
	  {
	     $results = db_query("Select SQL_CACHE StdFile,Descript From ".$db_name."stdname Where StdFile='".inj_str(strtolower($arg[0]))."'");
	     if(!mysql_error())
	     $object = mysql_fetch_object($results);
	     drupal_set_title($object->StdFile.(($object->Descript==NULL)?'':' - '.$object->Descript)." Standards");
	     setseasons_breadcrumb(array(l('Time Standards',null)));
	     $headers[] = array('data' => t('Age Group'), 'width' => '120px');
	    if(!mysql_error())
	     if(mysql_num_rows($results)>0)
	       {
		  $results = db_query("Select distinct ((Lo_Age*100)+Hi_Age) as Age From ".$db_name."".inj_str(strtolower($object->StdFile))." ORDER by Lo_Age,Hi_Age");
		  if(!mysql_error())
		  if(mysql_num_rows($results)==1)
		    {
		       $object = mysql_fetch_object($results);
		       drupal_goto($page_file,'q='.$arg[0].'/age/'.$object->Age.'/L');
		    }
		     if(!mysql_error())
		  while($object = mysql_fetch_object($results))
		    $rows[] = array(l(Age($object->Age),$arg[0].'/age/'.$object->Age.'/L'));
	       }
	    $output = theme_table($headers, $rows,array('id'=>'hyper','class'=>'hyper'),null);

	  }
	break;

      default:
	  {
	     drupal_set_title("Time Standards");
	     setseasons_breadcrumb(null);
	     $headers[] = array('data' => t('Name'), 'width' => '100px');
	     $headers[] = array('data' => t('Description'), 'width' => '150px');
	     $headers[] = array('data' => t('Year'), 'width' => '40px');
	     $results = db_query("Select SQL_CACHE StdFile,Descript,Year From ".$db_name."stdname Order BY Year desc,StdFile;",true);

	   
	     if(!mysql_error())
	     while($object = mysql_fetch_object($results))
	       $rows[] = array(l($object->StdFile,$object->StdFile.'/ages'),$object->Descript,$object->Year);
	     //  echo print_r($rows);
	     $output =  theme_table($headers, $rows,array('id'=>'hyper','class'=>'hyper'),null);
	  }
	break;
     }
     
     
     render_page();
     
     ?>

