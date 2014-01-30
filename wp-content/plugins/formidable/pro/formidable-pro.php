<?php 
/**
 * @package Formidable
 */

require($frm_path .'/pro/classes/controllers/FrmUpdatesController.php');
global $frm_update;
$frm_update  = new FrmUpdatesController();

$frm_vars['pro_is_authorized'] = $frm_update->pro_is_authorized();

require($frm_path .'/pro/classes/controllers/FrmProSettingsController.php');
$obj = new FrmProSettingsController();

if(!$frm_vars['pro_is_authorized'])
    return;


require($frm_path .'/pro/classes/models/FrmProSettings.php');

global $frmpro_settings;

$frmpro_settings = get_transient('frmpro_options');
if(!is_object($frmpro_settings)){
    if($frmpro_settings){ //workaround for W3 total cache conflict
        $frmpro_settings = unserialize(serialize($frmpro_settings));
    }else{
        $frmpro_settings = get_option('frmpro_options');

        // If unserializing didn't work
        if(!is_object($frmpro_settings)){
            if($frmpro_settings) //workaround for W3 total cache conflict
                $frmpro_settings = unserialize(serialize($frmpro_settings));
            else
                $frmpro_settings = new FrmProSettings();
            update_option('frmpro_options', $frmpro_settings);
            set_transient('frmpro_options', $frmpro_settings);
        }
    }
}
$frmpro_settings = get_option('frmpro_options');

// If unserializing didn't work
if(!is_object($frmpro_settings)){
    if($frmpro_settings) //workaround for W3 total cache conflict
        $frmpro_settings = unserialize(serialize($frmpro_settings));
    else
        $frmpro_settings = new FrmProSettings();
    update_option('frmpro_options', $frmpro_settings);
}

$frmpro_settings->set_default_options();

global $frm_input_masks;
$frm_input_masks = array();

global $frm_settings, $frm_vars;
if((!is_admin() or defined('DOING_AJAX')) and $frm_settings->jquery_css)
    $frm_vars['datepicker_loaded'] = true;

$frm_vars['next_page'] = $frm_vars['prev_page'] = array();
$frm_vars['pro_is_installed'] = true;
   
require($frm_path .'/pro/classes/models/FrmProDb.php');
require($frm_path .'/pro/classes/models/FrmProDisplay.php');
require($frm_path .'/pro/classes/models/FrmProEntry.php');
require($frm_path .'/pro/classes/models/FrmProEntryMeta.php');
require($frm_path .'/pro/classes/models/FrmProField.php');
require($frm_path .'/pro/classes/models/FrmProForm.php');
require($frm_path .'/pro/classes/models/FrmProNotification.php');

global $frmpro_display;
global $frmpro_entry;
global $frmpro_entry_meta;
global $frmpro_field;
global $frmpro_form;

$obj = new FrmProDb();
$frmpro_display     = new FrmProDisplay();
$frmpro_entry       = new FrmProEntry();
$frmpro_entry_meta  = new FrmProEntryMeta();
$frmpro_field       = new FrmProField();
$frmpro_form        = new FrmProForm();
$obj = new FrmProNotification();

// Instansiate Controllers
require($frm_path .'/pro/classes/controllers/FrmProAppController.php');
require($frm_path .'/pro/classes/controllers/FrmProDisplaysController.php');
require($frm_path .'/pro/classes/controllers/FrmProEntriesController.php');
require($frm_path .'/pro/classes/controllers/FrmProFieldsController.php');
require($frm_path .'/pro/classes/controllers/FrmProFormsController.php');
require($frm_path .'/pro/classes/controllers/FrmProStatisticsController.php');


$obj = new FrmProAppController();
$obj = new FrmProDisplaysController();
$obj = new FrmProEntriesController();
$obj = new FrmProFieldsController();
$obj = new FrmProFormsController();
$obj = new FrmProStatisticsController();

FrmProSettingsController::load_hooks();

/*if(is_admin()){
    require($frm_path .'/pro/classes/controllers/FrmProXMLController.php');
    $obj = new FrmProXMLController();
}*/

if (is_multisite()){
//Models
require($frm_path .'/pro/classes/models/FrmProCopy.php');
$obj = new FrmProCopy();
    
//Add options to copy forms and displays
require($frm_path .'/pro/classes/controllers/FrmProCopiesController.php');
$obj = new FrmProCopiesController();
}

// Instansiate Helpers
require($frm_path .'/pro/classes/helpers/FrmProAppHelper.php');
require($frm_path .'/pro/classes/helpers/FrmProDisplaysHelper.php');
require($frm_path .'/pro/classes/helpers/FrmProEntriesHelper.php');
require($frm_path .'/pro/classes/helpers/FrmProEntryMetaHelper.php');
require($frm_path .'/pro/classes/helpers/FrmProFieldsHelper.php');
require($frm_path .'/pro/classes/helpers/FrmProFormsHelper.php');

$obj = new FrmProAppHelper();
$obj = new FrmProDisplaysHelper();
$obj = new FrmProEntriesHelper();
$obj = new FrmProEntryMetaHelper();
$obj = new FrmProFieldsHelper();
$obj = new FrmProFormsHelper();
unset($obj);

// Register Widgets
if(class_exists('WP_Widget')){
    // Include Widgets
    require($frm_path .'/pro/classes/widgets/FrmListEntries.php');
    //require($frm_path .'/pro/classes/widgets/FrmPollResults.php');
    
    add_action('widgets_init', create_function('', 'return register_widget("FrmListEntries");'));
    //add_action('widgets_init', create_function('', 'return register_widget("FrmPollResults");'));
}
