<?php

namespace Ddeboer\DocumentManipulationBundle\Manipulator\Pdf;

/**
 * A simple wrapper around the pdftk command line utility
 *
 * @see http://www.pdflabs.com/tools/pdftk-the-pdf-toolkit/
 * @see http://www.pdflabs.com/docs/pdftk-man-page/
 */
class Pdftk
{
    /**
     * Path to the pdftk binary
     *
     * @var string
     */
    protected $pathToPdftk;

    /**
     * Construct pdftk service
     *
     * @param string $pathToPdftk   Path to the pdftk binary
     */
    public function __construct($pathToPdftk)
    {
        $this->pathToPdftk = $pathToPdftk;
        $this->tempDir = sys_get_temp_dir();
    }

    /**
     * Merge $inputFile with background $backgroundFile
     *
     * @param string $inputFile
     * @param string $backgroundFile
     * @param string $outputFile        Optional
     * @return string       Path to the output file
     */
    public function background($inputFile, $backgroundFile, $outputFile = null)
    {
        return $this->execute(escapeshellarg($inputFile) . ' background '
                             . escapeshellarg($backgroundFile), $outputFile);

    }

    /**
     * Reads a single, input PDF file and reports various statistics, metadata,
     * bookmarks (a/k/a outlines), and page labels to the given output filename
     * or (if no output is given) to stdout. Non-ASCII characters are encoded
     * as XML numerical entities. Does not create a new PDF.
     *
     * @param string $file
     * @return array        Data about the PDF file
     */
    public function dumpData($file)
    {
        $outputFile = $this->execute("'{$file}' dump_data_utf8");
        $output = file_get_contents($outputFile);

        $returnData = array();
        $lines = explode(PHP_EOL, $output);
        foreach ($lines as $line) {
            if (!empty($line)) {
                $splitLine = explode(': ', $line);
                $returnData[$splitLine[0]] = $splitLine[1];
            }
        }
        return $returnData;
    }

    /**
     * Merge, i.e., concatenate two or more PDF files
     *
     * @param array $files
     */
    public function merge(array $files, $outputFile = null)
    {
        $escaped = array();
        foreach ($files as $file) {
            $escaped[] = escapeshellarg($file);
        }

        return $this->execute(implode(' ', $escaped), $outputFile);
    }

    /**
     * Generate a temporary file name
     *
     * @return string   Temporary file name
     */
    protected function generateTemporaryFilename()
    {
        return tempnam($this->tempDir, 'pdftk') . '.pdf';
    }

    /**
     * Execute a pdftk command and return the path to the output file
     *
     * @param string $argumentString
     * @param string $outputFile        Optional, if not specified, will be
     *                                  generated
     * @return string   Path to the output file
     */
    protected function execute($argumentString, $outputFile = null)
    {
        if (!$outputFile) {
            $outputFile = $this->generateTemporaryFilename();
        }

        $command = escapeshellcmd("{$this->pathToPdftk} {$argumentString} output {$outputFile}");
        $return = exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            throw new \Exception('pdftk: non-zero return value');
        }

        return $outputFile;
    }
}