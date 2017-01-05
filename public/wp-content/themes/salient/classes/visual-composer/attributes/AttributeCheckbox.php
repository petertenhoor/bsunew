<?php
namespace VisualComposer\Attribute;

/**
 * Class AttributeCheckbox
 *
 * @package VisualComposer\Attribute
 */
class AttributeCheckbox extends AttributeAbstract
{
    /**
     * AttributeCheckbox constructor.
     *
     * @param string $paramName
     * @param string $heading
     * @param string $description
     * @param array  $args
     */
    public function __construct($paramName, $heading, $description = null, array $args = array())
    {
        parent::__construct('checkbox', $paramName, $heading, $description, $args);
    }
}