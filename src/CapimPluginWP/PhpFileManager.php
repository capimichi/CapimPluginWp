<?php

namespace CapimPluginWP;

use Symfony\Component\Finder\SplFileInfo;

class PhpFileManager{

    /**
     * @var SplFileInfo
     */
    protected $file;

    /**
     * PhpFileManager constructor.
     * @param SplFileInfo $file
     */
    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * @return string
     */
    public function getFilePath(){
        return $this->file->getRealPath();
    }

    /**
     * @return string
     */
    public function getNamespace(){
        $namespace = "";
        if(preg_match("/namespace(.*?);/is", $this->file->getContents(), $namespace)){
            $namespace = rtrim(trim($namespace[1]), "\\") . "\\";
        }
        return $namespace;
    }

    /**
     * @return string
     */
    public function getClassName(){
        $className = $this->getNamespace() . str_replace(".php", "", $this->file->getFilename());
        return $className;
    }


}