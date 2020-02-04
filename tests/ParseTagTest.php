<?php
declare(strict_types=1);
require_once __DIR__ . '/../src/phyper.php';
use PHPUnit\Framework\TestCase;
use function phyper\utils\parse_tag as p;

class ParseTagTest extends TestCase {
    public function testParsesElement() {
        $tag = 'tag';
        $props = ['a' => 'b'];
        p($tag, $props);
        $this->assertSame('tag', $tag);
        $this->assertSame(['a' => 'b'], $props);
    }

    public function testParsesElementWithClasses() {
        $tag = 'tag.c.d';
        $props = ['a' => 'b'];
        p($tag, $props);
        $this->assertSame('tag', $tag);
        $this->assertSame(['a' => 'b', 'class' => ['c', 'd']], $props);
    }

    public function testParsesElementWithId() {
        $tag = 'tag#c';
        $props = ['a' => 'b'];
        p($tag, $props);
        $this->assertSame('tag', $tag);
        $this->assertSame(['a' => 'b', 'id' => 'c'], $props);
    }

    public function testPrioritizesLastTagId() {
        $tag = 'tag#first#last';
        $props = null;
        p($tag, $props);
        $this->assertSame('tag', $tag);
        $this->assertSame(['id' => 'last'], $props);
    }

    public function testPrioritizesTagIdOverPropsId() {
        $tag = 'tag#tagid';
        $props = ['id' => 'propsid'];
        p($tag, $props);
        $this->assertSame('tag', $tag);
        $this->assertSame(['id' => 'tagid'], $props);
    }

    public function testPrependsTagClassesToPropsClassString() {
        $tag = 'tag.a.b';
        $props = ['class' => 'c d'];
        p($tag, $props);
        $this->assertSame('tag', $tag);
        $this->assertSame(['class' => 'a b c d'], $props);
    }

    public function testPrependsTagClassesToPropsClassArray() {
        $tag = 'tag.a.b';
        $props = ['class' => ['c', 'd']];
        p($tag, $props);
        $this->assertSame('tag', $tag);
        $this->assertSame(['class' => ['a', 'b', 'c', 'd']], $props);
    }

    public function testDefaultsToDivWhenEmpty() {
        $tag = '';
        $props = null;
        p($tag, $props);
        $this->assertSame('div', $tag);
        $this->assertSame(null, $props);
    }

    public function testDefaultsToDivWithClass() {
        $tag = '.a';
        $props = null;
        p($tag, $props);
        $this->assertSame('div', $tag);
        $this->assertSame(['class' => ['a']], $props);
    }

    public function testDefaultsToDivWithId() {
        $tag = '#a';
        $props = null;
        p($tag, $props);
        $this->assertSame('div', $tag);
        $this->assertSame(['id' => 'a'], $props);
    }
}
