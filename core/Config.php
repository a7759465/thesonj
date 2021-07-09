<?php


namespace core;


class Config
{
    protected $config = [];

    //扫描config文件夹, 加入到配置的大数组
    public function init()
    {
        foreach (glob(FRAME_BASE_PATH . '/config/*.php') as $file) {
            $key = str_replace('.php', '', basename($file));
            $this->config[$key] = require_once $file;
        }
    }

    public function get($key)
    {
        $keys = explode('.', $key);
        $config = $this->config;
        foreach ($keys as $key) {
            $config = $config[$key];
        }
        return $config;
    }

    public function set($key, $val)
    {
        $keys = explode('.', $key);
        $newconfig = &$this->config;
        foreach ($keys as $key) {
            $newconfig = &$newconfig[$key];
        }
        $newconfig = $val;
    }

}