/*
var total = parent.document.getElementById('total');
var total;
if(total !=null)
{
	total.innerHTML='0.00';
	link_total();
	
}
function link_total()
{
	var rows =  parent.document.getElementsByTagName('input');
	for(var i=0;i<rows.length ;i++)
	{
		var row = rows[i];
		if(row.name=='enter')
		{
			row.onclick = function(){cost_total(this);};
		//	row.cost=subString(row.id.indexof('cost')+4,row.id.length);
		}
	}

}


function table_total(dis)
{
	var tol = 0;
	var rows =  parent.document.getElementsByTagName('input');
	for(var i=0;i<rows.length ;i++)
	{
		var row = rows[i];
		if(row.name=='enter')
		{
			alert(row.cost);
		}
	}
	total.innerHTML=tol;
}
*/
/*
attachel();

function attachel()
{
var tables = parent.document.getElementsByTagName('table');
if(tables!=null)
for(var i=0;i<tables.length;i++)
	{var table = tables[i];
		if(table.id.indexOf('total')>=0)
		{tabletotal(table);}
	}
}


function get_para(str,para,token)
{
	pos1 = str.indexOf(para);
	pos2 = str.indexOf(token,pos1);
	if(pos2<0)
	pos2= str.length;
	return str.substring((pos1+para.length),pos2);
}

function tabletotal(table)
{
	var a_col = get_para(table.id,'A','_');
	var c_col = get_para(table.id,'C','_');
	var t = new table_total(get_para(table.id,'D','_'));
	
	var body =  table.getElementsByTagName('tbody');
	var rows =  body[0].getElementsByTagName('tr');
	for(var i=0;i<rows.length ;i++)
	{
		var row = rows[i];
		cols = row.getElementsByTagName('td');
		if(cols.length>=a_col & cols.length>=c_col)
		{		
		
			var col_a = cols[a_col];
			var col_c = cols[c_col];
			var value = (col_a.innerHTML*1);
			var check = col_c.getElementsByTagName('input');
			if(check.length>0)
			{		
			if(check[0].type=='checkbox')
			var ch = check[0]; 
			ch.onclick=function (){t.check(this,value)};
			}
		}
	}
}

function table_total(dis)
{this.total = 0;
this.ds = parent.document.getElementById(dis);
this.ds.innerHTML = '0';
};
table_total.prototype.add = function (value)
{this.total+=(value*1);
this.ds.innerHTML=this.total}

table_total.prototype.sub = function (value)
{this.total-=(value*1);
this.ds.innerHTML=this.total}

table_total.prototype.check = function (obj,value)
{if(obj.checked){this.add((value))}else{this.sub(value)}}

*/