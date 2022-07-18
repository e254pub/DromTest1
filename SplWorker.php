<?php

class SplWorker
{
    /**
     * @param string $dir
     * @param string $file
     * @return array
     * @throws Exception
     */
    private function getSplFiles(string $dir, string $file): array
    {
        if (!is_dir($dir)) {
            throw new Exception("Directory does not exist");
        }
        $dirIterator = new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS);
        $fileFilter = $this->fileFilter($dirIterator, $file);
        $rii = new RecursiveIteratorIterator($fileFilter);

        return iterator_to_array($rii);
    }

    /**
     * @param RecursiveDirectoryIterator $dirIterator
     * @param string $file
     * @return RecursiveCallbackFilterIterator
     */
    private function fileFilter(RecursiveDirectoryIterator $dirIterator, string $file): RecursiveCallbackFilterIterator
    {
        return new RecursiveCallbackFilterIterator($dirIterator, function ($curr) use ($file): bool
        {
            /** @var SplFileInfo $curr */
            if ($curr->isDir()) {
                return true;
            }

            $currFileName = $curr->getFilename();

            return $curr->isFile() && $currFileName == $file;
        });
    }

    /**
     * @param $dir
     * @return int
     * @throws Exception
     */
    public function getSumNumbers($dir): int
    {
        $files = $this->getSplFiles($dir, 'count');
        $sum = 0;
        /** @var SplFileInfo $file */
        foreach ($files as $file) {
            $spl = $file->openFile();
            $line = $spl->fgets();
            $sum += array_sum(explode(" ", $line));
        }
        return $sum;
    }
}
