<?php

class ScrapperTest extends PHPUnit_Framework_TestCase {

    public $articlesMock = null;
    public $scpr = null;

    /**
     * @before
     */
    public function setUp() {

        $this->scpr = new Scrapper;

        // mock the article's array
        $this->articlesMock = [];
        for ($i = 1; $i <= 5; $i++) {
            $this->articlesMock[] = [
                'title' => 'Title test' . $i,
                'href' => 'http://www.bbc.co.uk/news/health-30632453' . $i,
                'size' => $i . '1kb',
                'most_used_word' => 'test' . $i,
            ];
        }
    }

    /**
     * @covers Scrapper::loadArticleInfo($link)
     */
    public function testLoadArticleInfo() {

        // No need to mock internal types
        $dom = new DomDocument;
        $link = $dom->createElement('a', 'Test Article');

        $href = $dom->createAttribute('href');
        $href->value = 'file://ArticleDummy.html';
        $link->appendChild($href);

        $span = $dom->createElement('span', '1:');
        $link->appendChild($span);
        // this method has validation
        // if the article is pushed to the articles array
        // is because everything is OK
        $this->scpr->loadArticleInfo($link);
        $this->assertEquals(1, count($this->scpr->articles));
    }

    /**
     * @covers Scrapper::returnJson()
     */
    public function testreturnJson() {

        // this method only depends on this attribute
        // being populated to be able to run
        $this->scpr->articles = $this->articlesMock;

        $outputModel = json_encode(['results' => $this->articlesMock]);
        $output = $this->scpr->returnJson();

        $this->assertJsonStringEqualsJsonString($outputModel, $output);
    }

}
