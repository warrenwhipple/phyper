<?php
declare(strict_types=1);
require_once __DIR__ . '/../src/index.php';
require_once __DIR__ . '/../src/helpers.php';
use PHPUnit\Framework\TestCase;
use function phyper\h;
use function phyper\helpers\{div, h1, p};

function card(): callable {
    return function ($props): string {
        ['title' => $title, 'children' => $children] = $props;
        return div([], h1($title), p($children));
    };
}

function twoCards(): callable {
    return function ($props): string {
        [
            'sharedTitle' => $sharedTitle,
            'text1' => $text1,
            'text2' => $text2,
        ] = $props;
        return div(
            h(card(), ['title' => $sharedTitle], $text1),
            h(card(), ['title' => $sharedTitle], $text2)
        );
    };
}

function wrapper(): callable {
    return function ($props): string {
        ['title' => $title, 'children' => $children] = $props;
        return div(h1($title), $children);
    };
}

class HyperscriptComponentTest extends TestCase {
    public function testRendersClosureInline() {
        $html = '<div><h1>a</h1><p>b</p></div>';
        $this->assertSame(
            $html,
            h(
                function ($props) {
                    ['title' => $title, 'children' => $children] = $props;
                    return div([], h1($title), p($children));
                },
                ['title' => 'a'],
                'b'
            )
        );
    }

    public function testRendersClosureReturn() {
        $html = '<div><h1>a</h1><p>b</p></div>';
        $this->assertSame($html, h(card(), ['title' => 'a'], 'b'));
    }

    public function testRendersClosureVariable() {
        $html = '<div><h1>a</h1><p>b</p></div>';
        $card = card();
        $this->assertSame($html, h($card, ['title' => 'a'], 'b'));
    }

    public function testPassesPropsDownNestedComponents() {
        $html =
            '<div>' .
            '<div><h1>a</h1><p>b</p></div>' .
            '<div><h1>a</h1><p>c</p></div>' .
            '</div>';
        $this->assertSame(
            $html,
            h(twoCards(), [
                'sharedTitle' => 'a',
                'text1' => 'b',
                'text2' => 'c',
            ])
        );
    }

    public function testPassesChildrenDownWrappedComponents() {
        $html =
            '<div>' .
            '<h1>a</h1>' .
            '<div><h1>b</h1><p>c</p></div>' .
            '<div><h1>d</h1><p>e</p></div>' .
            '</div>';
        $this->assertSame(
            $html,
            h(
                wrapper(),
                ['title' => 'a'],
                h(card(), ['title' => 'b'], 'c'),
                h(card(), ['title' => 'd'], 'e')
            )
        );
    }
}
