<?php
/**
 * Code Embed Visual Composer Custom Param Type
 *
 * @var array $settings
 * @var array $value
 */
?>

<div class="vc-custom-attribute-code-embed">

    <?php /* --  contains current value -- */ ?>
    <script type="text/html" class="current-data"><?php echo $value; ?></script>

    <?php /* --  store param data -- */ ?>
    <div class="my_param_block">
        <input name="<?php echo esc_attr($settings['param_name']) ?>"
               class="wpb_vc_param_value wpb-textinput <?php echo esc_attr($settings['param_name']) ?> <?php echo esc_attr($settings['type']) ?>_field"
               type="hidden" value="<?php echo esc_attr($value) ?>"
               data-bind="value: value"/>
    </div>

    <?php /* --  code -- */ ?>
    <textarea cols="30" rows="10" data-bind="value: code, valueUpdate: 'input'"></textarea>

    <?php /* --  preview -- */ ?>
    <!-- ko if: !_.isEmpty(code()) -->
    <div class="wpb_element_label" style="margin-top: 1em;"><?php _e('Preview') ?></div>
    <div class="preview-html" data-bind="html: code"></div>
    <!-- /ko -->
</div>