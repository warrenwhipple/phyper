<?php
declare(strict_types=1);
require_once __DIR__ . '/../src/index.php';
require_once __DIR__ . '/../src/helpers.php';
use PHPUnit\Framework\TestCase;
use function phyper\{component_args, render_children};
use function phyper\helpers\{div, h1, p};

function card(...$args): string {
    component_args($args, $props, $children);
    ['title' => $title] = $props;
    return div([], h1($title), p($children));
}

class ComponentTest extends TestCase {
    public function testRendersComponent() {
        $html = '<div><h1>a</h1><p>bc</p></div>';
        $this->assertSame($html, card(['title' => 'a'], 'bc'));
        $this->assertSame($html, card(['title' => 'a'], 'b', 'c'));
        $this->assertSame($html, card(['title' => 'a'], ['b', 'c']));
    }
}
