<?php

namespace Tests\Feature\Deploy;

use Tests\TestCase;

/**
 * Locks in the production split-layout behaviour of the web-served uploads disk:
 * it defaults to the app's public/uploads locally, but can be pointed at the
 * real docroot (public_html/uploads) in production via UPLOADS_ROOT. If this
 * regresses, uploaded images are written outside the served root in production.
 */
class UploadsDiskConfigTest extends TestCase
{
    public function test_uploads_disk_defaults_to_public_uploads_when_env_is_unset(): void
    {
        $config = require base_path('config/filesystems.php');

        $this->assertSame(public_path('uploads'), $config['disks']['uploads']['root']);
        $this->assertSame('/uploads', $config['disks']['uploads']['url']);
    }

    public function test_uploads_disk_honours_the_production_env_overrides(): void
    {
        $this->withEnv('UPLOADS_ROOT', '/home/site/public_html/uploads');
        $this->withEnv('UPLOADS_URL', '/media');

        try {
            $config = require base_path('config/filesystems.php');

            $this->assertSame('/home/site/public_html/uploads', $config['disks']['uploads']['root']);
            $this->assertSame('/media', $config['disks']['uploads']['url']);
        } finally {
            $this->clearEnv('UPLOADS_ROOT');
            $this->clearEnv('UPLOADS_URL');
        }
    }

    private function withEnv(string $key, string $value): void
    {
        putenv("$key=$value");
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }

    private function clearEnv(string $key): void
    {
        putenv($key);
        unset($_ENV[$key], $_SERVER[$key]);
    }
}
