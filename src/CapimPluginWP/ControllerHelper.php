<?php
namespace Utils;

use Symfony\Component\Finder\Finder;
use Twig_Environment;
use Twig_Loader_Filesystem;
use Twig_Extension_Debug;

class ControllerHelper{

    /**
     * Load controllers instances
     *
     * @return array
     */
    public function getControllers(){
        $controllers = array();
        $pathToControllers = dirname(dirname(__FILE__)) . "/Controller/";
        $finder = new Finder();
        $files = $finder->files()->in($pathToControllers)->name("*.php")->notName("CMController.php");
        foreach ($files as $file) {
            $baseName = str_replace(".php", "", $file->getBasename());
            $className = "Controller\\{$baseName}";
            $twig = new Twig_Environment(new Twig_Loader_Filesystem(dirname(dirname(dirname(__FILE__))) . "/app/Resources/views"), array(
                'cache' => false,
            ));
//            $twig->addExtension(new Twig_Extension_Debug());
            $twig->addGlobal("ajaxUrl", admin_url( 'admin-ajax.php' ));
            $dbHelper = new CMDbHelper(dirname(dirname(dirname(__FILE__))) . "/var/cache/cm-db");
            $class = new $className($twig, $dbHelper);
            $controllers[] = $class;
        }
        return $controllers;
    }
}