<?php
namespace Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("METHOD")
 */
final class CMAdminPage extends Annotation {
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $icon = "";

    /**
     * @var string
     */
    public $capability;

    /**
     * @var int|null
     */
    public $position = null;

    /**
     * @var string|null
     */
    public $parent = null;

    /**
     * @var array|string|null
     */
    public $js = null;

    public $attributes;
}