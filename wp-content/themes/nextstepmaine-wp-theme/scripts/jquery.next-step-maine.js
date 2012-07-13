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
(function(d){jQuery.fn.lavaLamp=function(a){function e(g){g=parseInt(g);return isNaN(g)?0:g}a=d.extend({target:"li",container:"",fx:"swing",speed:500,click:function(){return true},startItem:"",includeMargins:false,autoReturn:true,returnDelay:0,setOnClick:true,homeTop:0,homeLeft:0,homeWidth:0,homeHeight:0,returnHome:false,autoResize:false},a||{});if(a.container=="")a.container=a.target;a.autoResize&&d(window).resize(function(){d(a.target+".selectedLava").trigger("mouseenter")});return this.each(function(){function g(c){c||(c=b);if(!a.includeMargins){i=e(c.css("marginLeft"));j=e(c.css("marginTop"))}c={left:c.position().left+i,top:c.position().top+j,width:c.outerWidth()-l,height:c.outerHeight()-m};f.stop().animate(c,a.speed,a.fx)}d(this).css("position")=="static"&&d(this).css("position","relative");if(a.homeTop||a.homeLeft){var n=d("<"+a.container+' class="homeLava"></'+a.container+">").css({left:a.homeLeft,top:a.homeTop,width:a.homeWidth,height:a.homeHeight,position:"absolute",display:"block"});d(this).prepend(n)}var s=location.pathname+location.search+location.hash,b,f,k=d(a.target+"[class!=noLava]",this),h,l=0,m=0,p=0,q=0,i=0,j=0;b=d(a.target+".selectedLava",this);if(a.startItem!="")b=k.eq(a.startItem);if((a.homeTop||a.homeLeft)&&b.length<1)b=n;if(b.length<1){var o=0,r;k.each(function(){var c=d("a:first",this).attr("href");if(s.indexOf(c)>-1&&c.length>o){r=d(this);o=c.length}});if(o>0)b=r}if(b.length<1)b=k.eq(0);b=d(b.eq(0).addClass("selectedLava"));k.bind("mouseenter",function(){if(h){clearTimeout(h);h=null}g(d(this))}).click(function(c){if(a.setOnClick){b.removeClass("selectedLava");b=d(this).addClass("selectedLava")}return a.click.apply(this,[c,this])});f=d("<"+a.container+' class="backLava"><div class="leftLava"></div><div class="bottomLava"></div><div class="cornerLava"></div></'+a.container+">").css({position:"absolute",display:"block",margin:0,padding:0}).prependTo(this);if(a.includeMargins){p=e(b.css("marginTop"))+e(b.css("marginBottom"));q=e(b.css("marginLeft"))+e(b.css("marginRight"))}l=e(f.css("borderLeftWidth"))+e(f.css("borderRightWidth"))+e(f.css("paddingLeft"))+e(f.css("paddingRight"))-q;m=e(f.css("borderTopWidth"))+e(f.css("borderBottomWidth"))+e(f.css("paddingTop"))+e(f.css("paddingBottom"))-p;if(a.homeTop||a.homeLeft)f.css({left:a.homeLeft,top:a.homeTop,width:a.homeWidth,height:a.homeHeight});else{if(!a.includeMargins){i=e(b.css("marginLeft"));j=e(b.css("marginTop"))}f.css({left:b.position().left+i,top:b.position().top+j,width:b.outerWidth()-l,height:b.outerHeight()-m})}d(this).bind("mouseleave",function(){var c=null;if(a.returnHome)c=n;else if(!a.autoReturn)return true;if(a.returnDelay){h&&clearTimeout(h);h=setTimeout(function(){g(c)},a.returnDelay)}else g(c);return true})})}})(jQuery);

/*
 * jQuery Selectbox plugin 0.1.3
 *
 * Copyright 2011, Dimitar Ivanov (http://www.bulgaria-web-developers.com/projects/javascript/selectbox/)
 * Licensed under the MIT (http://www.opensource.org/licenses/mit-license.php) license.
 * 
 * Date: Wed Jul 29 23:20:57 2011 +0200
 */
(function($,undefined){var PROP_NAME="selectbox",FALSE=false,TRUE=true;function Selectbox(){this._state=[];this._defaults={classHolder:"sbHolder",classHolderDisabled:"sbHolderDisabled",classSelector:"sbSelector",classOptions:"sbOptions",classGroup:"sbGroup",classSub:"sbSub",classDisabled:"sbDisabled",classToggleOpen:"sbToggleOpen",classToggle:"sbToggle",speed:200,effect:"slide",onChange:null,onOpen:null,onClose:null}}$.extend(Selectbox.prototype,{_isOpenSelectbox:function(target){if(!target){return FALSE}var inst=this._getInst(target);return inst.isOpen},_isDisabledSelectbox:function(target){if(!target){return FALSE}var inst=this._getInst(target);return inst.isDisabled},_attachSelectbox:function(target,settings){if(this._getInst(target)){return FALSE}var $target=$(target),self=this,inst=self._newInst($target),sbHolder,sbSelector,sbToggle,sbOptions,s=FALSE,optGroup=$target.find("optgroup"),opts=$target.find("option"),olen=opts.length;$target.attr("sb",inst.uid);$.extend(inst.settings,self._defaults,settings);self._state[inst.uid]=FALSE;$target.hide();function closeOthers(){var key,uid=this.attr("id").split("_")[1];for(key in self._state){if(key!==uid){if(self._state.hasOwnProperty(key)){if($(":input[sb='"+key+"']")[0]){self._closeSelectbox($(":input[sb='"+key+"']")[0])}}}}}sbHolder=$("<div>",{id:"sbHolder_"+inst.uid,"class":inst.settings.classHolder});sbSelector=$("<a>",{id:"sbSelector_"+inst.uid,href:"#","class":inst.settings.classSelector,click:function(e){e.preventDefault();closeOthers.apply($(this),[]);var uid=$(this).attr("id").split("_")[1];if(self._state[uid]){self._closeSelectbox(target)}else{self._openSelectbox(target)}}});sbToggle=$("<a>",{id:"sbToggle_"+inst.uid,href:"#","class":inst.settings.classToggle,click:function(e){e.preventDefault();closeOthers.apply($(this),[]);var uid=$(this).attr("id").split("_")[1];if(self._state[uid]){self._closeSelectbox(target)}else{self._openSelectbox(target)}}});sbToggle.appendTo(sbHolder);sbOptions=$("<ul>",{id:"sbOptions_"+inst.uid,"class":inst.settings.classOptions,css:{display:"none"}});$target.children().each(function(i){var that=$(this),li,config={};if(that.is("option")){getOptions(that)}else{if(that.is("optgroup")){li=$("<li>");$("<span>",{text:that.attr("label")}).addClass(inst.settings.classGroup).appendTo(li);li.appendTo(sbOptions);if(that.is(":disabled")){config.disabled=true}config.sub=true;getOptions(that.find("option"),config)}}});function getOptions(){var sub=arguments[1]&&arguments[1].sub?true:false,disabled=arguments[1]&&arguments[1].disabled?true:false;arguments[0].each(function(i){var that=$(this),li=$("<li>"),child;if(that.is(":selected")){sbSelector.text(that.text());s=TRUE}if(i===olen-1){li.addClass("last")}if(!that.is(":disabled")&&!disabled){child=$("<a>",{href:"#"+that.val(),rel:that.val(),text:that.text(),click:function(e){e.preventDefault();var t=sbToggle,uid=t.attr("id").split("_")[1];self._changeSelectbox(target,$(this).attr("rel"),$(this).text());self._closeSelectbox(target)}});if(sub){child.addClass(inst.settings.classSub)}child.appendTo(li)}else{child=$("<span>",{text:that.text()}).addClass(inst.settings.classDisabled);if(sub){child.addClass(inst.settings.classSub)}child.appendTo(li)}li.appendTo(sbOptions)})}if(!s){sbSelector.text(opts.first().text())}$.data(target,PROP_NAME,inst);sbSelector.appendTo(sbHolder);sbOptions.appendTo(sbHolder);sbHolder.insertAfter($target)},_detachSelectbox:function(target){var inst=this._getInst(target);if(!inst){return FALSE}$("#sbHolder_"+inst.uid).remove();$.data(target,PROP_NAME,null);$(target).show()},_changeSelectbox:function(target,value,text){var inst=this._getInst(target),onChange=this._get(inst,"onChange");$("#sbSelector_"+inst.uid).text(text);$(target).find("option[value='"+value+"']").attr("selected",TRUE);if(onChange){onChange.apply((inst.input?inst.input[0]:null),[value,inst])}else{if(inst.input){inst.input.trigger("change")}}},_enableSelectbox:function(target){var inst=this._getInst(target);if(!inst||!inst.isDisabled){return FALSE}$("#sbHolder_"+inst.uid).removeClass(inst.settings.classHolderDisabled);inst.isDisabled=FALSE;$.data(target,PROP_NAME,inst)},_disableSelectbox:function(target){var inst=this._getInst(target);if(!inst||inst.isDisabled){return FALSE}$("#sbHolder_"+inst.uid).addClass(inst.settings.classHolderDisabled);inst.isDisabled=TRUE;$.data(target,PROP_NAME,inst)},_optionSelectbox:function(target,name,value){var inst=this._getInst(target);if(!inst){return FALSE}inst[name]=value;$.data(target,PROP_NAME,inst)},_openSelectbox:function(target){var inst=this._getInst(target);if(!inst||inst.isOpen||inst.isDisabled){return }var el=$("#sbOptions_"+inst.uid),viewportHeight=parseInt($(window).height(),10),offset=$("#sbHolder_"+inst.uid).offset(),scrollTop=$(window).scrollTop(),height=el.prev().height(),diff=viewportHeight-(offset.top-scrollTop)-height/2,onOpen=this._get(inst,"onOpen");el.css({top:height+"px",maxHeight:(diff-height)+"px"});inst.settings.effect==="fade"?el.fadeIn(inst.settings.speed):el.slideDown(inst.settings.speed);$("#sbToggle_"+inst.uid).addClass(inst.settings.classToggleOpen);this._state[inst.uid]=TRUE;inst.isOpen=TRUE;if(onOpen){onOpen.apply((inst.input?inst.input[0]:null),[inst])}$.data(target,PROP_NAME,inst)},_closeSelectbox:function(target){var inst=this._getInst(target);if(!inst||!inst.isOpen){return }var onClose=this._get(inst,"onClose");inst.settings.effect==="fade"?$("#sbOptions_"+inst.uid).fadeOut(inst.settings.speed):$("#sbOptions_"+inst.uid).slideUp(inst.settings.speed);$("#sbToggle_"+inst.uid).removeClass(inst.settings.classToggleOpen);this._state[inst.uid]=FALSE;inst.isOpen=FALSE;if(onClose){onClose.apply((inst.input?inst.input[0]:null),[inst])}$.data(target,PROP_NAME,inst)},_newInst:function(target){var id=target[0].id.replace(/([^A-Za-z0-9_-])/g,"\\\\$1");return{id:id,input:target,uid:Math.floor(Math.random()*99999999),isOpen:FALSE,isDisabled:FALSE,settings:{}}},_getInst:function(target){try{return $.data(target,PROP_NAME)}catch(err){throw"Missing instance data for this selectbox"}},_get:function(inst,name){return inst.settings[name]!==undefined?inst.settings[name]:this._defaults[name]}});$.fn.selectbox=function(options){var otherArgs=Array.prototype.slice.call(arguments,1);if(typeof options=="string"&&options=="isDisabled"){return $.selectbox["_"+options+"Selectbox"].apply($.selectbox,[this[0]].concat(otherArgs))}if(options=="option"&&arguments.length==2&&typeof arguments[1]=="string"){return $.selectbox["_"+options+"Selectbox"].apply($.selectbox,[this[0]].concat(otherArgs))}return this.each(function(){typeof options=="string"?$.selectbox["_"+options+"Selectbox"].apply($.selectbox,[this].concat(otherArgs)):$.selectbox._attachSelectbox(this,options)})};$.selectbox=new Selectbox();$.selectbox.version="0.1.3"})(jQuery);

/*!
 * jQuery Cookie Plugin v1.1
 * https://github.com/carhartl/jquery-cookie
 *
 * Copyright 2011, Klaus Hartl
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.opensource.org/licenses/GPL-2.0
 */
(function(e,b){var a=/\+/g;function d(f){return f}function c(f){return decodeURIComponent(f.replace(a," "))}e.cookie=function(k,j,o){if(arguments.length>1&&(!/Object/.test(Object.prototype.toString.call(j))||j==null)){o=e.extend({},e.cookie.defaults,o);if(j==null){o.expires=-1}if(typeof o.expires==="number"){var l=o.expires,n=o.expires=new Date();n.setDate(n.getDate()+l)}j=String(j);return(b.cookie=[encodeURIComponent(k),"=",o.raw?j:encodeURIComponent(j),o.expires?"; expires="+o.expires.toUTCString():"",o.path?"; path="+o.path:"",o.domain?"; domain="+o.domain:"",o.secure?"; secure":""].join(""))}o=j||e.cookie.defaults||{};var f=o.raw?d:c;var m=b.cookie.split("; ");for(var h=0,g;(g=m[h]&&m[h].split("="));h++){if(f(g.shift())===k){return f(g.join("="))}}return null};e.cookie.defaults={}})(jQuery,document);

/*
 * 
 * TableSorter 2.0 - Client-side table sorting with ease!
 * Version 2.0.5b
 * @requires jQuery v1.2.3
 * 
 * Copyright (c) 2007 Christian Bach
 * Examples and docs at: http://tablesorter.com
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 * 
 */
(function($){$.extend({tablesorter:new function(){var parsers=[],widgets=[];this.defaults={cssHeader:"header",cssAsc:"headerSortUp",cssDesc:"headerSortDown",cssChildRow:"expand-child",sortInitialOrder:"asc",sortMultiSortKey:"shiftKey",sortForce:null,sortAppend:null,sortLocaleCompare:true,textExtraction:"simple",parsers:{},widgets:[],widgetZebra:{css:["even","odd"]},headers:{},widthFixed:false,cancelSelection:true,sortList:[],headerList:[],dateFormat:"us",decimal:'/\.|\,/g',onRenderHeader:null,selectorHeaders:'thead th',debug:false};function benchmark(s,d){log(s+","+(new Date().getTime()-d.getTime())+"ms");}this.benchmark=benchmark;function log(s){if(typeof console!="undefined"&&typeof console.debug!="undefined"){console.log(s);}else{alert(s);}}function buildParserCache(table,$headers){if(table.config.debug){var parsersDebug="";}if(table.tBodies.length==0)return;var rows=table.tBodies[0].rows;if(rows[0]){var list=[],cells=rows[0].cells,l=cells.length;for(var i=0;i<l;i++){var p=false;if($.metadata&&($($headers[i]).metadata()&&$($headers[i]).metadata().sorter)){p=getParserById($($headers[i]).metadata().sorter);}else if((table.config.headers[i]&&table.config.headers[i].sorter)){p=getParserById(table.config.headers[i].sorter);}if(!p){p=detectParserForColumn(table,rows,-1,i);}if(table.config.debug){parsersDebug+="column:"+i+" parser:"+p.id+"\n";}list.push(p);}}if(table.config.debug){log(parsersDebug);}return list;};function detectParserForColumn(table,rows,rowIndex,cellIndex){var l=parsers.length,node=false,nodeValue=false,keepLooking=true;while(nodeValue==''&&keepLooking){rowIndex++;if(rows[rowIndex]){node=getNodeFromRowAndCellIndex(rows,rowIndex,cellIndex);nodeValue=trimAndGetNodeText(table.config,node);if(table.config.debug){log('Checking if value was empty on row:'+rowIndex);}}else{keepLooking=false;}}for(var i=1;i<l;i++){if(parsers[i].is(nodeValue,table,node)){return parsers[i];}}return parsers[0];}function getNodeFromRowAndCellIndex(rows,rowIndex,cellIndex){return rows[rowIndex].cells[cellIndex];}function trimAndGetNodeText(config,node){return $.trim(getElementText(config,node));}function getParserById(name){var l=parsers.length;for(var i=0;i<l;i++){if(parsers[i].id.toLowerCase()==name.toLowerCase()){return parsers[i];}}return false;}function buildCache(table){if(table.config.debug){var cacheTime=new Date();}var totalRows=(table.tBodies[0]&&table.tBodies[0].rows.length)||0,totalCells=(table.tBodies[0].rows[0]&&table.tBodies[0].rows[0].cells.length)||0,parsers=table.config.parsers,cache={row:[],normalized:[]};for(var i=0;i<totalRows;++i){var c=$(table.tBodies[0].rows[i]),cols=[];if(c.hasClass(table.config.cssChildRow)){cache.row[cache.row.length-1]=cache.row[cache.row.length-1].add(c);continue;}cache.row.push(c);for(var j=0;j<totalCells;++j){cols.push(parsers[j].format(getElementText(table.config,c[0].cells[j]),table,c[0].cells[j]));}cols.push(cache.normalized.length);cache.normalized.push(cols);cols=null;};if(table.config.debug){benchmark("Building cache for "+totalRows+" rows:",cacheTime);}return cache;};function getElementText(config,node){var text="";if(!node)return"";if(!config.supportsTextContent)config.supportsTextContent=node.textContent||false;if(config.textExtraction=="simple"){if(config.supportsTextContent){text=node.textContent;}else{if(node.childNodes[0]&&node.childNodes[0].hasChildNodes()){text=node.childNodes[0].innerHTML;}else{text=node.innerHTML;}}}else{if(typeof(config.textExtraction)=="function"){text=config.textExtraction(node);}else{text=$(node).text();}}return text;}function appendToTable(table,cache){if(table.config.debug){var appendTime=new Date()}var c=cache,r=c.row,n=c.normalized,totalRows=n.length,checkCell=(n[0].length-1),tableBody=$(table.tBodies[0]),rows=[];for(var i=0;i<totalRows;i++){var pos=n[i][checkCell];rows.push(r[pos]);if(!table.config.appender){var l=r[pos].length;for(var j=0;j<l;j++){tableBody[0].appendChild(r[pos][j]);}}}if(table.config.appender){table.config.appender(table,rows);}rows=null;if(table.config.debug){benchmark("Rebuilt table:",appendTime);}applyWidget(table);setTimeout(function(){$(table).trigger("sortEnd");},0);};function buildHeaders(table){if(table.config.debug){var time=new Date();}var meta=($.metadata)?true:false;var header_index=computeTableHeaderCellIndexes(table);$tableHeaders=$(table.config.selectorHeaders,table).each(function(index){this.column=header_index[this.parentNode.rowIndex+"-"+this.cellIndex];this.order=formatSortingOrder(table.config.sortInitialOrder);this.count=this.order;if(checkHeaderMetadata(this)||checkHeaderOptions(table,index))this.sortDisabled=true;if(checkHeaderOptionsSortingLocked(table,index))this.order=this.lockedOrder=checkHeaderOptionsSortingLocked(table,index);if(!this.sortDisabled){var $th=$(this).addClass(table.config.cssHeader);if(table.config.onRenderHeader)table.config.onRenderHeader.apply($th);}table.config.headerList[index]=this;});if(table.config.debug){benchmark("Built headers:",time);log($tableHeaders);}return $tableHeaders;};function computeTableHeaderCellIndexes(t){var matrix=[];var lookup={};var thead=t.getElementsByTagName('THEAD')[0];var trs=thead.getElementsByTagName('TR');for(var i=0;i<trs.length;i++){var cells=trs[i].cells;for(var j=0;j<cells.length;j++){var c=cells[j];var rowIndex=c.parentNode.rowIndex;var cellId=rowIndex+"-"+c.cellIndex;var rowSpan=c.rowSpan||1;var colSpan=c.colSpan||1; var firstAvailCol;if(typeof(matrix[rowIndex])=="undefined"){matrix[rowIndex]=[];}for(var k=0;k<matrix[rowIndex].length+1;k++){if(typeof(matrix[rowIndex][k])=="undefined"){firstAvailCol=k;break;}}lookup[cellId]=firstAvailCol;for(var k=rowIndex;k<rowIndex+rowSpan;k++){if(typeof(matrix[k])=="undefined"){matrix[k]=[];}var matrixrow=matrix[k];for(var l=firstAvailCol;l<firstAvailCol+colSpan;l++){matrixrow[l]="x";}}}}return lookup;}function checkCellColSpan(table,rows,row){var arr=[],r=table.tHead.rows,c=r[row].cells;for(var i=0;i<c.length;i++){var cell=c[i];if(cell.colSpan>1){arr=arr.concat(checkCellColSpan(table,headerArr,row++));}else{if(table.tHead.length==1||(cell.rowSpan>1||!r[row+1])){arr.push(cell);}}}return arr;};function checkHeaderMetadata(cell){if(($.metadata)&&($(cell).metadata().sorter===false)){return true;};return false;}function checkHeaderOptions(table,i){if((table.config.headers[i])&&(table.config.headers[i].sorter===false)){return true;};return false;}function checkHeaderOptionsSortingLocked(table,i){if((table.config.headers[i])&&(table.config.headers[i].lockedOrder))return table.config.headers[i].lockedOrder;return false;}function applyWidget(table){var c=table.config.widgets;var l=c.length;for(var i=0;i<l;i++){getWidgetById(c[i]).format(table);}}function getWidgetById(name){var l=widgets.length;for(var i=0;i<l;i++){if(widgets[i].id.toLowerCase()==name.toLowerCase()){return widgets[i];}}};function formatSortingOrder(v){if(typeof(v)!="Number"){return(v.toLowerCase()=="desc")?1:0;}else{return(v==1)?1:0;}}function isValueInArray(v,a){var l=a.length;for(var i=0;i<l;i++){if(a[i][0]==v){return true;}}return false;}function setHeadersCss(table,$headers,list,css){$headers.removeClass(css[0]).removeClass(css[1]);var h=[];$headers.each(function(offset){if(!this.sortDisabled){h[this.column]=$(this);}});var l=list.length;for(var i=0;i<l;i++){h[list[i][0]].addClass(css[list[i][1]]);}}function fixColumnWidth(table,$headers){var c=table.config;if(c.widthFixed){var colgroup=$('<colgroup>');$("tr:first td",table.tBodies[0]).each(function(){colgroup.append($('<col>').css('width',$(this).width()));});$(table).prepend(colgroup);};}function updateHeaderSortCount(table,sortList){var c=table.config,l=sortList.length;for(var i=0;i<l;i++){var s=sortList[i],o=c.headerList[s[0]];o.count=s[1];o.count++;}}function multisort(table,sortList,cache){if(table.config.debug){var sortTime=new Date();}var dynamicExp="var sortWrapper = function(a,b) {",l=sortList.length;for(var i=0;i<l;i++){var c=sortList[i][0];var order=sortList[i][1];var s=(table.config.parsers[c].type=="text")?((order==0)?makeSortFunction("text","asc",c):makeSortFunction("text","desc",c)):((order==0)?makeSortFunction("numeric","asc",c):makeSortFunction("numeric","desc",c));var e="e"+i;dynamicExp+="var "+e+" = "+s;dynamicExp+="if("+e+") { return "+e+"; } ";dynamicExp+="else { ";}var orgOrderCol=cache.normalized[0].length-1;dynamicExp+="return a["+orgOrderCol+"]-b["+orgOrderCol+"];";for(var i=0;i<l;i++){dynamicExp+="}; ";}dynamicExp+="return 0; ";dynamicExp+="}; ";if(table.config.debug){benchmark("Evaling expression:"+dynamicExp,new Date());}eval(dynamicExp);cache.normalized.sort(sortWrapper);if(table.config.debug){benchmark("Sorting on "+sortList.toString()+" and dir "+order+" time:",sortTime);}return cache;};function makeSortFunction(type,direction,index){var a="a["+index+"]",b="b["+index+"]";if(type=='text'&&direction=='asc'){return"("+a+" == "+b+" ? 0 : ("+a+" === null ? Number.POSITIVE_INFINITY : ("+b+" === null ? Number.NEGATIVE_INFINITY : ("+a+" < "+b+") ? -1 : 1 )));";}else if(type=='text'&&direction=='desc'){return"("+a+" == "+b+" ? 0 : ("+a+" === null ? Number.POSITIVE_INFINITY : ("+b+" === null ? Number.NEGATIVE_INFINITY : ("+b+" < "+a+") ? -1 : 1 )));";}else if(type=='numeric'&&direction=='asc'){return"("+a+" === null && "+b+" === null) ? 0 :("+a+" === null ? Number.POSITIVE_INFINITY : ("+b+" === null ? Number.NEGATIVE_INFINITY : "+a+" - "+b+"));";}else if(type=='numeric'&&direction=='desc'){return"("+a+" === null && "+b+" === null) ? 0 :("+a+" === null ? Number.POSITIVE_INFINITY : ("+b+" === null ? Number.NEGATIVE_INFINITY : "+b+" - "+a+"));";}};function makeSortText(i){return"((a["+i+"] < b["+i+"]) ? -1 : ((a["+i+"] > b["+i+"]) ? 1 : 0));";};function makeSortTextDesc(i){return"((b["+i+"] < a["+i+"]) ? -1 : ((b["+i+"] > a["+i+"]) ? 1 : 0));";};function makeSortNumeric(i){return"a["+i+"]-b["+i+"];";};function makeSortNumericDesc(i){return"b["+i+"]-a["+i+"];";};function sortText(a,b){if(table.config.sortLocaleCompare)return a.localeCompare(b);return((a<b)?-1:((a>b)?1:0));};function sortTextDesc(a,b){if(table.config.sortLocaleCompare)return b.localeCompare(a);return((b<a)?-1:((b>a)?1:0));};function sortNumeric(a,b){return a-b;};function sortNumericDesc(a,b){return b-a;};function getCachedSortType(parsers,i){return parsers[i].type;};this.construct=function(settings){return this.each(function(){if(!this.tHead||!this.tBodies)return;var $this,$document,$headers,cache,config,shiftDown=0,sortOrder;this.config={};config=$.extend(this.config,$.tablesorter.defaults,settings);$this=$(this);$.data(this,"tablesorter",config);$headers=buildHeaders(this);this.config.parsers=buildParserCache(this,$headers);cache=buildCache(this);var sortCSS=[config.cssDesc,config.cssAsc];fixColumnWidth(this);$headers.click(function(e){var totalRows=($this[0].tBodies[0]&&$this[0].tBodies[0].rows.length)||0;if(!this.sortDisabled&&totalRows>0){$this.trigger("sortStart");var $cell=$(this);var i=this.column;this.order=this.count++%2;if(this.lockedOrder)this.order=this.lockedOrder;if(!e[config.sortMultiSortKey]){config.sortList=[];if(config.sortForce!=null){var a=config.sortForce;for(var j=0;j<a.length;j++){if(a[j][0]!=i){config.sortList.push(a[j]);}}}config.sortList.push([i,this.order]);}else{if(isValueInArray(i,config.sortList)){for(var j=0;j<config.sortList.length;j++){var s=config.sortList[j],o=config.headerList[s[0]];if(s[0]==i){o.count=s[1];o.count++;s[1]=o.count%2;}}}else{config.sortList.push([i,this.order]);}};setTimeout(function(){setHeadersCss($this[0],$headers,config.sortList,sortCSS);appendToTable($this[0],multisort($this[0],config.sortList,cache));},1);return false;}}).mousedown(function(){if(config.cancelSelection){this.onselectstart=function(){return false};return false;}});$this.bind("update",function(){var me=this;setTimeout(function(){me.config.parsers=buildParserCache(me,$headers);cache=buildCache(me);},1);}).bind("updateCell",function(e,cell){var config=this.config;var pos=[(cell.parentNode.rowIndex-1),cell.cellIndex];cache.normalized[pos[0]][pos[1]]=config.parsers[pos[1]].format(getElementText(config,cell),cell);}).bind("sorton",function(e,list){$(this).trigger("sortStart");config.sortList=list;var sortList=config.sortList;updateHeaderSortCount(this,sortList);setHeadersCss(this,$headers,sortList,sortCSS);appendToTable(this,multisort(this,sortList,cache));}).bind("appendCache",function(){appendToTable(this,cache);}).bind("applyWidgetId",function(e,id){getWidgetById(id).format(this);}).bind("applyWidgets",function(){applyWidget(this);});if($.metadata&&($(this).metadata()&&$(this).metadata().sortlist)){config.sortList=$(this).metadata().sortlist;}if(config.sortList.length>0){$this.trigger("sorton",[config.sortList]);}applyWidget(this);});};this.addParser=function(parser){var l=parsers.length,a=true;for(var i=0;i<l;i++){if(parsers[i].id.toLowerCase()==parser.id.toLowerCase()){a=false;}}if(a){parsers.push(parser);};};this.addWidget=function(widget){widgets.push(widget);};this.formatFloat=function(s){var i=parseFloat(s);return(isNaN(i))?0:i;};this.formatInt=function(s){var i=parseInt(s);return(isNaN(i))?0:i;};this.isDigit=function(s,config){return/^[-+]?\d*$/.test($.trim(s.replace(/[,.']/g,'')));};this.clearTableBody=function(table){if($.browser.msie){function empty(){while(this.firstChild)this.removeChild(this.firstChild);}empty.apply(table.tBodies[0]);}else{table.tBodies[0].innerHTML="";}};}});$.fn.extend({tablesorter:$.tablesorter.construct});var ts=$.tablesorter;ts.addParser({id:"text",is:function(s){return true;},format:function(s){return $.trim(s.toLocaleLowerCase());},type:"text"});ts.addParser({id:"digit",is:function(s,table){var c=table.config;return $.tablesorter.isDigit(s,c);},format:function(s){return $.tablesorter.formatFloat(s);},type:"numeric"});ts.addParser({id:"currency",is:function(s){return/^[£$€?.]/.test(s);},format:function(s){return $.tablesorter.formatFloat(s.replace(new RegExp(/[£$€]/g),""));},type:"numeric"});ts.addParser({id:"ipAddress",is:function(s){return/^\d{2,3}[\.]\d{2,3}[\.]\d{2,3}[\.]\d{2,3}$/.test(s);},format:function(s){var a=s.split("."),r="",l=a.length;for(var i=0;i<l;i++){var item=a[i];if(item.length==2){r+="0"+item;}else{r+=item;}}return $.tablesorter.formatFloat(r);},type:"numeric"});ts.addParser({id:"url",is:function(s){return/^(https?|ftp|file):\/\/$/.test(s);},format:function(s){return jQuery.trim(s.replace(new RegExp(/(https?|ftp|file):\/\//),''));},type:"text"});ts.addParser({id:"isoDate",is:function(s){return/^\d{4}[\/-]\d{1,2}[\/-]\d{1,2}$/.test(s);},format:function(s){return $.tablesorter.formatFloat((s!="")?new Date(s.replace(new RegExp(/-/g),"/")).getTime():"0");},type:"numeric"});ts.addParser({id:"percent",is:function(s){return/\%$/.test($.trim(s));},format:function(s){return $.tablesorter.formatFloat(s.replace(new RegExp(/%/g),""));},type:"numeric"});ts.addParser({id:"usLongDate",is:function(s){return s.match(new RegExp(/^[A-Za-z]{3,10}\.? [0-9]{1,2}, ([0-9]{4}|'?[0-9]{2}) (([0-2]?[0-9]:[0-5][0-9])|([0-1]?[0-9]:[0-5][0-9]\s(AM|PM)))$/));},format:function(s){return $.tablesorter.formatFloat(new Date(s).getTime());},type:"numeric"});ts.addParser({id:"shortDate",is:function(s){return/\d{1,2}[\/\-]\d{1,2}[\/\-]\d{2,4}/.test(s);},format:function(s,table){var c=table.config;s=s.replace(/\-/g,"/");if(c.dateFormat=="us"){s=s.replace(/(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})/,"$3/$1/$2");}else if(c.dateFormat=="uk"){s=s.replace(/(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})/,"$3/$2/$1");}else if(c.dateFormat=="dd/mm/yy"||c.dateFormat=="dd-mm-yy"){s=s.replace(/(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{2})/,"$1/$2/$3");}return $.tablesorter.formatFloat(new Date(s).getTime());},type:"numeric"});ts.addParser({id:"time",is:function(s){return/^(([0-2]?[0-9]:[0-5][0-9])|([0-1]?[0-9]:[0-5][0-9]\s(am|pm)))$/.test(s);},format:function(s){return $.tablesorter.formatFloat(new Date("2000/01/01 "+s).getTime());},type:"numeric"});ts.addParser({id:"metadata",is:function(s){return false;},format:function(s,table,cell){var c=table.config,p=(!c.parserMetadataName)?'sortValue':c.parserMetadataName;return $(cell).metadata()[p];},type:"numeric"});ts.addWidget({id:"zebra",format:function(table){if(table.config.debug){var time=new Date();}var $tr,row=-1,odd;$("tr:visible",table.tBodies[0]).each(function(i){$tr=$(this);if(!$tr.hasClass(table.config.cssChildRow))row++;odd=(row%2==0);$tr.removeClass(table.config.widgetZebra.css[odd?0:1]).addClass(table.config.widgetZebra.css[odd?1:0])});if(table.config.debug){$.tablesorter.benchmark("Applying Zebra widget",time);}}});})(jQuery);

/**
 * Next Step Maine Custom Scripts
 * By Pica Design, LLC
**/
(function($){

	//Scroll to an element
	$.fn.scrollHere = function (adjustment) {
		var offset = $(this).offset().top;
		console.log(offset)
		$('html, body').animate({scrollTop:offset + adjustment}, 500);
	}

	$('table.tablesorter.programs th, table.tablesorter.jobs th').click(function(){
		$(this).parent().parent().scrollHere(0)
	})

	//Bind an event listener to the window scroll event (when a user scrolls on the website)
	$(window).scroll(function () {
		if ($(this).scrollTop() > 500) {
			//Enable our 'Back to Top' button if the user has scrolled down 500+ pixels
			$('.back-to-top').fadeIn(400)
		} else {
			//And hide the button if the user has scrolled up to within 500 pixels from the top
			$('.back-to-top').fadeOut(400)
		}
	});

	//Scroll back to the top of the page
	$('.back-to-top').click(function(){
		//Scroll back to the top over 800 milliseconds 
		$('html, body').animate({scrollTop: 0 }, 800);
	})

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
	//var steps = $('nav.next-step .inner ul li')
	//var index = steps.index(steps.filter('.current_page_item'))
	homeTop = 0
	homeLeft = 0
	
	//console.log(index)

	//However, if the user is not on one of these pages (index == -1) then let's attempt to grab the nextstep cookie
		//The cookie is set in header.php on each of the 4 step pages so the user can navigate around the site and the arrow stays on their step
	//if (index == -1) { 
	index = $.cookie('nextstep')
	//} 

	//However, if the user has not yet visited one of these pages OR the user's browser does not accept cookies we use 0 as a fallback 
	if (index == -1) {
		homeTop = -1
		homeLeft = -1
	}


	//Enable the lavalamp using the index decided on above
	$('nav.next-step .inner').lavaLamp({
		fx: 'linear',
		speed: 200,
		startItem: +index,
		homeTop: homeTop,
		homeLeft: homeLeft
	});
	
	/* CONTENT ACCORDIANS */
	$('section.accordion header').click(function(){
		//If the accordian is open let's go ahead and close it
		if ($(this).parent().is('.open')) {
			$(this)
				.parent()
					.find('article')
						.slideUp()
						.parent()
				.removeClass('open')
				.addClass('closed')
		} else {
			//Whereas if the menu is currently closed, let's open it
			$(this)
				.parent()
					.find('article')
						.slideDown()
						.parent()
					.removeClass('closed')
					.addClass('open')
		}
	})
	
	$('.widget#institutions select').selectbox({
		onChange: function (val, inst) {
			if (val != "") {
				window.location = val
			}
		}
	})

	$('table.tablesorter.programs, table.tablesorter.jobs').tablesorter({
		sortList: [[1,0]]
	})


	/*! Copyright (c) 2011 by Jonas Mosbech - https://github.com/jmosbech/StickyTableHeaders 
    MIT license info: https://github.com/jmosbech/StickyTableHeaders/blob/master/license.txt */
	$.StickyTableHeaders = function (el, options) {
        // To avoid scope issues, use 'base' instead of 'this'
        // to reference this class from internal events and functions.
        var base = this;

        // Access to jQuery and DOM versions of element
        base.$el = $(el);
        base.el = el;

        // Cache DOM refs for performance reasons
        base.$window = $(window);
        base.$clonedHeader = null;
        base.$originalHeader = null;

        // Add a reverse reference to the DOM object
        base.$el.data('StickyTableHeaders', base);

        base.init = function () {
            base.options = $.extend({}, $.StickyTableHeaders.defaultOptions, options);

            base.$el.each(function () {
                var $this = $(this);

                // remove padding on <table> to fix issue #7
                $this.css('padding', 0);

                $this.wrap('<div class="divTableWithFloatingHeader"></div>');

                base.$originalHeader = $('thead:first', this);
                base.$clonedHeader = base.$originalHeader.clone()//.wrap('div');

                base.$clonedHeader.addClass('tableFloatingHeader');
                base.$clonedHeader.css({
                    'position': 'fixed',
                    'top': 0,
                    'left': $this.css('margin-left'),
                    'display': 'none'
                });

                base.$originalHeader.addClass('tableFloatingHeaderOriginal');
                
                base.$originalHeader.before(base.$clonedHeader);

                // enabling support for jquery.tablesorter plugin
                // forward clicks on clone to original
                $('th', base.$clonedHeader).click(function(e){
                    var index = $('th', base.$clonedHeader).index(this);
                    $('th', base.$originalHeader).eq(index).click();
                });
                $this.bind('sortEnd', base.updateCloneFromOriginal );
            });

            base.updateTableHeaders();
            base.$window.scroll(base.updateTableHeaders);
            base.$window.resize(base.updateTableHeaders);
        };

        base.updateTableHeaders = function () {
            base.$el.each(function () {
                var $this = $(this);

                var fixedHeaderHeight = isNaN(base.options.fixedOffset) ? base.options.fixedOffset.height() : base.options.fixedOffset;

                var offset = $this.offset();
                var scrollTop = base.$window.scrollTop() + fixedHeaderHeight;
                var scrollLeft = base.$window.scrollLeft();

                if ((scrollTop > offset.top) && (scrollTop < offset.top + $this.height())) {
                    base.$clonedHeader.css({
                        'top': fixedHeaderHeight,
                        'margin-top': 0,
                        'left': offset.left - scrollLeft,
                        'display': 'block'
                    });

                    base.updateCloneFromOriginal();
                }
                else {
                    base.$clonedHeader.css('display', 'none');
                }
            });
        };

        base.updateCloneFromOriginal = function () {
            // Copy cell widths and classes from original header
            $('th', base.$clonedHeader).each(function (index) {
                var $this = $(this);
                var origCell = $('th', base.$originalHeader).eq(index);
                $this.removeClass().addClass(origCell.attr('class'));
                //console.log(origCell.width())
                $this.css('width', origCell.outerWidth());
            });

            // Copy row width from whole table
            base.$clonedHeader.css('width', base.$originalHeader.width());
        };

        // Run initializer
        base.init();
    };

    $.StickyTableHeaders.defaultOptions = {
        fixedOffset: 0
    };

    $.fn.stickyTableHeaders = function (options) {
        return this.each(function () {
            (new $.StickyTableHeaders(this, options));
        });
    };


    $('table.tablesorter.programs, table.tablesorter.jobs').stickyTableHeaders()
})(jQuery)