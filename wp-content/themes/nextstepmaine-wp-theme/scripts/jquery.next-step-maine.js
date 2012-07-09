/**
 * jquery.LavaLamp v1.3.5 - light up your menus with fluid, jQuery powered animations.
 * Requires jQuery v1.2.3 or better from http://jquery.com
 * Tested on jQuery 1.4.4, 1.3.2 and 1.2.6
 * http://nixbox.com/projects/jquery-lavalamp/
 * Source code Copyright (c) 2008, 2009, 2010 Jolyon Terwilliger, jolyon@nixbox.com
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 */
(function(d){jQuery.fn.lavaLamp=function(a){function e(g){g=parseInt(g);return isNaN(g)?0:g}a=d.extend({target:"li",container:"",fx:"swing",speed:500,click:function(){return true},startItem:"",includeMargins:false,autoReturn:true,returnDelay:0,setOnClick:true,homeTop:0,homeLeft:0,homeWidth:0,homeHeight:0,returnHome:false,autoResize:false},a||{});if(a.container=="")a.container=a.target;a.autoResize&&d(window).resize(function(){d(a.target+".selectedLava").trigger("mouseenter")});return this.each(function(){function g(c){c||
(c=b);if(!a.includeMargins){i=e(c.css("marginLeft"));j=e(c.css("marginTop"))}c={left:c.position().left+i,top:c.position().top+j,width:c.outerWidth()-l,height:c.outerHeight()-m};f.stop().animate(c,a.speed,a.fx)}d(this).css("position")=="static"&&d(this).css("position","relative");if(a.homeTop||a.homeLeft){var n=d("<"+a.container+' class="homeLava"></'+a.container+">").css({left:a.homeLeft,top:a.homeTop,width:a.homeWidth,height:a.homeHeight,position:"absolute",display:"block"});d(this).prepend(n)}var s=
location.pathname+location.search+location.hash,b,f,k=d(a.target+"[class!=noLava]",this),h,l=0,m=0,p=0,q=0,i=0,j=0;b=d(a.target+".selectedLava",this);if(a.startItem!="")b=k.eq(a.startItem);if((a.homeTop||a.homeLeft)&&b.length<1)b=n;if(b.length<1){var o=0,r;k.each(function(){var c=d("a:first",this).attr("href");if(s.indexOf(c)>-1&&c.length>o){r=d(this);o=c.length}});if(o>0)b=r}if(b.length<1)b=k.eq(0);b=d(b.eq(0).addClass("selectedLava"));k.bind("mouseenter",function(){if(h){clearTimeout(h);h=null}g(d(this))}).click(function(c){if(a.setOnClick){b.removeClass("selectedLava");
b=d(this).addClass("selectedLava")}return a.click.apply(this,[c,this])});f=d("<"+a.container+' class="backLava"><div class="leftLava"></div><div class="bottomLava"></div><div class="cornerLava"></div></'+a.container+">").css({position:"absolute",display:"block",margin:0,padding:0}).prependTo(this);if(a.includeMargins){p=e(b.css("marginTop"))+e(b.css("marginBottom"));q=e(b.css("marginLeft"))+e(b.css("marginRight"))}l=e(f.css("borderLeftWidth"))+e(f.css("borderRightWidth"))+e(f.css("paddingLeft"))+
e(f.css("paddingRight"))-q;m=e(f.css("borderTopWidth"))+e(f.css("borderBottomWidth"))+e(f.css("paddingTop"))+e(f.css("paddingBottom"))-p;if(a.homeTop||a.homeLeft)f.css({left:a.homeLeft,top:a.homeTop,width:a.homeWidth,height:a.homeHeight});else{if(!a.includeMargins){i=e(b.css("marginLeft"));j=e(b.css("marginTop"))}f.css({left:b.position().left+i,top:b.position().top+j,width:b.outerWidth()-l,height:b.outerHeight()-m})}d(this).bind("mouseleave",function(){var c=null;if(a.returnHome)c=n;else if(!a.autoReturn)return true;
if(a.returnDelay){h&&clearTimeout(h);h=setTimeout(function(){g(c)},a.returnDelay)}else g(c);return true})})}})(jQuery);


(function($){

	//do work
	
	/* PAGE SLIDESHOW */
	$('.slideshow .slides').cycle({
		timeout: 0, //Disable auto-advance
		fx:		'scrollHorz',  //Enable the horizontal scrolling effect for changing slides
		speed: 	700, //Set the slide transition to a speed of .7 seconds
		prev:   '.slideshow-prev', //Bind the previous slide link to our prev arrow icon
		next:   '.slideshow-next' //Bind the next slide link to our next arrow icon
	})
	
	/* 
		NEXT STEP LAVA MENU 
			- Position the lava arrow image above the user's current step
	*/
	//The first thing we try to do is see if the user is on a step page 
		//If they are we want to use the index of the current page to position the arrow
	var steps = $('nav.next-step .inner ul li')
	var index = steps.index(steps.filter('.current_page_item'))

	//However, if the user is not on one of these pages (index == -1) then let's attempt to grab the nextstep cookie
		//The cookie is set in header.php on each of the 4 step pages so the user can navigate around the site and the arrow stays on their step
	if (index == -1) { 
		index = $.cookie('nextstep')
	} 

	//However, if the user has not yet visited one of these pages OR the user's browser does not accept cookies we use 0 as a fallback 
	if (index == null) {
		index = 0 
	}

	//Enable the lavalamp using the index decided on above
	$('nav.next-step .inner').lavaLamp({
		fx: 'linear',
		speed: 200,
		startItem: +index
	});
	
	
	/* CONTENT ACCORDIANS */
	$('.accordion .title').click(function(){
		//If the accordian is open let's go ahead and close it
		if ($(this).parent().is('.open')) {
			$(this)
				.parent()
					.find('.content')
						.slideUp()
						.parent()
				.removeClass('open')
				.addClass('closed')
		} else {
			//Whereas if the menu is currently closed, let's open it
			$(this)
				.parent()
					.find('.content')
						.slideDown()
						.parent()
					.removeClass('closed')
					.addClass('open')
		}
	})
	
	
	/* Institution Widget Dropdown Menu 

	$('#blog-categories').selectbox({
		onChange: function (val, inst) {
			window.location = val
		}
	})
	
	*/
	$('.widget#institutions select').selectbox({
		onChange: function (val, inst) {
			if (val != "") {
				window.location = val
			}
		}
	})
	

	$('table.tablesorter.programs').tablesorter({
		sortList: [[1,0]]/*,
		headers: {2:{sorter: false}}*/
	}); 

	$('table.tablesorter.jobs').tablesorter({
		sortList: [[1,0]]
	}); 
	
})(jQuery)