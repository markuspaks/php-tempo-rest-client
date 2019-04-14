<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use TempoRestApi\WorkLog\WorkLogService;

final class WorkLogServiceTest extends TestCase
{
    /** @var WorkLogService */
    private static $workLogService;

    /**
     * @throws Exception
     */
    public static function setUpBeforeClass(): void
    {
        self::$workLogService = new WorkLogService();
    }

    public function testServiceInstance(): void
    {
        try {
            $this->assertInstanceOf(
                WorkLogService::class,
                self::$workLogService
            );
        } catch (Exception $e) {
            $this->assertTrue(false, $e->getMessage());
        }
    }

    public function testGet(): void
    {
        $workLogId = 2;

        try {
            $workLog = self::$workLogService->get($workLogId);

            $this->assertInstanceOf(
                \TempoRestApi\WorkLog\WorkLog::class,
                $workLog
            );

            $this->assertEquals($workLog->tempoWorklogId, $workLogId);
        } catch (Exception $e) {
            $this->assertTrue(false, $e->getMessage());
        }
    }

    public function testGetNotFound(): void
    {
        try {
            $workLog = self::$workLogService->get(9999999999);
            $this->assertNull($workLog, 'WorkLog found');
        } catch (Exception $e) {
            $this->assertTrue(false, $e->getMessage());
        }
    }
}