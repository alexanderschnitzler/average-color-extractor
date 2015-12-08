<?php
defined('TYPO3_MODE') or die();

call_user_func(function () {

    $extractorRegistry = \TYPO3\CMS\Core\Resource\Index\ExtractorRegistry::getInstance();
    $extractorRegistry->registerExtractionService(
        \Schnitzler\AverageColorExtractor\AverageColorExtractor::class
    );

});
