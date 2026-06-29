<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Auth\AuthManager;
use App\Repositories\CommentRepository;
use App\Support\Flash;
use App\Support\Redirect;
use App\Validation\CommentValidator;
use Lukman\Http\Request;
use Lukman\Http\Response;

class CommentController
{
    private CommentRepository $repo;
    private CommentValidator $validator;

    public function __construct()
    {
        $this->repo = new CommentRepository();
        $this->validator = new CommentValidator();
    }

    public function store(Request $request): Response
    {
        $postId = (int)($_POST['post_id'] ?? 0);
        if ($postId <= 0) {
            Flash::set('error', 'Invalid post ID.');
            return Redirect::back('/');
        }

        $user = AuthManager::guard()->user();
        
        $data = [
            'post_id' => $postId,
            'user_id' => $user ? (int)$user['id'] : 0,
            'author_name' => $user ? $user['name'] : strip_tags(trim($_POST['author_name'] ?? '')),
            'author_email' => $user ? $user['email'] : strip_tags(trim($_POST['author_email'] ?? '')),
            'content' => strip_tags(trim($_POST['content'] ?? '')),
            'status' => 'pending'
        ];

        $errors = $this->validator->validate($data);

        if (!empty($errors)) {
            Flash::set('error', implode(' ', $errors));
            // Normally redirect to specific post, we can try to guess from referer, or just back
            return Redirect::back('/');
        }

        $this->repo->create($data);
        
        Flash::set('success', 'Your comment has been submitted and is awaiting moderation.');
        
        return Redirect::back('/');
    }
}
