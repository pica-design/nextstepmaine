<div class="frm_form_fields">
<?php
global $frm_vars;
if(!isset($frm_vars['star_loaded']) or !is_array($frm_vars['star_loaded']))
    $frm_vars['star_loaded'] = array();
if(!$frm_vars['forms_loaded'] or empty($frm_vars['forms_loaded']))
    $frm_vars['forms_loaded'][] = true;

$rand = FrmProAppHelper::get_rand(3);
$name = $field->id . $rand;
if(in_array($name, $frm_vars['star_loaded'])){
    $rand = FrmProAppHelper::get_rand(3);
    $name = $field->id . $rand;
}
$frm_vars['star_loaded'][] = $name;   

$field->options = maybe_unserialize($field->options);
$max = max($field->options);
$class = '';

if($stat != floor($stat)){
    if(!in_array('split', $frm_vars['star_loaded']))
        $frm_vars['star_loaded'][] = 'split';
    $factor = 4;
    $class = " {split:$factor}";
    $max = $max * $factor;
    $stat = round($stat * $factor);
}

for($i=1; $i<=$max; $i++){
    $checked = (round($stat) == $i) ? 'checked="checked"' : '';
    ?>
<input type="radio" name="item_meta[<?php echo $name ?>]" value="<?php echo isset($factor) ? ($i/$factor) : $i; ?>" <?php echo $checked ?> class="star<?php echo $class ?>" disabled="disabled" />
<?php } ?>
</div>