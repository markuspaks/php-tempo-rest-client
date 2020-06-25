<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Tempo\TempoClient;

final class TempoClientTest extends TestCase
{
    public function testCanBeCreated(): void
    {
        try {
            $this->assertInstanceOf(
                TempoClient::class,
                new TempoClient()
            );
        } catch (Exception $e) {
            $this->assertTrue(false, $e->getMessage());
        }
    }
}
