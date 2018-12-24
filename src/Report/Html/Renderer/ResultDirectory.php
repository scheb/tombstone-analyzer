<?php

namespace Scheb\Tombstone\Analyzer\Report\Html\Renderer;

use Scheb\Tombstone\Analyzer\AnalyzerFileResult;

class ResultDirectory
{
    /**
     * @var string[]
     */
    private $path;

    /**
     * @var ResultDirectory[]
     */
    private $directories = array();

    /**
     * @var AnalyzerFileResult[]
     */
    private $files = array();

    /**
     * @param string[] $path
     */
    public function __construct(array $path = array())
    {
        $this->path = $path;
    }

    /**
     * @return ResultDirectory[]
     */
    public function getDirectories(): array
    {
        return $this->directories;
    }

    /**
     * @return AnalyzerFileResult[]
     */
    public function getFiles(): array
    {
        return $this->files;
    }

    public function getName(): string
    {
        return $this->path[count($this->path) - 1];
    }

    public function getPath(): string
    {
        return implode('/', $this->path);
    }

    public function getDeadCount(): int
    {
        $count = 0;
        /** @var ResultDirectory|AnalyzerFileResult $element */
        foreach (array_merge($this->directories, $this->files) as $element) {
            $count += $element->getDeadCount();
        }

        return $count;
    }

    public function getUndeadCount(): int
    {
        $count = 0;
        /** @var ResultDirectory|AnalyzerFileResult $element */
        foreach (array_merge($this->directories, $this->files) as $element) {
            $count += $element->getUndeadCount();
        }

        return $count;
    }

    public function addFileResult($filePath, AnalyzerFileResult $fileResult): void
    {
        $firstSlash = strpos($filePath, '/');
        if (false === $firstSlash) {
            $this->files[$filePath] = $fileResult;

            return;
        }

        $dirName = substr($filePath, 0, $firstSlash);
        if (isset($this->directories[$dirName])) {
            $directory = $this->directories[$dirName];
        } else {
            $directory = new ResultDirectory(array_merge($this->path, array($dirName)));
            $this->directories[$dirName] = $directory;
        }

        $subPath = substr($filePath, $firstSlash + 1);
        $directory->addFileResult($subPath, $fileResult);
    }
}
