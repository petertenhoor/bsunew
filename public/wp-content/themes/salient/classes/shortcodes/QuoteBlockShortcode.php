<?php

namespace Bsu\Shortcode;

use Bsu\BsuTheme;
use VisualComposer\VisualComposerFactory;

/**
 * Class QuoteBlockShortcode
 * @package Bsu\Shortcode
 */
class QuoteBlockShortcode extends AbstractShortcode
{
    const IDENTIFIER = 'single-quote-block';

    protected function __construct()
    {
        parent::__construct(
            self::IDENTIFIER,
            __('Single Quote Block', BsuTheme::TEXTDOMAIN),
            array(
                'quote_text'   => false,
                'quote_link'   => false,
                'person_image' => false,
                'person_name'  => false,
                'person_title' => false,
            )
        );

        $this->template = 'partials/shortcode-quote-block.php';
        $this->addToVisualComposer($this->getVcAttributes(), array(
            'description' => __('A single quote block with author', BsuTheme::TEXTDOMAIN)
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
            $factory->createAttributeTextArea('quote_text', __('Quote Text', BsuTheme::TEXTDOMAIN)),
            $factory->createAttributeTextField('quote_link', __('Quote link', BsuTheme::TEXTDOMAIN)),
            $factory->createAttributeImage('person_image', __('Author Image', BsuTheme::TEXTDOMAIN)),
            $factory->createAttributeTextField('person_name', __('Author name', BsuTheme::TEXTDOMAIN)),
            $factory->createAttributeTextField('person_title', __('Author Job Title', BsuTheme::TEXTDOMAIN)),
        );
    }
}