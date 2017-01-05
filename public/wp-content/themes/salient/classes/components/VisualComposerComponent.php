<?php
namespace Bsu\Component;

/**
 * Class VisualComposerComponent
 *
 * @package Bsu\Component
 */
class VisualComposerComponent extends AbstractComponent
{
    private $defaultShortcodes = array(
        //        'vc_row',
        //        'vc_row_inner',
        //        'vc_column',
        //        'vc_column_inner',
        //'vc_column_text',
        'vc_btn',
        'vc_icon',
        'vc_separator',
        'vc_text_separator',
        'vc_message',
        'vc_facebook',
        'vc_tweetmeme',
        'vc_googleplus',
        'vc_pinterest',
        'vc_toggle',
        'vc_single_image',
        'vc_gallery',
        'vc_images_carousel',
        'vc_tta_tabs',
        'vc_tta_tour',
        'vc_tta_accordion',
        'vc_tta_pageable',
        'vc_tta_section',
        'vc_tabs',
        'vc_tour',
        'vc_tab',
        'vc_accordion',
        'vc_accordion_tab',
        'vc_posts_grid',
        'vc_carousel',
        'vc_posts_slider',
        'vc_widget_sidebar',
        'vc_button',
        'vc_button2',
        'vc_cta_button',
        'vc_cta_button2',
        'vc_video',
        'vc_gmaps',
        'vc_raw_html',
        'vc_raw_js',
        'vc_flickr',
        'vc_progress_bar',
        'vc_pie',
        'vc_round_chart',
        'vc_line_chart',
        'vc_wp_search',
        'vc_wp_meta',
        'vc_wp_recentcomments',
        'vc_wp_calendar',
        'vc_wp_pages',
        'vc_wp_tagcloud',
        //'vc_wp_custommenu',
        'vc_wp_text',
        'vc_wp_posts',
        'vc_wp_links',
        'vc_wp_categories',
        'vc_wp_archives',
        'vc_wp_rss',
        'vc_empty_space',
        'vc_custom_heading',
        'vc_cta',
        'vc_basic_grid',
        'vc_media_grid',
        'vc_masonry_grid',
        'vc_masonry_media_grid',
    );

    /**
     * VisualComposerComponent constructor.
     */
    protected function __construct()
    {
        // remove not used elements from visual composer
        foreach ($this->defaultShortcodes as $code) {
            $this->removeElement($code);
        }

        // remove not used params of element from visual composer
        $this->removeParams('vc_column_text', 'css_animation');
    }

    /**
     * Remove element from visual composer
     *
     * @param string $shortcode
     */
    public function removeElement($shortcode)
    {
        if (function_exists('vc_remove_element')) {
            vc_remove_element($shortcode);
        }
    }

    /**
     * Remove params from visual composer
     *
     * @param string $name
     * @param string $attributeName
     */
    public function removeParams($name, $attributeName)
    {
        if (function_exists('vc_remove_param')) {
            vc_remove_param($name, $attributeName);
        }
    }
}