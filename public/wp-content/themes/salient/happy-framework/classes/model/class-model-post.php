<?php

namespace HappyFramework\Model;

/**
 * Class Post
 *
 * @package HappyFramework\Model
 */
class Post
{
    
    /**
     * Get posts by given post type and fields
     *
     * @param   string $post_type
     * @param   array  $fields
     * @return  array
     */
    public static function getPostsByFields($post_type, array $fields = array('ID'))
    {
        /* @var \wpdb $wpdb */
        global $wpdb;

        // set fields
        $fields = array_map(function ($field) use ($wpdb) {
            return sprintf('`%1$s`.`%2$s`', $wpdb->posts, $field);
        }, $fields);

        // get fields with custom query
        $posts = $wpdb->get_results(
            $wpdb->prepare(
                '
                    SELECT %2$s FROM `%3$s`
                    WHERE `%3$s`.`post_status` = \'publish\'
                    AND `%3$s`.`post_type` = \'%1$s\'
                    ORDER BY `%3$s`.`post_date` DESC
                ',
                $post_type,
                implode(',', $fields),
                $wpdb->posts
            )
        );

        return $posts;
    }
}