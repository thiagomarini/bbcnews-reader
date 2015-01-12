<?php

class Article extends WebPage {

    // used to find the article text
    public $xpathQuery = '//*[@id="main-content"]/div[2]/div[1]//p';
    public $title = null;
    public $href = null;
    public $mostUsedWord = null;

    /**
     * Format bytes for human reading
     * @link http://stackoverflow.com/questions/2510434/format-bytes-to-kilobytes-megabytes-gigabytes
     * @param Int $bytes
     * @param Int $precision
     */
    public function formatBytes($precision = 2) {
        $units = ['b', 'kb', 'mb', 'gb', 'tb'];
        $this->size = max($this->size, 0);
        $pow = floor(($this->size ? log($this->size) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $this->size /= pow(1024, $pow);
        $this->size = round($this->size, $precision) . $units[$pow];
    }

    /**
     * Finds the most used word in a text excluding blacklisted words
     * @link http://stackoverflow.com/questions/3175390/most-used-words-in-text-with-php
     */
    function findMostUsedWord() {

        $text = trim(preg_replace('/ss+/i', '', $this->content));
        // only take alphabet characters, but keep the spaces and dashes tooF
        $text = strtolower(preg_replace('/[^a-zA-Z -]/', '', $text));

        preg_match_all('/\b.*?\b/i', $text, $machWords);
        $machWords = $machWords[0];
        $bl = $GLOBALS['appRoot'] . 'BlackListedWords.txt';

        $stopWords = file($bl, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($machWords as $key => $item) {
            if ($item == '' || in_array(strtolower($item), $stopWords) || strlen($item) <= 3) {
                unset($machWords[$key]);
            }
        }

        $word_count = str_word_count(implode(" ", $machWords), 1);
        $frequency = array_count_values($word_count);
        arsort($frequency);

        $keywords = array_slice($frequency, 0);
        $this->mostUsedWord = key($keywords);
    }

    /**
     * Method to guarantee compliance with the output format
     * @return array
     */
    public function toArray() {
        return [
            'title' => trim($this->title),
            'href' => $this->href,
            'size' => $this->size,
            'most_used_word' => $this->mostUsedWord,
        ];
    }

    /**
     * Separate the article content from the rest of the
     * webpage using xpath query
     * @throws RuntimeException
     */
    public function convertHTML2Text() {

        $this->loadDom();

        $xpath = new DOMXPath($this->dom);
        $page = $xpath->query($this->xpathQuery);
        if (!$page) {
            throw new RuntimeException('Failed to extract content from webpage');
        }
        $this->content = '';
        foreach ($page as $element) {
            $this->content .= ' ' . $element->nodeValue;
        }
    }

    /**
     * Validates all attributes that can be returned
     * @return boolean
     * @throws DomainException
     */
    public function validateJsonReturnables() {
        if (!$this->title) {
            throw new DomainException('Title cannot be empty');
        }
        if (!$this->size) {
            throw new DomainException('Size cannot be empty');
        }
        if (!$this->mostUsedWord) {
            throw new DomainException('MostUsedWord cannot be empty');
        }
        if (!filter_var($this->href, FILTER_VALIDATE_URL)) {
            throw new DomainException('Href: Invalid URL');
        }
        return true;
    }

    /**
     * Get webpage info
     * @return array
     * @throws RuntimeException
     */
    public function loadInfo() {
        $this->loadDom();
        $this->formatBytes();
        $this->convertHTML2Text();
        $this->findMostUsedWord();
    }

}
