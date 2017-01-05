<?php
/**
 * @var string $title Title of the block
 * @var int $image Image ID to show as image
 * @var string $file Base64 encoded string
 * @var string $btn_label Label of the button
 */

use Bsu\BsuTheme;
use Bsu\Shortcode\ShortcodeDownloadBlock;
use HappyFramework\Helpers\File;

$image = $image ? File::getImageWithSrcset($image, array(), array('class' => 'shortcode-download-block-image'), 'large') : false;
$file = ShortcodeDownloadBlock::getInstance()->base64ToObject($file);
$uploadDir = wp_upload_dir();
$fileUrl = str_replace($uploadDir['basedir'], $uploadDir['baseurl'], File::idToPath($file->attachmentId));

if ($file): ?>
    <aside class="shortcode-download-block">
        <?php /* --  image -- */ ?>
        <figure class="shortcode-download-block-figure">
            <?php echo $image ?>
        </figure>
        <?php /* --  label -- */ ?>
        <?php if ($title): ?>
            <h3 class="shortcode-download-block-label"><?php echo $title ?></h3>
        <?php endif; ?>
        <?php /* --  download label -- */ ?>
        <?php if (is_object($file) && property_exists($file, 'attachmentUrl')): ?>
            <a class="nectar-button large regular extra-color-1 has-icon regular-button"
               style="margin-top: 20px; visibility: visible;" href="<?php echo $fileUrl; ?>" data-color-override="false"
            data-hover-color-override="false" data-hover-text-color-override="#fff">
            <span><?php echo $btn_label ?: __('Download', BsuTheme::TEXTDOMAIN) ?></span><i
                class="fa fa-download"></i></a>
        <?php endif; ?>
    </aside>
<?php endif;