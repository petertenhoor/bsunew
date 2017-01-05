<?php
namespace HappyFramework\TinyMce;

use HappyFramework\Abstracts\Singleton;
use HappyFramework\Helpers\Color;

/**
 * Class TinyMceFilterInit
 *
 * @package HappyFramework\TinyMce
 */
class TinyMceFilterInit extends Singleton
{
    // menubar item tyoe
    const MENUBAR_ITEM_TYPE_FILE   = 'file';
    const MENUBAR_ITEM_TYPE_INSERT = 'insert';
    const MENUBAR_ITEM_TYPE_EDIT   = 'edit';
    const MENUBAR_ITEM_TYPE_VIEW   = 'view';
    const MENUBAR_ITEM_TYPE_FORMAT = 'format';
    const MENUBAR_ITEM_TYPE_TABLE  = 'table';
    const MENUBAR_ITEM_TYPE_TOOLS  = 'tools';

    /**
     * Set menu bar
     *
     * @param array $items
     */
    public function setMenuBar(array $items)
    {
        add_filter('tiny_mce_before_init', function ($mceInit) use ($items) {
            $mceInit['menubar'] = implode(' ', $items);

            return $mceInit;
        });
    }

    /**
     * Set colors
     *
     * @param array $colors
     */
    public function setColors(array $colors)
    {
        // check if we have to swap key values
        $value = reset($colors);
        if (Color::isHex($value)) {
            $colors = array_flip($colors);
        }

        // generate tinymce colors arg
        $tinymce_colors = '';
        foreach ($colors as $colorValue => $colorName) {
            $tinymce_colors .= '"' . preg_replace('/^#/', '', $colorValue) . '", "' . $colorName . '"';
            if (end($colors) !== $colorName) {
                $tinymce_colors .= ',';
            }
        }

        // add colors to tinymce before the object has initialized
        add_filter('tiny_mce_before_init', function ($init) use ($tinymce_colors) {
            $init['textcolor_map'] = '[' . $tinymce_colors . ']';

            return $init;
        });
    }
}