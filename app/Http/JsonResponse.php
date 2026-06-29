<?php

declare(strict_types=1);

namespace App\Http;

use Lukman\Http\Response;

class JsonResponse extends Response
{
    public function __construct(mixed $data = null, int $status = 200, array $headers = [])
    {
        $content = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        parent::__construct($content, $status, $headers);
        $this->headers->set('Content-Type', 'application/json; charset=utf-8');
    }
}
