var prev=-1;
var delay_function;

//browser prompting
/*app = navigator.appName;
if(app.indexOf('microsoft') >= 0 || app.indexOf('Microsoft') >= 0)
{
if(confirm("You are using Internet Explorer Inferior web browser!!\nTrying using FireFox to experince the full Capabilites of web and our site.\nClick yes to download!"))
{parent.document.location = "http://www.mozilla.com/";}
}
*/

function Time (Score)
{if(Score==0)
{return '';}
else
{t=(Score<1000)?('0'+(Score/100)):(Score<6000)?(Score/100)+((((Score%100)%10)==0)?'0':''):(Score%100);
if(t<10)
t='0'+t;

if(Score >=6000)
{s = ((Score-t)%6000)/100;
m = (Score-t-(s*100))/6000;
s=(s<10)?'0'+s:s;
m=(m<10)?'0'+m:m;
t= m+':'+s+'.'+t;}
return t;}};


function dis_splits(id,s)
{
if(delay_function!=null)
clearTimeout(delay_function);
delay_function = setTimeout("dis_splits2("+id+",'"+s+"')",50);

}

function dis_splits2(id,s)
{
prev_count = 1;
prev_time=0;
	if(prev!=id)
	{
	if(prev!=-1)
	parent.document.getElementById('s'+prev).innerHTML ="";
	if(s!=null & s !='' )
	{
		var data = s.split(",");
		
		if((data.length/10) >=1)
		{
			coloms=5;
			width = 400;
		}
		else
		{
			coloms = (data.length/2)%5;
			width = (coloms*80);
		}
		body="<table class='entries' width='"+width+"px' border='2' cellpadding='0' bgcolor='#FFFFFF' bordercolor='#000000' style='border-collapse: collapse'>";
		for(i2=0;i2<(data.length/10);i2+=1)
		{
			body+="<tr class='odd'>";
			for(i=0;i<coloms*2;i+=2)
			{
			if(data[i+10*i2] ==null)
			body+="<td></td>"; 
			else
			body+="<td width='80px'><b>"+(data[i+10*i2]*25)+"m</b></td>";
			}
			body+="</tr>";
			
			//splits parts
			body+="<tr bgcolor='#FFFFFF'>";
			for(i=1;i<coloms*2;i+=2)
			{
			if(data[i+10*i2] <=0 || data[i+10*i2] ==null)
			{
				body+="<td class='white'></td>";
			}
			else
			{	
				//body+="<td class='white'><b><u>"+Time(((i+10*i2-2)<=0?data[i+10*i2]:(data[i+10*i2]-data[i+10*i2-2])))+"</u></b></td>";
				body+="<td class='white'><b>"+((data[i+10*i2]*1>=prev_time)?Time(Math.round((data[i+10*i2]-prev_time)/prev_count)):'-')+"</b></td>";
				
				
				
				
			}
			if(data[i+10*i2] >0)
				{
					prev_time = data[i+10*i2];
					prev_count=1;
				}
				else
				{
					prev_count++;
				}
			}
			
			body+="<tr bgcolor='#FFFFFF'>";
			for(i=1;i<coloms*2;i+=2)
			{
			if(data[i+10*i2] ==null)
			body+="<td class='white'></td>";
			else
			body+="<td class='white'>"+Time(data[i+10*i2])+"</td>";
			}
			body+="</tr>";	
			
			body+="</tr>";	
		
		}
		
		
		body+="</table>";
		parent.document.getElementById('s'+id).innerHTML =body;
		
		prev=id;
	}
	else
	prev=-1;
	}
}


function hide_dis()
{
	if(prev!=-1)
	{
	parent.document.getElementById('s'+prev).innerHTML ="";
	}
	prev=-1;
	if(delay_function!=null)
	clearTimeout(delay_function);
}

function dis_qt(id,d0,d1,d2,d3,d4,d5,multi)
{
if(delay_function!=null)
clearTimeout(delay_function);
delay_function = setTimeout("dis_qt2("+id+","+d0+","+d1+","+d2+","+d3+","+d4+","+d5+","+multi+")",50);

}

function dis_qt2(id,d0,d1,d2,d3,d4,d5,multi)
{
//long course 0
	if(prev!=id)
	{

	if(prev!=-1)
	parent.document.getElementById('s'+prev).innerHTML ="";


		body="<table class='entries' width='"+((multi>=3)?420:270)+"px' border='2' cellpadding='0' bgcolor='#FFFFFF' bordercolor='#000000' style='border-collapse: collapse'>";
		body+="<tr class='odd'><td width='80px' >Time <small>"+((d1==0)?'L':'S')+"</small></td>";
		if(multi==1 || multi>=3)
		body+="<td width='80px' "+(((d1==1 & d0!=0) || multi==1||(d0==0 & multi==4))?'style="font-weight: bold"':'')+" nowrap >SC&nbsp;Faster</td><td width='80px' "+(((d1==1 & d0!=0) || multi==1||(d0==0 & multi==4))?'style="font-weight: bold"':'')+" nowrap>SC&nbsp;Slower</td>";
		if(multi==0  || multi>=3)
		body+="<td width='80px'"+(((d1==0  & d0!=0)|| multi==0||(d0==0 & multi==3))?'style="font-weight: bold"':'')+" nowrap>LC&nbsp;Faster</td><td width='80px' "+(((d1==0  & d0!=0)|| multi==0||(d0==0 & multi==3))?'style="font-weight: bold"':'')+"  nowrap>LC&nbsp;Slower</td><tr>";
		
		body+="<tr  class='white'><td class='white'>"+Time(d0)+"&nbsp;";
		
		if(multi==1 || multi>=3)
		{
		body+="</td><td class='"+(((d1==1 & d0!=0) || multi==1||(d0==0 & multi==4)|| (multi>=3 &d2!=0 & d4==0))?(((d0<=d2 & d2!=0 & d0!=0) || (d5==0 & d4==0 & d2==0))?'green':'red'):'white')+"'>"+Time(d2);
		body+="</td><td class='"+(((d1==1 & d0!=0) || multi==1||(d0==0 & multi==4)|| (multi>=3 &d3!=0 & d5==0))?(((d0>=d3 & d3!=0 & d0!=0) || (d5==0 & d4==0 & d3==0))?'green':'red'):'white')+"'>"+Time(d3);
		}
		if(multi==0  || multi>=3)
		{
		body+="</td><td class='"+(((d1==0  & d0!=0)|| multi==0||(d0==0 & multi==3)|| (multi>=3 &d4!=0 & d2==0))?(((d0<=d4 & d4!=0 & d0!=0) || (d4==0 & d3==0 & d2==0))?'green':'red'):'white')+"'>"+Time(d4);
		body+="</td><td class='"+(((d1==0  & d0!=0)|| multi==0||(d0==0 & multi==3)|| (multi>=3 &d5!=0 & d3==0))?(((d0>=d5 & d5!=0 & d0!=0) || (d5==0 & d3==0 & d2==0))?'green':'red'):'white')+"'>"+Time(d5);
		}
		body+="</td><tr>";
		body+="</table>";
	//	parent.document.getElementById('s'+id).style.top="5px";
		parent.document.getElementById('s'+id).innerHTML =body;
		
		prev=id;
	
	}

}