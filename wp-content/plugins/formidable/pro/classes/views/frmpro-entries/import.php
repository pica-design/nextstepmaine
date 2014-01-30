<div id="form_entries_page" class="wrap">
    <div class="frmicon icon32"><br/></div>
    <h2><?php _e('Import Entries', 'formidable') ?></h2>
    
    <?php include(FrmAppHelper::plugin_path() .'/classes/views/shared/errors.php'); ?>
    
    <?php if($step == 'import'){ ?>
        
        <div class="with_frm_style" id="frm_import_message" style="margin:15px 0;line-height:2.5em;"><span class="frm_message" style="padding:7px;"><?php printf(__('The next 250 of the remaining %1$s entries are importing.', 'formidable'), $left) ?> <a id="frm_import_link" class="button-secondary" href="javascript:frmImportCsv(<?php echo $form_id ?>)"><?php _e('Import Now', 'formidable') ?></a></span></div>
<script type="text/javascript">
/*<![CDATA[*/
__FRMURLVARS="<?php echo $url_vars ?>";
setTimeout( "frmImportCsv(<?php echo $form_id ?>)", 250 );
/*]]>*/
</script>
        
    <?php }else{ ?>
    <form enctype="multipart/form-data" method="post">
        <input type="hidden" name="frm_action" value="import" />
        <?php //wp_nonce_field('frm_import_csv_nonce', 'frm_import_csv'); ?>
        
        
        <div id="poststuff" class="metabox-holder">
            <div id="post-body">
            <div id="post-body-content">
                <div class="postbox ">
                <div class="handlediv"><br/></div><h3 class="hndle"><span><?php echo __('Step', 'formidable') . ' '. $step; ?></span></h3>
                <div class="inside">
                    
                <?php if($step == 'One'){ ?>
                <input type="hidden" name="step" value="Two" />
                <table class="form-table">
                    <tr class="form-field">
                        <th scope="row"><?php _e('Select CSV', 'formidable'); ?></th>
                        <td>
                            <input type="file" name="csv" id="csv" value="" />
                            <?php if($csvs){ ?>
                                <span style="padding:0 20px"><?php _e('or', 'formidable'); ?></span>
                                <select name="csv">
                                    <option value="">&mdash; <?php _e('Select previously uploaded CSV', 'formidable') ?> &mdash;</option>
                                <?php foreach($csvs as $c){ ?>
                                    <option value="<?php echo $c->ID ?>"><?php echo $c->post_title ?></option>
                                <?php } ?>
                                </select>
                            <?php } ?>
                        </td>
                    </tr>
                    
                    <tr class="form-field">
                        <th scope="row"><?php _e('CSV Delimiter', 'formidable'); ?></th>
                        <td>
                            <input type="text" name="csv_del" value="<?php echo esc_attr($csv_del) ?>" />
                        </td>
                    </tr>

                    <tr class="form-field">
                        <th scope="row"><?php _e('Import Into Form', 'formidable'); ?></th>
                        <td><?php FrmFormsHelper::forms_dropdown( 'form_id', $form_id, true, false); ?></td>
                    </tr>
                    
                </table>
                <?php }else if($step == 'Two'){ ?>
                    <input type="hidden" name="step" value="import" />
                    <input type="hidden" name="csv" value="<?php echo $media_id ?>" />
                    <input type="hidden" name="row" value="<?php echo $row ?>" />
                    <input type="hidden" name="form_id" value="<?php echo $form_id ?>" />
                    <input type="hidden" name="csv_del" value="<?php echo esc_attr($csv_del) ?>" />
                    <table class="form-table">
                        <thead>
                        <tr class="form-field">
                            <th><b><?php _e('CSV header' ,'formidable') ?></b></th>
                            <th><b><?php _e('Sample data' ,'formidable') ?></b></th>
                            <th><b><?php _e('Corresponding Field' ,'formidable') ?></b></th>
                        </tr>
                        </thead>
                        <?php foreach($headers as $i => $header){ ?>
                        <tr class="form-field">
                            <td><?php echo htmlspecialchars($header) ?></td>
                            <td><?php if(isset($example[$i])){ ?>
                                <span class="howto"><?php echo htmlspecialchars($example[$i]) ?></span>
                            <?php } ?></td>
                            <td>
                                <select name="data_array[<?php echo $i ?>]" id="mapping_<?php echo $i ?>">
                                    <option value=""></option>
                                    <?php foreach ($fields as $field){ 
                                        if(in_array($field->type, array('break','divider','captcha','html')))
                                            continue;
                                    ?>
                                        <option value="<?php echo $field->id ?>" <?php selected(strip_tags($field->name), htmlspecialchars($header)) ?>><?php echo FrmAppHelper::truncate($field->name, 50) ?></option>
                                    <?php
                                        unset($field);
                                    }
                                    ?>
                                    <option value="post_id"><?php _e('Post ID', 'formidable') ?></option>
                                    <option value="created_at" <?php selected(__('Timestamp', 'formidable'), strtolower(htmlspecialchars($header))) . selected(__('created at', 'formidable'), strtolower(htmlspecialchars($header))) . selected('created_at', $header) ?>><?php _e('created at', 'formidable') ?></option>
                                    <option value="user_id" <?php selected(__('created by', 'formidable'), strtolower(htmlspecialchars($header))) . selected('user_id', $header) ?>><?php _e('Created by', 'formidable') ?></option>
                                    <option value="updated_at" <?php selected(__('last updated', 'formidable'), strtolower(htmlspecialchars($header))) . selected(__('updated at', 'formidable'), strtolower(htmlspecialchars($header))) . selected('updated_at', $header) ?>><?php _e('Updated at', 'formidable') ?></option>
                                    <option value="updated_by" <?php selected(__('updated by', 'formidable'), strtolower(htmlspecialchars($header))) . selected('updated_by', $header) ?>><?php _e('Updated by', 'formidable') ?></option>
                                    <option value="ip" <?php selected('ip', strtolower($header)) ?>><?php _e('IP Address', 'formidable') ?></option>
                                    <option value="item_key" <?php selected(__('Entry Key', 'formidable'), htmlspecialchars($header)) . selected('key', strtolower(htmlspecialchars($header))); ?>><?php _e('Entry Key', 'formidable') ?></option>
                                </select>
                            </td>
                        </tr>
                        <?php } ?>
                      </table>
                <?php } ?>
                <p class="submit"><input type="submit" value="<?php echo $next_step ?>" class="button-primary" /></p>
                </div>
                </div>
    
            </div>
            </div>
        </div>
    </form>
    <?php } ?>

</div>