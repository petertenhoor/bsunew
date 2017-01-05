<?php

namespace VisualComposer;

if (!class_exists('\WPBakeryShortCode')) {
    return false;
}

/**
 * Class VisualComposerShortcode
 * Can be overridden by param settings `php_class_name`
 *
 * @package Zge\Shortcode
 */
class VisualComposerShortcode extends \WPBakeryShortCode
{
    public function contentAdmin($atts, $content = null)
    {
        $printLabels = !empty($this->settings['print_labels']) && $this->settings['print_labels'] === true;
        if ($printLabels) {
            $width = $custom_markup = '';
            $shortcode_attributes = array('width' => '1/1');
            if (isset($this->settings['params'])) {
                foreach ($this->settings['params'] as $param) {
                    if ('content' !== $param['param_name']) {
                        $shortcode_attributes[$param['param_name']] = isset($param['value']) ? $param['value'] : null;
                    } elseif ('content' === $param['param_name'] && null === $content) {
                        $content = $param['value'];
                    }
                }
            }
            extract(shortcode_atts($shortcode_attributes, $atts));
            $elem = $this->getElementHolder($width);
            $inner = $this->outputTitle($this->settings['name']);
            if (isset($this->settings['params'])) {
                foreach ($this->settings['params'] as $param) {
                    // bypass 'code' since the output is base64 encoded
                    if ($param['param_name'] === 'code') {
                        continue;
                    }
                    $param_value = isset(${$param['param_name']}) ? ${$param['param_name']} : '';
                    if (is_array($param_value)) {
                        reset($param_value);
                        $first_key = key($param_value);
                        $param_value = $param_value[$first_key];
                    }
                    $param['admin_label'] = true;
                    $param['heading'] = $param['param_name'];
                    unset($param['holder']);
                    $inner .= $this->singleParamHtmlHolder($param, $param_value);
                }
            }

            $output = str_ireplace('%wpb_element_content%', $inner, $elem);

        } else {
            $output = parent::contentAdmin($atts, $content);
        }

        return $output;
    }
}