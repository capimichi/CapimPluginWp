<?php
namespace CapimPluginWP\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("METHOD")
 */
final class Action extends Annotation {
    /**
     * @var string|array
     */
    public $hook = "init";
}