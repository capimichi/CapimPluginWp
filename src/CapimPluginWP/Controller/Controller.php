<?php
namespace Controller;

use CapimPluginWP\PersistenceManager;
use Twig_Environment;

abstract class Controller
{

    /**
     * @var Twig_Environment
     */
    private $twig;

    /**
     * @var PersistenceManager
     */
    private $persistenceManager;

    public function __construct($twig, $persistenceManager)
    {
        $this->twig = $twig;
        $this->persistenceManager = $persistenceManager;
    }

    /**
     * Render a template
     *
     * @param $name
     * @param array $context
     * @return string
     */
    protected function render($name, array $context = array()){
        return $this->twig->render($name, $context);
    }

    /**
     * @return PersistenceManager
     */
    protected function getPersistenceManager(){
        return $this->persistenceManager;
    }
}