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
     * @var string|bool
     */
    protected $twigCacheDir;

    /**
     * @var string|null
     */
    protected $annotationCacheDir;

    /**
     * @var string|null
     */
    protected $cmdbCacheDir;

    /**
     * @var array
     */
    protected $controllerDirectories;

    /**
     * Kernel constructor.
     * @param $templateDir
     * @param null $pluginFile
     * @param array $controllerDirectories
     * @param null|bool $twigCacheDir
     * @param null|string $annotationCacheDir
     * @param null|string $cmdbCacheDir
     */
    public function __construct($templateDir, $pluginFile = null, $controllerDirectories = array(), $twigCacheDir = false, $annotationCacheDir = null, $cmdbCacheDir = null)
    {
        $this->templateDir = $templateDir;
        $this->pluginFile = $pluginFile;
        $this->twigCacheDir = rtrim($twigCacheDir, "/") . "/";
        $this->annotationCacheDir = rtrim($annotationCacheDir, "/") . "/";
        $this->cmdbCacheDir = rtrim($cmdbCacheDir, "/") . "/";
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
            dirname(__FILE__) . "/Annotation/Action.php",
            dirname(__FILE__) . "/Annotation/AdminPage.php",
            dirname(__FILE__) . "/Annotation/Ajax.php",
            dirname(__FILE__) . "/Annotation/Filter.php",
            dirname(__FILE__) . "/Annotation/Shortcode.php",
            dirname(__FILE__) . "/Annotation/ThemePage.php",
            dirname(__FILE__) . "/Annotation/Metabox.php",
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
        if ($this->annotationCacheDir) {
            $reader = new FileCacheReader(new AnnotationReader(), $this->annotationCacheDir);
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
                    'cache' => $this->twigCacheDir,
                ));
                if(function_exists("admin_url")) {
                    $twig->addGlobal("ajaxUrl", admin_url('admin-ajax.php'));
                }
                $persistenceManager = new PersistenceManager($this->cmdbCacheDir);
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