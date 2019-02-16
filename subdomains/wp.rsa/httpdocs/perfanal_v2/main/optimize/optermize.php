<?php
	header("Cache-Control: max-age=-1, no-store"); 
	require('../../main_include.php');
	
	$running_config=null;
	
	$config['version'] =  inj_int($_GET['version']);
	$version = inj_int($_GET['version']);
	$db_name = $config['db_name'].'.'.$config['db_prefix'].'_'.$version.'_';
	
	drupal_set_title('Optermizing database - '.$version);
	
	set_time_limit(60*5);		//5 minutes script time out
	Ignore_User_Abort(True);	//continue even if user aborts
	
	
	
	$output='';
	

		  //Creat aditional fields needed.
		  $output.= "Adding Coloms <br><br>";
		  $output.= "table: code <br>";
		  $output.= "Alter code table code filed name <br>";
		  
		  db_query("Alter Table ".$db_name."code change `DESC` `_desc` varchar(30) ");
		  $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>");
		  
		  db_query("Alter Table ".$db_name."code ADD tindex tinyint");
		   $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 
		   
		   db_query("update ".$db_name."code Set tindex = 0;");
		  $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 
		  
		  db_query("set @pos=-1;");
		  $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 
		  
		  db_query("update ".$db_name."code Set tindex = (@pos:=@pos+2) WHERE type=3 and abbr !='' order by abbr");
		  $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 
		  
		  db_query("Alter Table ".$db_name."code ADD KEY `ABBR` (`ABBR`,`TYPE`) ");
		 $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 
		  
		  
		  $query = "UPDATE ".$db_name."meet SET Sanction = trim(if((SUBSTRING_INDEX(trim(Sanction),',', -1)='' ),Sanction,concat(Sanction,',')))";
		  db_query($query);
		 $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 
		     
			//Section must be exported to optimization so only happens once.
		  
			db_query("DROP TABLE IF EXISTS ".$db_name."meet_sanctions");
			  $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 	
			  
			  db_query("CREATE TABLE IF NOT EXISTS ".$db_name."meet_sanctions (meet INT, abbr VARCHAR(5),c smallint,include smallint) ENGINE=MYISAM;");
			  $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 
			  
			  db_query("Alter Table ".$db_name."meet_sanctions ADD KEY `ABBR` (`ABBR`) ");
			  $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 	
			  
			  db_query("Alter Table ".$db_name."meet_sanctions ADD KEY `meet` (`meet`) ");
			  $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 
			  
			  db_query("DELETE FROM ".$db_name."meet_sanctions");
			  $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>");
			  
			 $results = db_query("Select meet, sanction from ".$db_name."meet");
			 $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>");
			 
			 
			 $max=1;
			 if(!mysql_error())
			 while($object = mysql_fetch_object($results))
			 {
				 $keys = explode(',',$object->sanction);
				 if($max<(sizeof($keys)-1))
				 {
					 $max=(sizeof($keys)-1);
				 }
				 
				 for($i=0;$i<(sizeof($keys)-1);$i++)
				 {
					 $pos=strpos($keys[$i],'!');
					 if($pos==false)
					 {
					 		  
					 db_query("INSERT delayed into ".$db_name."meet_sanctions (meet,abbr,c,include) values(".$object->meet.",'".trim(substr($keys[$i],0,strlen($keys[$i])))."',".($i+1).",1)");
					 $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 
					 }else{
					 		  
					 db_query("INSERT delayed into ".$db_name."meet_sanctions (meet,abbr,c,include) values(".$object->meet.",'".trim(substr($keys[$i],0,$pos))."',".($i+1).",0)");
					 $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 
					 }
				 }

			 }
			$max-=1;
			 $running_config['max_sanctions'] = $max;
			
		  $output.= "table: Meet <br>";
		 		 
		  db_query("Alter Table ".$db_name."meet ADD QTSS tinyint, ADD QTS tinyint, ADD QTLS tinyint, ADD QTL tinyint, ADD QTYS tinyint, ADD QTY tinyint  ");
		   $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 
		  
		  $output.= "table: mtevent <br>";		  
		  db_query("Alter Table ".$db_name."mtevent ADD Results smallint ");
		  $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 

		  $output.= "table: result <br>";
		  $commands[]="ADD Fina smallint";
		  $commands[]="ADD RBest tinyint";
		  $commands[]="ADD RType tinyint";
		  for($i=2;$i<=$max;$i++)
		  {$commands[]="ADD RType".$i." tinyint";}
		  
		  $commands[]="ADD RAll tinyint";
		  //$commands[]="ADD RFINA tinyint";
		  $commands[]="drop origin";
		  $commands[]="Drop SPLIT";
		  $commands[]="Drop misc";
		  $commands[]="Drop TRANK";
		  $commands[]="Drop Rank";
		  foreach($commands as $table =>$command)
		    {
		       		  
		       db_query("Alter Table ".$db_name."result ".$command);
		       $output.="Alter result ".$command."<br>";
		       $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 
		    }
		    
		     db_query("Alter Table ".$db_name."result ADD KEY `MtEvent1` (`MTEVENT`) ");
		     $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 

		     db_query("Alter Table ".$db_name."result ADD KEY `RTYPE` (`RType`) ");
		     $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 
		     for($i=2;$i<=$max;$i++)
			  {
			     		  
			     db_query("Alter Table ".$db_name."result ADD KEY `RTYPE".$i."` (`RType".$i."`) ");
			     $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 
			  }
		         		  
			 db_query("Alter Table ".$db_name."result ADD KEY `RALL` (`RAll`) ");
			 $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 
			 
			 //$output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 		  db_query("Alter Table ".$db_name."result_".$season." ADD KEY `RALL` (`RFINA`) ");
			  		  
			 db_query("Alter Table ".$db_name."athlete ADD FINA_age tinyint ");
			 $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>");
			 
		  $output.= "Alter athlete table Group filed name <br>";
				  
		  db_query("Alter Table ".$db_name."athlete change `_group` `group` varchar(3) ");
		    $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 
		  
		  $output.= "Alter code table Desc to _DESC <br>";
		  		  
		  db_query("Alter Table ".$db_name."code  change `desc` `_desc` varchar(30) ");
		  $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 

		  $output.= "Alter Splits table Group filed name <br>";
		  		  
		  db_query("Alter Table ".$db_name."splits drop StrokeRate");
		   $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>");

	     

		  //$output.= "Thrashing non swimmers: athlete <br>";
	/*
	$output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 		  db_query("delete From r using".$db_name."result_".$season." as r,".$db_name."athlete_".$season." as a Where r.athlete = a.athlete and a.group !='A'");
	$output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 		  db_query("delete ".$db_name."athlete_".$season." From ".$db_name."athlete_".$season." as a where a.Group!='A'");
	*/

		  //add adtional indecies
		  $output.= "Formating table: athinfo <br>";
		  		  
		  db_query("Alter Table ".$db_name."athinfo ADD UNIQUE KEY `Athlete` (`Athlete`)");
		  $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 
	    
		  $output.= "Formating table: athlete<br>";		  
		  db_query("Alter Table ".$db_name."athlete ADD  UNIQUE KEY `Athlete` (`Athlete`)");
		   $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 
		  		  
		  db_query("Alter Table ".$db_name."athlete add KEY `Agekey` (`Age`,`Athlete`)");
		  $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 	
		  
		  db_query("Alter Table ".$db_name."athlete add  KEY `ID_NO` (`ID_NO`)");
		  $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 		  
		  
		  db_query("Alter Table ".$db_name."athlete add  KEY `LastName` (`Last`), add  KEY `First` (`First`),add  KEY `Initial` (`Initial`)");
		  $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 
	      

		 
		  
	     
		      
		       
//		  $output.= "Formating table: meet<br>";
//		  $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 		  db_query("Alter Table ".$db_name."meet_".$season." ADD UNIQUE KEY `ZID` (`Meet`),add  KEY `MName` (`MName`,`Start`),add KEY `Start` (`Start`)");
	      
  		$output.= "Formating table: meet<br>";
		  
		db_query("Alter Table ".$db_name."meet ADD  UNIQUE KEY `Meet` (`meet`)");
		$output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 
		
		db_query("Alter Table ".$db_name."meet add KEY `Start` (`Start`)");
		  $output.= "Formating table: mtevent <br>";
		  $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 
		  
		  db_query("Alter Table ".$db_name."mtevent ADD  UNIQUE KEY `ZID` (`MtEvent`)");
		  $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 
		  
		  db_query("Alter Table ".$db_name."mtevent add KEY `Meet` (`Meet`,`MtEv`,`MtEvX`)");
		    $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 
		    
		    db_query("Alter Table ".$db_name."mtevent add KEY `MLo_Hi` (`Meet`,`Lo_Hi`)");
		    $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 

		  $output.= "Formating table: mtevente <br>";
		  		  
		  db_query("Alter Table ".$db_name."mtevente ADD UNIQUE KEY `ZID` (`MtEvent`) ");
		  $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>");
 		  
		  db_query("Alter Table ".$db_name."mtevente add  KEY `Meet2` (`Meet`,`MtEv`,`MtEvX`) ");
		  $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 
		  
		  db_query("Alter Table ".$db_name."mtevente add KEY `MLo_Hi` (`Meet`,`Lo_Hi`)");
		  $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>");

		  $output.= "Formating table: team<br>";
		 		  
		db_query("Alter Table ".$db_name."team ADD  UNIQUE KEY `team` (`team`)");
		$output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 
		
		db_query("Alter Table ".$db_name."team ADD  KEY `tcntry_lsc` (`TCntry`,`lsc`)");
		 $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 
		 
		  $output.= "Formating table:records <br>";
		  db_query("Alter Table ".$db_name."records ADD KEY `ZID` (`record`)");
		  $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 
		  
		  db_query("Alter Table ".$db_name."records ADD KEY `Age` (`Lo_Age`,`Hi_Age`) ");
		   $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 
		   
		   db_query("Alter Table ".$db_name."records ADD KEY `RecDate` (`RecDate`) ");
	      

		  $output.= "Formating table: relay <br>";
		  $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 		  
		  db_query("Alter Table ".$db_name."relay ADD UNIQUE KEY `RELAY` (`RELAY`) ");
		  $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 

		  $output.= "Formating table: result<br>";
		  db_query("Alter Table ".$db_name."result ADD UNIQUE KEY `ZID` (`RESULT`)");
		 $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 
		 
		 db_query("Alter Table ".$db_name."result add  KEY `MEET` (`MEET`,`MTEVENT`,`I_R`,`F_P`) ");
		 $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 
		 
		 db_query("Alter Table ".$db_name."result  add KEY `Fast` (`ATHLETE`,`COURSE`,`STROKE`,`DISTANCE`) ");
		 $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>");
	     
		  $output.= "Formating table: splits<br>";
		  db_query("Alter Table ".$db_name."splits ADD KEY `SplitID` (`SplitID`,`SplitIndex`)");
		  $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>");
//		  $output.= "Formating table: team<br>";
//		  $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 		  db_query("Alter Table ".$db_name."team_".$season." ADD UNIQUE KEY `ZID` (`Team`), add KEY `LSC` (`LSC`,`TCode`)");
	     
		  $query = "Update ".$db_name."mtevent AS upe,";
		  $query .=" (Select e.MTEVENT AS event,a.sex AS sex, Count(DISTINCT a.sex) as cnt From  ".$db_name."mtevent as e Cross JOIN ".$db_name."result as r";
		  $query .=" on e.MtEvent=r.MTEVENT Cross JOIN ".$db_name."athlete as a on r.ATHLETE=a.Athlete WHERE e.Sex ='X' and e.MtEvX <>'' ";
		  $query .=" Group By e.MTEVENT) As es SET upe.Sex=es.sex";
		  $query .=" WHERE es.cnt=1 and upe.MtEvent=es.event";
		  $output.=$query;
		  $result =  db_query($query);
	      
		  $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 	

		  //Doing some admin work,formating
		  $pref_temp_yes=true;
		  $result=null;
		  $result =  db_query("Select distinct  pref_temp from ".$db_name."athlete");
		   $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 
		  if(!mysql_error())
		  if($object = mysql_fetch_array($result))
		  {
			echo $pref_temp_yes=false;
		  }
		  
		  if($pref_temp_yes)
		  {
			  		  
			  db_query("Alter Table ".$db_name."athlete ADD pref_temp varchar(20)");
			  $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 
			  
			  $query = "UPDATE ".$db_name."athlete SET Last = TRIM(CONCAT(UCase(LEFT(Last,1)),LCase(SUBSTRING(Last,2))))";
			  $result =  db_query($query);
			  $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>");
			  
			  $output.= "Formating Last Name<br>";
			  $query = "UPDATE ".$db_name."athlete SET First = TRIM(CONCAT(UCase(LEFT(FIRST,1)),LCase(SUBSTRING(FIRST,2))))";
			  $result = db_query($query);
			  $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 
			  
			  $output.= "Formating First Name<br>";
			  $query = "UPDATE ".$db_name."athlete SET Pref = TRIM(CONCAT(UCase(LEFT(Pref,1)),LCase(SUBSTRING(Pref,2))))";
			  $result =db_query($query);
			   $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 
			   
			  $output.= "Formating Pref Name<br>";  
			  $query = "UPDATE ".$db_name."athlete as a SET a.pref_temp = a.Pref, a.Pref = a.First";
			  $result = db_query($query);
			  $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 		  
			  
			  $query = "UPDATE ".$db_name."athlete SET First = if(pref_temp='',First,pref_temp)";
			  $result = db_query($query);
			   $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 
			   
			  $output.= "Switching Prefered and first<br>";
			 		  
		  }

		  $output.= "Gender problem with Mixed<br>";

		  $query = "Update ".$db_name."mtevent as e, (Select MTEVENT, Count(*) AS resultscount From ".$db_name."result WHERE I_R <> 'L' Group By MTEVENT) as r";
		  $query .= " SET e.Results = r.resultscount";
		  $query .= " Where e.MtEvent=r.MTEVENT";
		  $result = db_query($query);
		   $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 
		  
		  $output.=$query;
		  $output.= "Result counts<br>";
				  


		  $output.= "Reseting Best Time Rank<br>";
		  db_query("Update ".$db_name."result  as p Set p.RBest = 0");
		  $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 		 
		  
		  $output.= "Reranking Best Time Rank<br>";

		  $grp='grp'.rand(0,500);
		  db_query("set @".$grp."='0'");
		  $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 		  
		  
		  //$output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 		  db_query("Set @grp='9';");

		 db_query("update ".$db_name."result as r set r.RBest = if(((@".$grp." != (@".$grp.":=concat(r.athlete,r.COURSE,r.STROKE,r.distance)))),1,0) where (r.NT=0 and r.I_R != 'R') order by r.Athlete,r.Course,r.Stroke,r.Distance,r.score");
		  $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 		  

		  
		  /*
		  $query = "Update ".$db_name."result_".$season." as up,(select Min(rs.Score),rs.result From (Select r.athlete,r.course,r.stroke,r.distance,r.score,r.result From ".$db_name."result_".$season." as r";
		  $query.= " Where r.I_R='I' and r.NT=0 order by r.athlete,r.course,r.stroke,r.distance,r.score) as rs";
		  $query.= " Group by rs.athlete,rs.course,rs.stroke,rs.distance) as res";
		  $query.= " Set up.RBest = 1 WHERE up.result=res.result";
		  $output.=$query;
		  
		  $result = $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 		  db_query($query);
*/
	     
		  $output.= "Reseting Fina Scores<br>";
		  db_query("Update ".$db_name."result as p Set p.Fina = 0");
		  $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 		  
		
		  
		  //retire the latests year of fina base times avalible for ranking.
		  
		  $results = db_query("Select distinct Years from ".$config['db_name'].".fina_points where Years <=".inj_int($_GET['ss'])." order by Years desc");
		  if(!mysql_error())
		  if($object = mysql_fetch_object($results))
		  {
			  $fina_year = $object->Years;
			  $output.='Updating Fina Rankings using Base Yimes :'.$fina_year.'<br>';
			  
			  
			  $running_config['fina_points_rank_year'] = $fina_year;
			
			 
			  
			 
			  $output.= "Calculating Fina Scores male<br>";
			  $query="Update ".$db_name."result as u, (SELECT r.result,  Round(1000*Pow((f.Score/r.score),3)) as Fina2 FROM ".$db_name."result as r inner join ".$db_name."athlete as a on (r.ATHLETE=a.Athlete) inner join ".$config['db_name'].".fina_points as f on (r.Course=f.Course and (r.I_R=f.I_R Or (r.I_R='L' And f.I_R='I')) and r.Stroke=f.Stroke and r.Distance=f.Distance and a.sex=f.Sex)";
			  $query.=" Where f.years=".$fina_year." and r.I_R!='R' and r.NT =0 and a.sex='M') as r";
			  $query.=" Set u.fina = r.fina2 Where u.result = r.result";
	
		/* This is the fast way with tansaction locing db non mysam
		$query="Update ".$db_name."result_2006 as r inner join ".$db_name."athlete_2006 as a on (r.ATHLETE=a.Athlete) inner join {fina_points} as f on (r.Course=f.Course and (r.I_R=f.I_R Or (r.I_R='L' And f.I_R='I')) and r.Stroke=f.Stroke and r.Distance=f.Distance and a.sex=f.Sex)";
		$query.="Set r.Fina = Round(1000*Pow((f.Score/r.score),3))";
		$query.="Where f.years=2004 and r.I_R!='R' and r.NT =0 and a.sex='M';";*/
			  //$output.=$query;
			  db_query($query);
			  $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 		  
	
		     
			  $output.= "Calculating Fina Scores female <br>";
	
			  $query="Update ".$db_name."result as u, (SELECT r.result,  Round(1000*Pow((f.Score/r.score),3)) as Fina2 FROM ".$db_name."result as r inner join ".$db_name."athlete as a on (r.ATHLETE=a.Athlete) inner join ".$config['db_name'].".fina_points as f on (r.Course=f.Course and (r.I_R=f.I_R Or (r.I_R='L' And f.I_R='I')) and r.Stroke=f.Stroke and r.Distance=f.Distance and a.sex=f.Sex)";
			  $query.=" Where f.years=".$fina_year." and r.I_R!='R' and r.NT =0 and a.sex='F') as r";
			  $query.=" Set u.fina = r.fina2 Where u.result = r.result";
	
			  db_query($query);
			  $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 
		  }
		  
		  $output.= "Correcting sum split glitch that arise from ET communication with TM <br>";
		  
		  $query="delete from ".$db_name."splits where mod(splitindex,2)=1 and splitid in  (select * from (select s.splitid from ".$db_name."splits as s group by s.splitid having count(*) = (sum(if(s.split=0,1,0))*2) and count(*) = (sum(mod(splitindex,2))*2)) as sp_r)";
		   db_query($query);
		   $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>");
		   
		  $query="update ".$db_name."splits as s inner join (select distinct r.distance/(s.splitindex*25) as updatefactor, r.result ";
		  $query.=" from ".$db_name."result as r inner join ".$db_name."splits as s on (r.result = s.splitID) where r.I_R!='R' and r.nt=0 and r.score!=0 and r.score = s.split and ((s.splitindex*25)/r.distance=0.5 or (s.splitindex*25)/r.distance=2) ) as r2 on (r2.result = s.splitID) set s.splitindex = floor(s.splitindex*r2.updatefactor)";
		  
		  db_query($query);
		   $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>");

	      

		  $output.= "Doing some Db enqueries to enhance functionality<br>";

		  $result = db_query("Update ".$db_name."meet  as up  Set up.QTSS = 0, up.QTS = 0, up.QTLS = 0, up.QTL = 0, up.QTYS = 0, up.QTY = 0");
		   $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>");
		   
		  $query="Update ".$db_name."meet as up,(Select e.Meet,If(sum( If(e.FastCut>0 ,1,0)),1,0) as QTS,If(sum(If(e.SlowCut>0,1,0)),1,0) as QTSS,If(sum(If(e.Fast_L>0,1,0)),1,0) as QTL,If(sum(if(e.Slow_L>0,1,0)),1,0) as QTLS,If(sum(If(e.Fast_Y>0,1,0)),1,0) as QTY,If(sum(if(e.Slow_Y>0,1,0)),1,0) as QTYS";
		  $query.=" FROM ".$db_name."mtevente as e,".$db_name."meet as m where m.meet=e.Meet group by meet) as rs";

		  $query.=" Set up.QTSS = rs.QTSS, up.QTS = rs.QTS, up.QTLS = rs.QTLS, up.QTL = rs.QTL, up.QTYS = rs.QTYS, up.QTY = rs.QTY";

		  $query.=" Where up.meet = rs.meet";
		  $result = db_query($query);
		  $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 		  
	/*
	Query to precheack subsite links
	$query="update ".$db_name."athlete_".$season." as up,(select a.athlete,s.site from ".$db_name."athlete_".$season." as a inner join ".$db_name."team_".$season." as t on (a.team1=t.team), {perfanal_subsites} as s ";
	$query=" Where s.enabled=true and t.lsc = s.lsc) as r 	Set up.subsite = r.site	WHERE up.athlete=r.athlete";

	*/

	      
		 
	/*
	$output.= "Formating table: <br>";
	$output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 		  db_query("Alter Table ".$db_name."athinfo_".$season." ");
	*/

		  //minipulation of time standard tables                                              
		  $query = "Select LCase(StdFile) as std from ".$db_name."stdname ";
		  $result = db_query($query);
		  $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 		  
		  if(!mysql_error())
		  while($object = mysql_fetch_object($result))
		    {
		       $output.= "Formating standard table: ".$object->std."<br>";
		       db_query("Alter Table ".$db_name."".$object->std." Add UNIQUE KEY `ZID` (`Std`)");
		       $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 		  
		       db_query("Alter Table ".$db_name."".$object->std." Add UNIQUE KEY `Distance` (`Distance`,`Stroke`,`Sex`,`I_R`,`Hi_age`,`Lo_Age`)");
		       $output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 		  
		    }    


			    {
		    	$output.= "Adding column for ofbest time computation: <br>";
		    	db_query("Alter Table ".$db_name."result Add COLUMN `OfBest` SMALLINT ");
		    	$output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 		  

		    	//Alter Table wp_rsa_perfanal_league_2012_61_result Add COLUMN `OfBest` SMALLINT;
		    	$output.= "Computing best time: <br>";
		    	db_query("Update ".$db_name."result as up inner join ".$db_name."result as rs on ( up.Course = rs.Course and up.STROKE = rs.STROKE AND up.DISTANCE = rs.DISTANCE and up.ATHLETE=rs.ATHLETE And rs.RBest = True  ) Set up.`OfBest` = (up.SCORE - rs.SCORE)");
		    	$output.= ((mysql_errno()==0)?'':mysql_errno() . ": " . mysql_error() . "<br/>"); 		  

/*
Update wp_rsa_perfanal_league_2012_61_result as up inner join wp_rsa_perfanal_league_2012_61_result as rs on (
up.Course = rs.Course and up.STROKE = rs.STROKE AND up.DISTANCE = rs.DISTANCE and up.ATHLETE=rs.ATHLETE And rs.RBest = True  )
Set up.`OfBest` = (up.SCORE - rs.SCORE)

*/

		    }  

		  
	  $output.="<br>".l2("Click Here update rankings and to finsh the setup.",'version='.$_GET['version'],'../rankings/admin/ranking_manual.php');
	  $running_config['ranking_last_update']=null;
	  $running_config['ranking_updating']=true;
	   require('../../running_options.php');
	  render_page();
     
?>