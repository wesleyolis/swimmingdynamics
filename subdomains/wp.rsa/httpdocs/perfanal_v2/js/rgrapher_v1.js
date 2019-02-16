function addLoadEvent(func) {
  var oldonload = window.onload;
  if (typeof window.onload != 'function') {
    window.onload = func;
  } else {
    window.onload = function() {
      if (oldonload) {
        oldonload();
      }
      func();
    }
  }
}

function isDomReadyWindowLoad()
{
	if ( domReady != null && domReady.runonce )
	{
		isDomReady();
		domReady.windowloadexcuted = true;
	}
}

function domLoaded( f )
{

	if ( domReady != null && domReady.done ) return f();

	if ( domReady != null && domReady.timer )
	{
		domReady.ready.push( f );
	}
	else
	{
		domReady.windowloadexcuted = false;


		addLoadEvent( isDomReadyWindowLoad );

		setInterval( function(){domReady.windowloadexcuted = true;isDomReady();}, 5000 );

		domReady.ready = [ f ];

		domReady.timedelay = 10;

		domReady.timer = setInterval( isDomReady, domReady.timedelay );

		domReady.done = false;

		domReady.runonce = true;

	}


}

function isDomReady()
{
	if ( domReady.done ) return false;

	if (document && document.getElementsByTagName && document.getElementById && document.body )
	{
		if ( domReady.ready !== null )
		{
			for ( var i = 0; i < domReady.ready.length;i++ )
				domReady.ready[i]();

			domReady.ready = null;
		}


		clearInterval ( domReady.timer );
		domReady.done = true;
	}
}


function score_time(obj, Score)
{
if(Score==0)
{return '00.00';}
else
{t=(Score<1000)?('0.'+(Score/100)+''+((Score%10==0)?'0':'')):(Score<6000)?(Score/100)+((Score%100==0)?'.00':((((Score%100)%10)==0)?'0':'')):(Score%100);
if(t<10)
t='0'+t;
if(Score >=6000)
{s = ((Score-t)%6000)/100;
m = (Score-t-(s*100))/6000;
s=(s<10)?'0'+s:s;
m=(m<10)?'0'+m:m;
t= m+':'+s+'.'+t;}
if(Score >=360000)
{
h = (Score-(Score%360000))/360000;
t = h + ':' + t; 	
}
return t;}
}

	function get_max_min(arr)
	{
	var mn=Array(null,null),eq=Array('>','<');
	var f='';
	for(var k in eq)
	f+="if((mn["+k+"]"+eq[k]+"arr[i][j]&&arr[i][j]!=null)||mn["+k+"]==null)mn["+k+"]=arr[i][j];";
	eval("f=function(){"+f+"}");
	for(var i in arr)
	for(var j in arr[i])
	f();
	return mn;
	}

//domLoaded( function() { hook_graphs(); } );
    
  
        window.onload = function ()
        {hook_graphs();
	}

	
	function hook_graphs()
	{
		var obj = document.getElementsByClassName('rgraph');
		for(i=0;i<obj.length;i++)
		init_rgraph(obj[i],i);
	}
	
	function init_rgraph(obj,c)
	{
	try{
	eval('var data ='+obj.id);
	}
	catch(e)
	{}
		obj.id = 'rgraph_'+c;
		var time_graph = new RGraph.Line(obj.id, data['plot']);
		time_graph.Set('chart.labels',data['clabels']);
		mn = get_max_min(data['plot']);
		if(mn[0]==mn[1])
		{
			mn[0]-=50;
			mn[1]+=50;
		}
		time_graph.Set('chart.ymin',mn[0]-5);
		time_graph.Set('chart.ymax',mn[1]+5);
		//time_graph.Set('chart.background.image','/_graphs/my_project/swimming.jpg');		   
		if(data['type']=='lcsc')
		{
		time_graph.Set('chart.scale.formatter', score_time);	
		time_graph.Set('chart.key', ['LC', 'SC']);
		}
		time_graph.Set('chart.key.background', 'white');
		time_graph.Set('chart.key.rounded', true);
		time_graph.Set('chart.gutter', 85 );
		
		time_graph.Set('chart.zoom.background',false);
		time_graph.Set('chart.zoom.fade.in',false);
		time_graph.Set('chart.zoom.fade.out',false);
		time_graph.Set('chart.zoom.delay',0);
		time_graph.Set('chart.zoom.frames',0);
		time_graph.Set('chart.background.grid.vlines',0);
		
		time_graph.Set('chart.height',330);
		time_graph.Set('chart.width',790);
		time_graph.context.translate(0,-70);
            if (!RGraph.isIE8()) {
                time_graph.Set('chart.zoom.mode', 'area');
		time_graph.Set('chart.zoom.factor',2);
            } else {
            }

            time_graph.Set('chart.filled', false);
            time_graph.Set('chart.tickmarks', 'filledcircle');
            time_graph.Set('chart.background.barcolor1', 'white');
            time_graph.Set('chart.background.barcolor2', 'white');
            time_graph.Set('chart.background.grid.autofit', true);
            time_graph.Set('chart.colors', ['blue', 'orange', '#ff0']);
           // time_graph.Set('chart.fillstyle', ['#daf1fa', '#faa', '#ffa']);
            time_graph.Set('chart.text.angle', 30);
            time_graph.Set('chart.yaxispos', 'left');
            time_graph.Set('chart.linewidth', 2);
	    time_graph.Draw();
	   
	}
	//hook_graphs();