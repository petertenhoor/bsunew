<?php

namespace Bsu\Shortcode;

use HappyFramework\Helpers\Log;
use VisualComposer\Element;
use VisualComposer\VisualComposerFactory;
use Bsu\BsuTheme;

/**
 * Class AbstractShortcode
 *
 * @package Bsu\Shortcode
 */
abstract class AbstractShortcode extends \HappyFramework\Abstracts\AbstractShortcode
{
    /**
     * @var Element
     */

    private $vcElement;

    /**
     * AbstractShortcode constructor.
     *
     * @param string $identifier
     * @param string $label
     * @param array  $attributes
     */
    protected function __construct($identifier, $label, array $attributes = array())
    {
        parent::__construct($identifier, $label, $attributes);
    }

    /**
     * Get attributes by given shortcode type
     *
     * @param string $type
     * @return array
     */
    public static function getAttributesByType($type)
    {
        $attributes = array();
        foreach (BsuTheme::$shortcodes as $shortcode) {
            /* @var AbstractShortcode $shortcode */
            if ($shortcode->identifier === $type) {
                $attributes = $shortcode->attributes;
                break;
            }
        }

        return $attributes;
    }

    /**
     * Add shortcode to visual composer only for given post types
     *
     * @see https://wpbakery.atlassian.net/wiki/pages/viewpage.action?pageId=524332
     * @param array $posttypes  List of post types
     * @param array $attributes List of attached attributes also known by parameter `params`
     * @param array $args       Default `base` and `name` is included. To extend properties use this array
     * @return \VisualComposer\Element
     */
    public function addToVisualComposerForPosttypes(array $posttypes, array $attributes = array(), array $args = array())
    {
        $insertVisualComposer = false;

        $args = array_merge($args, array(
            'group'  => 'post_type_shortcodes',
            'weight' => 999
        ));

        // catch current post type match
        foreach ($posttypes as $posttype) {
            if (
                (isset($_GET['post']) && get_post_type($_GET['post']) === $posttype) || // check for edit screen
                (isset($_GET['post_type']) && $_GET['post_type'] === $posttype) || // check for new screen
                (empty($_GET)) // callback from visual composer
            ) {
                $insertVisualComposer = true;
                break;
            }
        }

        if ($insertVisualComposer) {
            $this->addToVisualComposer($attributes, $args);
        }
    }

    /**
     * Add shortcode to visual composer
     *
     * @see https://wpbakery.atlassian.net/wiki/pages/viewpage.action?pageId=524332
     * @param array $attributes List of attached attributes also known by parameter `params`
     * @param array $args       Default `base` and `name` is included. To extend properties use this array
     * @return \VisualComposer\Element
     */
    public function addToVisualComposer(array $attributes = array(), array $args = array())
    {
        $args = array_merge(array('icon' => sprintf('vc-icon-%1$s', $this->identifier)), $args);
        $this->vcElement = VisualComposerFactory::getInstance()->createElement($this->identifier, $this->label, $args, $attributes);
        if (function_exists('vc_map')) {
            vc_map($this->vcElement->toArray());
        }
    }

    /**
     * Convert base64 encoded string to object
     *
     * @param string $data
     * @return bool|\stdClass
     */
    public function base64ToObject($data)
    {
        $obj = false;
        $data = base64_decode($data);
        if (!empty($data)) {
            $obj = json_decode($data);
        }

        return $obj;
    }
}
