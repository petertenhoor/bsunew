<?php

namespace Bsu;

use HappyFramework\Abstracts\AbstractPostType;
use HappyFramework\Helpers\Metabox;

/**
 * Class MetaboxesPost
 * @package Bsu
 */
class MetaboxesPost
{
    private static $instance;

    /**
     * MetaboxesPost constructor.
     */
    protected function __construct()
    {
    }

    final static public function getInstance()
    {
        static $instance = null;

        return $instance ?: $instance = new static;
    }

    final private function __clone()
    {
    }

    final private function __wakeup()
    {
    }
}