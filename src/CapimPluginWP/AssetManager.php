<?php
namespace CapimPluginWP;

class AssetManager
{
    /**
     * @var string
     */
    private $adminJsPaths;

    /**
     * @var string
     */
    private $adminCssPaths;

    /**
     * @var string
     */
    private $publicJsPaths;

    /**
     * @var string
     */
    private $publicCssPaths;

    /**
     * @var string
     */
    private $loginJsPaths;

    /**
     * @var string
     */
    private $loginCssPaths;

    /**
     * AssetHelper constructor.
     */
    public function __construct()
    {
        $this->adminJsPaths = array();
        $this->adminCssPaths = array();
        $this->publicJsPaths = array();
        $this->publicCssPaths = array();
        $this->loginJsPaths = array();
        $this->loginCssPaths = array();
    }

    /**
     * Load in memory
     */
    public function load()
    {
        if (function_exists("add_action")) {
            add_action('admin_footer', array($this, "enqueueAdminJs"));
            add_action('admin_footer', array($this, 'enqueueAdminCss'));
            add_action('wp_footer', array($this, "enqueuePublicJs"));
            add_action('wp_footer', array($this, 'enqueuePublicCss'));
            add_action('login_enqueue_scripts', array($this, "enqueueLoginJs"));
            add_action('login_enqueue_scripts', array($this, 'enqueueLoginCss'));
        }
    }

    /**
     * Add new public css
     *
     * @param $path
     */
    public function addPublicCss($path)
    {
        $this->publicCssPaths[] = $path;
    }

    /**
     * Add new public js
     *
     * @param $path
     */
    public function addPublicJs($path)
    {
        $this->publicJsPaths[] = $path;
    }

    /**
     * Add new login css
     *
     * @param $path
     */
    public function addLoginCss($path)
    {
        $this->loginCssPaths[] = $path;
    }

    /**
     * Add new login js
     *
     * @param $path
     */
    public function addLoginJs($path)
    {
        $this->loginJsPaths[] = $path;
    }

    /**
     * Add new admin css
     *
     * @param $path
     */
    public function addAdminCss($path)
    {
        $this->adminCssPaths[] = $path;
    }

    /**
     * Add new admin js
     *
     * @param $path
     */
    public function addAdminJs($path)
    {
        $this->adminJsPaths[] = $path;
    }

    /**
     * Enqueue public css
     */
    public function enqueuePublicCss()
    {
        foreach ($this->publicCssPaths as $publicCssPath) {
            ?>
            <link rel="stylesheet" href="<?php echo $publicCssPath; ?>"/>
            <?php
        }
    }

    /**
     * Enqueue public js
     */
    public function enqueuePublicJs()
    {
        if (function_exists("wp_register_script") && function_exists("wp_enqueue_script")) {
            foreach ($this->publicJsPaths as $publicJsPath) {
                $name = basename($publicJsPath);
                $name = "cm-{$name}";
                wp_register_script($name, $publicJsPath, array(), false, true);
                wp_enqueue_script($name);
            }
        }
    }

    /**
     * Enqueue login css
     */
    public function enqueueLoginCss()
    {
        foreach ($this->publicCssPaths as $publicCssPath) {
            ?>
            <link rel="stylesheet" href="<?php echo $publicCssPath; ?>"/>
            <?php
        }
    }

    /**
     * Enqueue login js
     */
    public function enqueueLoginJs()
    {
        if (function_exists("wp_register_script") && function_exists("wp_enqueue_script")) {
            foreach ($this->publicJsPaths as $publicJsPath) {
                $name = basename($publicJsPath);
                $name = "cm-{$name}";
                wp_register_script($name, $publicJsPath, array(), false, true);
                wp_enqueue_script($name);
            }
        }
    }

    /**
     * Enqueue admin css
     */
    public function enqueueAdminCss()
    {
        foreach ($this->adminCssPaths as $adminCssPath) {
            ?>
            <link rel="stylesheet" href="<?php echo $adminCssPath; ?>"/>
            <?php
        }
    }

    /**
     * Enqueue admin js
     */
    public function enqueueAdminJs()
    {
        if (function_exists("wp_register_script") && function_exists("wp_enqueue_script")) {
            foreach ($this->adminJsPaths as $adminJsPath) {
                $name = basename($adminJsPath);
                $name = "cm-{$name}";
                wp_register_script($name, $adminJsPath, array(), false, true);
                wp_enqueue_script($name);
            }
        }
    }
}