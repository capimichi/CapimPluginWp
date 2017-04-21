<?php
namespace CapimPluginWP;


class Kernel{

    /**
     * @var array
     */
    protected $controllerDirectories;

    /**
     * Kernel constructor.
     * @param $controllerDirectories
     */
    public function __construct($controllerDirectories)
    {
        $this->controllerDirectories = $controllerDirectories;
    }


}