<?php

$filename = FrmAppHelper::get_unique_key($form->form_key, $wpdb->prefix .'frm_forms',  'form_key') . '.php';
header("Content-Type: application/x-php");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Expires: ".gmdate("D, d M Y H:i:s", mktime(date("H")+2, date("i"), date("s"), date("m"), date("d"), date("Y")))." GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");

echo '<?php ';
?>
if(!defined('ABSPATH')) die('You are not allowed to call this page directly.');

$values['name'] = '<?php echo addslashes($form->name) ?>';
$values['description'] = '<?php echo addslashes($form->description) ?>';
$values['editable'] = <?php echo ($form->editable) ? 1 : 0 ?>;
$values['logged_in'] = <?php echo ($form->logged_in) ? 1 : 0 ?>;
$values['options'] = array();
<?php 
foreach($form->options as $opt => $val){ 
    FrmProFormsHelper::get_template_values($opt, $val, "['{$opt}']");
    unset($opt);
    unset($val);
} ?>

if ($form){
    $form_id = $form->id;
    $frm_form->update($form_id, $values );
    $form_fields = $frm_field->getAll(array('fi.form_id' => $form_id), 'field_order');
    if (!empty($form_fields)){
        foreach ($form_fields as $field)
            $frm_field->destroy($field->id);
    }
}else
    $form_id = $frm_form->create( $values );

<?php foreach ($fields as $field){ 
    $field->field_options = maybe_unserialize($field->field_options);
    $new_key = FrmAppHelper::get_unique_key($field->field_key, $frmdb->fields, 'field_key'); ?>
    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('<?php echo $field->type ?>', $form_id));
$field_values['id'] = <?php echo $field->id ?>;
$field_values['field_key'] = '<?php echo $new_key ?>';
<?php foreach (array('name', 'description', 'type', 'default_value', 'options', 'required', 'field_order') as $col){ ?>
$field_values['<?php echo $col ?>'] = '<?php echo ($col != 'options' and !is_array($field->$col)) ? addslashes($field->$col) : str_replace("'", "\'", maybe_serialize($field->$col)); ?>';
<?php } ?>
<?php foreach($field->field_options as $opt_key => $field_opt){ 
        if($opt_key == 'custom_html' and $field_opt == FrmFieldsHelper::get_default_html($field->type)) continue; ?>
$field_values['field_options']['<?php echo $opt_key ?>'] = '<?php echo (is_array($field_opt) ? str_replace("'", "\'", maybe_serialize($field_opt)) : addslashes($field_opt)) ?>';
<?php } ?>
$frm_field->create( $field_values );

<?php } ?>