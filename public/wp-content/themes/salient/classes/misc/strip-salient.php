<?php

/**
 * Class StripSalient
 */
class StripSalient
{
    /**
     * StripSalient constructor.
     */
    public function __construct()
    {
        add_action('admin_menu', array($this, 'removeMenuItems'));
    }

    /**
     * Removes menu items from admin
     */
    public function removeMenuItems()
    {
        remove_menu_page('edit.php?post_type=portfolio');
        remove_menu_page('edit.php?post_type=home_slider');
    }

}

new StripSalient;