<?php

/**
 * Class LoginCustomization
 */
class LoginCustomization
{
    /**
     * LoginCustomization constructor.
     */
    public function __construct()
    {
        add_action('login_head', array($this, 'changeLoginLogo'));
        add_filter('login_headerurl', array($this, 'changeLoginLogoUrl'));
        add_filter('login_headertitle', array($this, 'changeLoginLogoUrlTitle'));
    }

    /**
     * Changes login logo
     */
    public static function changeLoginLogo()
    {
        ?>
        <style type="text/css">
            #login h1 a, .login h1 a {
                background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/img/BSUlogo.png) !important;
                padding-bottom: 65px;
                background-size: 100%;
                width: 100%;
                height: auto;
            }
        </style>
        <?php
    }

    /**
     * Changes login logo url to homepage
     * @return string|void
     */
    public static function changeLoginLogoUrl()
    {
        return get_bloginfo('url');
    }

    /**
     * Changes image title of login logo
     * @return string
     */
    function changeLoginLogoUrlTitle()
    {
        return 'Bsu - Bsu simply does it';
    }
}

new LoginCustomization;


