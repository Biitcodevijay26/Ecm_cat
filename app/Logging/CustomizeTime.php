<?php

namespace App\Logging;

use Monolog\Formatter\LineFormatter;

class CustomizeTime
{
    /**
     * Customize the given logger instance.
     *
     * @param  \Monolog\Logger  $logger
     * @return void
     */
    public function __invoke($logger)
    {
        // Loop through each handler and set the custom format
        foreach ($logger->getHandlers() as $handler) {
            // Customize the format
            $handler->setFormatter(new LineFormatter(
                "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n", // Customize the log format
                'Y-m-d H:i:s' // Customize the date format
            ));
        }
    }
}
