			<footer>
            	<div class="inner">
                	<section class="column first">
                    	<figure class="button gray gradient hover uppercase"><a href="<?php bloginfo('url') ?>/feedback" title="Give Next Step Maine Your Feedback">Feedback</a></figure>
                        <figure class="button gray gradient hover uppercase"><a href="" title="Signup for the Next Step Maine Newsletter">Newsletter</a></figure>
                        <figure class="button gray gradient hover uppercase"><a href="" title="Invite your friends to Next Step maine">Tell a Friend</a></figure>
                        <figure class="button gray gradient hover uppercase"><a href="" title="Find Next Step Maine on Facebook">Facebook</a></figure>
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
                        <?php wp_nav_menu('Site Map Menu') ?>
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
                        <a href="<?php bloginfo('url') ?>/site-requirements" title="Website Requirements">Site Requirements</a> 
                        / 
                        <a href="mailto:web@picadesign.com?subject=Bug Submission: <?php bloginfo('name')?> Website - Page: <?php echo $post->post_title ?>&body=Please detail the issue you've found and how to repeat it. Please let us know what web browser/version you are using. Thank You! - Team Pica" title="Submit a problem with this website">Submit an issue with this website</a>
                    </section>                                                      
                </div>
            </footer>
        </section><!-- .website-wrapper -->
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    	<script src="<?php bloginfo('template_directory') ?>/scripts/jquery.cycle.all.js"></script>
        <script src="<?php bloginfo('template_directory') ?>/scripts/jquery.next-step-maine.js"></script>
    </body>
</html>