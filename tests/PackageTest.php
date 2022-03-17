<?php

use Jmerilainen\SatisBuilder\Package;

it('will output correct package array from parsed data', function () {
    $package = Package::fromParsed([
        'vendor' => 'test',
        'name' => 'package',
        'version' => '1.2',
        'type' => 'package',
        'url' => '/path/to/test/package-1.2.zip',
    ]);

    $expect = [
        "type" => "package",
        "package" => [
            "name" => 'test/package',
            "version" => '1.2',
            "type" => 'package',
            "dist" => [
                "url" => '/path/to/test/package-1.2.zip',
                "type" => 'zip',
            ],
        ],
    ];

    expect($package->toArray())->toBe($expect);
});
