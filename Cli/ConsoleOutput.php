<?php

declare(strict_types=1);

namespace Scheb\Tombstone\Analyzer\Cli;

use Symfony\Component\Console\Output\OutputInterface;

class ConsoleOutput implements ConsoleOutputInterface
{
    /**
     * @var OutputInterface
     */
    private $output;

    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    public function write(string $string): void
    {
        $this->output->write($string);
    }

    public function writeln(?string $string = null): void
    {
        $this->output->writeln($string ?? '');
    }

    public function debug(string $string): void
    {
        if ($this->output->isDebug()) {
            $this->writeln($string);
        }
    }

    public function createProgressBar(int $width): ProgressBar
    {
        return new ProgressBar($this->output, $width);
    }

    public function error(string $message, ?\Throwable $exception = null): void
    {
        $this->output->writeln(sprintf('<error>%s</error>', $message));
        if (null !== $exception && $this->output->isDebug()) {
            $this->output->writeln(sprintf(
                '%s: %s at %s line %s',
                \get_class($exception),
                $exception->getMessage(),
                $exception->getFile(),
                $exception->getLine()
            ));
        }
    }
}
