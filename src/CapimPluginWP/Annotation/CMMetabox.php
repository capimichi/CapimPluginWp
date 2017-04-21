<?php
namespace Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("METHOD")
 */
final class CMMetabox extends Annotation {
    /**
     * @var string
     */
    public $name;

    /**
     * @var string|array
     */
    public $screen = "post";

    /**
     * @var string , accepted values: normal, side, advanced
     */
    public $context = "advanced";

    /**
     * @var string , accepted values: high, low
     */
    public $priority = "default";

    /**
     * @var array
     */
    public $form = array();
}