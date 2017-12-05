<?php
namespace HappyFramework\Options;

use HappyFramework\Abstracts\AbstractOptionMenuItem;

/**
 * Class OptionSubmenuItem
 *
 * @package HappyFramework\Options
 */
class OptionSubMenuItem extends AbstractOptionMenuItem
{
    protected $parentSlug;

    /**
     * OptionSubMenuItem constructor.
     *
     * @param string $title
     * @param string $optionKey
     * @param string $parentSlug
     * @param string $slug
     * @param string $callback
     */
    protected function __construct($title, $optionKey, $parentSlug, $slug, $callback = '')
    {
        parent::__construct($title, $optionKey, $slug, $callback);
        $this->parentSlug = $parentSlug;
        
        add_action('admin_init', array($this, 'addSectionFields'));
    }

    /**
     * Add submenu page
     *
     * @hook admin_menu
     */
    public function addAdminMenu()
    {
        add_submenu_page(
            $this->parentSlug,
            $this->title,
            $this->title,
            'manage_options',
            $this->slug,
            array($this, 'adminMenuPageHtml')
        );
    }

    public function addSectionFields()
    {
    }
}
