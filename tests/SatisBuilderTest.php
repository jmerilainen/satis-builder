<?php

use Jmerilainen\SatisBuilder\SatisBuilder;

it('will generate correct satis.json from path', function () {
    $root = normalizePath(__DIR__ . '/fixtures/case1');
    $satis = (new SatisBuilder())
        ->from($root)
        ->name('vendor/satis')
        ->homepage('https://satis.vendor.fi');

    $pacakges = [
        [
            "type" => "vcs",
            "url" => "https://github.com/jmerilainen/test-package",
        ],
        [
            "type" => "package",
            "package" => [
                "name" => 'testvendor/test-package',
                "version" => '1.1',
                "type" => 'package',
                "dist" => [
                    "url" => normalizePath($root .'/package/testvendor/test-package-1.1.zip'),
                    "type" => 'zip',
                ],
            ],
        ],
        [
            "type" => "package",
            "package" => [
                "name" => 'testvendor/test-package',
                "version" => '1.0.2',
                "type" => 'package',
                "dist" => [
                    "url" => normalizePath($root .'/package/testvendor/test-package.1.0.2.zip'),
                    "type" => 'zip',
                ],
            ],
        ],
    ];

    $expect = [
        "name" => 'vendor/satis',
        "homepage" => 'https://satis.vendor.fi',
        "repositories" => $pacakges,
        "archive" => [
            "directory" => 'dist',
            "format" => 'tar',
            "skip-dev" => true,
        ],
        "require-all" => true,
    ];

    expect($satis->toArray())->toBe($expect);
});
