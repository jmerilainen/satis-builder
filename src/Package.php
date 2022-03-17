<?php

namespace Jmerilainen\SatisBuilder;

class Package
{
    protected string $fullName;

    protected string $distType = 'zip';

    public function __construct(
        protected string $vendor,
        protected string $name,
        protected string $version,
        protected string $type,
        protected string $distUrl,
    ) {
        $this->fullName = "{$this->vendor}/{$this->name}";
    }

    public static function fromParsed(array $data): static
    {
        return new static(
            $data['vendor'],
            $data['name'],
            $data['version'],
            $data['type'],
            $data['url'],
        );
    }

    public function toArray()
    {
        return [
            "type" => "package",
            "package" => [
                "name" => $this->fullName,
                "version" => $this->version,
                "type" => $this->type,
                "dist" => [
                    "url" => $this->distUrl,
                    "type" => $this->distType,
                ],
            ],
        ];
    }
}
