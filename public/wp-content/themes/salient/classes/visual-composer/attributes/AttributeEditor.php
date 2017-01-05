<?php
namespace VisualComposer\Attribute;

/**
 * Class AttributeEditor
 *
 * @package VisualComposer\Attribute
 */
class AttributeEditor extends AttributeAbstract
{

    public function __construct($paramName, $heading, $description = null, $default = '', array $params = array())
    {
        parent::__construct('textarea_html', $paramName, $heading, $description, $params);
    }
}