<?php

namespace Jmerilainen\SatisBuilder;

use Symfony\Component\Finder\Finder;

class SatisBuilder
{
    protected $name;

    protected $homepage;

    protected $root;

    protected $input;

    protected $folder;

    protected $external;

    protected $satis = [];

    public function from(string $folder): self
    {
        $this->generate($folder);

        return $this;
    }

    protected function generate($input)
    {
        $files = (new Finder)
            ->in($input)
            ->files()
            ->name('*.zip')
            ->depth('< 3');

        $files = array_keys(iterator_to_array($files));

        $packages = array_map(function($path) use ($input) {
            $basename = basename($path);
            $relativePath = str_replace($input, '', $path);
            $depth = substr_count($relativePath, '/');

            if ($depth != 3) {
                throw new \Exception("File's $relativePath strucutre seems to be off.");
            }

            return Package::fromParsed([
                'url' => $path,
                'version' => $this->parseVersionFromFile($basename),
                'name' => $this->parsePackageNameFromFile($basename),
                'vendor' => basename(dirname($path)),
                'type' => $this->parseTypeFromFile($relativePath),
            ]);
        }, $files);

        $files = array_map(function(Package $pacakges) {
            return $pacakges->toArray();
        }, $packages);



        $external = $this->getExternalRepos($input . '/' . $this->external);

        $satis = array_merge($external, $files);

        $this->satis = $this->generateSatisJson($satis);
    }

    public function toArray()
    {
        return $this->satis;
    }

    public function name(string $input): self
    {
        $this->satis['name'] = $input;

        return $this;
    }

    public function homepage(string $input): self
    {
        $this->satis['homepage'] = $input;

        return $this;
    }

    public function external(string $input): self
    {
        $this->external = $input;

        return $this;
    }

    public function save($path): void
    {
        $file = fopen($path, 'w');

        fwrite(
            $file,
            json_encode($this->satis, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );

        fclose($file);
    }

    protected function getExternalRepos(string $file): array
    {
        if (! file_exists($file)) {
            return [];
        }

        $external = file_get_contents($file);

        $repos = json_decode($external, true)['repositories'] ?? [];

        return $repos;
    }

    protected function parseVersionFromFile(string $file): string
    {
        preg_match('/\d+(\.\d+)+/', $file, $matches);

        return $matches[0];
    }

    protected function parsePackageNameFromFile($file)
    {
        return preg_replace('/[\.\-_][\dv]+(\.\d+)+.[^\.]+$/', '', $file);
    }

    protected function parseTypeFromFile($file)
    {
        preg_match('/\/(.[^\/]+)\//', $file, $matches);

        return $matches[1] ?? 'package';
    }

    protected function generateSatisJson(array $pacakges): array
    {
        return [
            "name" => $this->name,
            "homepage" => $this->homepage,
            "repositories" => $pacakges,
            "archive" => [
                "directory" => 'dist',
                "format" => "tar",
                "skip-dev" => true
            ],
            "require-all" => true,
        ];
    }
}
