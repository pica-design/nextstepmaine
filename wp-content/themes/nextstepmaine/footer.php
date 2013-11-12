            <div class="clear"></div>
            <br /><br /><br /><br />
			<footer>
            	<div class="inner">
                	<section class="column first">
                    	<figure class="button gray gradient hover uppercase"><a href="<?php bloginfo('url') ?>/feedback" title="Give Next Step Maine Your Feedback">Feedback</a></figure>
                        <figure class="button gray gradient hover uppercase"><a href="<?php bloginfo('url') ?>/newsletter" title="Signup for the Next Step Maine Newsletter">Newsletter</a></figure>
                        <div class="button-w-tab">
                            <figure class="button gray gradient hover uppercase">Tell a Friend</figure>
                            <div class="button-tab">
                                <figure class="icon email">
                                    <a title="Email this website to a friend" href="mailto:?subject=Check out this new website I found: Next Step Maine&body=Next Step Maine is part of our effort to capitalize on Maine’s ready population of non-traditional students and working adults to help them increase their value in the workforce through increased skills and knowledge. Visit http://www.nextstepmaine.com for more information."></a>
                                </figure>
                                <!--<figure class="icon twitter">
                                    <a title="Share this website on Twitter" href="https://twitter.com/share?url=<?php bloginfo('url') ?>" target="_blank"></a>
                                </figure>-->
                                <figure class="icon facebook">
                                    <a name="fb_share" title="Share this website on Facebook" type="icon" share_url="<?php bloginfo('url') ?>"></a> 
                                </figure>
                            </div>
                        </div>
                        <div class="button-w-tab">
                            <figure class="button gray gradient hover uppercase"><a href="https://www.facebook.com/pages/Next-Step-Maine/176909589111001" title="Find Next Step Maine on Facebook" target="_blank">Facebook</a></figure>
                            <div class="button-tab facebook-like">
                                <iframe src="//www.facebook.com/plugins/like.php?href=https://www.facebook.com/pages/Next-Step-Maine/176909589111001&amp;send=false&amp;layout=button_count&amp;width=200&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font=arial&amp;height=21&amp;appId=309173685769794" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:200px; height:21px;" allowTransparency="true"></iframe>
                            </div>
                        </div>
                    </section>
                	<section class="column">
                    	<h4 class="uppercase">Contact</h4>
                        <ul>
                        	<li>295 WATER STREET</li>
                        	<li>SUITE 5, AUGUSTA, ME 04330</li>
                        	<li><strong>PHONE:</strong> 207-622-6345</li>
                        	<li><strong>FAX:</strong> 207-622-6346</li>
                        	<li><strong>EMAIL:</strong> <a href="mailto:nextstepmaine@mdf.org?Subject=Next Step Maine" title="Email Next Step Maine">nextstepmaine@mdf.org</a></li>
                        </ul>
                    </section>
                	<section class="column">
                    	<h4 class="uppercase">Site Map</h4>
                        <?php wp_nav_menu(array('menu' => 'Footer Menu')) ?>
                    </section>
                	<section class="column last">
                    	<h4 class="uppercase">Brought to you by</h4>
                        <a href="http://mdf.org/" title="Maine Development Foundation Website">
                        	<img src="<?php bloginfo('template_directory') ?>/images/content/mdf-logo.jpg" alt="Maine Development Foundation Logo" />
                        </a>
                    </section>
                    <div class="clear"></div>
                    <section class="row copyright">
                    	<figure class="pica">
                        	<a href="http://www.pica.is" title="Crafted by Pica" target="_blank" class="link-fill-container"></a> 
                        </figure>
                        <?php echo date('Y', time()) ?> &copy; Maine Development Foundation. All Rights Reserved.
                        <br />
                        <a href="<?php bloginfo('url') ?>/disclaimer" title="Website Disclaimer">Disclaimer</a> 
                        /
                        <a href="<?php bloginfo('url') ?>/site-requirements" title="Website Requirements">Site Requirements</a> 
                        / 
                        <a href="mailto:web@pica.is?subject=Bug Submission: <?php bloginfo('name')?> Website - Page: <?php echo $post->post_title ?>&body=Please detail the issue you've found and how to repeat it. Please let us know what web browser/version you are using. Thank You! - Team Pica" title="Submit a problem with this website">Submit an issue with this website</a>
                        <br />
                        <a href="http://www.gedtestingservice.com/" title="GED® Testing Service Website" target="_blank">GED® is a registered trademark of the American Council on Education.</a>
                    </section>                                                      
                </div>
            </footer>
        </section><!-- .website-wrapper -->
        <?php wp_footer() ?>
    </body>
</html>