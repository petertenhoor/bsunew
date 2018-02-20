<?php
use Bsu\Option\CookieNotificationOptions;
use Bsu\BsuTheme;
use HappyFramework\Helpers\Formatting;
$cookieNotificationMessage = CookieNotificationOptions::getOption('cookie_notification_message');
$cookieNotificationStatement = CookieNotificationOptions::getOption('cookie_notification_statement');
$cookieNotificationButtonText = CookieNotificationOptions::getOption('cookie_notification_button_text');
//don't render cookie notification if no HTML is entered in WP admin
if (empty($cookieNotificationMessage)) {
    return false;
}
?>

<div class="cookie-notification">
    <div class="cookie-notification__content container">
        <div class="cookie-notification__message">
            <?php echo Formatting::toHtml($cookieNotificationMessage); ?>
        </div>
        <?php if ($cookieNotificationStatement) : ?>
            <div class="cookie-notification__statement">
                <?php echo Formatting::toHtml($cookieNotificationStatement); ?>
                <a href="#" class="cookie-notification__close">
                    <span><?php echo __('Close', BsuTheme::TEXTDOMAIN); ?></span>
                </a>
            </div>
        <?php endif; ?>
        <a href="#" class="cookie-notification__agree">
            <span><?php echo(!empty($cookieNotificationButtonText) ? $cookieNotificationButtonText : __('Yes, I accept', BsuTheme::TEXTDOMAIN)); ?></span>
        </a>
    </div>
</div>