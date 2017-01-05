<?php
namespace HappyFramework\Helpers;

class Taxonomy
{
    /**
     * Convert term objects to term ids
     *
     * @param array $terms
     * @return array
     */
    public static function termObjectsToTermIds(array $terms)
    {
        return array_map(function ($term) {
            return (int)$term->term_id;
        }, $terms);
    }

    /**
     * Get term name
     *
     * @param int|null $term_id
     * @return null|string
     */
    public static function getTermName($term_id = null)
    {
        $name = null;
        if ($term_id) {
            $term = get_term($term_id, Taxonomy::getTaxonomyFromTermId($term_id));
            if ($term instanceof \WP_Term) {
                $name = $term->name;
            }
        } elseif (is_tax()) {
            $name = single_term_title('', false);
        }

        return $name;
    }

    /**
     * Get taxonomy from term_id
     *
     * @param   int   $term_id
     * @return  null|string
     * @global  \wpdb $wpdb
     */
    public static function getTaxonomyFromTermId($term_id)
    {
        /* @var \wpdb $wpdb */
        global $wpdb;
        $taxonomy = $wpdb->get_var(
            $wpdb->prepare(
                '
                    SELECT `term_tax`.`taxonomy` FROM ' . $wpdb->term_taxonomy . ' AS `term_tax`
                    WHERE `term_tax`.`term_id` = %d
                    LIMIT 1;
                ',
                $term_id
            )
        );

        return $taxonomy;
    }

    /**
     * Get current taxonomy
     *
     * @param int|null $term_id
     * @return null|string
     */
    public static function getTermTaxonomy($term_id = null)
    {
        $taxonomy = null;
        if ($term_id) {
            $taxonomy = Taxonomy::getTaxonomyFromTermId($term_id);
        } elseif (is_tax() || is_tag()) {
            $queriedObject = get_queried_object();
            $taxonomy = $queriedObject instanceof \WP_Term ? $queriedObject->taxonomy : null;
        }

        return $taxonomy;
    }

    public static function getTermDescription($term_id = null)
    {
        $description = null;
        if ($term_id) {
            $term = get_term($term_id, Taxonomy::getTaxonomyFromTermId($term_id));
            if ($term instanceof \WP_Term) {
                $description = $term->description;
            }
        } elseif (is_tax()) {
            $description = term_description($term_id);
        }

        return $description;
    }

    /**
     * Get parents of given term when taxonomy is hierarchical
     *
     * @param int    $term_id
     * @param string $taxonomy
     * @return array
     */
    public static function getTermAncestors($term_id, $taxonomy)
    {
        $ancestors = array();
        foreach (get_ancestors($term_id, $taxonomy) as $term_id) {
            array_push($ancestors, get_term_by('id', $term_id, $taxonomy));
        }

        return $ancestors;
    }

    /**
     * Get post types which registered the taxonomy
     *
     * @param string $taxonomy
     * @return array|null
     */
    public static function registeredPostTypes($taxonomy)
    {
        $post_types = get_taxonomy($taxonomy)->object_type;

        return (is_array($post_types) && count($post_types) > 0) ? $post_types : null;
    }

    /**
     * Get singular label from taxonomy
     *
     * @param string|null $taxonomy
     * @return null
     */
    public static function getTaxonomyLabelSingular($taxonomy = null)
    {
        $singularLabel = null;
        $taxonomy = $taxonomy ?: (get_queried_object() instanceof \WP_Term ? get_queried_object()->taxonomy : null);
        if ($taxonomy && $taxonomyObject = get_taxonomy($taxonomy)) {
            $singularLabel = $taxonomyObject->labels->singular_name;
        }

        return $singularLabel;
    }
}
