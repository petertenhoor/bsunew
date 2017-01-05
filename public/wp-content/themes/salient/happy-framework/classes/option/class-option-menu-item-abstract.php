<?php
namespace HappyFramework\Abstracts;

use HappyFramework\Abstracts\AbstractTheme as Theme;
use HappyFramework\Interfaces\iOptionMenuItem;
use HappyFramework\Options\OptionSubMenuItem;
use HappyFramework\Options\SettingsSection;

/**
 * Class OptionMenuItem
 *
 * @package HappyFramework\Options
 */
abstract class AbstractOptionMenuItem implements IOptionMenuItem
{
    const NOTICE_TYPE_SUCCESS = 'success';
    const NOTICE_TYPE_ERROR   = 'error';

    public  $callback;
    public  $slug;
    public  $title;
    public  $optionKey;
    public  $optionGroup;
    private $customIcon;
    private $pageHookName;

    /**
     * AbstractOptionMenuItem constructor.
     *
     * @param string $title
     * @param string $optionKey
     * @param string $slug
     * @param string $callback
     */
    protected function __construct($title, $optionKey, $slug, $callback = '')
    {
        $this->title = $title;
        $this->optionKey = $optionKey;
        $this->optionGroup = sanitize_html_class($this->optionKey) . '-group';
        $this->slug = $slug;
        $this->callback = $callback;

        add_action('admin_init', array($this, 'registerOption'));
        add_action('admin_menu', array($this, 'addAdminMenu'));
        add_action('update_option_' . $this->optionKey, array($this, 'updateOptions'), 99, 2);
        add_filter('pre_update_option_' . $this->optionKey, array($this, 'beforeUpdateOptions'), 99, 2);
        add_action(sprintf('load-%s', $this->optionKey), array($this, 'showNotices'), 1);
        add_action(sprintf('load-%s', $this->optionKey), array($this, 'addSectionFields'));
    }

    /**
     * Get instance
     *
     * @return static
     */
    final static public function getInstance()
    {
        static $instance = null;

        return $instance ?: $instance = new static;
    }

    /**
     * Get page hook name
     *
     * @return string|null
     */
    public function getPageHookName()
    {
        return $this->pageHookName;
    }

    /**
     * Set icon
     *
     * @param string $iconName
     */
    public function setIcon($iconName)
    {
        $this->customIcon = $iconName;
    }

    /**
     * When options are being updated
     *
     * @param array $oldFields
     * @param array $newFields
     */
    public function updateOptions($oldFields, $newFields)
    {
        $this->flushRewriteRules();
    }

    /**
     * Flush rewrite rules when option is updated
     */
    public function flushRewriteRules()
    {
        flush_rewrite_rules(false);
    }

    /**
     * Before updating options
     *
     * @param array $newFields
     * @param array $oldFields
     * @return array
     */
    public function beforeUpdateOptions($newFields, $oldFields)
    {
        return $newFields;
    }

    /**
     * Create section
     *
     * @param string $title       [optional]
     * @param string $description [optional
     * @return SettingsSection
     */
    public function createSection($title = '', $description = '')
    {
        return new SettingsSection(uniqid('section_id_'), $this->optionKey, $title, $this->slug, $description);
    }

    /**
     * Add submenu item
     *
     * @param string $title
     * @param string $optionKey
     * @param string $slug
     * @param string $callback
     * @return OptionSubMenuItem
     */
    public function addSubmenuItem($title, $optionKey, $slug, $callback = '')
    {
        return new OptionSubMenuItem($title, $optionKey, $this->slug, $slug, $callback);
    }

    /**
     * Register settings
     *
     * @hook admin_init
     */
    public function registerOption()
    {
        if (!current_user_can('edit_theme_options')) {
            return;
        }

        register_setting($this->optionGroup, $this->optionKey);
    }

    /**
     * Add menu item to admin
     *
     * @hook admin_menu
     */
    public function addAdminMenu()
    {
        $this->pageHookName = add_menu_page(
            $this->title,
            $this->title,
            'edit_theme_options',
            $this->slug,
            array($this, 'adminMenuPageHtml'),
            $this->customIcon ?: 'dashicons-' . sanitize_html_class($this->optionKey)
        );

        // dispatch load-{option-key} hook when option page is loaded
        $okey = $this->optionKey;
        add_action(sprintf('load-%s', $this->pageHookName), function () use ($okey) {
            do_action(sprintf('load-%s', $okey));
        });
    }

    /**
     * Print the HTML of the admin menu page
     */
    public function adminMenuPageHtml()
    {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have permission to manage options.'));
        } ?>
        <div class="wrap">
        <h2><?php echo $this->title; ?></h2>

        <?php if ($this->optionKey): ?>
        <form method="post" enctype="multipart/form-data" action="options.php">
            <?php settings_fields($this->optionGroup) ?>
            <?php do_settings_sections($this->slug) ?>
            <?php
            if (!empty($this->callback)) {
                call_user_func($this->callback);
            }
            ?>
            <?php submit_button() ?>
        </form>
    <?php endif; ?>

        <?php $this->adminMenuPageBelowFormHtml(); ?>
        </div><?php
    }

    /**
     * Add html to the option admin page below te form
     */
    public function adminMenuPageBelowFormHtml()
    {
    }

    /**
     * Get option value by given variable
     *
     * @param string                  $variable
     * @param string                  $default        [optional]
     * @param string                  $imageSize      [optional] when meta data is an attachment
     * @param \AbstractOptionMenuItem $optionInstance [optional] for search in instance
     * @return string|null
     */
    public static function getOption($variable, $default = null, $imageSize = null, $optionInstance = null)
    {
        $value = self::getRawOption($variable, $default, $optionInstance);
        if (is_array($value) && array_key_exists('attachment', $value)) {
            $value = $value['attachment'];
            $attachmentId = (int)$value['id'];
            if ($imageSize && $attachment = wp_get_attachment_image_src($attachmentId, $imageSize)) {
                $value = $attachment[0];
            } else {
                $value = !empty($value['url']) ? $value['url'] : null;
            }
        }

        return $value;
    }

    /**
     * @param string                       $variable
     * @param null|string                  $default
     * @param \AbstractOptionMenuItem|null $optionInstance
     * @return bool|null
     */
    public static function getRawOption($variable, $default = null, $optionInstance = null)
    {
        $value = null;

        /* @var AbstractOptionMenuItem $instance */
        $instance = $optionInstance ?: Theme::getOptionInstanceByClassName(get_called_class());
        if ($instance) {
            $options = get_option($instance->optionKey);
            $value = ($options && !empty($options[$variable])) ? $options[$variable] : false;
            $value = (!$value && $default) ? $default : $value;
        }

        return $value;
    }

    /**
     * Set option value by given variable
     *
     * @param string                  $variable
     * @param string                  $value
     * @param \AbstractOptionMenuItem $optionInstance
     */
    public static function setOption($variable, $value, $optionInstance = null)
    {
        /* @var AbstractOptionMenuItem $instance */
        $instance = $optionInstance ?: Theme::getOptionInstanceByClassName(get_called_class());

        if ($instance) {
            $options = get_option($instance->optionKey);
            $options[$variable] = $value;

            update_option($instance->optionKey, $options);
        }
    }

    /**
     * Delete option by given variable
     *
     * @param string $variable
     * @param null   $optionInstance
     */
    public static function deleteOption($variable, $optionInstance = null)
    {
        /* @var AbstractOptionMenuItem $instance */
        $instance = $optionInstance ?: Theme::getOptionInstanceByClassName(get_called_class());

        if ($instance) {
            $options = get_option($instance->optionKey);
            if (isset($options[$variable])) {
                unset($options[$variable]);

                update_option($instance->optionKey, $options);
            }
        }
    }

    /**
     * Add error notice
     *
     * @param string|array $message
     */
    public function addErrorNotice($message)
    {
        $this->setNotices(self::NOTICE_TYPE_ERROR, $message);
    }

    /**
     * Set notices
     *
     * @param string       $type
     * @param array|string $message
     * @return boolean
     */
    public function setNotices($type, $message)
    {
        $message = is_array($message) ? '<ul><li>' . implode('</li><li>', $message) . '</li></ul>' : $message;
        $notices = $this->getNotices();
        $notices[$type] = isset($notices[$type]) ? $notices[$type] : array();
        $notices[$type][] = $message;

        return update_option(sprintf('%s_notices', $this->optionKey), $notices);
    }

    /**
     * Get notices
     *
     * @return array
     */
    private function getNotices()
    {
        return get_option(sprintf('%s_notices', $this->optionKey)) ?: array();
    }

    /**
     * Get error notices
     *
     * @return array
     */
    public function getErrorNotices()
    {
        $notices = $this->getNotices();

        return isset($notices[self::NOTICE_TYPE_ERROR]) ? $notices[self::NOTICE_TYPE_ERROR] : array();
    }

    /**
     * Get success notices
     *
     * @return array
     */
    public function getSuccessNotices()
    {
        $notices = $this->getNotices();

        return isset($notices[self::NOTICE_TYPE_SUCCESS]) ? $notices[self::NOTICE_TYPE_SUCCESS] : array();
    }

    /**
     * Add success notice
     *
     * @param string|array $message
     */
    public function addSuccessNotice($message)
    {
        $this->setNotices(self::NOTICE_TYPE_SUCCESS, $message);
    }

    /**
     * Show notices
     */
    public function showNotices()
    {
        // check if option page is loaded
        if (did_action(sprintf('load-%s', $this->pageHookName)) === 0) {
            return false;
        }

        $notifications = $this->getNotices();
        if ($notifications) {
            add_action('admin_notices', function () use ($notifications) {
                foreach (isset($notifications['error']) ? $notifications['error'] : array() as $error) {
                    echo sprintf('<div class="notice %1$s">%2$s</div>', 'notice-error', preg_match('/^<ul/', $error) ? $error : sprintf('<p>%1$s</p>', $error));
                }
                foreach (isset($notifications['success']) ? $notifications['success'] : array() as $success) {
                    echo sprintf('<div class="notice %1$s"><p>%2$s</p></div>', 'notice-success', preg_match('/^<ul/', $success) ? $success : sprintf('<p>%1$s</p>', $success));
                }
            });
            $this->deleteNotices();
        }
    }

    /**
     * Remove notices
     *
     * @return boolean
     */
    public function deleteNotices()
    {
        return delete_option(sprintf('%s_notices', $this->optionKey));
    }

    /**
     * Check if current admin page
     *
     * @return bool
     */
    public function isOptionPage()
    {
        // make sure current page is a admin page and get_current_screen is not a null object
        if (!is_admin() || !function_exists('get_current_screen') || is_null(get_current_screen()) || !$this->pageHookName) {
            return false;
        }

        // check current screen id match the page hook name
        return get_current_screen()->id === $this->pageHookName;
    }

    /**
     * Magic method prevent cloning of the instance of the Singleton instance
     */
    final private function __clone()
    {
    }

    /**
     * Magic method prevent unserializing of the Singleton instance.
     */
    final private function __wakeup()
    {
    }
}
