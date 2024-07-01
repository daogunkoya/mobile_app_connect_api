<?php

namespace App\Logging;

use Illuminate\Database\Connection;
use Illuminate\Http\Request;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\DB;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger as MonologLogger;
use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Processor\IntrospectionProcessor;
use Monolog\Processor\UidProcessor;
use Monolog\Processor\WebProcessor;
use Monolog\LogRecord;

class DatabaseLogger extends AbstractProcessingHandler
{
    protected $connection;
    protected $table;

    public function __construct(Connection $connection, $table, $level = MonologLogger::DEBUG, $bubble = true)
    {
        $this->connection = $connection;
        $this->table = $table;

        parent::__construct($level, $bubble);
    }

    protected function write(LogRecord $record): void
    {
        $requestData = $this->getRequestData();

        DB::table($this->getTable())->insert([
            'channel' => $record['channel'],
            'level' => $record['level'],
            'message' => $record['message'],
            'context' => json_encode($record['context']),
            'request_method' => $requestData['method'],
            'request_url' => $requestData['url'],
            'request_ip' => $requestData['ip'],
            'request_body' => $requestData['body'],
            'response_data' => json_encode($record['extra']),
            'created_at' => $record['datetime']->format('Y-m-d H:i:s'),
        ]);
    }

    protected function getTable(): string
    {
        return $this->table;
    }

    protected function getRequestData(): array
    {
        $request = app(Request::class);

        return [
            'method' => $request->method(),
            'url' => $request->url(),
            'ip' => $request->ip(),
            'body' => json_encode($request->all()),
        ];
    }
}
