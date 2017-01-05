<?php
namespace Bsu\Shortcode;

use Bsu\BsuTheme;
use VisualComposer\Attribute;

/**
 * Class ZipcodeChecker
 * @package Hbs\Shortcode
 */
class ZipcodeChecker extends AbstractShortcode
{
    const IDENTIFIER = 'zipcodechecker';

    /**
     * ShortcodePostsAgenda constructor.
     */
    protected function __construct()
    {
        parent::__construct(
            self::IDENTIFIER,
            __('Zipcode Checker', BsuTheme::TEXTDOMAIN),
            array()
        );

        $this->template = 'partials/shortcode-zipcode-checker.php';
        $this->addToVisualComposer();
    }
}