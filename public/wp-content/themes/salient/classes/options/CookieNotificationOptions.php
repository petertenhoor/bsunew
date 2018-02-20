<?php

namespace Bsu\Option;

use Bsu\BsuTheme;
use HappyFramework\Abstracts\AbstractOptionMenuItem;
use HappyFramework\Ajax;

/**
 * Class CookieNotificationOptions
 * @package Bsu\Option
 */
class CookieNotificationOptions extends AbstractOptionMenuItem
{
    public static $okey = 'cookie_options';
    public static $oslug = 'cookie-options';

    /**
     * CookieNotificationOptions constructor.
     */
    protected function __construct()
    {
        parent::__construct(__('Cookie Notification', BsuTheme::TEXTDOMAIN), self::$okey, self::$oslug);
        Ajax::register('fetchCookieNotificationHTML', array($this, 'xhrGetCookieNotificationHTML'), true);
    }

    /**
     * Add section fields
     */
    public function addSectionFields()
    {
        //Cookie Notification
        $cookieNotificationSection = $this->createSection(__('Cookie Notification', BsuTheme::TEXTDOMAIN));
        $cookieNotificationSection->addCheckbox(
            'cookie_notification_enabled',
            __('Enable Cookie notification', BsuTheme::TEXTDOMAIN)
        );
        $cookieNotificationSection->addEditor(
            'cookie_notification_message',
            __('Cookie notification message', BsuTheme::TEXTDOMAIN),
            __('If you leave this field empty, the cookie notification won\'t be shown.', BsuTheme::TEXTDOMAIN)
        );
        $cookieNotificationSection->addEditor(
            'cookie_notification_statement',
            __('Cookie notification statement*', BsuTheme::TEXTDOMAIN),
            __('This text will be shown when the notification is collapsed.', BsuTheme::TEXTDOMAIN)
        );
        $cookieNotificationSection->addTextField(
            'cookie_notification_button_text',
            __('Button text', BsuTheme::TEXTDOMAIN),
            __('Button text', BsuTheme::TEXTDOMAIN),
            'text',
            __('The text for the button that accepts the use of cookies', BsuTheme::TEXTDOMAIN)
        );
    }

    /**
     * Return cookie notification object for XHR request
     */
    public static function xhrGetCookieNotificationHTML()
    {
        ob_start();
        get_template_part('component', 'cookie-notification');
        $returnObj = new \stdClass();
        $returnObj->html = ob_get_clean();
        $returnObj->enabled = self::getOption('cookie_notification_enabled');
        echo json_encode($returnObj);
        die();
    }
}