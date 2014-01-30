<div id="frm_logic_<?php echo $email_key ?>_<?php echo $meta_name ?>" class="frm_logic_row">
<select name="notification[<?php echo $email_key ?>][conditions][<?php echo $meta_name ?>][hide_field]" onchange="frmGetFieldValues(this.value,<?php echo $email_key ?>,<?php echo $meta_name ?>,'<?php echo (isset($field['type'])) ? $field['type'] : ''; ?>','notification[<?php echo $email_key ?>][conditions][<?php echo $meta_name ?>][hide_opt]')">
    <option value=""><?php _e('Select Field', 'formidable') ?></option>
    <?php foreach ($form_fields as $ff){ 
        if(is_array($ff)) $ff = (object)$ff;
        $selected = ($ff->id == $condition['hide_field'])?' selected="selected"':''; ?>
    <option value="<?php echo $ff->id ?>"<?php echo $selected ?>><?php echo FrmAppHelper::truncate($ff->name, 30); ?></option>
    <?php
        unset($ff);
        } ?>
</select>
<?php _e('is', 'formidable'); ?>

<select name="notification[<?php echo $email_key ?>][conditions][<?php echo $meta_name ?>][hide_field_cond]">
    <option value="==" <?php selected($condition['hide_field_cond'], '==') ?>><?php _e('equal to', 'formidable') ?></option>
    <option value="!=" <?php selected($condition['hide_field_cond'], '!=') ?>><?php _e('NOT equal to', 'formidable') ?> &nbsp;</option>
    <option value=">" <?php selected($condition['hide_field_cond'], '>') ?>><?php _e('greater than', 'formidable') ?></option>
    <option value="<" <?php selected($condition['hide_field_cond'], '<') ?>><?php _e('less than', 'formidable') ?></option>
</select>

<span id="frm_show_selected_values_<?php echo $email_key; ?>_<?php echo $meta_name ?>" class="no_taglist">
<?php 
    if ($condition['hide_field'] and is_numeric($condition['hide_field'])){
        global $frm_field;
        $new_field = $frm_field->getOne($condition['hide_field']);   
    }
    
    $val = isset($condition['hide_opt']) ? $condition['hide_opt'] : '';
    if(!isset($field))
        $field = array('hide_opt' => array($meta_name => $val));
    $field_name = 'notification['. $email_key .'][conditions]['. $meta_name .'][hide_opt]';
    
    require(FrmAppHelper::plugin_path() .'/pro/classes/views/frmpro-fields/field-values.php');
?>
</span>
<a class="frm_remove_tag frm_icon_font" data-removeid="frm_logic_<?php echo $email_key ?>_<?php echo $meta_name ?>"></a>
<a class="frm_add_tag frm_icon_font frm_add_form_logic" data-emailkey="<?php echo $email_key ?>"></a>
</div>