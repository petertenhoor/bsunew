<?php
/**
 * Iterator Visual Composer Custom Param Type
 *
 * @var array $settings
 * @var array $value
 */

?>

<div class="vc-custom-attribute-iterator">

    <?php /* --  contains single item block data -- */ ?>
    <script type="text/html" class="item-block-data">
        <?php echo json_encode($settings ? array($settings) : array()); ?>
    </script>

    <?php /* --  contains current value -- */ ?>
    <script type="text/html" class="item-current-data">
        <?php echo $value; ?>
    </script>

    <?php /* --  store param data -- */ ?>
    <div class="my_param_block">
        <input name="<?php echo esc_attr($settings['param_name']) ?>"
               class="wpb_vc_param_value wpb-textinput <?php echo esc_attr($settings['param_name']) ?> <?php echo esc_attr($settings['type']) ?>_field"
               type="hidden" value="<?php echo esc_attr($value) ?>"
               data-bind="value: value"/>
    </div>

    <?php /* --  contains the items -- */ ?>
    <div class="items" data-bind="foreach: items">
        <div class="item">
            <div class="item-fields" data-bind="html: html"></div>
            <a href="#" class="button button-delete" data-bind="click: $parent.remove.bind($root, $data)"><?php _e('Delete') ?></a>
        </div>
    </div>

    <?php /* --  loader -- */ ?>
    <div class="spinner" data-bind="style: {'display': loading() === true ? 'inline-block': 'none'}">
        <?php _e('Loading data...') ?>
    </div>

    <a href="#" class="button button-add" data-bind="click: add"><?php _e('Add') ?></a>
</div>