<?php
/**
 * SimpleLogger Class
 * 
 * A simple logging library for logging messages to a file.
 */
class SimpleLogger
{
    private $logFile;
    private $dateFormat = 'Y-m-d H:i:s';

    const DEBUG = 'DEBUG';
    const INFO = 'INFO';
    const WARNING = 'WARNING';
    const ERROR = 'ERROR';

    public function __construct($filePath)
    {
        $this->logFile = $filePath;
        if (!file_exists($this->logFile)) {
            file_put_contents($this->logFile, '');
        }
    }

    private function log($level, $message)
    {
        $date = new DateTime();
        $formattedMessage = sprintf(
            "[%s] [%s] %s%s",
            $date->format($this->dateFormat),
            $level,
            $message,
            PHP_EOL
        );
        file_put_contents($this->logFile, $formattedMessage, FILE_APPEND);
    }

    public function debug($message)
    {
        $this->log(self::DEBUG, $message);
    }

    public function info($message)
    {
        $this->log(self::INFO, $message);
    }

    public function warning($message)
    {
        $this->log(self::WARNING, $message);
    }

    public function error($message)
    {
        $this->log(self::ERROR, $message);
    }
}

// Usage example
$logger = new SimpleLogger('app.log');
$logger->info('This is an info message.');
$logger->error('This is an error message.');
?>
