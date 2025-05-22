<?php
// config/cors.php

return [

    'paths' => ['api/*', 'sanctum/csrf-cookie'], // السماح للمسارات الخاصة بـ API و CSRF cookies

    'allowed_methods' => ['*'], // السماح بكل طرق HTTP (GET, POST, PUT, DELETE...)

    'allowed_origins' => ['http://localhost:3000', 'http://127.0.0.1:3000'],  // السماح بالاتصال من هذا العنوان
    // السماح باستخدام الكوكيز مع Sanctum

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'], // السماح بجميع رؤوس الطلبات

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true, // تأكد من أنها true إذا كنت تستخدم cookies مع Sanctum

];
