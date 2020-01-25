<?php
declare(strict_types=1);
namespace phyper {
    use function phyper\utils\{
        parse_tag,
        render_class,
        render_style,
        render_attribute,
        is_void_html_tag
    };

    function h(string $tag, ...$args): string {
        component_args($args, $props, $children);
        parse_tag($tag, $props);
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
}

namespace phyper\utils {
    function parse_tag(string &$tag, array &$props = null) {
        $firstType = substr($tag, 0, 1);
        $parts = preg_split('/(?=\.|#)/', $tag);
        if ($firstType !== '' && $firstType !== '#' && $firstType !== '.') {
            $tag = array_shift($parts);
        } else {
            $tag = 'div';
        }
        $classes = null;
        foreach ($parts as $part) {
            $name = substr($part, 1);
            if (!$name) {
                continue;
            }
            $type = substr($part, 0, 1);
            if ($type === '.') {
                $classes[] = $name;
            } elseif ($type === '#') {
                $props['id'] = $name;
            }
        }
        if ($classes !== null) {
            if (is_array($props['class'])) {
                $props['class'] = array_merge($classes, $props['class']);
            } elseif (is_string($props['class'])) {
                $props['class'] =
                    implode(' ', $classes) . ' ' . $props['class'];
            } else {
                $props['class'] = $classes;
            }
        }
    }

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
