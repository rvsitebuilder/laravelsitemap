<?php

use Rvsitebuilder\Manage\Lib\ConfigLib;

return [
    'name' => 'rvsitebuilder/laravelsitemap',
    'leaving_out' => ConfigLib::getDbConfig('rvsitebuilder/laravelsitemap.leaving_out', 'admin'),
    'COOKIES' => ConfigLib::getDbConfig('rvsitebuilder/laravelsitemap.COOKIES', true),
    'ALLOW_REDIRECTS' => ConfigLib::getDbConfig('rvsitebuilder/laravelsitemap.ALLOW_REDIRECTS', false),
    'CONNECT_TIMEOUT' => ConfigLib::getDbConfig('rvsitebuilder/laravelsitemap.CONNECT_TIMEOUT', 10),
    'TIMEOUT' => ConfigLib::getDbConfig('rvsitebuilder/laravelsitemap.TIMEOUT', 10),
];
