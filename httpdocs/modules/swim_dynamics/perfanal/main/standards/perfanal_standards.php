<?php
$season = get_seasons();
$tm4db = variable_get('perfanal_database', 'perfanal');
switch(arg(2))
     {
      case 'age':
	  {
	     $results = db_query("Select SQL_CACHE * From ".$tm4db."stdname_".$season." Where StdFile='%s'",strtolower(arg(3)));
	     $object = db_fetch_array($results);
	     drupal_set_title($object['StdFile'].(($object['Descript']==NULL)?'':' - '.$object['Descript'])." Standards &nbsp;&nbsp;".Age(arg(4))." - ".course(1,arg(5)));
	     setseasons_breadcrumb(array(l('Time Standards','standards/'.arg(1)),l('Age Groups','standards/'.arg(1).'/ages/'.arg(3))));
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
	     $coloms=(arg(5)=='L')?12:0;

	     $output.="<br><div class='tabs'><ul class='tabs primary'>";
	     $output.="<li ".((arg(5)=='L')?"class='active'":'').">".l('Long Course 50m','standards/'.arg(1).'/age/'.arg(3).'/'.arg(4).'/L')."</li>";
	     $output.="<li ".((arg(5)=='S')?"class='active'":'').">".l('Short Course 25m','standards/'.arg(1).'/age/'.arg(3).'/'.arg(4).'/S')."</li>";
	     $output.="</ul></div>";

	     $output.="<table><tr><td><div style='display:none;width:0px;height:0px;'>";
	     if(db_num_rows($results)>0)
	       {
		  $results = db_query("SELECT * from ".$tm4db."".strtolower($object['StdFile'])."_".$season." Where Lo_Age =%d and Hi_Age =%d Order by Sex,I_R,Stroke,Distance",floor((arg(4)/100)),(arg(4)%100));

		  //Grouping
		  $Gender=null;

		  while($object = db_fetch_array($results))
		    {
		       if($Gender <> $object['Sex'])
			 {
			    $Gender = $object['Sex'];
			    if($rows !=NULL)
			      $output.= theme('table', $headers, $rows).'<br>';
			    $output.= '</div><div align="center" style="position:relative;float:left;padding:2px"><b>'.t(Gender($object['Sex'])).'</b>';
			    $rows = NULL;
			 }
		       $temp[] = $object['Distance'];
		       $temp[] = Stroke($object['Stroke']);
		       $temp[] = $object['I_R'];
		       $temp[] = $object['Division'];
		       for($c=$coloms;$c<$count+$coloms;$c++)
			 $temp[] = get_time($object['S'.$c.'']);
		       $rows[]=$temp;
		       $temp=null;
		    }
	       }
	       $output.= theme('table', $headers, $rows).'<br></div>';
	       
	     $output.="</tr></td></table>";
	
	  }
	break;

      case 'ages':
	  {
	     $results = db_query("Select SQL_CACHE StdFile,Descript From ".$tm4db."stdname_".$season." Where StdFile='%s'",strtolower(arg(3)));
	     $object = db_fetch_object($results);
	     drupal_set_title($object->StdFile.(($object->Descript==NULL)?'':' - '.$object->Descript)." Standards");
	     setseasons_breadcrumb(array(l('Time Standards','standards/'.arg(1))));
	     $headers[] = array('data' => t('Age Group'), 'width' => '120px');
	     if(db_num_rows($results)>0)
	       {
		  $results = db_query("Select distinct ((Lo_Age*100)+Hi_Age) as Age From ".$tm4db."".strtolower($object->StdFile)."_".$season." ORDER by Lo_Age,Hi_Age");
		  if(db_num_rows($results)==1)
		    {
		       $object = db_fetch_object($results);
		       drupal_goto('standards/'.arg(1).'/age/'.arg(3).'/'.$object->Age.'/L');
		    }
		  while($object = db_fetch_object($results))
		    $rows[] = array(l(Age($object->Age),'standards/'.arg(1).'/age/'.arg(3).'/'.$object->Age.'/L'));
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

	     $results = db_query("Select SQL_CACHE StdFile,Descript,Year From ".$tm4db."stdname_".$season." Order BY Year desc,StdFile");

	     while($object = db_fetch_object($results))
	       $rows[] = array(l($object->StdFile,'standards/'.arg(1).'/ages/'.$object->StdFile),$object->Descript,$object->Year);

	     $output =  theme_table($headers, $rows,array('id'=>'hyper','class'=>'hyper'),null);
	  }
	break;
     }
     ?>

