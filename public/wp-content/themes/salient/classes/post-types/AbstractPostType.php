<?php

namespace Bsu\PostType;

use HappyFramework\Helpers\Taxonomy;
use Bsu\Component\MobileViewsComponent;
use Bsu\MetaboxesPost;
use Bsu\BsuTheme;

/**
 * Class AbstractPostType
 *
 * @package Hbs\PostType
 */
abstract class AbstractPostType extends \HappyFramework\Abstracts\AbstractPostType
{
    /**
     * Settings options class name
     *
     * @var string
     */
    private $settingsOptionsClassName;

    /**
     * AbstractPostType constructor.
     *
     * @param string|null $type
     */
    protected function __construct($type = null)
    {
        parent::__construct($type);

        add_action('pre_get_posts', array($this, 'setPostsPerPage'));
        add_filter('get_the_categories', array($this, 'filterTaxonomyCategories'), 10, 2);
        add_filter('widget_categories_args', array($this, 'filterCategoryArgs'), 99);

        // show title
        add_filter('display_post_title', array($this, 'filterDisplayPostTitle'));

        // static block page bottom
        add_filter('above_footer', array($this, 'addStaticblockBottom'));

        // meunu links
        //add_filter('custom_menu_links', array($this, 'customMenuLinks'));
        //add_action('wp', array($this, 'customMenuLinksMobile'), 1);
        //add_action('before_breadcrumb', array($this, 'customMenuLinksToggleBtn'));

        // set update post type hooks
        $this->setRemoveTransientDataHooks();

        // filter excerpt length
        $self = $this;
        add_filter('excerpt_length', function ($length) use ($self) {
            global $post;
            if (is_object($post) && property_exists($post, 'post_type') && $post->post_type === $self->type) {
                $length = $self->filterExcerptLength($length);
            }

            return $length;
        }, 999);
    }

    /**
     * Set remove transient data action hooks
     * Checks if update hook appears om current post or current taxonomy
     */
    private function setRemoveTransientDataHooks()
    {
        $self = $this;

        // when post is being saved or deleted
        $postUpdated = function ($post_id) use ($self) {
            if (get_post_type($post_id) === $self->type) {
                $self->removeTransientData();
            }
        };

        // when a term is being created, edited or deleted
        $termUpdated = function ($term_id) use ($self) {
            $taxonomy = Taxonomy::getTaxonomyFromTermId($term_id);
            if (in_array($taxonomy, $self->taxonomies)) {
                $self->removeTransientData();
            }
        };

        add_action('delete_post', $postUpdated);
        add_action('save_post', $postUpdated);
        add_action('create_term', $termUpdated);
        add_action('edit_term', $termUpdated);
        add_action('delete_term', $termUpdated);
    }

    /**
     * Remove transient data
     * Delete transient and remove transient key from option table
     */
    public function removeTransientData()
    {
        $key = $this->type . '_transient_keys';
        $transient_keys = get_option($key) ?: array();
        foreach ($transient_keys as $transient_key) {
            if (delete_transient($transient_key)) {
                $_transient_keys = $transient_keys;
                $index = array_search($transient_key, $_transient_keys);
                if ($index !== false) {
                    array_splice($_transient_keys, $index, 1);
                    if (count($_transient_keys) === 0) {
                        delete_option($key);
                    } else {
                        update_option($key, $_transient_keys);
                    }
                }
            }
        }
    }

    /**
     * Filter excerpt message
     *
     * @param int $length
     * @return int
     */
    public function filterExcerptLength($length)
    {
        return $length;
    }

    /**
     * Set transient data and store key in `option` table
     *
     * @param string $transient_key
     * @param mixed  $data
     * @param int    $expiration
     */
    public function setTransientData($transient_key, $data, $expiration = HOUR_IN_SECONDS)
    {
        $transient_keys = get_option($this->type . '_transient_keys') ?: array();
        if (!in_array($transient_key, $transient_keys)) {
            $transient_keys[] = $transient_key;
            update_option($this->type . '_transient_keys', $transient_keys);
        }

        set_transient($transient_key, $data, $expiration);
    }

    /**
     * Get transient data
     *
     * @param string $transient_key
     * @return mixed
     */
    public function getTransientData($transient_key)
    {
        return get_transient($transient_key);
    }


    /**
     * Filter display post title
     *
     * @hook display_post_title
     * @param string $showTitle
     * @return mixed
     */
    public function filterDisplayPostTitle($showTitle)
    {
        if ($this->isSinglePage()) {
            $showTitle = self::getMetaValue('_title_enabled') === '1';
        }

        return $showTitle;
    }

    /**
     * Add static block footer
     *
     * @hook above_footer
     * @param string $aboveFooterContent
     * @return string
     */
    public function addStaticblockBottom($aboveFooterContent)
    {
        if ($this->isSinglePage()) {
            $staticblockContent = MetaboxesPost::getInstance()->getStaticblockBottom(get_the_ID());
            if (!empty($staticblockContent)) {
                $aboveFooterContent = '<div class="staticblock-bottom">' . $staticblockContent . '</div>' . $aboveFooterContent;
            }
        }

        return $aboveFooterContent;
    }

    /**
     * Custom menu links for pages
     *
     * @hook filter custom_menu_links
     * @param string $menuLinks
     * @return string
     */
    public function customMenuLinks($menuLinks)
    {
        if ($this->isSinglePage()) {
            $menuLinks = MetaboxesPost::getInstance()->getMenuLinksHtml(get_the_ID());
        }

        return $menuLinks;
    }

    /**
     * State if current page has custom menu links
     *
     * @param int|null $post_id
     * @return boolean
     */
    public function hasMenuLinks($post_id = null)
    {
        $post_id = $post_id ?: get_the_ID();
        $menuLinks = $this->getMetaValue('_menu_links', $post_id);

        return $menuLinks && $menuLinks !== '-1';
    }

    /**
     * Add toggle button for custom menu links
     */
    public function customMenuLinksToggleBtn()
    {
        $menuLinks = $this::getMetaValue('_menu_links');
        if ($this->isSinglePage() && !empty($menuLinks) & $menuLinks !== '-1') {
            printf('<a
                href="#"
                class="toggle-menu-links"
                data-bind="click: $root.mobileView.toggle.bind(this, \'menu-links\')">%1$s</a>
            ', __('Pages', BsuTheme::TEXTDOMAIN));
        }
    }

    /**
     * Filter taxonomy categories
     *
     * @hook get_the_categories
     * @param array $categories
     * @param int   $id
     * @return array
     */
    public function filterTaxonomyCategories($categories, $id)
    {
        $id = $id ?: get_the_ID();
        if ($id && get_post_type($id) === $this->type) {
            // get taxonomy with `category` name
            $taxonomy = $this->getCategoryTaxonomy();

            // set terms of post with taxonomy
            if (isset($taxonomy)) {
                $categories = get_the_terms($id, $taxonomy);
                if (!$categories || is_wp_error($categories)) {
                    $categories = array();
                }
                $categories = array_values($categories);
            }
        }

        return $categories;
    }

    /**
     * Get category taxonomy
     * get taxonomy with `category` name in it
     *
     * @return string|null
     */
    private function getCategoryTaxonomy()
    {
        foreach ($this->taxonomies as $tax) {
            if (preg_match('/category/', $tax)) {
                $taxonomy = $tax;
                break;
            }
        }

        return isset($taxonomy) ? $taxonomy : null;
    }

    /**
     * Filter category arguments for widget `widget_categories`
     *
     * @hook widget_categories_args
     * @param array $cat_args
     * @return array
     */
    public function filterCategoryArgs($cat_args)
    {
        if ($this->isArchivePage() || $this->isTaxonomyPage()) {
            if ($taxonomy = $this->getCategoryTaxonomy()) {
                $cat_args = array_merge($cat_args, array(
                    'taxonomy' => $taxonomy
                ));
            }
        }

        return $cat_args;
    }

    /**
     * Set posts per page
     *
     * @param \WP_Query $query
     */
    public function setPostsPerPage(\WP_Query $query)
    {
        if (
            !is_admin() &&
            $query->is_main_query() &&
            $this->isArchivePage() &&
            $settingsOptionInstance = $this->getSettingsOptionsClassInstance()
        ) {
            if ($postPerPage = $settingsOptionInstance::getOption('posts_per_page') ?: null) {
                $query->set('posts_per_page', (int)$postPerPage);
            }
        }
    }

    /**
     * Get Settings Option Class Instance
     *
     * @return \HappyFramework\Abstracts\AbstractOptionMenuItem|null
     */
    public function getSettingsOptionsClassInstance()
    {
        $className = $this->getSettingsOptionsClassName();

        return $className ? BsuTheme::getOptionInstanceByClassName($className) : null;
    }

    /**
     * Get settings options class name
     *
     * @return string
     */
    public function getSettingsOptionsClassName()
    {
        return $this->settingsOptionsClassName;
    }

    /**
     * Set settings options class name
     *
     * @param string $settingsOptionsClassName
     */
    public function setSettingsOptionsClassName($settingsOptionsClassName)
    {
        $this->settingsOptionsClassName = $settingsOptionsClassName;
    }

    /**
     * Force using visual composer for this post type
     * Set post type to list when rewrite rules are being stored in the database
     * Normally posts in Visual Composer can only be toggled on when they're registered as public in VC role manager tab
     */
    public function forceUseVisualComposer()
    {
        global $wpdb;
        $type = $this->type;

        add_action('generate_rewrite_rules', function () use ($type, $wpdb) {

            // get user roles
            $userRoles = $wpdb->get_var(
                $wpdb->prepare(
                    '
                        SELECT `o`.`option_value` FROM `%1$s` AS `o`
                        WHERE `o`.`option_name` = \'%2$s\'
                    ',
                    $wpdb->options,
                    $wpdb->prefix . 'user_roles'
                )
            );

            if ($userRoles = @unserialize($userRoles)) {
                // update user roles
                foreach ($userRoles as $role => &$arr) {
                    $capabilities = &$arr['capabilities'];
                    if (in_array($role, array('administrator', 'editor', 'author', 'contributor'))) {
                        $capabilities['vc_access_rules_post_types'] = 'custom';
                        $capabilities[sprintf('vc_access_rules_post_types/%1$s', sanitize_key($type))] = true;
                    }
                }

                // save user roles
                $userRoles = serialize($userRoles);
                $wpdb->update(
                    $wpdb->options,
                    array('option_value' => $userRoles),
                    array('option_name' => $wpdb->prefix . 'user_roles'),
                    array('%s'),
                    array('%s')
                );
            }
        });
    }

    /**
     * Get rewrite slug
     * Check on BASE_SLUG constant or from settings option key `post_type_slug`
     *
     * @return null|string
     */
    public function getRewriteSlug()
    {
        // get class by names
        $calledClass = new \ReflectionClass(get_called_class());
        $settingsClass = $this->settingsOptionsClassName ? new \ReflectionClass($this->settingsOptionsClassName) : null;

        // get base slug
        $slug = $calledClass->hasConstant('BASE_SLUG') ? $calledClass->getConstant('BASE_SLUG') : null;

        // if option `post_type_slug` is defined, set the slug to this value
        $settingsOptionKey = $settingsClass ? $settingsClass->getStaticPropertyValue('okey') : null;
        $settingsOption = $settingsOptionKey ? get_option($settingsOptionKey) : null;
        if ($settingsOption && !empty($settingsOption['post_type_slug'])) {
            $slug = sanitize_title($settingsOption['post_type_slug']);
        }

        return $slug;
    }

    /**
     * Get taxonomy rewrite slug
     *
     * @param string $taxonomyName
     * @return string|boolean
     */
    public function getTaxonomyRewriteSlug($taxonomyName)
    {
        $settingsOptionInstance = $this->getSettingsOptionsClassInstance();
        $settings = get_option($settingsOptionInstance::$okey);
        $slugs = array(
            'category' => $settings && !empty($settings['tax_category_slug']) ? $settings['tax_category_slug'] : 'category',
            'tag'      => $settings && !empty($settings['tax_tag_slug']) ? $settings['tax_tag_slug'] : 'tag',
        );

        $key = false;

        // get key if taxonomy name contains `category`
        if (strpos($taxonomyName, 'category') !== false) {
            $key = 'category';
        }

        // get key if taxonomy name contains `tags`
        if (strpos($taxonomyName, 'tag') !== false) {
            $key = 'tag';
        }

        return $key ? $slugs[$key] : false;
    }

    /**
     * Filter number revisions to keeo
     *
     * @param int      $num
     * @param \WP_Post $post
     * @return mixed
     */
    public function filterNumberRevisionsToKeep($num, $post)
    {
        return 10;
    }
}