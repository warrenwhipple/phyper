<?php
declare(strict_types=1);
require_once __DIR__ . '/../src/index.php';
use PHPUnit\Framework\TestCase;
use function phyper\core\utils\renderClass as r;

class RenderClassTest extends TestCase {
    public function testRendersString() {
        $att = ' class="a"';
        $this->assertSame($att, r('a'));
    }

    public function testRendersIndexedArray() {
        $att = ' class="a b c"';
        $this->assertSame($att, r(['a', 'b', 'c']));
        $this->assertSame($att, r(['a b', 'c']));
    }

    public function testIgnoresArrayNonStringValues() {
        $att = ' class="a b"';
        $this->assertSame($att, r(['a', null, 'b']));
        $this->assertSame($att, r(['a', true, 'b']));
        $this->assertSame($att, r(['a', 1, 'b']));
        $this->assertSame($att, r(['a', ['x'], 'b']));
    }

    public function testIgnoresArrayEmptyStringValues() {
        $att = ' class="a b"';
        $this->assertSame($att, r(['a', '', 'b']));
    }

    public function testReturnsEmptyString() {
        $this->assertSame('', r(null));
        $this->assertSame('', r(''));
        $this->assertSame('', r([]));
        $this->assertSame('', r([null, '']));
    }
}
