<?php
namespace VisualComposer\Attribute;

/**
 * Class AttributePostsSelection
 *
 * @package VisualComposer\Attribute
 */
class AttributePostsSelection extends AttributeAbstract
{
    /**
     * AttributePostsSelection constructor.
     *
     * @param string $paramName
     * @param string $heading
     * @param string $postType
     * @param string $description
     * @param array  $params
     */
    public function __construct($paramName, $heading, $postType, $description = null, array $params = array())
    {
        $params['custom_param_value'] = array(
            'post_type' => $postType
        );
        parent::__construct('posts_selection', $paramName, $heading, $description, $params);
    }
}