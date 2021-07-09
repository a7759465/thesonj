<?php


function hello()
{
    return "world";
}


if (!function_exists('response')) {
    function response()
    {
        return App::getContainer()->get('response');
    }
}


function app($name = null)
{
    if ($name) // 如果选择了具体实例
        return App::getContainer()->get($name);
    return App::getContainer();
}

function endView()
{
    $time = microtime(true) - FRAME_START_TIME;
    $memory = memory_get_usage() - FRAME_START_MEMORY;

    echo '<br/><br/><br/><br/><br/><hr/>';
    echo "运行时间: " . round($time * 1000, 2) . 'ms<br/>';
    echo "消耗内存: " . round($memory / 1024 / 1024, 2) . 'm';
}


function config($key = null)
{
    if ($key)
        return App::getContainer()->get('config')->get($key);
    return App::getContainer()->get('config');
}

if (! function_exists('env')) {
    /**
     * Gets the value of an environment variable.
     *
     * @param string $key
     * @param null|mixed $default
     */
    function env($key, $default = null)
    {
        $value = getenv($key);
        if ($value === false) {
            return value($default);
        }
        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return;
        }
        if (($valueLength = strlen($value)) > 1 && $value[0] === '"' && $value[$valueLength - 1] === '"') {
            return substr($value, 1, -1);
        }
        return $value;
    }
}

if (! function_exists('value')) {
    /**
     * Return the default value of the given value.
     *
     * @param mixed $value
     */
    function value($value)
    {
        return $value instanceof \Closure ? $value() : $value;
    }
}

function dd($data) {
    echo json_encode($data);die;
}

function de($data) {
    echo "<pre>";
    var_export($data);die;
}