<?php

namespace VisualComposer;

use VisualComposer\Attribute\AttributeCheckbox;
use VisualComposer\Attribute\AttributeCodeEmbed;
use VisualComposer\Attribute\AttributeColorpicker;
use VisualComposer\Attribute\AttributeDropdown;
use VisualComposer\Attribute\AttributeEditor;
use VisualComposer\Attribute\AttributeFile;
use VisualComposer\Attribute\AttributeImage;
use VisualComposer\Attribute\AttributeIterator;
use VisualComposer\Attribute\AttributePostsSelection;
use VisualComposer\Attribute\AttributeTextArea;
use VisualComposer\Attribute\AttributeTextField;

/**
 * Class VisualComposerFactory
 *
 * @package VisualComposer
 */
class VisualComposerFactory
{
    static $instance;

    /**
     * VisualComposerFactory constructor.
     */
    protected function __construct()
    {
    }

    /**
     * Returns the *Singleton* instance of this class.
     *
     * @return static
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }


    /**
     * Create element
     *
     * @param string $shortcode
     * @param string $name
     * @param array  $args
     * @param array  $attributes
     * @return Element
     */
    public function createElement($shortcode, $name, $args = array(), $attributes = array())
    {
        return new Element($shortcode, $name, $args, $attributes);
    }

    /**
     * Create attribute iterator
     *
     * @param string $name
     * @param string $heading
     * @param null   $description
     * @param array  $iterateAttributes
     * @param array  $args
     * @return AttributeIterator
     */
    public function createAttributeIterator($name, $heading, $description = null, array $iterateAttributes = array(), array $args = array())
    {
        return new AttributeIterator($name, $heading, $description, $iterateAttributes, $args);
    }

    /**
     * Create textfield attribute
     *
     * @param string      $name
     * @param string      $heading
     * @param null|string $description
     * @param array       $args
     * @return AttributeTextField
     */
    public function createAttributeTextField($name, $heading, $description = null, array $args = array())
    {
        return new AttributeTextField($name, $heading, $description, $args);
    }

    /**
     * Create textfield attribute
     *
     * @param string      $name
     * @param string      $heading
     * @param null|string $description
     * @param array       $args
     * @return AttributeTextField
     */
    public function createAttributeTextArea($name, $heading, $description = null, array $args = array())
    {
        return new AttributeTextArea($name, $heading, $description, $args);
    }

    /**
     * Create code embed attribute
     *
     * @param string      $name
     * @param string      $heading
     * @param null|string $description
     * @param array       $args
     * @return AttributeTextField
     */
    public function createAttributeCodeEmbed($name, $heading, $description = null, array $args = array())
    {
        return new AttributeCodeEmbed($name, $heading, $description, $args);
    }

    /**
     * Create file attribute
     *
     * @param string      $name
     * @param string      $heading
     * @param null|string $description
     * @param array       $args
     * @return AttributeTextField
     */
    public function createAttributeFile($name, $heading, $description = null, array $args = array())
    {
        return new AttributeFile($name, $heading, $description, $args);
    }

    /**
     * Create dropdown attribute
     *
     * @param string      $name
     * @param string     $heading
     * @param null|string $description
     * @param array       $values
     * @param string      $default
     * @param array       $args
     * @return AttributeDropdown
     */
    public function createAttributeDropdown($name, $heading, $description = null, array $values = array(), $default = null, array $args = array())
    {
        return new AttributeDropdown($name, $heading, $description, $values, $default, $args);
    }

    /**
     * Create editor attribute
     *
     * @param string      $name
     * @param string      $heading
     * @param null|string $description
     * @param array       $args
     * @return AttributeEditor
     */
    public function createAttributeEditor($name, $heading, $description = null, array $args = array())
    {
        return new AttributeEditor($name, $heading, $description, $args);
    }

    /**
     * Create checkbox attribute
     *
     * @param string      $name
     * @param string      $heading
     * @param null|string $description
     * @param array       $args
     * @return AttributeEditor
     */
    public function createAttributeCheckbox($name, $heading, $description = null, array $args = array())
    {
        return new AttributeCheckbox($name, $heading, $description, $args);
    }

    /**
     * Create posts selection attribute
     *
     * @param string $name
     * @param string $heading
     * @param string $postType
     * @param null   $description
     * @param array  $args
     * @return AttributePostsSelection
     */
    public function createAttributePostsSelection($name, $heading, $postType, $description = null, array $args = array())
    {
        return new AttributePostsSelection($name, $heading, $postType, $description, $args);
    }

    /**
     * Create colorpicker attribute
     *
     * @param string      $name
     * @param string      $heading
     * @param null|string $description
     * @param string      $default
     * @param array       $args
     * @return AttributeColorpicker
     */
    public function createAttributeColorpicker($name, $heading, $description = null, $default = '', array $args = array())
    {
        return new AttributeColorpicker($name, $heading, $description, $default, $args);
    }

    /**
     * Create image attribute
     *
     * @param string      $name
     * @param string      $heading
     * @param null|string $description
     * @param array       $args
     * @return AttributeImage
     */
    public function createAttributeImage($name, $heading, $description = null, array $args = array())
    {
        return new AttributeImage($name, $heading, $description, $args);
    }

    /**
     * Private clone method to prevent cloning of the instance of the
     * *Singleton* instance.
     *
     * @return void
     */
    private function __clone()
    {
    }

    /**
     * Private unserialize method to prevent unserializing of the *Singleton*
     * instance.
     *
     * @return void
     */
    private function __wakeup()
    {
    }
}