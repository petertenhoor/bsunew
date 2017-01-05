<?php
namespace VisualComposer\Attribute;

/**
 * Class AttributeTextArea
 *
 * @package VisualComposer\Attribute
 */
class AttributeTextArea extends AttributeAbstract
{
    /**
     * AttributeTextArea constructor.
     *
     * @param string $paramName
     * @param string $heading
     * @param string $description
     * @param array  $args
     */
    public function __construct($paramName, $heading, $description = null, array $args = array())
    {
        parent::__construct('textarea', $paramName, $heading, $description);
    }
}