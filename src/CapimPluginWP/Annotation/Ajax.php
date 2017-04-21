<?php
namespace CapimPluginWP\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("METHOD")
 */
final class Ajax extends Annotation {

    /**
     * @var string
     */
    public $name;

    /**
     * @var bool
     */
    public $public = true;

    /**
     * @var bool
     */
    public $admin = true;
}