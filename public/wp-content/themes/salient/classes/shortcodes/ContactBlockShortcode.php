<?php

namespace Bsu\Shortcode;

use Bsu\BsuTheme;
use VisualComposer\VisualComposerFactory;

/**
 * Class ContactBlockShortcode
 * @package Bsu\Shortcode
 */
class ContactBlockShortcode extends AbstractShortcode
{
    const IDENTIFIER = 'contact-block';

    protected function __construct()
    {
        parent::__construct(
            self::IDENTIFIER,
            __('Contact Block', BsuTheme::TEXTDOMAIN),
            array(
                'contact_title'    => false,
                'contact_subtitle' => false,
                'person_image'     => false,
                'person_name'      => false,
                'person_title'     => false,
                'person_email'     => false,
                'person_phone'     => false,
                'form_id'          => false,

            )
        );

        $this->template = 'partials/shortcode-contact-block.php';
        $this->addToVisualComposer($this->getVcAttributes(), array(
            'description' => __('a block with contact information and form', BsuTheme::TEXTDOMAIN)
        ));
    }

    /**
     * Get attributes for Visual Composer
     *
     * @return array
     */
    private function getVcAttributes()
    {
        $factory = VisualComposerFactory::getInstance();

        return array(
            $factory->createAttributeTextField(
                'contact_title',
                __('Contact title', BsuTheme::TEXTDOMAIN),
                __('The title of your contact block', BsuTheme::TEXTDOMAIN)
            ),
            $factory->createAttributeTextField(
                'contact_subtitle',
                __('Contact subtitle', BsuTheme::TEXTDOMAIN),
                __('The subtitle of your contact block', BsuTheme::TEXTDOMAIN)
            ),
            $factory->createAttributeImage('person_image', __('Person Image', BsuTheme::TEXTDOMAIN)),
            $factory->createAttributeTextField('person_name', __('Person name', BsuTheme::TEXTDOMAIN)),
            $factory->createAttributeTextField('person_title', __('Person Job Title', BsuTheme::TEXTDOMAIN)),
            $factory->createAttributeTextField('person_email', __('Person Email', BsuTheme::TEXTDOMAIN)),
            $factory->createAttributeTextField('person_phone', __('Person Phone Number', BsuTheme::TEXTDOMAIN)),
            $factory->createAttributeDropdown(
                'form_id',
                __('Contact form',
                    BsuTheme::TEXTDOMAIN),
                null,
                $this->getContactForms()
            ),
        );
    }

    /**
     * Get all contact form 7 forms
     *
     * @return array
     */
    public function getContactForms()
    {
        if (is_plugin_active('contact-form-7/wp-contact-form-7.php') || defined('WPCF7_PLUGIN')) {

            $cf7Posts = get_posts('post_type="wpcf7_contact_form"&numberposts=-1');

            $contact_forms = array(
                '' => 'No form selected'
            );

            if ($cf7Posts) {
                foreach ($cf7Posts as $cform) {
                    $contact_forms[$cform->ID] = $cform->post_title;
                }
            } else {
                $contact_forms[__('No contact forms found', 'js_composer')] = 0;
            }

            return $contact_forms;
        }
    }

}