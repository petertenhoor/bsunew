<?php

/**
 * Template Name: Postcode Check Formulier
 *
 */

global $post;
get_header();
nectar_page_header($post->ID);

//full page
$fp_options = nectar_get_full_page_options();
extract($fp_options);

?>

<div class="container-wrap">

    <div class="<?php if ($page_full_screen_rows != 'on') echo 'container'; ?> main-content">

        <div class="row">

            <?php

            //breadcrumbs
            if (function_exists('yoast_breadcrumb') && !is_home() && !is_front_page()) {
                yoast_breadcrumb('<div class="full-width-section breadcrumbs"><div class="row"><p id="breadcrumbs">', '</p></div></div>');
            }

            //buddypress
            global $bp;
            if ($bp && !bp_is_blog_page()) echo '<h1>' . get_the_title() . '</h1>';

            //fullscreen rows
            if ($page_full_screen_rows == 'on') echo '<div id="nectar_fullscreen_rows" data-animation="' . $page_full_screen_rows_animation . '" data-row-bg-animation="' . $page_full_screen_rows_bg_img_animation . '" data-animation-speed="' . $page_full_screen_rows_animation_speed . '" data-content-overflow="' . $page_full_screen_rows_content_overflow . '" data-mobile-disable="' . $page_full_screen_rows_mobile_disable . '" data-dot-navigation="' . $page_full_screen_rows_dot_navigation . '" data-footer="' . $page_full_screen_rows_footer . '" data-anchors="' . $page_full_screen_rows_anchors . '">';

            if (have_posts()) : while (have_posts()) : the_post();

                the_content();

            endwhile; endif;

            if ($page_full_screen_rows == 'on') echo '</div>'; ?>

        </div><!--/row-->

    </div><!--/container-->

</div><!--/container-wrap-->

<?php get_footer(); ?>

<script>
    (function ($) {
        $(document).ready(function () {
            //get URL parameters
            var postcode = getUrlParameter('postcode');
            var result   = getUrlParameter('result');
            var number   = getUrlParameter('huisnummer');
            var addition = getUrlParameter('toevoeging');

            //fill the form values if they are defined
            if (postcode !== undefined && result !== undefined) {
                $('#pakket').val(result);
                $('#postcode').val(postcode);
            }
            if (number !== undefined) {
                var nummerToevoeging = number;
                if (addition !== undefined) {
                    nummerToevoeging = nummerToevoeging + ' ' + addition;
                }
                $('#nummertoevoeging').val(nummerToevoeging);
            }


        });

        /**
         * Gets URL parameters seperated by '='
         * @param sParam
         * @returns {boolean}
         */
        var getUrlParameter = function getUrlParameter(sParam) {
            var sPageURL      = decodeURIComponent(window.location.search.substring(1)),
                sURLVariables = sPageURL.split('&'),
                sParameterName,
                i;

            for (i = 0; i < sURLVariables.length; i++) {
                sParameterName = sURLVariables[i].split('=');

                if (sParameterName[0] === sParam) {
                    return sParameterName[1] === undefined ? true : sParameterName[1];
                }
            }
        };
    })(jQuery);
</script>
