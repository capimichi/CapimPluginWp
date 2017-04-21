<?php
namespace CapimPluginWP\Annotation;

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