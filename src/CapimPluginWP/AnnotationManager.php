<?php
namespace CapimPluginWP;

use CapimPluginWP\Annotation\Action;
use CapimPluginWP\Annotation\AdminPage;
use CapimPluginWP\Annotation\Ajax;
use CapimPluginWP\Annotation\Filter;
use CapimPluginWP\Annotation\Metabox;
use CapimPluginWP\Annotation\Shortcode;
use CapimPluginWP\Annotation\ThemePage;
use Doctrine\Common\Annotations\Annotation;

class AnnotationManager{


    /**
     * AnnotationManager constructor.
     * @param Annotation $annotation
     * @param callable $callable
     */
    public function __construct($annotation, $callable)
    {
        switch (true) {
            case $annotation instanceof Action:
                $this->addAction($annotation->hook, $callable);
                break;
            case $annotation instanceof Filter:
                $this->addFilter($annotation->hook, $callable);
                break;
            case $annotation instanceof AdminPage:
                $adminPage = new CMWpAdminPage(
                    $annotation->name,
                    $annotation->capability,
                    $callable,
                    $annotation->parent,
                    $annotation->icon,
                    $annotation->position
                );
                $this->addAdminPage($adminPage);
                break;
            case $annotation instanceof Shortcode:
                $shortcode = new CMWpShortcode($annotation->name, $callable);
                $this->addShortcode($shortcode);
                break;
            case $annotation instanceof Ajax:
                $ajax = new CMWpAjax($annotation->name, $callable, $annotation->public, $annotation->admin);
                $this->addAjax($ajax);
                break;
            case $annotation instanceof ThemePage:
                $themePage = new CMWpThemePage(
                    $annotation->name,
                    $annotation->capability,
                    $callable
                );
                $this->addThemePage($themePage);
                break;
            case $annotation instanceof Metabox:
                $metabox = new CMWpMetabox($annotation->name, $callable, $annotation->screen, $annotation->context, $annotation->priority, $annotation->form);
                $this->addMetabox($metabox);
                break;
        }
    }

    /**
     * @param $hook
     * @param callable $callable
     */
    public function addAction($hook, callable $callable)
    {
        if (!$hook) {
            $hook = "init";
        }
        if(function_exists("add_action")) {
            add_action($hook, $callable);
        }
    }

    /**
     * @param $hook
     * @param callable $callable
     */
    public function addFilter($hook, callable $callable, $priority = 10, $args = 1)
    {
        if (!$hook) {
            $hook = "init";
        }
        if(function_exists("add_filter")) {
            add_filter($hook, $callable, $priority, $args);
        }
    }

    /**
     * @param CMWpAdminPage $adminPage
     */
    public function addAdminPage($adminPage)
    {
        $this->addAction("admin_menu", array($adminPage, "load"));
    }

    /**
     * @param CMWpThemePage $themePage
     */
    public function addThemePage($themePage)
    {
        $this->addAction("admin_menu", array($themePage, "load"));
    }

    /**
     * @param CMWpShortcode $shortcode
     */
    public function addShortcode($shortcode)
    {
        $this->addAction("init", array($shortcode, "load"));
    }

    /**
     * @param CMWpAjax $ajax
     */
    public function addAjax($ajax)
    {
        $ajax->load();
    }

    /**
     * @param CMWpMetabox $metabox
     */
    public function addMetabox($metabox)
    {
        $this->addAction("add_meta_boxes", array($metabox, "load"));
        $this->addAction("save_post", $metabox->getCallable());
    }
}