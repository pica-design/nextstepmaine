<?php
	/************************
			SHORTCODES
	************************/
	add_shortcode('accordion', 'generate_accordion_content');
	function generate_accordion_content ($atts, $content) {
		$html_str  = "<section class='accordion closed'>";
		$html_str .= "	<header>";
		$html_str .= "		<figcaption>{$atts['title']}</figcaption>";
		$html_str .= "		<div><figure></figure></div>";
		$html_str .= "	</header>";
		$html_str .= "	<article>$content</article>";
		$html_str .= "</section>";
		return $html_str;
	}

	add_shortcode('display-browser-icons', 'generate_browser_icons');
	function generate_browser_icons () {
		$html_str  = "<div class='browser-icons'>\n";
		$html_str .= "	<figure class='chrome'><a href='https://www.google.com/intl/en/chrome/browser/' title='Download Google Chrome' target='_blank'></a></figure>\n";
		$html_str .= "	<figure class='firefox'><a href='http://www.mozilla.org/en-US/firefox/new/' title='Download Mozilla Firefox' target='_blank'></a></figure>\n";
		$html_str .= "	<figure class='safari'><a href='http://www.apple.com/safari/download/' title='Apple Safari' target='_blank'></a></figure>\n";
		$html_str .= "	<figure class='opera'><a href='http://www.opera.com/download/' title='Opera' target='_blank'></a></figure>\n";		
		$html_str .= "	<figure class='internet-explorer'><a href='http://windows.microsoft.com/en-US/internet-explorer/downloads/ie/' title='Microsoft Internet Explorer' target='_blank'></a></figure>\n";
		$html_str .= "</div>\n";
		return $html_str;
	}
?>