<?php
declare(strict_types=1);
require_once __DIR__ . '/../src/index.php';
use PHPUnit\Framework\TestCase;
use function phyper\h;

class HyperscriptTest extends TestCase {
    public function testRendersTagWithAttributesAndChildren() {
        $html = '<tag k="v">content</tag>';
        $this->assertSame($html, h('tag', ['k' => 'v'], 'content'));
        $this->assertSame($html, h('tag', ['k' => 'v'], 'con', 'tent'));
    }

    public function testRendersMixedAttributesAndClassesAndStyles() {
        $html = '<tag k="v" class="a b" style="c:d;e:f">content</tag>';
        $this->assertSame(
            $html,
            h(
                'tag',
                [
                    'k' => 'v',
                    'class' => ['a', 'b'],
                    'style' => ['c' => 'd', 'e' => 'f'],
                ],
                'content'
            )
        );
    }

    public function testRendersTagWithoutChildren() {
        $html = '<tag k="v"></tag>';
        $this->assertSame($html, h('tag', ['k' => 'v']));
        $this->assertSame($html, h('tag', ['k' => 'v'], null, '', []));
        $this->assertSame($html, h('tag', ['k' => 'v'], [null, '', []]));
    }

    public function testRendersTagWithoutProps() {
        $html = '<tag>content</tag>';
        $this->assertSame($html, h('tag', null, 'content'));
        $this->assertSame($html, h('tag', [], 'content'));
        $this->assertSame($html, h('tag', null, 'con', 'tent'));
        $this->assertSame($html, h('tag', [], 'con', 'tent'));
    }

    public function testPrependsPropsStringToChildren() {
        $html = '<tag>content</tag>';
        $this->assertSame($html, h('tag', 'content'));
        $this->assertSame($html, h('tag', 'con', 'tent'));
    }

    public function testPrependsPropsNumberToChildren() {
        $this->assertSame('<t>-1c</t>', h('t', -1, 'c'));
        $this->assertSame('<t>0c</t>', h('t', 0, 'c'));
        $this->assertSame('<t>1c</t>', h('t', 1, 'c'));
        $this->assertSame('<t>1.5c</t>', h('t', 1.5, 'c'));
    }

    public function testPrependsPropsIndexedArrayToChildren() {
        $html = '<tag>content</tag>';
        $this->assertSame($html, h('tag', ['content']));
        $this->assertSame($html, h('tag', ['con'], 'tent'));
        $this->assertSame($html, h('tag', ['con'], 'te', 'nt'));
        $this->assertSame($html, h('tag', ['con'], ['tent']));
    }

    public function testRendersTagOnly() {
        $html = '<tag></tag>';
        $this->assertSame($html, h('tag'));
        $this->assertSame($html, h('tag', null, null));
        $this->assertSame($html, h('tag', [], null));
    }

    public function testRendersNestedElements() {
        $html = '<ul><li>a</li><li>b</li></ul>';
        $this->assertSame($html, h('ul', h('li', 'a'), h('li', 'b')));
    }

    public function testDefaultsEmptyTagToDiv() {
        $html = '<div>content</div>';
        $this->assertSame($html, h('', 'content'));
        $this->assertSame($html, h('', null, 'content'));
        $this->assertSame($html, h('', 'con', 'tent'));
        $this->assertSame($html, h('', [], 'content'));
    }

    public function testRendersVoidElementTag() {
        $this->assertSame('<br>', h('br'));
        $this->assertSame('<hr>', h('hr'));
        $this->assertSame('<img>', h('img'));
        $this->assertSame('<input>', h('input'));
    }

    public function testRendersVoidElementTagWithAttributes() {
        $html = '<img src="a.jpg" class="b" style="c:d">';
        $this->assertSame(
            $html,
            h('img', [
                'src' => 'a.jpg',
                'class' => ['b'],
                'style' => ['c' => 'd'],
            ])
        );
    }

    public function testSuppressesVoidElementTagChildren() {
        $html = '<img src="a.jpg">';
        $this->assertSame($html, h('img', ['src' => 'a.jpg'], 'content'));
        $this->assertSame($html, h('img', ['src' => 'a.jpg'], 'con', 'tent'));
        $this->assertSame($html, h('img', ['src' => 'a.jpg'], ['con', 'tent']));
    }

    public function testParsesTagsWithClass() {
        $html = '<div class="a b">content</div>';
        $this->assertSame($html, h('.a.b', 'content'));
        $this->assertSame($html, h('div.a.b', 'content'));
        $this->assertSame($html, h('.a', ['class' => 'b'], 'content'));
        $this->assertSame($html, h('.a', ['class' => ['b']], 'content'));
    }

    public function testParsesTagsWithId() {
        $html = '<div id="a">content</div>';
        $this->assertSame($html, h('#a', 'content'));
        $this->assertSame($html, h('div#a', 'content'));
    }
}
