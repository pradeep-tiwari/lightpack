<?php

declare(strict_types=1);

use Lightpack\Cache\Cache;
use Lightpack\Cache\Drivers\File;
use PHPUnit\Framework\TestCase;

final class FileTest extends TestCase
{
    private $cacheDir;

    public function setUp(): void
    {
        $this->cacheDir = __DIR__ . '/tmp';
        mkdir($this->cacheDir);
    }

    public function tearDown(): void
    {
        array_map('unlink', glob($this->cacheDir . '/*'));
        rmdir($this->cacheDir);
    }

    public function testConstructor(): void
    {
        $cache = new File($this->cacheDir);
        $this->assertTrue(file_exists($this->cacheDir));
    }

    public function testCanStoreItem()
    {
        $cache = new File($this->cacheDir);
        $cache->set('name', 'Lightpack', time() + (5 * 60));

        $this->assertTrue($cache->has('name'));
        $this->assertTrue($cache->get('name') === 'Lightpack');
    }

    public function testCanDeleteItem()
    {
        $cache = new File($this->cacheDir);
        $cache->set('name', 'Lightpack', time() + (5 * 60));

        $this->assertTrue($cache->has('name'));
        $cache->delete('name');
        $this->assertFalse($cache->has('name'));
    }
}