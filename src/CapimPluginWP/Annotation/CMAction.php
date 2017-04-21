<?php
namespace CapimPluginWP\Annotation;

use CapimPluginWP\Annotation;

/**
 * @Annotation
 * @Target("METHOD")
 */
final class CMAction extends Annotation {
    /**
     * @var string|array
     */
    public $hook = "init";
}