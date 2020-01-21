<?php
declare(strict_types=1);
namespace phyper\helpers;

use function phyper\core\h;

function div($props = null, ...$children): string {
    return h('div', $props, ...$children);
}

function p($props = null, ...$children): string {
    return h('p', $props, ...$children);
}

function span($props = null, ...$children): string {
    return h('span', $props, ...$children);
}

function a($props = null, ...$children): string {
    return h('a', $props, ...$children);
}

function ol($props = null, ...$children): string {
    return h('ol', $props, ...$children);
}

function ul($props = null, ...$children): string {
    return h('ul', $props, ...$children);
}

function li($props = null, ...$children): string {
    return h('li', $props, ...$children);
}

function h1($props = null, ...$children): string {
    return h('h1', $props, ...$children);
}

function h2($props = null, ...$children): string {
    return h('h2', $props, ...$children);
}

function h3($props = null, ...$children): string {
    return h('h3', $props, ...$children);
}

function h4($props = null, ...$children): string {
    return h('h4', $props, ...$children);
}

function h5($props = null, ...$children): string {
    return h('h5', $props, ...$children);
}

function h6($props = null, ...$children): string {
    return h('h6', $props, ...$children);
}

function button($props = null, ...$children): string {
    return h('button', $props, ...$children);
}

function br($props = null): string {
    return h('br', $props);
}

function hr($props = null): string {
    return h('hr', $props);
}

function img($props = null): string {
    return h('img', $props);
}

function input($props = null): string {
    return h('input', $props);
}
