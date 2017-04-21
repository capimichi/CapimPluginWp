<?php
namespace Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("METHOD")
 */
final class CMAjax extends Annotation {

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