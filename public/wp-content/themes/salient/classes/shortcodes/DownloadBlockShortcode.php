<?php
namespace Bsu\Shortcode;

use Bsu\BsuTheme;
use HappyFramework\Helpers\Log;
use VisualComposer\VisualComposerFactory;


/**
 * Class ShortcodeDownloadBlock
 *
 * @package Bsu\Shortcode
 */
class ShortcodeDownloadBlock extends AbstractShortcode
{
    const IDENTIFIER = 'download-block';

    /**
     * ShortcodeDownloadBlock constructor.
     */
    protected function __construct()
    {
        parent::__construct(
            self::IDENTIFIER,
            __('Download PDF Block', BsuTheme::TEXTDOMAIN),
            array(
                'image'     => false,
                'title'     => false,
                'btn_label' => false,
                'file'      => false,
            )
        );

        $this->template = 'partials/shortcode-download-block.php';
        $this->addToVisualComposer($this->getVcAttributes(), array(
            'description' => __('A downloadable item with a headline, image and button.', BsuTheme::TEXTDOMAIN)
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
            $factory->createAttributeImage('image', __('File Image', BsuTheme::TEXTDOMAIN)),
            $factory->createAttributeTextField('title', __('Title', BsuTheme::TEXTDOMAIN)),
            $factory->createAttributeFile('file', __('Downloadable File', BsuTheme::TEXTDOMAIN)),
            $factory->createAttributeTextField('btn_label', __('Button Label', BsuTheme::TEXTDOMAIN)),
        );
    }

    /**
     * Convert base64 encoded string to object
     *
     * @param string $data
     * @return bool|\stdClass
     */
    public function base64ToObject($data)
    {
        $obj = false;
        $data = base64_decode($data);
        if (!empty($data)) {
            $obj = json_decode($data);
        }

        return $obj;
    }
}