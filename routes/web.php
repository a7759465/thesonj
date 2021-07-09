<?php
$router->get('/hello', function(){
   return '你在访问hello';
});


$router->get('/user/login', function(){
    return '你在访问/user/login';
})->middleware(\App\middleware\WebMiddleWare::class);

$router->get('/config',function (){
   echo app('config')->get('redis.host');
});

$router->get('/db', function(){
   $ret = app('db')->select('select * from cs_product');
   dd($ret);
});