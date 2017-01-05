<?php
namespace HappyFramework\Helpers;

/**
 * Class Formatting
 *
 * @package HappyFramework\Helpers
 */
class Formatting
{
    /**
     * Format string to html
     * This function is an equivalant of the_content
     *
     * @param  string $str
     * @return string
     */
    public static function toHtml($str)
    {
        // replace common plain text characters
        $str = wptexturize($str);

        // convert smilies
        $str = convert_smilies($str);

        // Converts lone & characters into `&#038;` (a.k.a. `&amp;`)
        $str = convert_chars($str);

        // Replaces double line-breaks with paragraph elements.
        $str = wpautop($str);

        // Don't auto-p wrap shortcodes that stand alone
        $str = shortcode_unautop($str);

        // convert shortcodes
        $str = do_shortcode($str);

        // prepend attachment
        $str = prepend_attachment($str);

        // balance tags
        $str = force_balance_tags($str);

        // convert ]]> to html entity
        $str = str_replace(']]>', ']]&gt;', $str);

        // remove empty paragraphes
        $str = preg_replace('/\<p\>[\s]*\<\/p\>/', '', $str);

        return $str;
    }

    /**
     * Format string to attribute string
     *
     * @example data-bind="text: '<?php echo Formatting::toAttributeString($str); ?>'"
     * @param   string $str
     * @return  string|void
     */
    public static function toAttributeString($str)
    {
        return esc_attr(str_replace("'", "\\'", $str));
    }

    /**
     * Key Value paired array to style attribute
     *
     * @param array $arr
     * @return string
     */
    public static function arrToStyleAttribute($arr)
    {
        return implode(';', array_map(function ($k, $v) {
            return sprintf('%1$s:%2$s', $k, $v);
        }, array_keys($arr), $arr));
    }

    /**
     * Format phonenunmber to a formatted phonenumber which can be used in href attribute `tel:`
     *
     * @example: <a href="tel:<?php echo Formatting::toAttributeTel('(06) - 12 34 56 78') ?>">(06) - 12 34 56 78</a>
     * @param string $tel
     * @return string
     */
    public static function toAttributeTel($tel)
    {
        return preg_replace('/[\(\)\s\-]+/', '', $tel);
    }

    /**
     * Get knockout component params attribute value
     * base64 encoded json string values
     *
     * @param array $arr
     * @return string
     */
    public static function knockoutComponentAttributeParams(array $arr)
    {
        $properties = array();
        foreach ($arr as $key => $value) {
            $value = is_array($value) ? json_encode($value) : $value;
            $properties[] = sanitize_title($key) . ':\'' . base64_encode($value) . '\'';
        }

        return implode(',', $properties);
    }
}
