<?php

namespace GeoTool\Service;

use Psr\Log\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Stringable;
use Throwable;

/**
 * Handles logging explicitly to special channel dedicated to this bundle
 */
class LoggerService
{

    /**
     * @var LoggerInterface $keywordsFinderLogger
     */
    private LoggerInterface $keywordsFinderLogger;

    /**
     * @param LoggerInterface $geoToolLogger
     */
    public function __construct(LoggerInterface $geoToolLogger)
    {
        $this->keywordsFinderLogger = $geoToolLogger;
    }

    /**
     * System is unusable.
     *
     * @param string|Stringable $message
     * @param mixed[]           $context
     *
     * @return void
     */
    public function emergency(string|Stringable $message, array $context = [])
    {
        $this->keywordsFinderLogger->emergency($message, $context);
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string|Stringable $message
     * @param mixed[]           $context
     *
     * @return void
     */
    public function alert(string|Stringable $message, array $context = [])
    {
        $this->keywordsFinderLogger->alert($message, $context);
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string|Stringable $message
     * @param mixed[]           $context
     *
     * @return void
     */
    public function critical(string|Stringable $message, array $context = [])
    {
        $this->keywordsFinderLogger->critical($message, $context);
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string|Stringable $message
     * @param mixed[]           $context
     *
     * @return void
     */
    public function error(string|Stringable $message, array $context = [])
    {
        $this->keywordsFinderLogger->error($message, $context);
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string|Stringable $message
     * @param mixed[]           $context
     *
     * @return void
     */
    public function warning(string|Stringable $message, array $context = [])
    {
        $this->keywordsFinderLogger->warning($message, $context);
    }

    /**
     * Normal but significant events.
     *
     * @param string|Stringable $message
     * @param mixed[]           $context
     *
     * @return void
     */
    public function notice(string|Stringable $message, array $context = [])
    {
        $this->keywordsFinderLogger->notice($message, $context);
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string|Stringable $message
     * @param mixed[]           $context
     *
     * @return void
     */
    public function info(string|Stringable $message, array $context = []): void
    {
        $this->keywordsFinderLogger->info($message, $context);
    }

    /**
     * Detailed debug information.
     *
     * @param string|Stringable $message
     * @param mixed[]           $context
     *
     * @return void
     */
    public function debug(string|Stringable $message, array $context = []): void
    {
        $this->keywordsFinderLogger->debug($message, $context);
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed             $level
     * @param string|Stringable $message
     * @param mixed[]           $context
     *
     * @return void
     *
     * @throws InvalidArgumentException
     */
    public function log($level, string|Stringable $message, array $context = []): void
    {
        $this->keywordsFinderLogger->log($level, $message, $context);
    }

    /**
     * Will log exception
     *
     * @param Throwable $e
     * @param array     $context
     */
    public function logException(Throwable $e, array $context = []): void
    {
        $data = [
            "exception" => [
                "message" => $e->getMessage(),
                "trace"   => $e->getTraceAsString(),
                "class"   => get_class($e),
            ]
        ];

        $data = array_merge($data, $context);
        $this->critical("Exception was thrown", $data);
    }

}