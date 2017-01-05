<?php
/**
 * File Visual Composer Custom Param Type
 *
 * @var array $settings
 * @var array $value
 */
?>

<div class="vc-custom-attribute-file">

    <?php /* --  contains current value -- */ ?>
    <script type="text/html" class="current-data"><?php echo $value; ?></script>

    <?php /* --  store param data -- */ ?>
    <div class="my_param_block">
        <input name="<?php echo esc_attr($settings['param_name']) ?>"
               class="wpb_vc_param_value wpb-textinput <?php echo esc_attr($settings['param_name']) ?> <?php echo esc_attr($settings['type']) ?>_field"
               type="hidden" value="<?php echo esc_attr($value) ?>"
               data-bind="value: value"/>
    </div>

    <?php /* --  image selection -- */ ?>
    <div class="attribute-file-filename" data-bind="visible: image.showDeleteButton, text: image.fileName"></div>
    <a class="button button-delete" href="#"
       data-bind="visible: image.showDeleteButton, click: image.removeAttachment"><?php _e('Delete PDF file', \Bsu\BsuTheme::TEXTDOMAIN) ?></a>
    <a class="button button-add" href="#"
       data-bind="visible: image.showSelectButton, click: image.addAttachment"><?php _e('Select PDF file', \Bsu\BsuTheme::TEXTDOMAIN) ?></a>
</div>