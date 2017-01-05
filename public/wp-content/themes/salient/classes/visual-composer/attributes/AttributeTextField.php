<?php
namespace VisualComposer\Attribute;

/**
 * Class AttributeTextField
 *
 * @package VisualComposer\Attribute
 */
class AttributeTextField extends AttributeAbstract
{
    /**
     * AttributeTextField constructor.
     *
     * @param string $paramName
     * @param string $heading
     * @param string $description
     * @param array  $args
     */
    public function __construct($paramName, $heading, $description = null, array $args = array())
    {
        parent::__construct('textfield', $paramName, $heading, $description);
    }
}