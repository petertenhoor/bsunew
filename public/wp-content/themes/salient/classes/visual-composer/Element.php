<?php
namespace VisualComposer;

use VisualComposer\Attribute\AttributeAbstract;

/**
 * Class Element
 *
 * @see     https://wpbakery.atlassian.net/wiki/pages/viewpage.action?pageId=524332
 * @package VisualComposer
 */
class Element
{
    /**
     * Name of the element
     *
     * @var string
     */
    private $name;

    /**
     * Print labels in admin
     * Default true
     *
     * @var boolean
     */
    private $print_labels = true;

    /**
     * Provide a custom class for element
     * Default is uses WPBakeryShortCode
     *
     * @var string
     */
    private $php_class_name = 'VisualComposer\VisualComposerShortcode';

    /**
     * Shortcode tag. For [my_shortcode] shortcode base is my_shortcode
     *
     * @var string
     */
    private $base;

    /**
     * Short description of your element, it will be visible in "Add element" window
     *
     * @var string
     */
    private $description;

    /**
     * CSS class which will be added to the shortcode's content element in the page edit screen in Visual Composer backend edit mode
     *
     * @var string
     */
    private $class;

    /**
     * Set it to false if content element's settings page shouldn't open automatically after adding it to the stage
     *
     * @var bool
     */
    private $show_settings_on_create;

    /**
     * Content elements with greater weight will be rendered first in "Content Elements" grid
     *
     * @var int
     */
    private $weight;

    /**
     * Category which best suites to describe functionality of this shortcode. Default categories: Content, Social, Structure. You can
     * add your own category, simply enter new category title here
     *
     * @var string
     */
    private $category;

    /**
     * Group your params in groups, they will be divided in tabs in the edit element window
     *
     * @var string
     */
    private $group;

    /**
     * Absolute url to javascript file, this js will be loaded in the js_composer edit mode (it allows you to add more functionality
     * to your shortcode in js_composer edit mode)
     *
     * @var string|array
     */
    private $admin_enqueue_js;

    /**
     * Absolute url to css file if you need to add custom css for element block in js_composer constructor mode
     *
     * @var string|array
     */
    private $admin_enqueue_css;

    /**
     * Absolute url to javascript file (useful for storing your custom backbone.js views), this js will be loaded in the js_composer frontend edit mode
     * (it allows you to add more functionality to your shortcode in js_composer frontend edit mode).
     *
     * @var string|array
     */
    private $front_enqueue_js;

    /**
     * Absolute url to css file if you need to load custom css file in the frontend editing mode.
     *
     * @var string|array
     */
    private $front_enqueue_css;

    /**
     * URL or CSS class with icon image
     *
     * @see https://wpbakery.atlassian.net/wiki/pages/viewpage.action?pageId=524332#
     * @var string
     */
    private $icon;

    /**
     * Custom html markup for representing shortcode in visual composer editor
     *
     * @var string
     */
    private $custom_markup;

    /**
     * Set custom backbone.js view controller for this content element
     *
     * @var string
     */
    private $js_view;

    /**
     * Path to shortcode template. This is useful if you want to reassign path of existing content elements through your plugin
     *
     * @var string
     */
    private $html_template;

    /**
     * Enter version number from which content element will be deprecated. It will be moved to the "Deprecated" tab in "Add element" window and notification
     * message will be shown on elements edit page. To hide element from "Add element" all together use 'content_element'=>false
     *
     * @var string
     */
    private $deprecated;

    /**
     * If set to false, content element will be hidden from "Add element" window. It is handy to use this param in pair with 'deprecated' param
     *
     * @var bool
     */
    private $content_element;

    /**
     * If set to true, this element can have child elements
     *
     * @var bool
     */
    private $isContainer = false;

    /**
     * When element is a parent of other element(s) define by key `only` or `except`
     *
     * @see https://wpbakery.atlassian.net/wiki/pages/viewpage.action?pageId=524362
     * @var array
     */
    private $asParent;

    /**
     * When element is a child of other element(s) define by key `only` or `except`
     * See https://wpbakery.atlassian.net/wiki/pages/viewpage.action?pageId=524362
     *
     * @var array
     */
    private $asChild;

    /**
     * Attributes instances
     *
     * @var array
     */
    private $attributes = array();

    /**
     * Element constructor.
     *
     * @param string $shortcode
     * @param string $name
     * @param array  $args
     * @param array  $attributes
     */
    public function __construct($shortcode, $name, array $args = array(), $attributes = array())
    {
        // add base & name
        $this->base = $shortcode;
        $this->name = $name;

        // get reflection of this class
        $reflection = new \ReflectionClass(get_class($this));

        // add class properties
        foreach ($args as $key => $value) {
            if ($reflection->hasProperty($key) === false) {
                continue;
            }

            $prop = $reflection->getProperty($key);
            if ($prop->isPrivate() && $key !== 'params') {
                $this->$key = $value;
            }
        }

        // add attributes
        foreach ($attributes as $attribute) {
            // convert to an Attribute instance
            if (is_array($attribute)) {
                $attribute = call_user_func_array(array('VisualComposer\VisualComposerFactory', 'createAttribute'), $attribute);
            }

            // add when instance is a correct instance of Attribute
            if (is_a($attribute, 'VisualComposer\Attribute\AttributeAbstract')) {
                $this->addAttribute($attribute);
            }
        }
    }

    /**
     * Add attribute instance
     *
     * @param AttributeAbstract $attribute
     */
    public function addAttribute(AttributeAbstract $attribute)
    {
        $this->attributes[] = $attribute;
    }

    /**
     * Convert this class to a `vc_map` ready array
     *
     * @see https://wpbakery.atlassian.net/wiki/pages/viewpage.action?pageId=524332
     * @return array
     */
    public function toArray()
    {
        $args = array();

        // get a reflection of this class
        $reflection = new \ReflectionClass(get_class($this));

        // set class properties
        $excludeProperties = array('attributes');
        foreach ($reflection->getProperties() as $property) {
            $propName = $property->getName();
            $propValue = $this->$propName;
            if (!$property->isPrivate() || in_array($propName, $excludeProperties) || is_null($propValue)) {
                continue;
            }
            $args[$propName] = $propValue;
        }

        // add params property
        if (count($this->attributes) > 0) {
            $args['params'] = array();
            foreach ($this->attributes as $attribute) {
                /* @var Attribute $attribute */
                $params = $attribute->toArray();
                if (empty($params)) {
                    continue;
                }
                $params['holder'] = 'div';

                $args['params'][] = $params;
            }
        }

        return $args;
    }
}