# Satis.json builder

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jmerilainen/satis-builder.svg?style=flat-square)](https://packagist.org/packages/jmerilainen/satis-builder)
[![Tests](https://github.com/jmerilainen/satis-builder/actions/workflows/run-tests.yml/badge.svg?branch=main)](https://github.com/jmerilainen/satis-builder/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/jmerilainen/satis-builder.svg?style=flat-square)](https://packagist.org/packages/jmerilainen/satis-builder)

The tool will generate a `satis.json` file for Satis by scanning archives based on the folder structure.

Satis is a composer's static repository generator. Read more about Satis and satis.json from the composer's documentation: [https://getcomposer.org/doc/articles/handling-private-packages.md](https://getcomposer.org/doc/articles/handling-private-packages.md)

## Installation

> Note: The package has not yet been published to packagist

You can install the package via composer:

```bash
composer require jmerilainen/satis-builder
```

## Usage

```sh
vendor/bin/satis-builder build [options]

Options:
    --from          Root of the folder to build from (required, default: ./packages)
    --external      JSON file including any additional/exterenal repsoitories to include (required, default: ./packages/external.jspn)
    --output        JSON file to generate (required, default: satis.json)
    --name          Name of the satis repository package, exmpale vendor/satis (required)
    --homepage      Homepage of the output satis repository package (required)
```

### Folder structure

The tool will be generate the json based on the following folder structure:


```sh
./                      # Root
└── packages/           # All included packages, --from option
    ├── <type>          # First level: package type
    │   ├── <vendor>/   # Second level: vendor namespace
    │   │   ├── <package-name>-<version>.zip #: Third level: package archive
    │   │   └── ...
    │   └── ...
    ├── ...
    └── external.json # json file with additional repositories
```

All naming should follow the composer.json's schema:
[The composer.json schema](https://getcomposer.org/doc/04-schema.md#name).

See `tests/fixtures/case1` for working example.

### Usage with Satis repository

In the Satis repository project use the `satis-builder build` command to generate satis.json for Satis to consume.

Example `composer.json` file:

```json
{
    "name": "vendor/satis",
    "description": "oSatis Repository",
    "type": "project",
    "require": {
        "jmerilainen/satis-builder": "dev-main",
        "composer/satis": "dev-main",
    },
    "require-dev": {},
    "scripts": {
        "build-json": "vendor/bin/satis-builder build --from=packages --external=packages/external.json --name=vendor/satis --homepage=https://satis.vendor.fi --output=satis.json",
        "build-satis": "vendor/bin/satis build satis.json dist",
        "build": [
            "@build-json",
            "@build-satis",
            "rm satis.json"
        ]
    }
}
```

Running the `composer build` will then generate the satis.json and the repository with compiled assets and archives.

See also: [composer/satis](https://github.com/composer/satis)

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Roadmap

- [ ] Get the name and homepage defaults from the repository project's composer.json
- [ ] Validate structure and package names based on composer.json schema
- [ ] Add more options and modification possibilities based on satis.json schema
- [ ] Publish to packagist
## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Joonas Meriläinen](https://github.com/jmerilainen)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
