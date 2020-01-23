<?php
declare(strict_types=1);
require_once __DIR__ . '/../src/index.php';
use PHPUnit\Framework\TestCase;
use function phyper\utils\render_children as r;

class RenderChildrenTest extends TestCase {
    public function testRendersString() {
        $this->assertSame('a', r('a'));
    }

    public function testRendersNumber() {
        $this->assertSame('-1', r(-1));
        $this->assertSame('0', r(0));
        $this->assertSame('1', r(1));
        $this->assertSame('1.5', r(1.5));
    }

    public function testRendersArray() {
        $this->assertSame('abc', r(['a', 'b', 'c']));
        $this->assertSame('-1 0 1 1.5', r([-1, ' ', 0, ' ', 1, ' ', 1.5]));
    }

    public function testRendersNestedArray() {
        $this->assertSame('abc', r([[['a'], 'b'], 'c']));
    }

    public function testIsIndifferentToArrayKeys() {
        $this->assertSame('v', r(['k' => 'v']));
    }

    public function testReturnsEmptyString() {
        $this->assertSame('', r(null));
        $this->assertSame('', r(false));
        $this->assertSame('', r(''));
        $this->assertSame('', r([]));
        $this->assertSame('', r([null]));
        $this->assertSame('', r([false]));
        $this->assertSame('', r(['']));
        $this->assertSame('', r([[null], [false], [''], null, false, '']));
    }
}
