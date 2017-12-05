<?php

namespace VisualComposer\Attribute;


/**
 * Class AttributeAbstract
 *
 * @package VisualComposer\Attribute
 */
abstract class AttributeAbstract
{
    public $type;
    public $holder;
    public $class;
    public $heading;
    public $param_name;
    public $value;
    public $description;
    public $admin_label;
    public $dependency;
    public $edit_field_class;
    public $weight;
    public $group;
    public $custom_param_value;
    public $std;

    /**
     * AttributeAbstract constructor.
     *
     * @param       $type
     * @param       $paramName
     * @param       $heading
     * @param null  $description
     * @param array $params
     */
    public function __construct($type, $paramName, $heading, $description = null, array $params = array())
    {
        $this->type = $type;
        $this->heading = $heading;
        $this->param_name = $paramName;
        $this->description = $description;

        // get a reflection of this class
        $reflection = new \ReflectionClass(get_class($this));

        // add class properties
        foreach ($params as $key => $value) {
            if (!$reflection->hasProperty($key)) {
                continue;
            }
            $prop = $reflection->getProperty($key);
            if ($prop->isPublic()) {
                $this->$key = $value;
            }
        }
    }

    /**
     * Convert attribute to array which can be used in `vc_map`
     *
     * @return array
     */
    public function toArray()
    {
        $args = array();

        // get a reflection of this class
        $reflection = new \ReflectionClass(get_class($this));

        // set class properties
        $excludeProperties = array();
        foreach ($reflection->getProperties() as $property) {
            $propName = $property->getName();
            $propValue = $this->$propName;
            if (!$property->isPublic() || empty($propValue) || in_array($propName, $excludeProperties)) {
                continue;
            }
            $args[$propName] = $propValue;
        }

        return $args;
    }
}