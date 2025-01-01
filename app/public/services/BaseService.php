<?php

/**
 * This class provides a base service with common functionality for other services.
 */
abstract class BaseService
{
    /**
     * Gets and decodes JSON content from a file.
     *
     * @param string $jsonPath The path to the JSON file.
     * @return array|null The decoded JSON data, or null if decoding fails.
     */
    protected function getJsonContent(string $jsonPath): ?array
    {
        $jsonContent = file_get_contents($jsonPath);
        $jsonContent = mb_convert_encoding($jsonContent, 'UTF-8', 'UTF-16LE');
        $jsonContent = trim($jsonContent, "\xEF\xBB\xBF");
        return json_decode($jsonContent, true);
    }
}