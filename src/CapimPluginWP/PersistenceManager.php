<?php

namespace CapimPluginWP;

class PersistenceManager
{

    /**
     * @var string|null
     */
    protected $cacheDir;

    /**
     * DbHelper constructor.
     * @param null|string $cacheDir
     */
    public function __construct($cacheDir = null)
    {
        if ($cacheDir) {
            if (!file_exists($cacheDir)) {
                mkdir($cacheDir, 0777, true);
            }
            $cacheDir = rtrim($cacheDir, '/') . "/";
        }
        $this->cacheDir = $cacheDir;
    }

    /**
     * @param string $key
     * @return array
     */
    public function getArray($key)
    {
        $data = $this->getDbValue($key);
        $array = [];
        if ($data) {
            $array = unserialize(base64_decode($data));
        }
        return $array;
    }

    /**
     * @param string $key
     * @param array $array
     */
    public function setArray($key, array $array)
    {
        $data = base64_encode(serialize($array));
        $this->setDbValue($key, $data);
    }

    /**
     * @param string $key
     * @return null|string
     */
    public function getString($key)
    {
        $data = $this->getDbValue($key);
        return $data;
    }

    /**
     * @param string $key
     * @param string $text
     */
    public function setString($key, $text)
    {
        $this->setDbValue($key, $text);
    }

    /**
     * @param string $key
     * @return string|null
     */
    private function getDbValue($key)
    {
        global $wpdb;

        if ($this->isCacheEnabled()) {
            $val = $this->getCache($key);
        }
        if (!$this->isCacheEnabled() || ($val == null)) {
            $datas = $wpdb->get_results("
	        SELECT value 
	        FROM cm_options
	        WHERE name = '{$key}' 
            ");
            $val = null;
            foreach ($datas as $data) {
                $val = $data->value;
            }
            if(!$this->isCached($key) && ($val != null)){
                $this->setCache($key, $val);
            }
        }
        return $val;
    }

    /**
     * @param string $key
     * @param string $value
     */
    private function setDbValue($key, $value)
    {
        global $wpdb;
        if ($this->getDbValue($key) !== null) {
            $wpdb->update(
                'cm_options',
                array(
                    'value' => $value,
                ),
                array('name' => $key),
                array(
                    '%s'
                ),
                array('%s')
            );
        } else {
            $wpdb->insert(
                'cm_options',
                array(
                    'name' => $key,
                    'value' => $value,
                ),
                array(
                    '%s',
                    '%s'
                )
            );
        }
        $this->setCache($key, $value);
    }

    /**
     * @return bool
     */
    private function isCacheEnabled()
    {
        return ($this->cacheDir) ? true : false;
    }

    /**
     * @param $key
     * @return bool
     */
    private function isCached($key){
        $cachePath = $this->cacheDir . md5($key);
        return is_readable($cachePath);
    }

    /**
     * @param $key
     * @return mixed|null
     */
    private function getCache($key)
    {
        if ($this->isCacheEnabled()) {
            $cachePath = $this->cacheDir . md5($key);
            if (is_readable($cachePath)) {
                return unserialize(base64_decode(file_get_contents($cachePath)));
            }
        }
        return null;
    }

    /**
     * @param $key
     * @param $value
     */
    private function setCache($key, $value)
    {
        if ($this->isCacheEnabled()) {
            $cachePath = $this->cacheDir . md5($key);
            file_put_contents($cachePath, base64_encode(serialize($value)));
        }
    }

    /**
     * @param $key
     */
    private function removeCache($key)
    {
        if ($this->isCacheEnabled()) {
            $cachePath = $this->cacheDir . md5($key);
            if (file_exists($cachePath)) {
                unlink($cachePath);
            }
        }
    }

}