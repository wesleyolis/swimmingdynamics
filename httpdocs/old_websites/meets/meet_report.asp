<%@ Language=JavaScript %>
<!--METADATA TYPE="typelib" 
uuid="00000206-0000-0010-8000-00AA006D2EA4" -->

<%	
//Response.Buffer = false;
function CTime (Score)
{
if(Score !=0)
{
if(Score<6000)
{t = (Score/100);
if(t <10.0)
{t ="0"+t;}
if(t.toString().length == 2)
{t = t + ".00";}else{
if(t.toString().length<5)
{t=t+"0";}}
}
else
{
s = (Score % 100);
if(s==0){s=".00"}else{
if(s <10){s = "0" + s;}}
if(s.toString().length<2){s = s + "0";}
Score = ((Score -s) / 100);
m = (Score % 60);
if(m < 10)
{m = "0" + m;}
Score = ((Score - m)/60);
h = (Score % 60);
if(h.toString().length<2){h = "0" + h;}
t = h + ":" + m + "." + s;}}
else{t = "";}
return t;}

function Age(Lo,Hi)
{if(Lo <  Hi)
{
if(Lo > 0 & Hi < 99)
{
age = Lo + " - " + Hi + "yrs";
}else
{
if(Hi == 99 & Lo >0)
{
age = Lo + "&nbsp;&amp;&nbsp;Over";
}
else
{
if(Lo == 0 & Hi < 99)
{
age = Hi + "&nbsp;&amp;&nbsp;Under";
}
else
{
if(Lo == 0 & Hi == 99)
{
age = "Open";
}}}}}else{age = Hi + "yrs";}

return age;
}

var strokes = new Array("","Freestyle","Back","Breast","Butterfly","Medley");
	
	var oConn;	
	var rs;		
	var filePath;	
	filePath = Server.MapPath("../Swim.mdb");
	oConn = Server.CreateObject("ADODB.Connection");
	oConn.Open("Provider=Microsoft.Jet.OLEDB.4.0;Data Source=" +filePath);
	if((Request.QueryString("Meet").Count == 0 || Request.QueryString("MName").Count == 0) & Request.QueryString("MtEvent").Count == 0 & Request.QueryString("PrintReport").Count == 0 )
	{
	rs = oConn.Execute("SELECT Meet, MName, Start, [End], Course, Location FROM MEET WHERE ((([Start]) < Date())) ORDER BY Start DESC;");
%>
<HTML>
<HEAD>
<meta http-equiv="Content-Language" content="en-za">
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1252">
<TITLE>Meet Results</TITLE>
<link rel="stylesheet" type="text/css" href="../styles.css">

<script src="../menu.asp" language="javascript" type="Text/Javascript">
</script>

<script language="javascript" type="Text/Javascript">
makemenu(1);
</script>
</head>
<body topmargin="0" leftmargin="0">


<table border="0" cellpadding="0" cellspacing="0" style="font-weight: bold" width="800">
<tr style="font-weight: bold">
	<td colspan="5" align="center" height="47" valign="top"><font color="#000080" size="6">Meet Results</font></td></tr>
<tr valign="top" height="25"  align="left" style="font-weight: bold; color:#000080">
<td width="300" >Meet</td>
<td width="90">Start Date</td>
<td width="90">End Date</td>
<td width="60">Course</td>
<td>Location</td></tr>

<%while(!rs.eof){%><tr><td><a href="meet_report.asp?Meet=<%=rs.Fields("Meet")%>&MName=<%=rs.Fields("MName")%>"><%=rs.Fields("MName")%></a></td><td><%=rs.Fields("Start")%></td><td><%=rs.Fields("End")%></td><td align="center"><%=rs.Fields("Course")%></td><td><%=rs.Fields("Location")%></td></tr>
<%rs.MoveNext()}%>
</table>
<p>&nbsp;</p>
</body></HTML>
<%
rs.close();
}
else
{if(Request.QueryString("PrintReport").Count == 0)
{
var multi_team = "";
%>
<html>

<head>
<meta http-equiv="Content-Language" content="en-za">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Meet Results-P.R <%=Request.QueryString("MName")%></title>

<link rel="stylesheet" type="text/css" href="../styles.css">

<script src="../menu.asp" language="javascript" type="Text/Javascript">
</script>

<script language="javascript" type="Text/Javascript">
makemenu(1);
</script>

<style>
<!--
table{ font-size: 10pt; color: #00000; font-weight: normal; }
//-->
</style>


</head>
<body  topmargin="0" leftmargin="0">

<form name="F">
	<div align="left">
	<table border="0" width="640" cellspacing="0" cellpadding="0">
		<tr>
			<td align="center" height="72" class="Heading">
			<%=Request.QueryString("MName")%></td>
		</tr>
		<tr>
			<td align="center" class="THeader">
			<a href="mailto:14544830@sun.ac.za?subject=Meet Reports">Please 
			email use and let us know your thoughts</a></td>
		</tr>
		<tr>
			<td align="center" class="THeader">
			Filters for Print Report</td>
		</tr>
		<tr>
			<td align="center">
			<table border="0" width="634" cellspacing="4" cellpadding="0">
				<tr>
					<td colspan="2" height="25"><b>Session&nbsp;&nbsp; </b>
					<select onchange="parent.page()" name="sess" style="font-weight: 700;">
					<option value="All" selected>All</option>
					<%
					
					rs = oConn.Execute("SELECT MTEVENT.[Session] FROM MTEVENT GROUP BY MTEVENT.[Session], MTEVENT.Meet HAVING (((MTEVENT.Meet)=" + Request.QueryString("Meet") + "));");
					
					while(!rs.eof){
					%>
					<option><%=rs.Fields("Session")%></option>
					<%rs.MoveNext();}rs.Close();rs = null;%>
					</select></td>
					<td colspan="3" align="right">
					<%
					rs = oConn.Execute("SELECT TEAM.Team, TEAM.TName, TEAM.TCode FROM TEAM INNER JOIN (RESULT INNER JOIN Athlete ON RESULT.ATHLETE = Athlete.Athlete) ON TEAM.Team = Athlete.Team1 GROUP BY TEAM.Team, TEAM.TName, TEAM.TCode, RESULT.MEET HAVING (((RESULT.MEET)=" + Request.QueryString("Meet") + ")) ORDER BY TCode;");
					while(!rs.eof){
					//multi_team += "<option value='" + rs.Fields("Team") + "'>" + rs.Fields("TName") + "&nbsp;&nbsp;" + rs.Fields("TCode") + "</cheackbox><br>";
					multi_team += "<tr><td><input type='checkbox' name='Club' value='" + rs.Fields("Team") + "'>" + rs.Fields("TName") + "&nbsp;&nbsp;" + rs.Fields("TCode") + "</input></td><td><script language='javascript'><!--\ncolorsel(" + rs.Fields("Team") + ");//--></script></td></tr>";
					
					%>
					<%rs.MoveNext();}rs.Close();rs = null;%>
					<b><font size="2" color="#000080">Please adjust your print 
					margin to fit page, FILE &gt; PAGE SETUP</font></b>
					<input type="submit" name="PrintReport" style="font-weight: 700; width: 111; height: 26" value="Create Report"></td>
				</tr>
				<tr>
					<td width="47" height="25"><b>&nbsp;Age</b></td>
					<td width="100" height="25">
					
					<select size="1" name="Lo_Hi" style="font-weight: 700; width:95; height:21" onchange="if(Lo_Hi.value=='null'){Lo_Hi.selectedIndex=0;} parent.page()">
					<option value="All"selected>All</option>
					<%
					rs = oConn.Execute("SELECT MTEVENT.Lo_Hi, Int(MTEVENT.[Lo_Hi]/100) AS Lo_Age, Int(MTEVENT.[Lo_Hi] Mod 100) AS Hi_Age FROM MTEVENT GROUP BY MTEVENT.Lo_Hi, MTEVENT.Meet HAVING (((MTEVENT.Meet)=" + Request.QueryString("Meet") + "));");
					while(!rs.eof){
					%>
					
					<option value="<%=rs.Fields("Lo_Hi")%>"><%=Age(rs.Fields("Lo_Age"),rs.Fields("Hi_Age"))%></option>
					<%rs.MoveNext();}rs.Close();rs = null;%>
					<option value="null">- - - - - - - - - -</option>
					<option value="-07">7</option>
					<option value="-08">8</option>
					<option value="-09">9</option>
					<option value="-10">10</option>
					<option value="-11">11</option>
					<option value="-12">12</option>
					<option value="-13">13</option>
					<option value="-14">14</option>
					<option value="-15">15</option>
					<option value="-16">16</option>
					<option value="-17">17</option>
					<option value="-18">18</option>
					<option value="-19">19</option>
					<option value="-20">20</option>
					<option value="-21">21</option>
					<option value="-22">22</option>
					<option value="-23">23</option>
					<option value="-24">24</option>
					<option value="-24">25</option>
					</select></td>
					<td width="125"><b>Stroke&nbsp;&nbsp; </b><font size="1">
					<select size="1" onchange="parent.page()" name="Stroke" style="font-weight: 700; position:relative">
					<option selected value="All">All</option>
					<option value="1">Free</option>
					<option value="2">Back</option>
					<option value="3">Breast</option>
					<option value="4">Fly</option>
					<option value="5">Medley</option>
					</select></font></td>
					<td align="left" width="191"><b>Distance </b>
					<font size="1">
					<select size="1" onchange="parent.page()" name="Distance" style="font-weight: 700; width:60; height:25">
					<option value="All">All</option>
					<option value="50">50</option>
					<option value="100">100</option>
					<option value="200">200</option>
					<option value="400">400</option>
					<option value="500">500</option>
					<option value="800">800</option>
					<option value="1500">1500</option>
					</select></font></td>
					<td align="left" width="147"><b>
					<font color="#C0C0C0">
					<input type="checkbox" onclick="" disabled name="Relays" value="1" checked>Include 
					Relays</font></b></td>
				</tr>
				<tr>
					<td width="47" height="25"><b>Show</b></td>
					<td width="100" height="25"><b>
					<select size="1" onchange="parent.page()" name="Gender" style="font-weight: 700; width:94; height:22">
					<option selected value="Both Sex">Both Sex</option>
					<option value="F">Female</option>
					<option value="M">Male</option>
					</select></b></td>
					<td colspan="2"><b>Include&nbsp;
					<select size="1" name="Top" onchange="if(parent.h.document.F.Age.selectedIndex!=0 || parent.h.document.F.Club.selectedIndex!=0){parent.page()}" style="font-weight: 700">
					<option selected>0</option>
					<option>1</option>
					<option>2</option>
					<option>3</option>
					<option>4</option>
					<option>5</option>
					<option>6</option>
					<option>7</option>
					<option>8</option>
					<option>9</option>
					<option>10</option>
					</select>&nbsp; top places in </b><font size="1">
					<select size="1"  disabled name="Filter" onchange="if(parent.h.document.F.Age.selectedIndex!=0 || parent.h.document.F.Club.selectedIndex!=0){parent.page()}" style="font-weight: 700; position:relative">
					<option>every event</option>
					<option selected>filter events only</option>
					</select></font></td>
					<td width="147"><b>
					<input type="checkbox" onclick="parent.page()" name="Finals" value="1" checked> 
					Include Finals</b></td>
				</tr>
				<tr>
					<td colspan="2" height="25"><b>Print Columns</b>
					<select size="1" disabled name="Coloums" style="font-weight: 700">
					<option>1</option>
					<option selected>2</option>
					</select>&nbsp; </td>
					<td colspan="2"><b><font color="#C0C0C0">Include&nbsp;
					<select size="1" name="Around" disabled onchange="if(parent.h.document.F.Age.selectedIndex!=0 || parent.h.document.F.Club.selectedIndex!=0){parent.page()}" style="font-weight: 700; width: 45; height: 22">
					<option selected>0</option>
					<option>1</option>
					<option>2</option>
					<option>3</option>
					<option>4</option>
					</select>&nbsp; before and after filtered</font></b></td>
					<td width="147"><b>
					<input type="checkbox" onclick="parent.page()" name="Prelims" value="1" checked>Include 
					Prelims</b></td>
				</tr>
			</table>
			<table border="0" width="400" id="table1" cellspacing="0" cellpadding="0">
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>
					<p align="left"><b><font size="3">Clubs to include in Report</font></b>
					<script language="javascript">
<!--
var colors = new Array("FF0000","Red","00FF00","Green","0000FF","Blue","FF9900","Orange","FFFF00","Yellow","990099","Purple","0000CC","Blue","FF00FF","Pink","66FF99","","9999FF","","FF9966","","CC6600","","FF6666","");
function colorsel(team)
{
	document.writeln("<input  type='hidden' name='ClubH' value='" + team + "'></input>");
	document.writeln("<select size='1' name='TeamHigh" + team + "'>");
	document.writeln("<option>none</option>");
	for(i=0;i<colors.length;i=i+2)
	{
		document.writeln("<option value='" + colors[i] + "' style='background-color:#" + colors[i] + "'>" + colors[i + 1] + "</option>");
	}
	
	document.writeln("</select>");
}
//-->
</script>
					</td>
				</tr>
				<tr>
					<td>
					<table border="0" cellpadding="0" cellspacing="0" width="400">
						<tr height=0><td width=300></td><td></td></tr>
						<tr><td>
							<input type='checkbox' name='ClubA' value='All' checked>All Clubs</input></td>
							<td align="center"><b><font size="3">Colour</font></b></td>
						<%=multi_team%>
					</table>
					
</td>
				</tr>
			</table>
			<p>&nbsp;</p>
			<p></p>
			<br></td>
		</tr>
	</table>
	</div>
	<input type="hidden" name="MName" value="<%=Request.QueryString("MName")%>">
	<input type="hidden" name="Meet" value="<%=Request.QueryString("Meet")%>">
	<input type='hidden' name='ClubA' value='All' checked>
</form>



</body>
</html>


<%}else
{
if(true)
{
var high = "",sthigh = "",page_size = 960,colom = 1,pos,page,ns_count,count,ns_pos,evnt = "",F_P="", query = "WHERE (((MTEVENT.Meet)=" + Request.QueryString("Meet") + ")" ;
var  NS = new Array("","");
rs = null;
rs = Server.CreateObject("ADODB.Recordset");
rs.PageSize = 1; //Request.QueryString("Around")+1;

if(Request.QueryString("Lo_Hi") != "All")
{
	if(Request.QueryString("Lo_Hi") >= 0)
	{
		query += " AND ((MTEVENT.Lo_Hi)=" + Request.QueryString("Lo_Hi") + ")";
	}
	else
	{
		if(Request.QueryString("Top") != 0 || Request.QueryString("Around") != 0)
		{
			query += " AND ((Int(MTEVENT.[Lo_Hi]/100)) <=" + (Request.QueryString("Lo_Hi")*-1) + ") And (Int(MTEVENT.[Lo_Hi] Mod 100) >=" + (Request.QueryString("Lo_Hi")*-1) + ")";
		}
		else
		{
			query += " AND ((RESULT.AGE)=" + (Request.QueryString("Lo_Hi")*-1) + ")";
		}
	}
}
if(Request.QueryString("Stroke") != "All")
{

query += " AND ((MTEVENT.Stroke)=" + Request.QueryString("Stroke") + ")";
}
if(Request.QueryString("Distance") != "All")
{

query += " AND ((MTEVENT.Distance)=" + Request.QueryString("Distance") + ")";
}
if(Request.QueryString("Gender") != "Both Sex")
{

query += " AND ((MTEVENT.Sex)='" + Request.QueryString("Gender") + "')";
}
/*if(Request.QueryString("TOP") == 0)// & Request.QueryString("Around") == 0)
{*/
	/*i=1;
	if(Request.QueryString("ClubA")(1)== "All")
	{i = 1;}
	*/

	if(Request.QueryString("Club").Count() > 0)
	{
		query += " AND ((";
		query += "((Athlete.Team1)=" + Request.QueryString("Club")(1) + ")";
		
		for(i = 2;i< Request.QueryString("Club").Count + 1;i++)
		{
			query += " OR ((Athlete.Team1)=" + Request.QueryString("Club")(i) + ")";	
		}	
		
		query += ")"; 
			
		if(Request.QueryString("Top").value != 0)
		{
			query += " OR (RESULT.PLACE <> 0 AND RESULT.PLACE <= " + Request.QueryString("Top") + ")";
		}
		query += ")";
	}
	
	//Response.write(query);
	
//}
			
if(Request.QueryString("Relays").count != 1) //no realys
{
	query += " AND ((MTEVENT.I_R)='I')";
		if(Request.QueryString("Finals").count == 1 & Request.QueryString("Prelims").count == 1) //both final and prelim
		{	
			
		}
		else
		{
			if(Request.QueryString("Finals").count == 1)
			{	
				query += " AND ((RESULT.F_P) ='F')";
			}
			if(Request.QueryString("Prelims").count == 1)
			{
				query += " AND ((RESULT.F_P) = 'P')";
			}
		}


}
else
{
		if(Request.QueryString("Finals").count != 1 & Request.QueryString("Prelims").count != 1)
		{	
			query += " AND ((MTEVENT.I_R)='R')";
		}
		else
		{
			if(!(Request.QueryString("Finals").count == 1 & Request.QueryString("Prelims").count == 1)) //both final and prelim
			{
				if(Request.QueryString("Finals").count == 1)
				{	
					query += " AND ((RESULT.F_P) = 'F')";
				}
				if(Request.QueryString("Prelims").count == 1)
				{
					query += " AND ((RESULT.F_P) = 'P')";
				}
			}
		}
}

		/*if(Request.QueryString("Finals") != 1)
		{	
			query += " AND ((RESULT.F_P) <> 'F')";
		}
		if(Request.QueryString("Prelims") != 1)
		{
			query += " AND ((RESULT.F_P) <> 'P')";
		}*/


/*if(!(Request.QueryString("Finals").Count == 0 & Request.QueryString("Prelims").Count == 0))
{
	if((Request.QueryString("Finals") != 1 || Request.QueryString("Prelims") != 1))
	{
		if(Request.QueryString("Finals") == 1)
		{	
			query += " AND ((RESULT.F_P)='F')";
		}
		if(Request.QueryString("Prelims") == 1)
		{
			query += " AND ((RESULT.F_P)='P')";
		}
	}
	else
	{
	
	}
}
else
{
query += " AND ((MTEVENT.I_R)='R')";
}*/

query +=")";


high += "Switch(";

for(i=1;i< Request.QueryString("ClubH").Count()+1;i++)
{
	if (Request.QueryString(("TeamHigh" + Request.QueryString("ClubH")(i))) != "none")
	{
		sthigh += ".h" + i + "{color:#" + Request.QueryString(("TeamHigh" + Request.QueryString("ClubH")(i))) + "};";
		high += "[RESULT].[TEAM]=" + Request.QueryString("ClubH")(i) + "," + i + ",";
	}
}
high += "-1,'') AS High_light,";
//Response.write(high + "<br>");
//Response.write(sthigh + "<br>");

//=1,"hr1",[RESULT].[Team]=2,"hr2") AS High_light,

//rs.Open ("SELECT Athlete.Last, Athlete.First, Athlete.Sex, RESULT.AGE, RESULT.PLACE, RESULT.SCORE, RESULT.POINTS, RESULT.F_P, ([MtEv] & ' ' & [MtEvX]) AS Evnt, MTEVENT.Lo_Hi, MTEVENT.Stroke, MTEVENT.Distance , MTEVENT.Sex As EvSex, MTEVENT.[I_R], MTEVENT.[Session] FROM (Athlete INNER JOIN RESULT ON Athlete.Athlete = RESULT.ATHLETE) INNER JOIN MTEVENT ON RESULT.MTEVENT = MTEVENT.MtEvent " + query + " ORDER BY ([MtEv] & ' ' & [MtEvX]), RESULT.F_P, RESULT.PLACE;", oConn, adOpenStatic);
//Response.write(query);
rs.Open ("SELECT [Athlete].[Athlete], [Athlete].[Last], Athlete.[First], Athlete.[Sex], TEAM.Team," + high + " (TEAM.TCode & '-' & TEAM.LSC) As TM, RESULT.[AGE], RESULT.SCORE, IIf(RESULT.POINTS=0,'',[RESULT.POINTS]) AS [POINTS], ([MtEv] & ' ' & [MtEvX]) AS [Evnt], RESULT.[F_P], RESULT.[PLACE],MTEVENT.[Lo_Hi], Int(MTEVENT.[Lo_Hi]/100) AS [Lo_Age], Int(MTEVENT.[Lo_Hi] Mod 100) AS [Hi_Age], MTEVENT.Stroke, MTEVENT.Distance, MTEVENT.Sex AS EvSex, MTEVENT.I_R, MTEVENT.[Session], MTEVENT.Meet FROM ((Athlete INNER JOIN RESULT ON Athlete.Athlete = RESULT.ATHLETE) INNER JOIN MTEVENT ON RESULT.MTEVENT = MTEVENT.MtEvent) INNER JOIN TEAM ON RESULT.TEAM = TEAM.Team  " + query + "  ORDER BY [MtEv], [MtEvX], RESULT.F_P, RESULT.PLACE;", oConn, adOpenStatic);


//rs = oConn.Execute("SELECT Athlete.Last, Athlete.First, Athlete.[Sex], RESULT.[AGE], RESULT.PLACE, RESULT.[SCORE], RESULT.POINTS, RESULT.F_P, ([MtEv] & ' ' & [MtEvX]) AS Evnt, MTEVENT.Lo_Hi, MTEVENT.Stroke, MTEVENT.Distance , MTEVENT.[Sex] As [EvSex], MTEVENT.[I_R], MTEVENT.[Session] FROM (Athlete INNER JOIN RESULT ON Athlete.Athlete = RESULT.ATHLETE) INNER JOIN MTEVENT ON RESULT.MTEVENT = MTEVENT.MtEvent " + query + " ORDER BY ([MtEv] & ' ' & [MtEvX]), RESULT.F_P, RESULT.PLACE;");
//rs.AbsolutePage = 4;
page = 1;
count = 0;
nscount = 0;
ns_pos = 0;

function Team_match(team)
{
	for(t=1;t<Request.QueryString("Club").Count() + 1;t++)
	{
		if(Request.QueryString("Club")(t) == team)
		{
		return true;
		}
	}

	return false;
}

%>
<html>

<head>
<meta http-equiv="Content-Language" content="en-za">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Meet Results P.R-<%=Request.QueryString("MName")%></title>

<link rel="stylesheet" type="text/css" href="../styles.css">

<style>
<!--
table{ font-size: 10pt; color: #00000; font-weight: normal; }
TR {height:15px}
.evnt {font-size:14px; font-weight:bold;padding:0px; font-family:Times New Roman}
.line {height:19px;border-bottom:1px dashed #000000; vertical-align:bottom;font-family:Times New Roman;}
.page {font-weight:bold;border-bottom:1px dashed #FFFFFF; color:#FFFFFF}
.h0{color:#44ccbb}
<%=sthigh%>
//-->
</style>
</head>
<body  topmargin="0" leftmargin="0">

<table border="0" cellpadding="0" cellspacing="0" width="780px">
<!--<tr class=Heading colspan=3><td colspan=3 align=center><%=Request.QueryString("MName")%></td></tr>-->
<tr hieght='0'><%
		while(!rs.eof)
			{
			
			if(colom == 1)
			{
			colom = 0;
			%>
</tr><tr><td class=page colspan=3 align=center>Page&nbsp;<%=page%></td></tr><tr style="height=<%=page_size%>px"><%page++;
			}
			else
			{
			%>
			<td width=10px>&nbsp;</td>
			<%
			colom = 1;
			}
			%>
<td valign=top><table border=0 cellpadding=0 cellspacing=0 width=380px><tr style="height:0"><td width=20px></td><td></td><td width=20px></td><td width=20px></td><td width=70px></td><td width=50px></td><td width=25px></td></tr><% 

			while(!rs.eof)
			{
				if(rs.Fields("F_P").Value != F_P || rs.Fields("Evnt").Value != evnt)
				{
					for(;(pos < ns_pos) & (count < page_size);pos++,count+=15)
					{
						Response.write(NS[pos]);
					}
					if(count >=page_size)
						{
							Response.write("</table></td>");
							count = 0;
							break;
						}
					
					if(rs.Fields("Evnt").Value != evnt)
					{	
						if(count + 90 >=page_size)
						{
							Response.write("</table></td>");
							count = 0;
							break;
						}
						F_P="";
if(count !=0)
{				
%>
<tr><td colspan=7>&nbsp;</td></tr><tr><%}%>
<td colspan=7><table cellspacing=0 width=380px style="height:25" class=evnt><tr><td width=75>Event:&nbsp;<%=rs.Fields("Evnt")%></td><td width=55><%if(rs.Fields("EvSex").Value == "M"){%>Male<%}else{%>Female<%}%></td><td width=85><%=Age(rs.Fields("Lo_Age"),rs.Fields("Hi_Age"))%></td><td width=50><%=rs.Fields("Distance")%>m</td><td width=60><%=strokes[rs.Fields("Stroke")]%></td><td><%if(rs.Fields("I_R")=='I'){Response.write("Indi");}else{Response.write("Relay");}%></td></tr></table></td></tr><%
					//F_P = rs.Fields("F_P").Value;
					count += 40;
					evnt = rs.Fields("Evnt").Value;
					}
					
					if(rs.Fields("F_P").Value != F_P)
					{
						if(count + 40 >=page_size)
						{
							Response.write("</table></td>");
							count = 0;
							break;
						}

						pos= 0;
						count+=20;
						F_P = rs.Fields("F_P").Value;
						NS = null;
						NS = new Array("","");
						ns_pos = 0;
%>
<tr><td colspan=7 align=center class=line><b><%if(rs.Fields("F_P")=='F'){Response.write("Final");}else{if(rs.Fields("F_P")=='P'){Response.write("Prelim");}else{Response.write("Semi");}}%></b></td></tr><%
						
					}
				}
				if(Request.QueryString("ClubA")(1) == "All" || Team_match(rs.Fields("Team").Value) /*( == Request.QueryString("ClubA")(1))|| Request.QueryString("ClubA").count() > 1*/)
				{	
			if((rs.Fields("PLACE").Value != 0))
			{
			count+=15;
%>
<tr<%if (rs.Fields("High_light").Value != ""){%> class=h<%=rs.Fields("High_light")%><%}%>><td><%=rs.Fields("PLACE")%></td><td><%=rs.Fields("Last")%>, <%=rs.Fields("First")%></td><td><%=rs.Fields("Sex")%></td><td><%=rs.Fields("Age")%></td><td><%=rs.Fields("TM")%></td><td><%=CTime(rs.Fields("SCORE"))%></td><td><%=rs.Fields("POINTS")%></td></tr><%
}
			else
			{
				NS[ns_pos] = "<tr class=h" + rs.Fields("High_light") + "><td></td><td>" + rs.Fields("Last") + ", " + rs.Fields("First") + "</td><td>" + rs.Fields("Sex") + "</td><td>" + rs.Fields("Age") + "</td><td>" + rs.Fields("TM") + "</td><td>" + CTime(rs.Fields("SCORE")) + "</td><td>" + rs.Fields("POINTS") + "</td></tr>";
				ns_pos++;
			}
		}
			
			rs.MoveNext();
			if(count >= page_size)
			{
				Response.write("</table></td>");
				count = 0;
				break;
			}
		}}%>
		<%for(;(pos < ns_pos) & (count < 930);pos++,count+=15)
		{
			Response.write(NS[pos]);
		}
%></tr>	
</table>
</td>
</tr>
</table>
</body>
</html>
<%rs.Close();}}}oConn.close();
%>