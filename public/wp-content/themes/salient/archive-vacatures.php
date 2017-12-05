<?php get_header(); ?>

<?php
/*blog archives*/
$options = get_nectar_theme_options();

$blog_type = $options['blog_type'];
$archive_bg_img = $options['blog_archive_bg_image']['url'];

$t_id = get_cat_ID(single_cat_title('', false));
$terms = get_option("taxonomy_$t_id");

$subtitle = null;
$heading = wp_title("", false);
?>

<?php

if ($blog_type == null) $blog_type = 'std-blog-sidebar';

$animate_in_effect = (!empty($options['header-animate-in-effect'])) ? $options['header-animate-in-effect'] : 'zoom-out';
$blog_standard_type = (!empty($options['blog_standard_type'])) ? $options['blog_standard_type'] : 'classic';
$archive_header_text_align = ($blog_type != 'masonry-blog-sidebar' && $blog_type != 'masonry-blog-fullwidth' && $blog_type != 'masonry-blog-full-screen-width' && $blog_standard_type == 'minimal') ? 'center' : 'left';

if (!empty($terms['category_image']) || !empty($archive_bg_img)) {

    if ($animate_in_effect == 'slide-down') {
        $wrapper_height_style = null;
    } else {
        $wrapper_height_style = 'style="height: 350px;"';
    }

    $bg_img = $archive_bg_img;
    if (!empty($terms['category_image'])) $bg_img = $terms['category_image']; ?>

    <div id="page-header-wrap" data-midnight="light" <?php echo $wrapper_height_style; ?>>
        <div id="page-header-bg" data-animate-in-effect="slide-down"
             id="page-header-bg" data-text-effect="" data-bg-pos="center"
             data-alignment="<?php echo $archive_header_text_align; ?>" data-alignment-v="center"
             data-parallax="0" data-height="350" style="height: 350px;">
            <div class="page-header-bg-image" style="background-image: url(<?php echo $bg_img; ?>);"></div>
            <div class="container">
                <div class="row">
                    <div class="col span_6">
                        <div class="inner-wrap">
                            <span class="subheader"><?php echo $subtitle; ?></span>
                            <h1><?php echo $heading; ?></h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

    <div class="container-wrap">
        <div class="container main-content">
            <div class="row">

                <?php

                //breadcrumbs
                if (function_exists('yoast_breadcrumb') && !is_home() && !is_front_page()) {
                    yoast_breadcrumb('<div class="full-width-section breadcrumbs"><div class="row"><p id="breadcrumbs">', '</p></div></div>');
                }

                ?>

                <?php $options = get_nectar_theme_options();

                $blog_type = $options['blog_type'];
                if ($blog_type == null) $blog_type = 'std-blog-sidebar';

                $masonry_class = null;
                $masonry_style = null;
                $infinite_scroll_class = null;
                $load_in_animation = (!empty($options['blog_loading_animation'])) ? $options['blog_loading_animation'] : 'none';
                $blog_standard_type = (!empty($options['blog_standard_type'])) ? $options['blog_standard_type'] : 'classic';

                //enqueue masonry script if selected
                if ($blog_type == 'masonry-blog-sidebar' || $blog_type == 'masonry-blog-fullwidth' || $blog_type == 'masonry-blog-full-screen-width') {
                    $masonry_class = 'masonry';
                }

                if ($blog_type == 'masonry-blog-full-screen-width') {
                    $masonry_class = 'masonry full-width-content';
                }

                if (!empty($options['blog_pagination_type']) && $options['blog_pagination_type'] == 'infinite_scroll') {
                    $infinite_scroll_class = ' infinite_scroll';
                }

                if ($masonry_class != null) {
                    $masonry_style = (!empty($options['blog_masonry_type'])) ? $options['blog_masonry_type'] : 'classic';
                }

                if ($blog_standard_type == 'minimal' && $blog_type == 'std-blog-fullwidth')
                    $std_minimal_class = 'standard-minimal full-width-content';
                else if ($blog_standard_type == 'minimal' && $blog_type == 'std-blog-sidebar')
                    $std_minimal_class = 'standard-minimal';
                else
                    $std_minimal_class = '';

                if ($blog_type == 'std-blog-sidebar' || $blog_type == 'masonry-blog-sidebar') {
                    echo '<div id="post-area" class="col ' . $std_minimal_class . ' span_9 ' . $masonry_class . ' ' . $masonry_style . ' ' . $infinite_scroll_class . '"> <div class="posts-container"  data-load-animation="' . $load_in_animation . '">';
                } else {
                    echo '<div id="post-area" class="col ' . $std_minimal_class . ' span_12 col_last ' . $masonry_class . ' ' . $masonry_style . ' ' . $infinite_scroll_class . '"> <div class="posts-container"  data-load-animation="' . $load_in_animation . '">';
                }

                if (have_posts()) : while (have_posts()) : the_post(); ?>

                    <?php

                    if (floatval(get_bloginfo('version')) < "3.6") {
                        //old post formats before they got built into the core
                        get_template_part('includes/post-templates-pre-3-6/entry', get_post_format());
                    } else {
                        //WP 3.6+ post formats
                        get_template_part('includes/post-templates/entry', get_post_format());
                    } ?>

                <?php endwhile; endif; ?>

            </div><!--/posts container-->

            <?php nectar_pagination(); ?>

        </div><!--/span_9-->

        <?php if ($blog_type == 'std-blog-sidebar' || $blog_type == 'masonry-blog-sidebar') { ?>
            <div id="sidebar" class="col span_3 col_last">
                <?php get_sidebar(); ?>
            </div><!--/span_3-->
        <?php } ?>

    </div><!--/row-->
    </div><!--/container-->
    </div><!--/container-wrap-->

<?php get_footer(); ?>