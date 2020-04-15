<?php
namespace Sak\Core\Log;

use Monolog\Processor\WebProcessor;
use Ramsey\Uuid\Uuid;

class LogProcessor extends WebProcessor
{
    public static $requestId;

    /**
     * @param  array $record
     * @return array
     */
    public function __invoke(array $record)
    {
        $record = parent::__invoke($record);

        if (isset($this->serverData['HTTP_X_REQUEST_ID']) && !self::$requestId) {
            $record['HTTP_X_REQUEST_ID'] = $this->serverData['HTTP_X_REQUEST_ID'];
        } else {
            self::$requestId && $record['HTTP_X_REQUEST_ID'] = self::$requestId;
        }

        return $record;
    }

    /**
     * 用于命令行下的set requestID
     */
    public static function setRequestId()
    {
        self::$requestId = Uuid::uuid1()->toString();
    }

    /**
     * 获取requestID
     * @return mixed
     */
    public static function getRequestId()
    {
        return self::$requestId;
    }
}
