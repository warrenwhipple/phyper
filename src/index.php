<?php
declare(strict_types=1);
namespace phyper {
    use function phyper\utils\{
        render_class,
        render_style,
        render_attribute,
        is_void_html_tag
    };

    function component_args(array $args, &$props, &$children) {
        if (
            array_key_exists(0, $args) &&
            ($args[0] === null ||
                (is_array($args[0]) && !array_key_exists(0, $args[0])))
        ) {
            $props = array_shift($args);
        } else {
            $props = null;
        }
        $argCount = count($args);
        if ($argCount === 1) {
            $children = $args[0];
        } elseif ($argCount > 1) {
            $children = $args;
        } else {
            $children = null;
        }
    }

    function render_children($children): string {
        if (is_array($children)) {
            $render = '';
            foreach ($children as $child) {
                $render .= render_children($child);
            }
            return $render;
        }
        return strval($children);
    }

    function h(string $tag, ...$args): string {
        component_args($args, $props, $children);
        if ($tag === '') {
            $tag = 'div';
        }
        $render = '<' . $tag;
        if (is_array($props)) {
            foreach ($props as $k => $v) {
                if ($k === 'class') {
                    $render .= render_class($v);
                } elseif ($k === 'style') {
                    $render .= render_style($v);
                } else {
                    $render .= render_attribute($k, $v);
                }
            }
        }
        if (is_void_html_tag($tag)) {
            return $render . '>';
        }
        return $render . '>' . render_children($children) . '</' . $tag . '>';
    }
}

namespace phyper\utils {
    function render_class($class): string {
        if (is_array($class)) {
            $classes = array_filter($class, function ($v) {
                return is_string($v) && $v !== '';
            });
            if (!empty($classes)) {
                return ' class="' . implode(' ', $classes) . '"';
            }
        } elseif (is_string($class) && $class !== '') {
            return ' class="' . $class . '"';
        }
        return '';
    }

    function render_style($style): string {
        if (is_array($style)) {
            $styles = [];
            foreach ($style as $k => $v) {
                if (is_string($k) && $v !== null && $v !== '') {
                    $styles[] = $k . ':' . $v;
                }
            }
            if (!empty($styles)) {
                return ' style="' . implode(';', $styles) . '"';
            }
        } elseif (is_string($style) && $style !== '') {
            return ' style="' . $style . '"';
        }
        return '';
    }

    function render_attribute($k, $v): string {
        if (is_string($k)) {
            if ($v === true) {
                return ' ' . $k;
            } elseif ($v === 0 || $v) {
                return ' ' . $k . '="' . $v . '"';
            }
        }
        return '';
    }

    function is_void_html_tag($tag) {
        return in_array(
            $tag,
            [
                'area',
                'base',
                'br',
                'col',
                'command',
                'embed',
                'hr',
                'img',
                'input',
                'keygen',
                'link',
                'meta',
                'param',
                'source',
                'track',
                'wbr',
            ],
            true
        );
    }
}
