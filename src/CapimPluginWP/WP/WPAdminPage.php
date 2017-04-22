<?php
namespace CapimPluginWP\WP;

class WPAdminPage
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $capability;

    /**
     * @var callable
     */
    private $callable;

    /**
     * @var string|null
     */
    private $parent;

    /**
     * @var int
     */
    private $position;

    /**
     * @var string
     */
    private $icon;

    /**
     * WPAdminPage constructor.
     * @param $name
     * @param $capability
     * @param callable $callable
     * @param string $parent
     * @param string $icon
     * @param null $position
     */
    public function __construct($name, $capability, callable $callable, $parent = null, $icon = "", $position = null)
    {
        $this->name = $name;
        $this->capability = $capability;
        $this->callable = $callable;
        $this->parent = $parent;
        $this->icon = $icon;
        $this->position = $position;
    }

    /**
     * Load in memory
     */
    public function load(){
        if (!$this->getParent()) {
            if(function_exists("add_menu_page")) {
                add_menu_page(
                    $this->getPageTitle(),
                    $this->getMenuTitle(),
                    $this->getCapability(),
                    $this->getMenuSlug(),
                    $this->getCallable(),
                    $this->getIcon(),
                    $this->getPosition()
                );
            }
        } else {
            if(function_exists("add_submenu_page")) {
                add_submenu_page(
                    $this->getParent(),
                    $this->getPageTitle(),
                    $this->getMenuTitle(),
                    $this->getCapability(),
                    $this->getMenuSlug(),
                    $this->getCallable()
                );
            }
        }
    }

    /**
     * @return string
     */
    protected function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    protected function getPageTitle()
    {
        return ucfirst($this->name);
    }

    /**
     * @return string
     */
    protected function getMenuTitle()
    {
        return ucfirst($this->name);
    }

    /**
     * @return string
     */
    protected function getMenuSlug()
    {
        return strtolower(str_replace(" ", "_", $this->name));
    }

    /**
     * @return string
     */
    protected function getCapability()
    {
        return $this->capability;
    }

    /**
     * @return callable
     */
    protected function getCallable()
    {
        return $this->callable;
    }

    /**
     * @return null|string
     */
    protected function getParent()
    {
        return str_replace(array(" "), "_", $this->parent);
    }

    /**
     * @return int|null
     */
    protected function getPosition()
    {
        return $this->position;
    }

    /**
     * @return string
     */
    protected function getIcon()
    {
        return $this->icon;
    }
}