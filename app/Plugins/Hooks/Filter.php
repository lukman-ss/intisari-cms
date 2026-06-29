<?php

declare(strict_types=1);

namespace App\Plugins\Hooks;

class Filter
{
    public function __construct(
        public readonly string $tag,
        /** @var callable */
        public $callback,
        public readonly int $priority,
        public readonly int $acceptedArgs
    ) {}
}
