<?php

declare(strict_types=1);

namespace App\Utilities;

use Illuminate\Support\Str;

use function filter_var;
use function ltrim;
use function rtrim;

use const FILTER_VALIDATE_URL;

class UrlManager
{
    protected string $url = '';

    public function setBaseUrl(string $url) : self
    {
        $this->url = rtrim($url, '/');

        return $this;
    }

    public function concat(string $uri) : self
    {
        $this->url  = rtrim($this->url, '/');
        $this->url .= $this->processPrefix($uri);

        return $this;
    }

    public function getUrl() : string
    {
        return $this->url;
    }

    protected function processPrefix(string $url, string $prefix = '/') : string
    {
        if (Str::startsWith($url, $prefix)) {
            $url = ltrim($url, $prefix);
        }

        if (filter_var($url, FILTER_VALIDATE_URL)) {
            return $url;
        }

        return $prefix . $url;
    }

    protected function processSuffix(string $url, string $suffix = '/') : string
    {
        if (Str::startsWith($url, $suffix)) {
            $url = rtrim($url, $suffix);
        }

        return $url . $suffix;
    }
}
