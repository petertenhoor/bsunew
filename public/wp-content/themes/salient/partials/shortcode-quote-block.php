<?php
/**
 * @var string $quote_text Text of quote block
 * @var string $quote_link URL of quote block
 * @var string $person_image Id of person image
 * @var string $person_name Name of person
 * @var string $person_title Title of person
 */

use Bsu\BsuTheme;
use HappyFramework\Helpers\File;

$person_image = $person_image ? File::getImageWithSrcset($person_image, array(), array('class' => 'shortcode-single-quote-block-person-image'), 'large') : false;

if (!empty($quote_text)): ?>
    <blockquote class="shortcode-single-quote-block">
        <p class="shortcode-single-quote-block-text"><?php echo $quote_text; ?>
            <?php if (!empty($quote_link)): ?>
                <br><a href="<?php echo $quote_link; ?>"><?php _e('Read More', BsuTheme::$domain); ?> ></a>
            <?php endif; ?>
        </p>
        <?php if (!empty($person_image)): ?>
            <div class="image-icon has-bg">
                <?php echo $person_image; ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($person_name) && !empty($person_title)): ?>
            <span class="shortcode-single-quote-block-person-name"><?php echo $person_name; ?></span>
            <span class="shortcode-single-quote-block-person-title"><?php echo $person_title; ?></span>
        <?php endif; ?>
    </blockquote>
<?php endif; ?>