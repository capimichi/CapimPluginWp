<?php
namespace Controller;

use Twig_Environment;

abstract class Controller
{

    /**
     * @var Twig_Environment
     */
    private $twig;

    /**
     * @var CMDbHelper
     */
    private $dbHelper;

    public function __construct($twig, $dbHelper)
    {
        $this->twig = $twig;
        $this->dbHelper = $dbHelper;
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
     * @return CMDbHelper
     */
    protected function getDbHelper(){
        return $this->dbHelper;
    }
}