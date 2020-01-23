<?php
declare(strict_types=1);
require_once __DIR__ . '/../src/index.php';
require_once __DIR__ . '/../src/helpers.php';
use PHPUnit\Framework\TestCase;
use function phyper\helpers\{div, h1, p, ul, li, br, hr, img, input};

class HelpersTest extends TestCase {
    public function testRendersAttributesAndChildren() {
        $html = '<div k="v">content</div>';
        $this->assertSame($html, div(['k' => 'v'], 'content'));
        $this->assertSame($html, div(['k' => 'v'], 'con', 'tent'));
    }

    public function testRendersWithoutChildren() {
        $html = '<div k="v"></div>';
        $this->assertSame($html, div(['k' => 'v']));
        $this->assertSame($html, div(['k' => 'v'], null, '', []));
        $this->assertSame($html, div(['k' => 'v'], [null, '', []]));
    }

    public function testRendersWithoutProps() {
        $html = '<div>content</div>';
        $this->assertSame($html, div(null, 'content'));
        $this->assertSame($html, div([], 'content'));
        $this->assertSame($html, div(null, 'con', 'tent'));
        $this->assertSame($html, div([], 'con', 'tent'));
    }

    public function testRendersElementOnly() {
        $html = '<div></div>';
        $this->assertSame($html, div());
        $this->assertSame($html, div(null, null));
        $this->assertSame($html, div([], null));
    }

    public function testRendersNestedElements() {
        $html = '<ul><li>a</li><li>b</li></ul>';
        $this->assertSame($html, ul(li('a'), li('b')));
    }

    public function testRendersVoidElement() {
        $this->assertSame('<br>', br());
        $this->assertSame('<hr>', hr());
        $this->assertSame('<img>', img());
        $this->assertSame('<input>', input());
        $this->assertSame('<input>', input());
    }

    public function testRendersVoidElementWithAttributes() {
        $this->assertSame('<img src="a.jpg">', img(['src' => 'a.jpg']));
    }

    public function testSuppressesVoidElementChildren() {
        $this->assertSame('<img>', img(['children' => 'a']));
    }
}
