<?php
namespace CapimPluginWP\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("METHOD")
 */
final class Shortcode extends Annotation {
    /**
     * @var string
     */
    public $name;
}