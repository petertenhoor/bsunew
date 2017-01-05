<?php

namespace Bsu;

//Post types
require_once 'post-types/AbstractPostType.php';
require_once 'post-types/JobPostType.php';

//Options

//Scripts
require_once 'scripts/AdminScript.php';
require_once 'scripts/FrontendScript.php';

//Components
require_once 'components/AbstractComponent.php';
require_once 'components/VisualComposerComponent.php';

//Shortcodes
require_once 'shortcodes/AbstractShortcode.php';
require_once 'shortcodes/ZipcodeCheckerShortcode.php';
require_once 'shortcodes/DownloadBlockShortcode.php';
require_once 'shortcodes/QuoteBlockShortcode.php';
require_once 'shortcodes/ContactBlockShortcode.php';

//Widgets

//Misc
require_once 'visual-composer/VisualComposer.php';
require_once 'MetaboxesPost.php';
require_once 'misc/login-styling.php';
require_once 'misc/strip-salient.php';

use Bsu\PostType;
use Bsu\Shortcode\ContactBlockShortcode;
use Bsu\Shortcode\QuoteBlockShortcode;
use Bsu\Shortcode\ShortcodeDownloadBlock;
use Bsu\Shortcode\ZipcodeChecker;
use HappyFramework\Abstracts\AbstractTheme;
use HappyFramework\TinyMce\TinyMceFilterInit;
use VisualComposer\VisualComposer;

/**
 * Class BsuTheme
 *
 * @package Bsu
 */
class BsuTheme extends AbstractTheme
{
    const TEXTDOMAIN = 'salient';

    /**
     * Theme color palette
     *
     * @var array
     */
    public static $colors = array(
        'blue'   => '#4aa4d5',
        'purple' => '#ca3d7f',
        'gray3'  => '#f4f4f4',
        'white'  => '#ffffff',
    );

    /**
     * @var VisualComposer|null
     */
    public static $visualComposer;

    protected function __construct()
    {
        parent::__construct();

        // initialize visual composer & custom forms
        self::$visualComposer = VisualComposer::getInstance();

        // set partials path
        $this->setPartialsPath(get_template_directory() . '/partials');

        // remove default emojicons
        $this->removeWpEmoji();

        // add shortcodes when theme textdomain is loaded
        add_action('theme_textdomain_loaded', array($this, 'addShortcodes'));

        // initialize theme
        $this->init(self::TEXTDOMAIN, self::TEXTDOMAIN, get_template_directory() . '/lang');

        // set admin-template path
        $this->setAdminTemplatesPath(get_template_directory() . '/admin-templates');

        // tinymce filter init
        TinyMceFilterInit::getInstance()->setColors(self::$colors);
        TinyMceFilterInit::getInstance()->setMenuBar(array(TinyMceFilterInit::MENUBAR_ITEM_TYPE_INSERT));

        // set post types
        $this->setPostTypes(
            array(
                'jobs' => PostType\JobPostType::getInstance()
            )
        );

        // set scripts
        $this->setScripts(
            array(
                'admin'    => new Script\AdminScript(),
                'frontend' => new Script\FrontendScript(),
            )
        );
    }

    /**
     * Add shortcodes after theme textdomain is loaded
     */
    public function addShortcodes()
    {
        $this->setShortcodes(
            array(
                'zipcodeChecker' => ZipcodeChecker::getInstance(),
                'downloadBlock'  => ShortcodeDownloadBlock::getInstance(),
                'quoteBlock'     => QuoteBlockShortcode::getInstance(),
                'contactBlock'   => ContactBlockShortcode::getInstance(),
            )
        );
    }

    /**
     * Set image sizes
     */
    public function setImageSizes()
    {

    }

    /**
     * Init menu's
     */
    public function initMenu()
    {

    }
}
