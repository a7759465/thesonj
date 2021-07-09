<?php


namespace core;


class Config
{
    protected $config = [];

    //扫描config文件夹, 加入到配置的大数组
    public function init()
    {
        foreach (glob())
    }
}