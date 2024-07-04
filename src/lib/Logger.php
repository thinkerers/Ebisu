<?php 

namespace src\lib;

class Logger{
    public function __construct(
        public ?bool $isLogging = true,
        public ?string $logFilePath = null
        ) {
            if (!$this->logFilePath) {
                $this->logFilePath = $this->getDefaultLogFilePath();
            }
        }
        
        private function getDefaultLogFilePath(): string
        {
            $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
            $callingClass = isset($backtrace[1]['class']) ? $backtrace[1]['class'] : 'default';
    
            return __DIR__ . '/' . basename(str_replace('\\', '/', $callingClass)) . '.log';
        }

        public function logMessage(string $message, object $dump, LogLevels $logLevel = LogLevels::INFO): void
        {
            if (!$dump) {
                throw new \Exception("Cannot log message without a valid Object.");
            }

            if (!$this->isLogging) {
                return;
            }

            $timestamp = date('Y-m-d H:i:s');
            $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
            $caller = $backtrace[1];

            $logMessage = sprintf(
                "[%s] [%s]: %s\n
                \tDump: %s\n
                \tCalled from: %s on line %s in function %s\n
                ────────────────────────────────────────\n",
                $timestamp,
                $logLevel,
                $message,
                print_r($dump, true),
                $caller['file'],
                $caller['line'],
                $caller['function']
            );

            if (!file_put_contents($this->logFilePath, $logMessage, FILE_APPEND)) {
                error_log("Failed to write to log file: $this->logFilePath");
            }
        }
}