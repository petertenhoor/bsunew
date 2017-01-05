<?php
namespace VisualComposer\Attribute;

/**
 * Class AttributeImage
 *
 * @package VisualComposer\Attribute
 */
class AttributeImage extends AttributeAbstract
{
    /**
     * AttributeImage constructor.
     *
     * @param string $paramName
     * @param string $heading
     * @param string $description
     * @param array  $args
     */
    public function __construct($paramName, $heading, $description = null, array $args = array())
    {
        parent::__construct('attach_image', $paramName, $heading, $description);
    }
}