<?php

class ArticleTest extends PHPUnit_Framework_TestCase {

    // For comparison with article convertion to array
    public $arrayModel = array(
        'title' => 'Title test',
        'href' => 'http://www.bbc.co.uk/news/health-30632453',
        'size' => '21kb',
        'most_used_word' => 'test',
    );

    /**
     * @before
     */
    protected function setUp() {
        $this->a = new Article;
        $this->a->title = 'Title test';
        $this->a->href = 'http://www.bbc.co.uk/news/health-30632453';
        $this->a->size = '21kb';
        $this->a->mostUsedWord = 'test';
    }

    /**
     * @covers Article::toArray
     */
    public function testToArray() {
        $this->assertEquals($this->arrayModel, $this->a->toArray());
    }

    /**
     * @covers Article::validateJsonReturnables
     * @expectedException DomainException
     */
    public function testIfTitleAttrIsInvalid() {
        $this->a->title = null;
        $this->a->validateJsonReturnables();
    }

    /**
     * @covers Article::validateJsonReturnables
     * @expectedException DomainException
     */
    public function testIfHrefAttrIsInvalid() {
        $this->a->href = 'http:www.bbc.co.uk';
        $this->a->validateJsonReturnables();
    }

    /**
     * @covers Article::validateJsonReturnables()
     * @expectedException DomainException
     */
    public function testIfAttrSizeIsInvalid() {
        $this->a->size = null;
        $this->a->validateJsonReturnables();
    }

    /**
     * @covers Article::validateJsonReturnables()
     * @expectedException DomainException
     */
    public function testIfAttrMostUsedWordIsInvalid() {
        $this->a->mostUsedWord = null;
        $this->a->validateJsonReturnables();
    }

    /**
     * @covers Article::validateJsonReturnables()
     */
    public function testAllAttributes() {
        $this->assertTrue($this->a->validateJsonReturnables());
    }

    /**
     * @covers Article::formatBytes()
     */
    public function testFormatBytes() {
        $a = new Article();
        $a->size = 10240;
        $a->formatBytes();
        $this->assertEquals('10kb', $a->size);
    }

    /**
     * @covers Article::findMostUsedWord()
     */
    public function testFindMostUsedWord() {
        $a = new Article();
        // mock the content
        $a->content = <<<'STR'
It is very important to note that the line with the closing identifier must contain no other characters, except a semicolon (;). 
That means especially that the identifier may not be indented, and there may not be any spaces or tabs before or after the semicolon. 
It's also important to realize that the first character before the closing identifier must be a newline as defined by the local operating system. 
This is \n on UNIX systems, including Mac OS X. The closing delimiter must also be followed by a newline.
STR;
        // as the array is also sorted "closing" will be chosen instead of "including"
        $a->findMostUsedWord();
        $this->assertEquals('closing', $a->mostUsedWord);
    }

    /**
     * @covers Article::convertHTML2Text()
     */
    public function testConvertHTML2Text() {
        $a = new Article();
        // mock the content and xpath query
        $a->xpathQuery = '//html/body/div';
        $a->content = '<html><body><div>foo</div></body></html>';
        $a->convertHTML2Text();
        $this->assertEquals('foo', trim($a->content));
    }

    /**
     * @covers Article::loadInfo()
     */
    public function testLoadInfo() {
        $a = new Article();
        $a->fecth('file://ArticleDummy.html');
        $a->loadInfo();
        $this->assertEquals('palestinian', $a->mostUsedWord);
    }

}
