<?php
declare(strict_types=1);

namespace Tests\Unit;

use App\Repositories\PostRepository;
use App\Repositories\RevisionRepository;
use PHPUnit\Framework\TestCase;

class AutosaveTest extends TestCase
{
    public function testAutosaveCreatesOrUpdatesRevision(): void
    {
        $postRepo = new PostRepository();
        $revRepo = new RevisionRepository();

        $postId = $postRepo->create([
            'title' => 'Original Title',
            'slug' => 'autosave-test-' . time(),
            'content' => 'Original Content',
            'excerpt' => '',
            'type' => 'post',
            'status' => 'draft',
            'author_id' => 1
        ]);

        $revRepo->createAutosave($postId, [
            'title' => 'Autosave 1',
            'content' => 'Content 1'
        ]);

        $revisions = $revRepo->getRevisions($postId);
        $this->assertGreaterThan(0, count($revisions));
        
        $autosave = null;
        foreach ($revisions as $rev) {
            if (str_ends_with($rev->slug, '-autosave')) {
                $autosave = $rev;
                break;
            }
        }

        $this->assertNotNull($autosave);
        $this->assertEquals('Autosave 1', $autosave->title);
        $this->assertEquals('Content 1', $autosave->content);

        // Update it
        $revRepo->createAutosave($postId, [
            'title' => 'Autosave 2',
            'content' => 'Content 2'
        ]);

        $revisions = $revRepo->getRevisions($postId);
        $autosaves = array_filter($revisions, fn($r) => str_ends_with($r->slug, '-autosave'));
        
        $this->assertCount(1, $autosaves);
        $autosave = reset($autosaves);
        $this->assertEquals('Autosave 2', $autosave->title);
        $this->assertEquals('Content 2', $autosave->content);

        // Cleanup
        $postRepo->delete($postId);
    }
}
