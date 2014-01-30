<?php
class FrmProEntry{

    function FrmProEntry(){
        add_filter('frm_continue_to_new', array(&$this, 'frmpro_editing'), 10, 3);
        add_action('frm_validate_entry', array(&$this, 'pre_validate'), 15, 2);
        add_action('frm_validate_form_creation', array(&$this, 'validate'), 10, 5);
        add_action('frm_after_create_entry', array(&$this, 'set_cookie'), 20, 2);
        add_action('frm_after_create_entry', array(&$this, 'create_post'), 40, 2);
        add_action('frm_after_update_entry', array(&$this, 'update_post'), 40, 2);
        add_action('frm_before_destroy_entry', array(&$this, 'destroy_post'));
        add_filter('frm_update_entry', array(&$this, 'check_draft_status'), 10, 2);
        add_action('frm_after_create_entry', array(&$this, 'remove_draft_hooks'), 1);
    }
    
    function frmpro_editing($continue, $form_id, $action='new'){
        //Determine if this is a new entry or if we're editing an old one
        $form_submitted = FrmAppHelper::get_param('form_id');
        if ($action == 'new' or $action == 'preview')
            $continue = true;
        else
            $continue = (is_numeric($form_submitted) and (int)$form_id != (int)$form_submitted) ? true : false;
        
        return $continue;
    }
    
    function user_can_edit($entry, $form=false){
        if(empty($form)){
            if(is_numeric($entry)){
                $frm_entry = new FrmEntry();
                $entry = $frm_entry->getOne($entry);
            }
            
            if(is_object($entry))
                $form = $entry->form_id;
        }
        
        if(is_numeric($form)){
            $frm_form = new FrmForm();
            $form = $frm_form->getOne($form);
        }
        
        $allowed = $this->user_can_edit_check($entry, $form);
        return apply_filters('frm_user_can_edit', $allowed, compact('entry', 'form'));
    }
    
    function user_can_edit_check($entry, $form){
        global $frm_entry, $wpdb;
        
        $user_ID = get_current_user_id();
        
        if(!$user_ID || empty($form))
            return false;
        
        if(is_object($entry) && $entry->is_draft && $entry->user_id == $user_ID)
            return true;
        
        //if editable and user can edit someone elses entry
        if($entry and $form->editable and ((isset($form->options['open_editable']) and $form->options['open_editable']) or !isset($form->options['open_editable'])) and isset($form->options['open_editable_role']) and FrmAppHelper::user_has_permission($form->options['open_editable_role']))
            return true;
        
        $where = $wpdb->prepare('fr.id=%d', $form->id);
        
        if($form->editable and !empty($form->options['editable_role']) and !FrmAppHelper::user_has_permission($form->options['editable_role']) and (!isset($form->options['open_editable_role']) or $form->options['open_editable_role'] ==  '-1' or ((isset($form->options['open_editable']) and !$form->options['open_editable']) or (isset($form->options['open_editable']) and $form->options['open_editable'] and !empty($form->options['open_editable_role']) and !FrmAppHelper::user_has_permission($form->options['open_editable_role']))))){
            //only allow editing of drafts
            $where .= $wpdb->prepare(" and user_id=%d and is_draft=%d", $user_ID, 1);
        }
        
        // check if this user can edit entry from another user
        if (!$form->editable || !isset($form->options['open_editable_role']) || $form->options['open_editable_role'] == '-1' || (isset($form->options['open_editable']) && empty($form->options['open_editable'])) || !FrmAppHelper::user_has_permission($form->options['open_editable_role'])) {            
            $where .= $wpdb->prepare(" and user_id=%d", $user_ID);
            
            if(is_object($entry) && $entry->user_id != $user_ID) {
                return false;
            }
            
            if ( $form->editable && !FrmAppHelper::user_has_permission($form->options['open_editable_role']) && !FrmAppHelper::user_has_permission($form->options['editable_role']) ){
                // make sure user cannot edit their own entry, even if a higher user role can unless it's a draft
                if ( is_object($entry) && !$entry->is_draft ) {
                    return false;
                } else if ( !is_object($entry) ) {
                    $where .= ' and is_draft=1';
                }
            }
        }else if ($form->editable && $user_ID && empty($entry)) {
            // make sure user is editing their own draft by default, even if they have permission to edit others' entries
           $where .= $wpdb->prepare(" and user_id=%d", $user_ID);
        }
        
        if ( !$form->editable ) {
            $where .= ' and is_draft=1';

            if(is_object($entry) && !$entry->is_draft)
                return false;
        }
        
        // If entry object, and we made it this far, then don't do another db call
        if(is_object($entry)){
            return true;
        }
        
        if ( !empty($entry) ) {
            if(is_numeric($entry))
                $where .= $wpdb->prepare(" and it.id=%d", $entry);
            else
                $where .= $wpdb->prepare(" and item_key=%s", $entry);
        }
        
        return $frm_entry->getAll( $where, ' ORDER BY created_at DESC', 1, true);
    }
    
    function get_tagged_entries($term_ids, $args = array()){
        return get_objects_in_term( $term_ids, 'frm_tag', $args );
    }
    
    function get_entry_tags($entry_ids, $args = array()){
        return wp_get_object_terms( $entry_ids, 'frm_tag', $args );
    }
    
    function get_related_entries($entry_id){
        $term_ids = FrmProEntry::get_entry_tags($entry_id, array('fields' => 'ids'));
        $entry_ids = FrmProEntry::get_tagged_entries($term_ids);
        foreach ($entry_ids as $key => $id){
            if ($id == $entry_id)
                unset($entry_ids[$key]);
        }
        return $entry_ids;
    }

    function pre_validate($errors, $values){
        global $frm_entry_meta, $frm_entry, $frmdb, $frmpro_settings, $frm_vars;
        
        $user_ID = get_current_user_id();
        $params = (isset($frm_vars['form_params']) && is_array($frm_vars['form_params']) && isset($frm_vars['form_params'][$values['form_id']])) ? $frm_vars['form_params'][$values['form_id']] : FrmEntriesController::get_params($values['form_id']);
        
        if($params['action'] != 'create'){
            if(FrmProFormsHelper::going_to_prev($values['form_id'])){
                add_filter('frm_continue_to_create', '__return_false');
                $errors = array();
            }else if(FrmProFormsHelper::saving_draft($values['form_id'])){
                $errors = array();
            }
            return $errors;
        }
        
        $frm_form = new FrmForm();
        $form = $frm_form->getOne($values['form_id']);
        $form_options = maybe_unserialize($form->options);
        
        $can_submit = true;
        if (isset($form_options['single_entry']) and $form_options['single_entry']){
            if ($form_options['single_entry_type'] == 'cookie' and isset($_COOKIE['frm_form'. $form->id . '_' . COOKIEHASH])){
                $can_submit = false;
            }else if ($form_options['single_entry_type'] == 'ip'){
                $prev_entry = $frm_entry->getAll(array('it.ip' => $_SERVER['REMOTE_ADDR']), '', 1);
                if ($prev_entry)
                    $can_submit = false;
            }else if (($form_options['single_entry_type'] == 'user' or (isset($form->options['save_draft']) and $form->options['save_draft'] == 1)) and !$form->editable){
                if($user_ID){
                    $args = array('user_id' => $user_ID, 'form_id' => $form->id);
                    if($form_options['single_entry_type'] != 'user')
                        $args['is_draft'] = 1;
                    $meta = $frmdb->get_var($frmdb->entries, $args);
                    unset($args);
                }
                
                if (isset($meta) and $meta)
                    $can_submit = false;
            }
            
            if (!$can_submit){
                $k = is_numeric($form_options['single_entry_type']) ? 'field'. $form_options['single_entry_type'] : 'single_entry';
                $errors[$k] = $frmpro_settings->already_submitted;
                add_filter('frm_continue_to_create', '__return_false');
                return $errors;
            }
        }
        unset($can_submit);
        
        if ((($_POST and isset($_POST['frm_page_order_'. $form->id])) or FrmProFormsHelper::going_to_prev($form->id)) and !FrmProFormsHelper::saving_draft($form->id)){
            add_filter('frm_continue_to_create', '__return_false');
        }else if ($form->editable and isset($form_options['single_entry']) and $form_options['single_entry'] and $form_options['single_entry_type'] == 'user' and $user_ID and (!is_admin() or defined('DOING_AJAX'))){
            $meta = $frmdb->get_var($frmdb->entries, array('user_id' => $user_ID, 'form_id' => $form->id));
            
            if($meta){
                $errors['single_entry'] = $frmpro_settings->already_submitted;
                add_filter('frm_continue_to_create', '__return_false');
            }
        }
        
        if(FrmProFormsHelper::going_to_prev($values['form_id']))
            $errors = array();
        
        return $errors;
    }
        
    function validate($params, $fields, $form, $title, $description){
        global $frm_entry, $frm_settings, $frm_vars;
        
        if ((($_POST and isset($_POST['frm_page_order_'. $form->id])) or FrmProFormsHelper::going_to_prev($form->id)) and !FrmProFormsHelper::saving_draft($form->id)){
            $errors = '';
            $fields = FrmFieldsHelper::get_form_fields($form->id);
            $form_name = $form->name;
            $submit = isset($form->options['submit_value']) ? $form->options['submit_value'] : $frm_settings->submit_value;
            $values = $fields ? FrmEntriesHelper::setup_new_vars($fields, $form) : array();
            require(FrmAppHelper::plugin_path() .'/classes/views/frm-entries/new.php');
            add_filter('frm_continue_to_create', '__return_false');
        }else if ($form->editable and isset($form->options['single_entry']) and $form->options['single_entry'] and $form->options['single_entry_type'] == 'user'){
            
            $user_ID = get_current_user_id();
            if($user_ID){
                $entry = $frm_entry->getAll(array('it.user_id' => $user_ID, 'it.form_id' => $form->id), '', 1, true);
                if($entry)
                    $entry = reset($entry);
            }else{
                $entry = false;
            }
            
            if ($entry and !empty($entry) and (!isset($frm_vars['created_entries'][$form->id]) or !isset($frm_vars['created_entries'][$form->id]['entry_id']) or $entry->id != $frm_vars['created_entries'][$form->id]['entry_id'])){
                FrmProEntriesController::show_responses($entry, $fields, $form, $title, $description);
            }else{
                $record = $frm_vars['created_entries'][$form->id]['entry_id'];
                $saved_message = isset($form->options['success_msg']) ? $form->options['success_msg'] : $frm_settings->success_msg;
                if(FrmProFormsHelper::saving_draft($form->id)){
                    global $frmpro_settings;
                    $saved_message = isset($form->options['draft_msg']) ? $form->options['draft_msg'] : $frmpro_settings->draft_msg;
                }
                $saved_message = apply_filters('frm_content', $saved_message, $form, ($record ? $record : false));
                $message = wpautop(do_shortcode($record ? $saved_message : $frm_settings->failed_msg));
                $message = '<div class="frm_message" id="message">'. $message .'</div>';
                
                FrmProEntriesController::show_responses($record, $fields, $form, $title, $description, $message, '', $form->options);
            }
            add_filter('frm_continue_to_create', '__return_false');
        }else if(FrmProFormsHelper::saving_draft($form->id)){
            global $frmpro_settings;
            
            $record = (isset($frm_vars['created_entries']) and isset($frm_vars['created_entries'][$form->id])) ? $frm_vars['created_entries'][$form->id]['entry_id'] : 0;
            if($record){
                $saved_message = isset($form->options['draft_msg']) ? $form->options['draft_msg'] : $frmpro_settings->draft_msg;
                $saved_message = apply_filters('frm_content', $saved_message, $form, $record);
                $message = '<div class="frm_message" id="message">'. wpautop(do_shortcode($saved_message)) .'</div>';

                FrmProEntriesController::show_responses($record, $fields, $form, $title, $description, $message, '', $form->options);
                add_filter('frm_continue_to_create', '__return_false');
            }
        }
    }
    
    function set_cookie($entry_id, $form_id){
        //if form options['single] or isset($_POST['frm_single_submit']){
        if(defined('WP_IMPORTING') or defined('DOING_AJAX')) return;
        
        if(isset($_POST) and isset($_POST['frm_skip_cookie'])){
            if(!headers_sent())
                FrmProEntriesController::set_cookie($entry_id, $form_id);
            return;
        }
?>
<script type="text/javascript">
jQuery(document).ready(function($){
jQuery.ajax({type:"POST",url:"<?php echo admin_url( 'admin-ajax.php' ); ?>",
data:"action=frm_entries_ajax_set_cookie&entry_id=<?php echo $entry_id; ?>&form_id=<?php echo $form_id; ?>"
});
});    
</script>
<?php
        //}
    }
    
    function update_post($entry_id, $form_id){
        if(isset($_POST['frm_wp_post'])){
            $post_id = self::get_field('post_id', $entry_id);
            if($post_id){
                $post = get_post($post_id, ARRAY_A);
                unset($post['post_content']);
                $this->insert_post($entry_id, $post, true, $form_id);
            }else{
                $this->create_post($entry_id, $form_id);
            }
        }
    }
    
    function create_post($entry_id, $form_id){
        global $wpdb, $frmdb, $frmpro_display;
        $post_id = NULL;
        if(isset($_POST['frm_wp_post'])){
            $post = array();
            $post['post_type'] = FrmProForm::post_type($form_id);
            if(isset($_POST['frm_user_id']) and is_numeric($_POST['frm_user_id']))
                $post['post_author'] = $_POST['frm_user_id'];
            
            $status = false;
            foreach($_POST['frm_wp_post'] as $post_data => $value){
                if($status)
                    continue;
                    
                $post_data = explode('=', $post_data);
                
                if($post_data[1] == 'post_status')
                    $status = true;
            }
            
            if(!$status){
                $form_options = $frmdb->get_var($wpdb->prefix .'frm_forms', array('id' => $form_id), 'options');
                $form_options = maybe_unserialize($form_options);
                if(isset($form_options['post_status']) and $form_options['post_status'] == 'publish')
                    $post['post_status'] = 'publish';
            }
            
            //check for auto view and set frm_display_id
            $display = $frmpro_display->get_auto_custom_display(compact('form_id', 'entry_id'));
            if($display)
                $_POST['frm_wp_post_custom']['=frm_display_id'] = $display->ID;

            $post_id = $this->insert_post($entry_id, $post, false, $form_id);
        }
        
        //save post_id with the entry
        $updated = $wpdb->update( $frmdb->entries, array('post_id' => $post_id), array( 'id' => $entry_id ) );
        if($updated)
            wp_cache_delete( $entry_id, 'frm_entry' );
    }
    
    function insert_post($entry_id, $post, $editing=false, $form_id=false){
        $field_ids = $new_post = array();
        
        foreach($_POST['frm_wp_post'] as $post_data => $value){
            $post_data = explode('=', $post_data);
            $field_ids[] = $post_data[0];
            
            if(isset($new_post[$post_data[1]]))
                $value = array_merge((array)$value, (array)$new_post[$post_data[1]]);
            
            $post[$post_data[1]] = $new_post[$post_data[1]] = $value;
            //delete the entry meta below so it won't be stored twice
        }
        
        //if empty post content and auto display, then save compiled post content
        $display_id = ($editing) ? get_post_meta($post['ID'], 'frm_display_id', true) : (isset($_POST['frm_wp_post_custom']['=frm_display_id']) ? $_POST['frm_wp_post_custom']['=frm_display_id'] : 0);
        
        if(!isset($post['post_content']) and $display_id){
            $dyn_content = get_post_meta($display_id, 'frm_dyncontent', true);
            $post['post_content'] = apply_filters('frm_content', $dyn_content, $form_id, $entry_id);
        }

        $post_ID = wp_insert_post( $post );
    	
    	if ( is_wp_error( $post_ID ) or empty($post_ID))
    	    return;
    	
    	// Add taxonomies after save in case user doesn't have permissions
    	if(isset($_POST['frm_tax_input']) ){
            foreach ($_POST['frm_tax_input'] as $taxonomy => $tags ) {
                if ( is_taxonomy_hierarchical($taxonomy) )
    				$tags = array_keys($tags);
    			
                wp_set_post_terms( $post_ID, $tags, $taxonomy );
    			
    			unset($taxonomy);
    			unset($tags);
    		}
        }
    	
    	global $frm_entry_meta, $user_ID, $frm_vars;

    	$exclude_attached = array();
    	if(isset($frm_vars['media_id']) and !empty($frm_vars['media_id'])){
    	    global $wpdb;
    	    //link the uploads to the post
    	    foreach((array)$frm_vars['media_id'] as $media_id){
    	        $exclude_attached = array_merge($exclude_attached, (array)$media_id);
    	        
    	        if(is_array($media_id)){
    	            $attach_string = implode( ',', array_filter($media_id) );
    				$attached = $wpdb->query( $wpdb->prepare( "UPDATE $wpdb->posts SET post_parent = %d WHERE post_type = %s AND ID IN ( $attach_string )", $post_ID, 'attachment' ) ) .'<br/>';
    				
    	            foreach($media_id as $m){
    	                clean_attachment_cache( $m );
    	                unset($m);
    	            }
    	        }else{
    	            $wpdb->update( $wpdb->posts, array('post_parent' => $post_ID), array( 'ID' => $media_id, 'post_type' => 'attachment' ) );
    	            clean_attachment_cache( $media_id );
    	        }
    	    }
    	}

    	if($editing and count($_FILES) > 0){
    	    global $wpdb;
    	    $args = array( 
    	        'post_type' => 'attachment', 'numberposts' => -1, 
    	        'post_status' => null, 'post_parent' => $post_ID, 
    	        'exclude' => $exclude_attached
    	    ); 

            //unattach files from this post
            $attachments = get_posts( $args );
            foreach($attachments as $attachment)
                $wpdb->update( $wpdb->posts, array('post_parent' => null), array( 'ID' => $attachment->ID ) );
    	}

    	if(isset($_POST['frm_wp_post_custom'])){
        	foreach($_POST['frm_wp_post_custom'] as $post_data => $value){
        	    $post_data = explode('=', $post_data);
                $field_id = $post_data[0];

                if($value == '')
                    delete_post_meta($post_ID, $post_data[1]);
                else
                    update_post_meta($post_ID, $post_data[1], $value);
            	$frm_entry_meta->delete_entry_meta($entry_id, $field_id);
            	
            	unset($post_data);
            	unset($value);
            }
        }
        
        if(isset($dyn_content)){
            $new_content = apply_filters('frm_content', $dyn_content, $form_id, $entry_id);
            if($new_content != $post['post_content']){
                global $wpdb;
                $wpdb->update( $wpdb->posts, array( 'post_content' => $new_content ), array('ID' => $post_ID) );
            }
        }
        
        foreach($field_ids as $field_id)
            $frm_entry_meta->delete_entry_meta($entry_id, $field_id); 
        
    	update_post_meta( $post_ID, '_edit_last', $user_ID );
    	return $post_ID;
    }
    
    function destroy_post($entry_id){
        global $frmdb;
        $entry = $frmdb->get_one_record($frmdb->entries, array('id' => $entry_id), 'post_id');
        if($entry and is_numeric($entry->post_id))
          wp_delete_post($entry->post_id);
    }
    
    function create_comment($entry_id, $form_id){
        $comment_post_ID = isset($_POST['comment_post_ID']) ? (int) $_POST['comment_post_ID'] : 0;

        $post = get_post($comment_post_ID);

        if ( empty($post->comment_status) )
        	return;

        // get_post_status() will get the parent status for attachments.
        $status = get_post_status($post);

        $status_obj = get_post_status_object($status);

        if ( !comments_open($comment_post_ID) ) {
        	do_action('comment_closed', $comment_post_ID);
        	//wp_die( __('Sorry, comments are closed for this item.') );
        	return;
        } elseif ( 'trash' == $status ) {
        	do_action('comment_on_trash', $comment_post_ID);
        	return;
        } elseif ( !$status_obj->public && !$status_obj->private ) {
        	do_action('comment_on_draft', $comment_post_ID);
        	return;
        } elseif ( post_password_required($comment_post_ID) ) {
        	do_action('comment_on_password_protected', $comment_post_ID);
        	return;
        } else {
        	do_action('pre_comment_on_post', $comment_post_ID);
        }

        $comment_content      = ( isset($_POST['comment']) ) ? trim($_POST['comment']) : '';

        // If the user is logged in
        $user_ID = get_current_user_id();
        if ( $user_ID ) {
            global $current_user;
        
        	$display_name = (!empty( $current_user->display_name )) ? $current_user->display_name : $current_user->user_login;
        	$comment_author       = $wpdb->escape($display_name);
        	$comment_author_email = ''; //get email from field
        	$comment_author_url   = $wpdb->escape($user->user_url);
        }else{
            $comment_author       = ( isset($_POST['author']) )  ? trim(strip_tags($_POST['author'])) : '';
            $comment_author_email = ( isset($_POST['email']) )   ? trim($_POST['email']) : '';
            $comment_author_url   = ( isset($_POST['url']) )     ? trim($_POST['url']) : '';
        }

        $comment_type = '';

        if (!$user_ID and get_option('require_name_email') and (6 > strlen($comment_author_email) || $comment_author == '') )
        		return;

        if ( $comment_content == '')
        	return;


        $commentdata = compact('comment_post_ID', 'comment_author', 'comment_author_email', 'comment_author_url', 'comment_content', 'comment_type', 'user_ID');

        $comment_id = wp_new_comment( $commentdata );
 
    }
    
    public function check_draft_status($values, $id){
        if(self::get_field('is_draft', $id) or $values['is_draft']){
            //remove update hooks if submitting for the first time or is still draft
            remove_action('frm_after_update_entry', array(&$this, 'update_post'), 40, 2);
            remove_action('frm_after_update_entry', 'FrmProNotification::entry_updated', 41, 2);
        }
        
        //if entry was not previously draft or continues to be draft
        if(!self::get_field('is_draft', $id) or $values['is_draft'])
            return $values;
        
        //add the create hooks since the entry is switching draft status
        add_action('frm_after_update_entry', array(&$this, 'add_published_hooks'), 2, 2);
        
        //change created timestamp
        $values['created_at'] = $values['updated_at'];
        
        return $values;
    }
    
    public function remove_draft_hooks($entry_id){
        if(!self::get_field('is_draft', $entry_id))
            return;
        
        //remove hooks if saving as draft
        remove_action('frm_after_create_entry', array(&$this, 'set_cookie'), 20, 2);
        remove_action('frm_after_create_entry', array(&$this, 'create_post'), 40, 2);
        remove_action('frm_after_create_entry', 'FrmProNotification::entry_created', 41, 2);
        remove_action('frm_after_create_entry', 'FrmProNotification::autoresponder', 41, 2);
    }
    
    public function add_published_hooks($entry_id, $form_id){
        //add the create hooks since the entry is switching draft status
        do_action('frm_after_create_entry', $entry_id, $form_id);
        do_action('frm_after_create_entry_'. $form_id, $entry_id);
        remove_action('frm_after_create_entry', 'FrmProNotification::entry_created', 41, 2);
        remove_action('frm_after_create_entry', 'FrmProNotification::autoresponder', 41, 2);
        remove_action('frm_after_update_entry', array(&$this, 'add_published_hooks'), 2, 2);
    }
    
    function get_field($field='is_draft', $id){
        $entry = wp_cache_get( $id, 'frm_entry' );
        if($entry)
            return $entry->{$field};
        
        global $wpdb, $frmdb;
        return $wpdb->get_var($wpdb->prepare("SELECT $field FROM $frmdb->entries WHERE id=%d", $id));
    }

}
