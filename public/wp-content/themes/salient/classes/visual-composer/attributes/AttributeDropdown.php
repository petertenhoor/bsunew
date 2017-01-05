<?php
namespace VisualComposer\Attribute;

/**
 * Class AttributeDropdown
 *
 * @package VisualComposer\Attribute
 */
class AttributeDropdown extends AttributeAbstract
{
    /**
     * AttributeDropdown constructor.
     *
     * @param string $paramName
     * @param string $heading
     * @param null   $description
     * @param array  $values
     * @param string $default
     * @param array  $params
     */
    public function __construct($paramName, $heading, $description = null, array $values = array(), $default = null, array $params = array())
    {
        $params['value'] = array_flip($values);
        $std = $default ? $default : null;
        if (!$std && count($params['value']) > 0) {
            $array_keys = array_keys($params['value']);
            $std = $array_keys[0];
        }
        $params['std'] = $std;
        parent::__construct('dropdown', $paramName, $heading, $description, $params);
    }
}