<?php
namespace CapimPluginWP;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\FileCacheReader;
use ReflectionMethod;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class Kernel
{

    /**
     * @var string|null
     */
    protected $pluginFile;

    /**
     * @var string|null
     */
    protected $cacheDir;

    /**
     * @var array
     */
    protected $controllerDirectories;

    /**
     * Kernel constructor.
     * @param null $pluginFile
     * @param null $cacheDir
     * @param array $controllerDirectories
     */
    public function __construct($pluginFile = null, $cacheDir = null, $controllerDirectories = array())
    {
        $this->pluginFile = $pluginFile;
        $this->cacheDir = rtrim($cacheDir, "/") . "/";
        $this->controllerDirectories = $controllerDirectories;
        $this->controllerDirectories[] = dirname(__FILE__) . "/Controller";
        $this->loadFiles();
        $this->loadAnnotations();
    }

    /**
     * Require files
     */
    protected function loadFiles()
    {
        $loaders = array(
            dirname(dirname(__FILE__)) . "/vendor/autoload.php",
            dirname(__FILE__) . "/Annotations/CMAction.php",
            dirname(__FILE__) . "/Annotations/CMAdminPage.php",
            dirname(__FILE__) . "/Annotations/CMAjax.php",
            dirname(__FILE__) . "/Annotations/CMFilter.php",
            dirname(__FILE__) . "/Annotations/CMShortcode.php",
            dirname(__FILE__) . "/Annotations/CMThemePage.php",
            dirname(__FILE__) . "/Annotations/CMMetabox.php",
        );
        foreach ($loaders as $loader) {
            require $loader;
        }
    }

    /**
     * Laod annotations
     */
    protected function loadAnnotations()
    {
        if($this->cacheDir){
            $reader = new FileCacheReader(new AnnotationReader(), $this->cacheDir . "annotations/");
        } else {
            $reader = new AnnotationReader();
        }
        foreach ($this->controllerDirectories as $controllerDirectory) {
            $finder = new Finder();
            $files = $finder->files()->in($controllerDirectory)->name("*.php")->notName("Controller.php");
            foreach ($files as $file) {
                $phpFileManager = new PhpFileManager($file);
                $className = $phpFileManager->getClassName();
                $class = new $className();
                $methods = get_class_methods($class);
                foreach ($methods as $method) {
                    $reflMethod = new ReflectionMethod($class, $method);
                    $annotations = $reader->getMethodAnnotations($reflMethod);
                    foreach ($annotations as $annotation) {
                        new AnnotationManager($annotation, array($class, $method));
                    }
                }
            }
        }
    }


}