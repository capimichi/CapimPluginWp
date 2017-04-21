<?php
namespace Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("METHOD")
 */
final class CMFilter extends Annotation {
    /**
     * @var string|array
     */
    public $hook = "init";
}