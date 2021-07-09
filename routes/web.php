<?php
$router->get('/hello', function(){
   return '你在访问hello';
});


$router->get('/user/login', function(){
    return '你在访问/user/login';
})->middleware(\App\middleware\WebMiddleWare::class);