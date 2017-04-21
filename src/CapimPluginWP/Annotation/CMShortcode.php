<?php
namespace Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("METHOD")
 */
final class CMShortcode extends Annotation {
    /**
     * @var string
     */
    public $name;
}