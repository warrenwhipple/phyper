<?php
declare(strict_types=1);
namespace phyper\helpers;
use function phyper\h;

function div(...$args): string {
    return h('div', ...$args);
}

function p(...$args): string {
    return h('p', ...$args);
}

function span(...$args): string {
    return h('span', ...$args);
}

function a(...$args): string {
    return h('a', ...$args);
}

function ol(...$args): string {
    return h('ol', ...$args);
}

function ul(...$args): string {
    return h('ul', ...$args);
}

function li(...$args): string {
    return h('li', ...$args);
}

function h1(...$args): string {
    return h('h1', ...$args);
}

function h2(...$args): string {
    return h('h2', ...$args);
}

function h3(...$args): string {
    return h('h3', ...$args);
}

function h4(...$args): string {
    return h('h4', ...$args);
}

function h5(...$args): string {
    return h('h5', ...$args);
}

function h6(...$args): string {
    return h('h6', ...$args);
}

function button(...$args): string {
    return h('button', ...$args);
}

function br($attributes = null): string {
    return h('br', $attributes);
}

function hr($attributes = null): string {
    return h('hr', $attributes);
}

function img($attributes = null): string {
    return h('img', $attributes);
}

function input($attributes = null): string {
    return h('input', $attributes);
}
