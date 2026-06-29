<?php

declare(strict_types=1);

namespace App\Validation;

class CommentValidator
{
    public function validate(array $data): array
    {
        $errors = [];

        if (empty($data['content'])) {
            $errors[] = "Comment content is required.";
        }
        
        if (empty($data['user_id'])) {
            if (empty($data['author_name'])) {
                $errors[] = "Author name is required for guests.";
            }
            if (empty($data['author_email']) || !filter_var($data['author_email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Valid email is required for guests.";
            }
        }

        return $errors;
    }
}
