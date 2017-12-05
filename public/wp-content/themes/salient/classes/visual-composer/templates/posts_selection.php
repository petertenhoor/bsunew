<?php
/**
 * Posts Selection Visual Composer Custom Param Type
 *
 * @var array $settings
 * @var array $value
 */

use HappyFramework\Model\Post;

$postType = (string)$settings['custom_param_value']['post_type'];
$posts = Post::getPostsByFields($postType, array('ID', 'post_title'));
?>

<div class="vc-custom-attribute-posts-selection">

    <?php /* --  all posts -- */ ?>
    <script type="text/html" class="all-posts"><?php echo json_encode($posts) ?></script>

    <?php /* --  contains current value -- */ ?>
    <script type="text/html" class="current-data"><?php echo $value ?: ''; ?></script>

    <?php /* --  store param data -- */ ?>
    <div class="my_param_block">
        <input name="<?php echo esc_attr($settings['param_name']) ?>"
               class="wpb_vc_param_value wpb-textinput <?php echo esc_attr($settings['param_name']) ?> <?php echo esc_attr($settings['type']) ?>_field"
               type="hidden" value="<?php echo esc_attr($value) ?>"
               data-bind="value: value"/>
    </div>

    <?php /* --  selection method -- */ ?>
    <div class="wpb_element_label" style="margin-top: 1em;"><?php _e('Selection Method', \Bsu\BsuTheme::TEXTDOMAIN) ?></div>
    <select class="selection-method" data-bind="value: selectionMethod">
        <option value="latest"><?php _e('Latest', \Bsu\BsuTheme::TEXTDOMAIN) ?></option>
        <option value="manual"><?php _e('Manual', \Bsu\BsuTheme::TEXTDOMAIN) ?></option>
    </select>

    <?php /* --  number of posts to show -- */ ?>
    <!-- ko if: selectionMethod() === 'latest' -->
    <div class="wpb_element_label" style="margin-top: 1em;"><?php _e('Number of posts', \Bsu\BsuTheme::TEXTDOMAIN) ?></div>
    <select class="number-of-posts" data-bind="value: numberOfPosts">
        <?php for ($i = 1; $i <= 20; $i++): ?>
            <option value="<?php echo $i ?>"><?php echo $i ?></option>
        <?php endfor; ?>
    </select>
    <!-- /ko -->

    <?php /* --  columns -- */ ?>
    <div class="wpb_element_label" style="margin-top: 1em;"><?php _e('Columns per row', \Bsu\BsuTheme::TEXTDOMAIN) ?></div>
    <select class="maximum-columns" data-bind="value: maximumColumns">
        <option value="12">1</option>
        <option value="6">2</option>
        <option value="4">3</option>
        <option value="3">4</option>
    </select>

    <?php /* --  manual post selection -- */ ?>
    <!-- ko if: selectionMethod() === 'manual' -->
        <ul data-bind="foreach: manualPosts, style: {display: (manualPosts().length > 0) ? 'flex' : 'none'}" class="manual-posts">
            <li class="manual-post">
                <span data-bind="text: post_title"></span>
                <a href="#" class="button button-delete" data-bind="click: $parent.removePost.bind($parent, $data)">
                    <?php _e('Remove', \Bsu\BsuTheme::TEXTDOMAIN) ?>
                </a>
            </li>
        </ul>

        <!-- ko if: manualPostsSelection().length > 0 -->
        <div class="selection">
            <select data-bind="value: currentPostSelection, options: manualPostsSelection, optionsText: 'post_title', optionsValue: 'ID'"></select>
            <a href="#" class="button button-add" data-bind="click: addPost"><?php _e('Add', \Bsu\BsuTheme::TEXTDOMAIN) ?></a>
        </div>
        <!-- /ko -->
    <!-- /ko -->

</div>