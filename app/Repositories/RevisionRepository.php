<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database\ConnectionFactory;
use App\Models\Post;
use PDO;

class RevisionRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = ConnectionFactory::make();
    }

    public function createRevision(int $postId): void
    {
        $stmt = $this->db->prepare("SELECT * FROM posts WHERE id = ?");
        $stmt->execute([$postId]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$post) return;

        // Skip if it's already a revision
        if ($post['type'] === 'revision') return;

        // Read config or default to 10
        // We will default to 10 max revisions
        $maxRevisions = 10;
        
        $stmt = $this->db->prepare("SELECT option_value FROM options WHERE option_name = 'max_revisions'");
        $stmt->execute();
        $opt = $stmt->fetchColumn();
        if ($opt !== false) {
            $maxRevisions = (int)$opt;
        }

        if ($maxRevisions <= 0) return;

        $stmt = $this->db->prepare("
            INSERT INTO posts (parent_id, type, title, slug, content, excerpt, status, author_id, created_at, updated_at) 
            VALUES (?, 'revision', ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)
        ");
        $stmt->execute([
            $post['id'],
            $post['title'],
            $post['slug'] . '-rev-' . time(),
            $post['content'],
            $post['excerpt'],
            'inherit',
            $post['author_id'],
            $post['updated_at']
        ]);

        $this->cleanupRevisions($post['id'], $maxRevisions);
    }

    public function createAutosave(int $postId, array $data): int
    {
        $stmt = $this->db->prepare("SELECT * FROM posts WHERE id = ?");
        $stmt->execute([$postId]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$post) return 0;

        $stmt = $this->db->prepare("SELECT id FROM posts WHERE parent_id = ? AND type = 'revision' AND slug LIKE '%-autosave'");
        $stmt->execute([$postId]);
        $autosaveId = $stmt->fetchColumn();

        if ($autosaveId) {
            $stmt = $this->db->prepare("UPDATE posts SET title = ?, content = ?, excerpt = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
            $stmt->execute([
                $data['title'] ?? $post['title'],
                $data['content'] ?? $post['content'],
                $data['excerpt'] ?? $post['excerpt'],
                $autosaveId
            ]);
            return (int)$autosaveId;
        }

        $stmt = $this->db->prepare("
            INSERT INTO posts (parent_id, type, title, slug, content, excerpt, status, author_id, created_at, updated_at) 
            VALUES (?, 'revision', ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
        ");
        $stmt->execute([
            $post['id'],
            $data['title'] ?? $post['title'],
            $post['slug'] . '-autosave',
            $data['content'] ?? $post['content'],
            $data['excerpt'] ?? $post['excerpt'],
            'inherit',
            $post['author_id']
        ]);

        return (int)$this->db->lastInsertId();
    }

    private function cleanupRevisions(int $postId, int $max): void
    {
        $stmt = $this->db->prepare("
            SELECT id FROM posts 
            WHERE parent_id = ? AND type = 'revision' 
            ORDER BY created_at DESC
        ");
        $stmt->execute([$postId]);
        $ids = $stmt->fetchAll(PDO::FETCH_COLUMN);

        if (count($ids) > $max) {
            $toDelete = array_slice($ids, $max);
            $inQuery = implode(',', array_fill(0, count($toDelete), '?'));
            $delStmt = $this->db->prepare("DELETE FROM posts WHERE id IN ($inQuery)");
            $delStmt->execute($toDelete);
        }
    }

    public function getRevisions(int $postId): array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM posts 
            WHERE parent_id = ? AND type = 'revision' 
            ORDER BY created_at DESC
        ");
        $stmt->execute([$postId]);
        return array_map(fn($row) => new Post($row), $stmt->fetchAll(PDO::FETCH_ASSOC));
    }
    
    public function findRevision(int $revisionId): ?Post
    {
        $stmt = $this->db->prepare("SELECT * FROM posts WHERE id = ? AND type = 'revision'");
        $stmt->execute([$revisionId]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? new Post($data) : null;
    }

    public function restoreRevision(int $revisionId): ?int
    {
        $stmt = $this->db->prepare("SELECT * FROM posts WHERE id = ? AND type = 'revision'");
        $stmt->execute([$revisionId]);
        $revision = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$revision) return null;

        $parentStmt = $this->db->prepare("UPDATE posts SET title = ?, content = ?, excerpt = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
        $parentStmt->execute([
            $revision['title'],
            $revision['content'],
            $revision['excerpt'],
            $revision['parent_id']
        ]);
        
        return (int)$revision['parent_id'];
    }
}
