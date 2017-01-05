<?php
namespace VisualComposer\Attribute;

/**
 * Class AttributeCodeEmbed
 *
 * @package VisualComposer\Attribute
 */
class AttributeCodeEmbed extends AttributeAbstract
{
    /**
     * AttributeCodeEmbed constructor.
     *
     * @param string $paramName
     * @param string $heading
     * @param string $description
     * @param array  $args
     */
    public function __construct($paramName, $heading, $description = null, array $args = array())
    {
        parent::__construct('code_embed', $paramName, $heading, $description);
    }
}