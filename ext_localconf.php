<?php
defined('TYPO3_MODE') or die();

call_user_func(function () {

    $GLOBALS['TYPO3_CONF_VARS']['LOG']['Schnitzler']['AverageColorExtractor']['AverageColorExtractor']['writerConfiguration'] = [
        // configuration for WARNING severity, including all
        // levels with higher severity (ERROR, CRITICAL, EMERGENCY)
        \TYPO3\CMS\Core\Log\LogLevel::DEBUG => [
            // add a SyslogWriter
            \TYPO3\CMS\Core\Log\Writer\FileWriter::class => [
                'logFile' => 'typo3temp/logs/average_color_extractor.log'
            ]
        ]
    ];

    $extractorRegistry = \TYPO3\CMS\Core\Resource\Index\ExtractorRegistry::getInstance();
    $extractorRegistry->registerExtractionService(
        \Schnitzler\AverageColorExtractor\AverageColorExtractor::class
    );

});
