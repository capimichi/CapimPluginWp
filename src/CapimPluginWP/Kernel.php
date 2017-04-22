<?php
namespace CapimPluginWP;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\FileCacheReader;
use ReflectionMethod;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Twig_Environment;
use Twig_Loader_Filesystem;

class Kernel
{

    /**
     * @var string
     */
    protected $templateDir;

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
     * @param string $templateDir
     * @param null $pluginFile
     * @param null $cacheDir
     * @param array $controllerDirectories
     */
    public function __construct($templateDir, $pluginFile = null, $cacheDir = null, $controllerDirectories = array())
    {
        $this->templateDir = $templateDir;
        $this->pluginFile = $pluginFile;
        $this->cacheDir = rtrim($cacheDir, "/") . "/";
        $this->controllerDirectories = $controllerDirectories;
        $this->controllerDirectories[] = dirname(__FILE__) . "/Controller";
        $this->loadFiles();
        $this->loadAnnotations();
    }

    /**
     * On plugin activation
     */
    protected function loadActivation()
    {
        if(function_exists("register_activation_hook")){
            if($this->pluginFile) {
                register_activation_hook($this->pluginFile, array(new Activation(), "load"));
            }
        }
    }

    /**
     * Require files
     */
    protected function loadFiles()
    {
        $loaders = array(
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
     * Load annotations
     */
    protected function loadAnnotations()
    {
        if ($this->cacheDir) {
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
                $twig = new Twig_Environment(new Twig_Loader_Filesystem($this->templateDir), array(
                    'cache' => $this->cacheDir ? $this->cacheDir . "twig/" : false,
                ));
                if(function_exists("admin_url")) {
                    $twig->addGlobal("ajaxUrl", admin_url('admin-ajax.php'));
                }
                $persistenceManager = new PersistenceManager($this->cacheDir ? $this->cacheDir . "cmdb/" : false);
                $class = new $className($twig, $persistenceManager);
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