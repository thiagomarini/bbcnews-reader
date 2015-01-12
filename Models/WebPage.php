<?php

class WebPage {

    public $size = null;
    public $content = null;
    public $dom = null;

    /**
     * @throws RuntimeException
     */
    public function fecth($url) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $this->content = curl_exec($ch);
        $info = curl_getinfo($ch);
        $this->size = $info['size_download'];
        curl_close($ch);
        if ($this->size <= 0) {
            throw new RuntimeException("Unable to acess article URL");
        }
    }

    /**
     * Creates a DOM from the HTML
     */
    public function loadDom() {
        $this->dom = new DOMDocument;
        // We don't want to bother with white spaces
        $this->dom->preserveWhiteSpace = false;
        // Most HTML Developers produce invalid markup...
        $this->dom->strictErrorChecking = false;
        // surpress any warning as PHPUnit can pick them up
        @$this->dom->loadHTML($this->content);
    }

}
