<?php
namespace Schnitzler\AverageColorExtractor;

use TYPO3\CMS\Core\Resource\AbstractFile;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\Index\ExtractorInterface;
use ColorThief\ColorThief;

/**
 * Class AverageColorExtractor
 * @package Schnitzler\AverageColorExtractor
 */
class AverageColorExtractor implements ExtractorInterface
{

    /**
     * Returns an array of supported file types;
     * An empty array indicates all filetypes
     *
     * @return array
     */
    public function getFileTypeRestrictions()
    {
        return [AbstractFile::FILETYPE_IMAGE];
    }

    /**
     * Get all supported DriverClasses
     *
     * Since some extractors may only work for local files, and other extractors
     * are especially made for grabbing data from remote.
     *
     * Returns array of string with driver names of Drivers which are supported,
     * If the driver did not register a name, it's the classname.
     * empty array indicates no restrictions
     *
     * @return array
     */
    public function getDriverRestrictions()
    {
        return ['Local'];
    }

    /**
     * Returns the data priority of the extraction Service.
     * Defines the precedence of Data if several extractors
     * extracted the same property.
     *
     * Should be between 1 and 100, 100 is more important than 1
     *
     * @return integer
     */
    public function getPriority()
    {
        return 10;
    }

    /**
     * Returns the execution priority of the extraction Service
     * Should be between 1 and 100, 100 means runs as first service, 1 runs at last service
     *
     * @return integer
     */
    public function getExecutionPriority()
    {
        return 10;
    }

    /**
     * Checks if the given file can be processed by this Extractor
     *
     * @param File $file
     * @return boolean
     */
    public function canProcess(File $file)
    {
        return true;
    }

    /**
     * The actual processing TASK
     *
     * Should return an array with database properties for sys_file_metadata to write
     *
     * @param File $file
     * @param array $previousExtractedData optional, contains the array of already extracted data
     * @return array
     */
    public function extractMetaData(File $file, array $previousExtractedData = [])
    {
        $metadata = [];

        try {
            if (!class_exists('ColorThief\\ColorThief')) {
                throw new \RuntimeException;
            }

            $path = $file->getForLocalProcessing();
            $averageColor = ColorThief::getColor($path);

            if (!is_array($averageColor)) {
                throw new \RuntimeException;
            }

            if (count($averageColor) !== 3) {
                throw new \RuntimeException;
            }

            $r = dechex((int)$averageColor[0]);
            $g = dechex((int)$averageColor[1]);
            $b = dechex((int)$averageColor[2]);

            $metadata['average_color'] = '#' . $r . $g . $b;

        } catch (\Exception $ignoredException) {
            // deliberately empty
        }

        return $metadata;
    }

}
