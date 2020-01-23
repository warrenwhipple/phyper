<?php
declare(strict_types=1);
require_once __DIR__ . '/../src/index.php';
use PHPUnit\Framework\TestCase;
use function phyper\utils\render_style as r;

class RenderStyleTest extends TestCase {
    public function testRendersString() {
        $att = ' style="a:b;c:d"';
        $this->assertSame($att, r('a:b;c:d'));
    }

    public function testRendersAssociativeArray() {
        $att = ' style="a:b;c:d"';
        $this->assertSame($att, r(['a' => 'b', 'c' => 'd']));
    }

    public function testRendersNumericArrayValues() {
        $att = ' style="a:-1;b:0;c:1;d:1.5"';
        $this->assertSame($att, r(['a' => -1, 'b' => 0, 'c' => 1, 'd' => 1.5]));
    }

    public function testIgnoresArrayNullValues() {
        $att = ' style="a:b"';
        $this->assertSame($att, r(['a' => 'b', 'c' => null]));
    }

    public function testIgnoresArrayEmptyStringValues() {
        $att = ' style="a:b"';
        $this->assertSame($att, r(['a' => 'b', 'c' => null]));
    }

    public function testIgnoresIndexedArrayKeys() {
        $att = ' style="a:b"';
        $this->assertSame($att, r(['a' => 'b', 'c:d']));
        $this->assertSame($att, r(['a' => 'b', 1 => 'c:d']));
    }

    public function testReturnsEmptyString() {
        $this->assertSame('', r(null));
        $this->assertSame('', r(''));
        $this->assertSame('', r([]));
        $this->assertSame('', r(['a' => null]));
        $this->assertSame('', r([null, '']));
    }
}
