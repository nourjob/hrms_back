<?php
// config/cors.php

return [

    'paths' => ['api/*', 'sanctum/csrf-cookie'], // السماح للمسارات الخاصة بـ API و CSRF cookies

    'allowed_methods' => ['*'], // السماح بكل طرق HTTP (GET, POST, PUT, DELETE...)

'allowed_origins' => ['*'],
    // السماح باستخدام الكوكيز مع Sanctum

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'], // السماح بجميع رؤوس الطلبات

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true, // تأكد من أنها true إذا كنت تستخدم cookies مع Sanctum

];
