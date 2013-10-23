/**************************************************************************************************/
/***
/***	WORDPRESS RTE BIOGRAPHY PLUGIN JAVASCRIPT
/***	-----------------------------------------------------------------------
/***	Written by Matthew Praetzel. Copyright (c) 2008 Matthew Praetzel.
/***	-----------------------------------------------------------------------
/***
/**************************************************************************************************/

/*-----------------------
	Variables
-----------------------*/

//this is for compatability with cforms
var purl = '';

/*-----------------------
	Initialize
-----------------------*/
window.onload = function () {
	tinyMCE.settings['save_callback'] = function (e,c,body) {
		return c;
	}
	tinyMCE.execCommand('mceAddControl',false,'description');
}
