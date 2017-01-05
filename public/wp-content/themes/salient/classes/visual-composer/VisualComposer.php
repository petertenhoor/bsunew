<?php
namespace VisualComposer;

// attributes

use HappyFramework\Ajax;

require_once 'attributes/AttributeAbstract.php';
require_once 'attributes/AttributeTextField.php';
require_once 'attributes/AttributeTextarea.php';
require_once 'attributes/AttributeDropdown.php';
require_once 'attributes/AttributeColorpicker.php';
require_once 'attributes/AttributeEditor.php';
require_once 'attributes/AttributeImage.php';
require_once 'attributes/AttributeIterator.php';
require_once 'attributes/AttributeCodeEmbed.php';
require_once 'attributes/AttributePostsSelection.php';
require_once 'attributes/AttributeCheckbox.php';
require_once 'attributes/AttributeFile.php';

require_once 'Element.php';
require_once 'VisualComposerFactory.php';
require_once 'VisualComposerShortcode.php';

/**
 * Class VisualComposer
 *
 * @package VisualComposer
 */
class VisualComposer
{
    /**
     * @var VisualComposer
     */
    static $instance;

    /**o
     * VisualComposer constructor.
     */
    protected function __construct()
    {
        // prevent edit visual composer settings in admin
        // since its part of the theme
        if (function_exists('vc_set_as_theme')) {
            vc_set_as_theme();
        }

        $this->addCustomParam('iterator');
        $this->addCustomParam('code_embed');
        $this->addCustomParam('posts_selection');
        $this->addCustomParam('file');
        Ajax::register('getIteratorFieldsItem', array($this, 'ajaxGetIteratorFieldsItem'));
        add_action('init', array($this, 'removeMetaData'), 100);
    }

    /**
     * Add custom attribute by name
     * Add template with `{$name}.php` in the `templates` directory
     * Optional add a javascript file in `js` directory with filename {$name}.js
     *
     * @param string $name
     * @return bool|\WP_Error
     */
    public function addCustomParam($name)
    {
        // bypass when function `vc_add_shortcode_param` does not exists
        if (!function_exists('vc_add_shortcode_param')) {
            return false;
        }

        $templateExists = is_file(sprintf('%1$s/templates/%2$s.php', dirname(__FILE__), $name));
        if (!$templateExists) {
            return new \WP_Error('vc', '`addCustomAttribute` should contain a template file');
        }

        $jsExists = is_file(sprintf('%1$s/js/%2$s.js', dirname(__FILE__), $name));
        $jsFile = $jsExists ? sprintf('//%1$s/%2$s/js/%3$s.js', isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '', str_replace(ABSPATH, '', dirname(__FILE__)), $name) : null;

        return vc_add_shortcode_param($name, function ($settings, $value) use ($name) {
            ob_start();
            include sprintf('%1$s/templates/%2$s.php', dirname(__FILE__), $name);

            return ob_get_clean();
        }, $jsFile);
    }

    /**
     * Returns the *Singleton* instance of this class.
     *
     * @return static
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Remove metadata `generator`
     */
    public function removeMetaData()
    {
        if (function_exists('visual_composer')) {
            remove_action('wp_head', array(visual_composer(), 'addMetaData'));
            remove_action('wp_head', array(visual_composer(), 'addIEMinimalSupport'));
        }
    }

    /**
     * Get ajax iterator fields item
     *
     * @var array $items
     * @var int   $index
     * @return array
     */
    public function ajaxGetIteratorFieldsItem()
    {
        $items = !empty($_REQUEST['items']) ? $_REQUEST['items'] : array();
        $index = !empty($_REQUEST['index']) ? (int)$_REQUEST['index'] : 0;

        $fields = array();
        $this->loadDefaultVcParams();
        foreach ($_REQUEST['items'] as $item) {
            $item['param_name'] = 'blocks_' . $index . '_' . $item['param_name'];
            $field = $this->renderParam($item);
            $fields[] = $field;
        }

        return $fields;
    }

    /**
     * Load default VC params
     *
     * @return bool
     */
    private function loadDefaultVcParams()
    {
        global $vc_params_list;
        if (empty($vc_params_list)) {
            return false;
        }
        $script_url = vc_asset_url('js/params/all.js');
        foreach ($vc_params_list as $param) {
            vc_add_shortcode_param($param, 'vc_' . $param . '_form_field', $script_url);
        }
        do_action('vc_load_default_params');

        return true;
    }

    /**
     * Render element attribute
     *
     * @see Vc_Edit_Form_Fields
     * @param array $param
     * @return string
     */
    public function renderParam(array $param = array())
    {
        $value = !empty($param['value']) ? $param['value'] : '';
        $base = '_self';
        $param['vc_single_param_edit_holder_class'] = array(
            'wpb_el_type_' . $param['type'],
            'vc_wrapper-param-type-' . $param['type'],
            'vc_shortcode-param',
        );
        if (!empty($param['param_holder_class'])) {
            $param['vc_single_param_edit_holder_class'][] = $param['param_holder_class'];
        }
        $param = apply_filters('vc_single_param_edit', $param, $value);
        $output = '<div class="' . implode(' ', $param['vc_single_param_edit_holder_class']) . '" data-vc-ui-element="panel-shortcode-param" data-vc-shortcode-param-name="' . esc_attr($param['param_name']) . '" data-param_type="' . esc_attr($param['type']) . '" data-param_settings="' . esc_attr(json_encode($param)) . '">';
        $output .= (isset($param['heading'])) ? '<div class="wpb_element_label">' . $param['heading'] . '</div>' : '';
        $output .= '<div class="edit_form_line">';
        $value = apply_filters(
            'vc_form_fields_render_field_' . $base . '_' . $param['param_name'] . '_param_value',
            $value,
            $param,
            $param,
            array()
        );
        $param = apply_filters(
            'vc_form_fields_render_field_' . $base . '_' . $param['param_name'] . '_param',
            $param,
            $value,
            $param,
            array()
        );
        $output = apply_filters('vc_edit_form_fields_render_field_' . $param['type'] . '_before', $output);
        $output .= vc_do_shortcode_param_settings_field($param['type'], $param, $value, $base);
        $output_after = '';
        if (isset($param['description'])) {
            $output_after .= '<span class="vc_description vc_clearfix">' . $param['description'] . '</span>';
        }
        $output_after .= '</div></div>';
        $output .= apply_filters('vc_edit_form_fields_render_field_' . $param['type'] . '_after', $output_after);

        return apply_filters(
            'vc_single_param_edit_holder_output',
            $output,
            $param,
            $value,
            $param,
            array()
        );
    }

    /**
     * Get element admin fields
     *
     * @param string $tag
     * @param array  $params
     * @return string
     */
    public function getAdminElementFields($tag, array $params = array())
    {
        $params = (array)stripslashes_deep($params);
        $params = array_map('vc_htmlspecialchars_decode_deep', $params);

        require_once vc_path_dir('EDITORS_DIR', 'class-vc-edit-form-fields.php');
        ob_start();
        $fields = new \Vc_Edit_Form_Fields($tag, $params);
        $fields->render();

        return ob_get_clean();
    }

    /**
     * Private clone method to prevent cloning of the instance of the
     * *Singleton* instance.
     *
     * @return void
     */
    private function __clone()
    {
    }

    /**
     * Private unserialize method to prevent unserializing of the *Singleton*
     * instance.
     *
     * @return void
     */
    private function __wakeup()
    {
    }
}