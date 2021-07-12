<?php
declare(strict_types=1);
require __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/../app.php';




1





//绑定request先
App::getContainer()->bind(\core\request\RequestInterface::class, function () {
    return \core\request\PhpRequest::create($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD'], $_SERVER);
});


//app()->get('response')->setContent(app()->get(\core\request\RequestInterface::class)->getMethod())->send();
//app()->get(\core\request\RequestInterface::class)->getMethod()

//app()->get('response')->setContent(['oh year'])->send();
//dd(app(\core\request\RequestInterface::class));
//echo app('router')->dispatch(app(\core\request\RequestInterface::class));die;

//app('response')->setContent(app('router')->dispatch(app(\core\request\RequestInterface::class)))->send();


App::getContainer()->get('response')->setContent(
    App::getContainer()->get('router')->dispatch(
        App::getContainer()->get(\core\request\RequestInterface::class)
    )
)->send();