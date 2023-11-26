<?php

namespace App\Widgets;

use App\Widgets\Widget;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use JetBrains\PhpStorm\ArrayShape;
use Override;

class HttpCheckWidget extends Widget
{
    private string $url;
    private string $name;
    private string $description;
    private int $httpCode;
    private float $responseTime;
    private string $icon;

    /**
     * Argument that is used to determine if the website is up or not.
     * @var int|mixed $okCode
     */
    private int $okCode;
    /**
     * Argument that is used to determine the timeout of the request.
     * @var int|mixed $timeout
     */
    private int $timeout;

    #[Override] public function __construct($arguments = [])
    {
        parent::__construct($arguments);

        $this->url = $this->arguments['url'] ?? '';
        $this->okCode = $this->arguments['okCode'] ?? 200;
        $this->timeout = $this->arguments['timeout'] ?? 10;

        $this->name = $this->arguments['name'] ?? 'Http Check';
        $this->description = $this->arguments['description'] ?? 'Check if a website is up or not.';
        $this->icon = $this->arguments['icon'] ?? 'fa fa-globe';
    }

    /** @noinspection PhpComposerExtensionStubsInspection */
    #[Override] public function process(): bool
    {
        if (function_exists('curl_version')) {
            $curl = curl_init($this->url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HEADER, true);
            curl_setopt($curl, CURLOPT_NOBODY, true);
            curl_setopt($curl, CURLOPT_TIMEOUT, 10);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_MAXREDIRS, 3);
            curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
            curl_exec($curl);
            $this->httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            // Convert from microseconds to ms
            $this->responseTime = curl_getinfo($curl, CURLINFO_TOTAL_TIME_T) / 1000.0;
            curl_close($curl);
        } else {
            $context = stream_context_create([
                'http' => [
                    'method' => 'HEAD',
                    'timeout' => $this->timeout,
                ],
            ]);
            $headers = get_headers($this->url, 1, $context);
            $this->httpCode = $headers[0];
            $this->responseTime = $headers['Request-Elapsed-Time'];
        }
        return true;
    }

    #[Override] public function render(): View|\Illuminate\Foundation\Application|Factory|Application|string
    {
        return view('widgets.http-check-widget', [
            'widget' => $this,
            'isUp' => $this->httpCode === $this->okCode,
            'url' => $this->url,
            'name' => $this->name,
            'description' => $this->description,
            'icon' => $this->icon,
            'response' => [
                'code' => $this->httpCode,
                'time' => $this->responseTime,
            ]
        ]);
    }

    #[ArrayShape([
        'max_width' => 'int',
        'max_height' => 'int',
        'min_width' => 'int',
        'min_height' => 'int'
    ])] #[Override] public function getConstraints(): array
    {
        return [
            'max_width' => 2,
            'max_height' => 1,
            'min_width' => 2,
            'min_height' => 1,
        ];
    }

    #[Override] public function canResize(): bool
    {
        return false;
    }
}
