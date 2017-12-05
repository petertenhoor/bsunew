<?php
namespace VisualComposer\Attribute;

/**
 * Class AttributeIterator
 *
 * @package VisualComposer\Attribute
 */
class AttributeIterator extends AttributeAbstract
{

    /**
     * AttributeIterator constructor.
     *
     * @param string $paramName
     * @param string $heading
     * @param null   $description
     * @param array  $iterateAttributes
     * @param array  $params
     */ 
    public function __construct($paramName, $heading, $description = null, array $iterateAttributes = array(), array $params = array())
    {
        $params['custom_param_value'] = $iterateAttributes;
        parent::__construct('iterator', $paramName, $heading, $description, $params);
    }
}