<?php
/**
 * @var string $person_image Id of person image
 * @var string $person_name Name of person
 * @var string $person_title Title of person
 * @var string $person_email Emailaddress of person
 * @var string $person_phone Phone number of person
 * @var string $form_id Contact form 7 shortcode
 */

use HappyFramework\Helpers\File;

$person_image = $person_image ? File::getImageWithSrcset($person_image, array(), array('class' => 'shortcode-single-quote-block-person-image'), 'large') : false; ?>

<aside class="shortcode-contact-block">
    <div class="row np">
        <div class="col span_5 shortcode-contact-block-person">
            <?php if (!empty($person_image)): ?>
                <div class="image-icon has-bg">
                    <?php echo $person_image; ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($person_name && !empty($person_title))): ?>
                <span class="shortcode-single-quote-block-person-name"><?php echo $person_name; ?></span>
                <span class="shortcode-single-quote-block-person-title"><?php echo $person_title; ?></span>
            <?php endif; ?>
        </div>
        <div class="col span_7 shortcode-contact-block-info">
            <h2>Vragen?</h2>
            <p>Neem contact op:</p>
            <?php if (!empty($person_phone)): ?>
                <a href="tel:<?php echo str_replace(' ', '', $person_phone); ?>">
                    <?php echo $person_phone; ?>
                </a>
            <?php endif; ?>
            <?php if (!empty($person_email)): ?>
                <a href="mailto:<?php echo $person_email; ?>">
                    <?php echo $person_email; ?>
                </a>
            <?php endif; ?>
        </div>
    </div>
    <?php if (!empty($form_id)): ?>
        <div class="row">
            <div class="col span_12">
                <?php echo do_shortcode('[contact-form-7 id="' . $form_id . '"]'); ?>
            </div>
        </div>
    <?php endif; ?>
</aside>