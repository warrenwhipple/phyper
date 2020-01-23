<?php
declare(strict_types=1);
require_once __DIR__ . '/../src/index.php';
use PHPUnit\Framework\TestCase;
use function phyper\utils\render_attribute as r;

class RenderAttributeTest extends TestCase {
    public function testRendersStringValue() {
        $att = ' a="b"';
        $this->assertSame($att, r('a', 'b'));
    }

    public function testRendersNumericValue() {
        $this->assertSame(' a="-1"', r('a', -1));
        $this->assertSame(' a="0"', r('a', 0));
        $this->assertSame(' a="1"', r('a', 1));
        $this->assertSame(' a="1.5"', r('a', 1.5));
    }

    public function testRendersBooleanTrueValue() {
        $att = ' a';
        $this->assertSame($att, r('a', true));
    }

    public function testReturnsEmptyString() {
        $this->assertSame('', r('a', null));
        $this->assertSame('', r('a', false));
        $this->assertSame('', r('a', ''));
        $this->assertSame('', r(0, 'v'));
        $this->assertSame('', r(1, 'v'));
    }
}
