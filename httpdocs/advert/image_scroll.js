var i,id,delay;
var adverts=new Array();
function add_advert(id,id2,url,images2,delay2)
{
//adverts[id2] = new Array(0,null);
//adverts[id2][0] = 0;
//adverts[id2][1] = new Array();
//images = adverts[id2][1];
//i=adverts[id2][0];
adverts[id] = new Array();
images = adverts[id];


i=0;
delay = delay2;

for(p=0;p<images2.length;p++)
{	images[p]=new Image();
	images[p].src= url + "/" + images2[p];}
	images = adverts[id];
roller(id,id2,0);
}

function roller(id,id2,i)
{
images = adverts[id];
if(images[i].complete)
{document.getElementById(id2).src=images[i].src;
i++;
if(i>=images.length){i=0;}
}setTimeout("roller("+id+",'"+id2+"',"+i+")",delay);
}
