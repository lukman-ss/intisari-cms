<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Http\JsonResponse;
use App\Repositories\TermRepository;
use Lukman\Http\Request;

class TermApiController
{
    private TermRepository $repo;

    public function __construct()
    {
        $this->repo = new TermRepository();
    }

    public function categories(Request $request): JsonResponse
    {
        $terms = $this->repo->all('category');
        
        $data = array_map(function($term) {
            return [
                'id' => $term->id,
                'name' => $term->name,
                'slug' => $term->slug,
                'description' => $term->description,
                'count' => $term->count
            ];
        }, $terms);
        
        return new JsonResponse(['data' => $data]);
    }

    public function tags(Request $request): JsonResponse
    {
        $terms = $this->repo->all('tag');
        
        $data = array_map(function($term) {
            return [
                'id' => $term->id,
                'name' => $term->name,
                'slug' => $term->slug,
                'description' => $term->description,
                'count' => $term->count
            ];
        }, $terms);
        
        return new JsonResponse(['data' => $data]);
    }
}
