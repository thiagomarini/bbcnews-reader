<?php

class Scrapper extends WebPage {

    // used to find the listing box
    public $xpathQuery = '(//div[@id="most-popular"]/div/ol)[1]/li/a';
    public $url = 'http://www.bbc.co.uk/news/';
    public $articles = [];

    /**
     * @throws RuntimeException
     */
    public function loadArticles() {

        $this->fecth($this->url);
        $this->loadDom();

        $xpath = new DOMXPath($this->dom);
        $links = $xpath->query($this->xpathQuery);

        if (!$links) {
            throw new RuntimeException('Failed to extract listing box from webpage');
        }
        foreach ($links as $link) {
            $this->loadArticleInfo($link);
        }
    }

    /**
     * @param DOMElement $link
     */
    public function loadArticleInfo(DOMElement $link) {

        $a = new Article;
        // remove <span>
        $span = $link->getElementsByTagName('span')->item(0);
        $link->removeChild($span);

        $a->title = $link->nodeValue;
        $a->href = $link->getAttribute('href');
        $a->fecth($a->href);
        $a->loadInfo();
        // validate before pushing
        $a->validateJsonReturnables();

        $this->articles[] = $a->toArray();
    }

    /**
     * @return Json
     */
    public function returnJson() {
        return json_encode(['results' => $this->articles]);
    }

}
