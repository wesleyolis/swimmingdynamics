var url = tinyMCE.getParam("flash_external_list_url");
if (url != null) {
	// Fix relative
	if (url.charAt(0) != '/' && url.indexOf('://') == -1)
		url = tinyMCE.documentBasePath + "/" + url;

	document.write('<sc'+'ript language="javascript" type="text/javascript" src="' + url + '"></sc'+'ript>');
}

function init() {
	tinyMCEPopup.resizeToInnerSize();

	document.getElementById("filebrowsercontainer").innerHTML = getBrowserHTML('filebrowser','file','flash','flash');

	// Image list outsrc
	var html = getFlashListHTML('filebrowser','file','flash','flash');
	if (html == "")
		document.getElementById("linklistrow").style.display = 'none';
	else
		document.getElementById("linklistcontainer").innerHTML = html;

	var formObj = document.forms[0];
	var swffile   = tinyMCE.getWindowArg('swffile');
	var swfwidth  = '' + tinyMCE.getWindowArg('swfwidth');
	var swfheight = '' + tinyMCE.getWindowArg('swfheight');

	if (swfwidth.indexOf('%')!=-1) {
		formObj.width2.value = "%";
		formObj.width.value  = swfwidth.substring(0,swfwidth.length-1);
	} else {
		formObj.width2.value = "px";
		formObj.width.value  = swfwidth;
	}

	if (swfheight.indexOf('%')!=-1) {
		formObj.height2.value = "%";
		formObj.height.value  = swfheight.substring(0,swfheight.length-1);
	} else {
		formObj.height2.value = "px";
		formObj.height.value  = swfheight;
	}

	formObj.file.value = swffile;
	formObj.insert.value =