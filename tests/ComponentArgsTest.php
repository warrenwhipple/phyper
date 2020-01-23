<?php
declare(strict_types=1);
require_once __DIR__ . '/../src/index.php';
use PHPUnit\Framework\TestCase;
use function phyper\component_args as p;

function f(...$args) {
    return $args;
}

class ComponentArgsTest extends TestCase {
    public function testParsesPropsWithOneChild() {
        $args = f(['a' => 'b'], 'c');
        p($args, $props, $children);
        $this->assertSame(['a' => 'b'], $props);
        $this->assertSame('c', $children);
    }

    public function testParsesPropsWithChildrenArray() {
        $args = f(['a' => 'b'], ['c', 'd']);
        p($args, $props, $children);
        $this->assertSame(['a' => 'b'], $props);
        $this->assertSame(['c', 'd'], $children);
    }

    public function testParsesPropsWithMultipleChildren() {
        $args = f(['a' => 'b'], 'c', 'd');
        p($args, $props, $children);
        $this->assertSame(['a' => 'b'], $props);
        $this->assertSame(['c', 'd'], $children);
    }

    public function testParsesOneChild() {
        $args = f('a');
        p($args, $props, $children);
        $this->assertSame(null, $props);
        $this->assertSame('a', $children);
    }

    public function testParsesChildrenArray() {
        $args = f(['a', 'b']);
        p($args, $props, $children);
        $this->assertSame(null, $props);
        $this->assertSame(['a', 'b'], $children);
    }

    public function testParsesMultipleChildren() {
        $args = f('a', 'b');
        p($args, $props, $children);
        $this->assertSame(null, $props);
        $this->assertSame(['a', 'b'], $children);
    }

    public function testAssignsNull() {
        $args = f();
        p($args, $props, $children);
        $this->assertSame(null, $props);
        $this->assertSame(null, $children);
        $args = f(null);
        p($args, $props, $children);
        $this->assertSame(null, $props);
        $this->assertSame(null, $children);
        $args = f(null, null);
        p($args, $props, $children);
        $this->assertSame(null, $props);
        $this->assertSame(null, $children);
    }
}
