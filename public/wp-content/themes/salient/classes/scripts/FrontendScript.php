<?php
namespace Bsu\Script;

use HappyFramework\Script;

/**
 * Class FrontendScript
 *
 * @package Bsu\Script
 */
class FrontendScript extends Script
{
    /**
     * FrontendScript constructor.
     */
    public function __construct()
    {
        // bypass admin
        if (is_admin()) {
            return false;
        }

        // set stylesheet uri
        $this->setStylesheetUri($this->getTemplateUrl() . '/build/master.css');

        // add styles
        $this->addStyles(
            array(
                array(
                    'handle' => 'bsucss',
                    'src'    => get_stylesheet_uri()
                )
            )
        );

        // add paths to head
        add_action('wp_head', array($this, 'addPathsToHead'));

        // add scripts
        $this->addScripts(
            array(
                array(
                    'handle'    => 'bsujs',
                    'src'       => $this->getTemplateUrl() . '/build/master.js',
                    'in_footer' => true
                )
            )
        );
    }
}
