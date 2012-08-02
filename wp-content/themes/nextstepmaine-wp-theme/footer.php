			<footer>
            	<div class="inner">
                	<section class="column first">
                    	<figure class="button gray gradient hover uppercase"><a href="<?php bloginfo('url') ?>/feedback" title="Give Next Step Maine Your Feedback">Feedback</a></figure>
                        <figure class="button gray gradient hover uppercase"><a href="<?php bloginfo('url') ?>/newsletter" title="Signup for the Next Step Maine Newsletter">Newsletter</a></figure>
                        <div class="button-w-tab">
                            <figure class="button gray gradient hover uppercase">Tell a Friend</figure>
                            <div class="button-tab">
                                <figure class="icon email">
                                    <a title="Email this website to a friend" href="mailto:?subject=I thought you would like Next Step Maine" target="_blank"></a>
                                </figure>
                                <figure class="icon twitter">
                                    <a title="Share this website on Twitter" href="https://twitter.com/share?url=<?php bloginfo('url') ?>" target="_blank"></a>
                                </figure>
                                <figure class="icon facebook">
                                    <a name="fb_share" title="Share this website on Facebook" type="icon" share_url="<?php bloginfo('url') ?>"></a> 
                                    <!--[if !IE]> -->
                                    <script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script>
                                    <!-- <![endif]-->
                                </figure>
                            </div>
                        </div>
                        <div class="button-w-tab">
                            <figure class="button gray gradient hover uppercase"><a href="" title="Find Next Step Maine on Facebook">Facebook</a></figure>
                            <div class="button-tab facebook-like">
                                <iframe src="//www.facebook.com/plugins/like.php?href=<?php bloginfo('url') ?>&amp;send=false&amp;layout=button_count&amp;width=200&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font=arial&amp;height=21&amp;appId=309173685769794" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:200px; height:21px;" allowTransparency="true"></iframe>
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
                        	<li><strong>EMAIL:</strong> mdf@mdf.org</li>
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
                        	<a href="http://www.picadesign.com" title="Crafted by Pica Design + Marketing" class="link-fill-container" target="_blank"></a>
                        </figure>
                        2012 &copy; Maine Development Foundation. All Rights Reserved.
                        <br />
                        <a href="<?php bloginfo('url') ?>/disclaimer" title="Website Disclaimer">Disclaimer</a> 
                        /
                        <a href="<?php bloginfo('url') ?>/site-requirements" title="Website Requirements">Site Requirements</a> 
                        / 
                        <a href="mailto:web@picadesign.com?subject=Bug Submission: <?php bloginfo('name')?> Website - Page: <?php echo $post->post_title ?>&body=Please detail the issue you've found and how to repeat it. Please let us know what web browser/version you are using. Thank You! - Team Pica" title="Submit a problem with this website">Submit an issue with this website</a>
                    </section>                                                      
                </div>
            </footer>
        </section><!-- .website-wrapper -->
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    	<script src="<?php bloginfo('template_directory') ?>/scripts/jquery.cycle.all.min.js"></script>
        <script src="<?php bloginfo('template_directory') ?>/scripts/jquery.next-step-maine.js"></script>
    </body>
</html>