<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use TempoRestApi\WorkLog\WorkLogService;
use TempoRestApi\WorkLog\WorkLogResultSet;

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

    public function testGetList(): void
    {
        try {
            $list = self::$workLogService->getList(
                (new \TempoRestApi\WorkLog\WorkLogListParameters())
                    ->setLimit(1)
            );

            $this->assertInstanceOf(
                WorkLogResultSet::class,
                $list
            );

            $list->fetchNext(false);

            $this->assertCount(2, $list);
        } catch (Exception $e) {
            $this->assertTrue(false, $e->getMessage());
        }
    }

    public function testWorkLogResultSetValid()
    {
        $resultSet = new WorkLogResultSet(self::$workLogService);

        $workLog = new \TempoRestApi\WorkLog\WorkLog();

        $resultSet[] = $workLog;

        $this->assertCount(1, $resultSet);
        $this->assertEquals($workLog, $resultSet[0]);
        $this->assertEquals($workLog, $resultSet->current());

        foreach ($resultSet as $item) {
            $this->assertEquals($workLog, $item);
        }
    }

    public function testWorkLogResultSetInvalid()
    {
        $this->expectException(\TempoRestApi\InvalidArgumentException::class);

        $resultSet = new WorkLogResultSet(self::$workLogService);

        // don't allow adding non-worklog objects
        $resultSet[] = 1;
    }
}
