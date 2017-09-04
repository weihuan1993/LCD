<?php

return [

    'driver' => 'smtp',
    'host' => 'smtp.qq.com',
    'port' => 465,
    'from' => ['address' => '850041698@qq.com', 'name' => '系统'],
    'encryption' => 'ssl',
    'username' => '850041698@qq.com',
    'password' => 'weihuan8500',
    'sendmail' => '/usr/sbin/sendmail -bs',
];
