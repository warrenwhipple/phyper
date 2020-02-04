<?php
declare(strict_types=1);
require_once __DIR__ . '/../src/phyper.php';
use PHPUnit\Framework\TestCase;
use function phyper\{h, component_args, render_children};

function card(...$args): string {
    component_args($args, $props, $children);
    ['title' => $title] = $props;
    return h('', [], h('h1', $title), h('p', $children));
}

class ComponentTest extends TestCase {
    public function testRendersComponent() {
        $html = '<div><h1>a</h1><p>bc</p></div>';
        $this->assertSame($html, card(['title' => 'a'], 'bc'));
        $this->assertSame($html, card(['title' => 'a'], 'b', 'c'));
        $this->assertSame($html, card(['title' => 'a'], ['b', 'c']));
    }
}
