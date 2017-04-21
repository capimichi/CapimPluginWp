<?php
namespace Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("METHOD")
 */
final class CMThemePage extends Annotation {
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $capability;

    /**
     * @var array|string|null
     */
    public $js = null;

}