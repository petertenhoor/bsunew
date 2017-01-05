<?php
namespace VisualComposer\Attribute;

/**
 * Class AttributeColorpicker
 *
 *@package VisualComposer\Attribute
 */
class AttributeColorpicker extends AttributeAbstract
{

    /**
     * AttributeColorpicker constructor.
     *
     * @param string $paramName
     * @param string $heading
     * @param null  $description
     * @param string $default
     * @param array $params
     */
    public function __construct($paramName, $heading, $description = null, $default = '', array $params = array())
    {
        $params['value'] = $default;
        parent::__construct('colorpicker', $paramName, $heading, $description, $params);
    }
}