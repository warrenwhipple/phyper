<?php
declare(strict_types=1);
require_once __DIR__ . '/../src/phyper.php';
use PHPUnit\Framework\TestCase;
use function phyper\clsx;

class ClassesTest extends TestCase {
    public function testRendersStrings() {
        $s = 'a b c';
        $this->assertSame($s, clsx('a b c'));
        $this->assertSame($s, clsx('a', 'b', 'c'));
    }

    public function testRendersArrays() {
        $s = 'a b c';
        $this->assertSame($s, clsx(['a', 'b', 'c']));
        $this->assertSame($s, clsx(['a', 'b'], ['c']));
    }

    public function testRendersBooleanKeyValuePairs() {
        $s = 'a c';
        $this->assertSame($s, clsx(['a' => true, 'b' => false, 'c' => true]));
    }

    public function testRendersMixed() {
        $s = 'a b c d e f g';
        $this->assertSame(
            $s,
            clsx('a', 'b c', ['d' => true], [['e', 'f'], 'g'])
        );
    }

    public function testSkipsEmptyValues() {
        $s = 'a b';
        $this->assertSame($s, clsx('a', null, 'b'));
        $this->assertSame($s, clsx('a', [], 'b'));
        $this->assertSame($s, clsx('a', '', 'b'));
    }

    public function testSkipsInvalidValues() {
        $s = 'a b';
        $this->assertSame($s, clsx('a', 0, 'b'));
        $this->assertSame($s, clsx('a', 7, 'b'));
        $this->assertSame($s, clsx('a', false, 'b'));
        $this->assertSame($s, clsx('a', true, 'b'));
    }

    public function testReturnEmptyString() {
        $this->assertSame('', clsx(null));
        $this->assertSame('', clsx(''));
        $this->assertSame('', clsx([]));
        $this->assertSame(
            '',
            clsx([[null, '', []], null, '', []], null, '', [])
        );
    }
}
