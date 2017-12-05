<?php
namespace Bsu\Script;

use HappyFramework\Script;

/**
 * Class AdminScript
 *
 * @package Bsu\Script
 */
class AdminScript extends Script
{
    /**
     * AdminScript constructor.
     */
    public function __construct()
    {
        // bypass frontend
        if (!is_admin()) {
            return false;
        }

        // add paths to head
        add_action('wp_head', array($this, 'addPathsToHead'));

        // add styles
        $this->addStyles(
            array(
                array(
                    'handle' => 'bsucss',
                    'src'    => $this->getTemplateUrl() . '/build/admin.css'
                )
            )
        );

        // add scripts
        $this->addScripts(
            array(
                array(
                    'handle'    => 'bsujs',
                    'src'       => $this->getTemplateUrl() . '/build/admin.js',
                    'in_footer' => true
                )
            )
        );
    }
}