<?php
	//Register our custom $_GET variable (aka query var, aka rewrite tag) ?program_type=foo
    add_rewrite_tag('%author_name%', '([^&]+)');
    //Create the rewrite write rule to convert site.com/programs/foo to site.com/programs/?program_type=foo
    add_rewrite_rule('^institutions/([^/]*)/?', 'index.php?author_name=$matches[1]', 'top');