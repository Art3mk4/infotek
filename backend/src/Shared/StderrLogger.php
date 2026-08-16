<?php

declare(strict_types=1);

namespace App\Shared;

use Psr\Log\AbstractLogger;

/**
 * Minimal PSR-3 logger that writes to stderr so logs appear in Docker output.
 */
final class StderrLogger extends AbstractLogger
{
    public function log(mixed $level, string|\Stringable $message, array $context = []): void
    {
        $line = sprintf(
            "[%s] %s: %s %s\n",
            date('Y-m-d H:i:s'),
            strtoupper((string) $level),
            $message,
            $context !== [] ? json_encode($context) : ''
        );

        file_put_contents('php://stderr', $line, FILE_APPEND);
    }
}
