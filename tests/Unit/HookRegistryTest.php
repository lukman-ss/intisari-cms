<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Plugins\Hooks\HookRegistry;
use PHPUnit\Framework\TestCase;

class HookRegistryTest extends TestCase
{
    public function testAddAndDoAction(): void
    {
        $registry = new HookRegistry();
        
        $called = false;
        $registry->addAction('test_action', function() use (&$called) {
            $called = true;
        });
        
        $registry->doAction('test_action');
        $this->assertTrue($called);
    }

    public function testActionPriority(): void
    {
        $registry = new HookRegistry();
        
        $order = [];
        $registry->addAction('test_priority', function() use (&$order) {
            $order[] = 2;
        }, 20);
        $registry->addAction('test_priority', function() use (&$order) {
            $order[] = 1;
        }, 10);
        
        $registry->doAction('test_priority');
        $this->assertEquals([1, 2], $order);
    }

    public function testActionAcceptedArgs(): void
    {
        $registry = new HookRegistry();
        
        $resultArgs = [];
        $registry->addAction('test_args', function($a, $b) use (&$resultArgs) {
            $resultArgs = [$a, $b];
        }, 10, 2);
        
        $registry->doAction('test_args', 'foo', 'bar', 'baz');
        $this->assertEquals(['foo', 'bar'], $resultArgs);
    }

    public function testAddAndApplyFilter(): void
    {
        $registry = new HookRegistry();
        
        $registry->addFilter('test_filter', function($val) {
            return $val . ' modified';
        });
        
        $result = $registry->applyFilters('test_filter', 'original');
        $this->assertEquals('original modified', $result);
    }

    public function testFilterPriority(): void
    {
        $registry = new HookRegistry();
        
        $registry->addFilter('test_filter_priority', function($val) {
            return $val . ' B';
        }, 20);
        $registry->addFilter('test_filter_priority', function($val) {
            return $val . ' A';
        }, 10);
        
        $result = $registry->applyFilters('test_filter_priority', 'original');
        $this->assertEquals('original A B', $result);
    }

    public function testFilterAcceptedArgs(): void
    {
        $registry = new HookRegistry();
        
        $registry->addFilter('test_filter_args', function($val, $arg1) {
            return $val . ' ' . $arg1;
        }, 10, 2);
        
        $result = $registry->applyFilters('test_filter_args', 'original', 'arg1', 'arg2');
        $this->assertEquals('original arg1', $result);
    }

    public function testSafeExceptionHandling(): void
    {
        $registry = new HookRegistry();
        
        $registry->addAction('test_exception', function() {
            throw new \Exception('Test exception');
        });
        
        $called = false;
        $registry->addAction('test_exception', function() use (&$called) {
            $called = true;
        }, 20);
        
        // This should not throw, instead error_log will be called
        $registry->doAction('test_exception');
        $this->assertTrue($called);
    }
}
