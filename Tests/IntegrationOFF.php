<?php

/**
 * Also testing the methods that can fail in runtime just to have peace of mind
 * I'm keeping these tests apart so they don't slow the TDD down
 * The non use of mocking was intented
 */
class IntegrationTest extends PHPUnit_Framework_TestCase {

    /**
     * @covers WebPage::fecth($url)
     */
    public function testWebPageFecth() {
        $wp = new WebPage();
        $wp->fecth('http://www.bbc.co.uk/');
        $this->assertTrue(true);
    }

    /**
     * @covers WebPage::fecth($url)
     * @expectedException RuntimeException
     */
    public function testWebPageFecthFailure() {
        $wp = new WebPage();
        $wp->fecth('http://qww.bbc.co.uk/');
    }

    /**
     * @covers Article::loadInfo()
     */
    public function testLoadWebPageInfo() {
        $a = new Article();
        $a->fecth('http://www.bbc.co.uk/news/world-middle-east-30639764');
        $a->loadInfo();
        $this->assertEquals('palestinian', $a->mostUsedWord);
    }

    /**
     * @covers Scrapper::loadArticles()
     */
    public function testArticleLoading() {
        $s = new Scrapper;
        $s->loadArticles();
        $this->assertEquals(5, count($s->articles));
    }

    /**
     * Test everything now
     * @covers Run.php
     */
    public function testScrapping() {
        $s = new Scrapper;
        $s->loadArticles();
        // decode to test the entire structure
        $decoded = json_decode($s->returnJson());
        $this->assertInternalType('array', $decoded->results);
        // test each Article
        foreach ($decoded->results as $returnedObj) {
            $a = new Article;
            $a->title = $returnedObj->title;
            $a->href = $returnedObj->href;
            $a->size = $returnedObj->size;
            $a->mostUsedWord = $returnedObj->most_used_word;
            $this->assertTrue($a->validateJsonReturnables());
        }
    }

}
