<?php
namespace VisualComposer\Attribute;

/**
 * Class AttributeFile
 *
 * @package VisualComposer\Attribute
 */
class AttributeFile extends AttributeAbstract
{

    public function __construct($paramName, $heading, $description = null, $default = '', array $params = array())
    {
        parent::__construct('file', $paramName, $heading, $description, $params);
    }
}