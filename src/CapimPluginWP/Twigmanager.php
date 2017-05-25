<?php

namespace CapimPluginWP;

use Twig_Environment;
use Twig_SimpleFunction;

class Twigmanager
{

    /**
     * @param Twig_Environment $twig
     * @return Twig_Environment
     */
    public static function addGlobals($twig)
    {
        if (function_exists("admin_url")) {
            $twig->addGlobal("ajaxUrl", admin_url('admin-ajax.php'));
        }
        return $twig;
    }

    /**
     * @param Twig_Environment $twig
     * @return Twig_Environment
     */
    public static function addFunctions($twig)
    {
        $functions = [
            "wp_get_attachment_image_src",
            "get_the_post_thumbnail_url",
        ];
        foreach ($functions as $function) {
            if (function_exists($function)) {
                $twigFunction = new Twig_SimpleFunction($function, $function);
                $twig->addFunction($twigFunction);
            }
        }
        return $twig;
    }
}