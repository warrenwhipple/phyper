<?php
/**
 * Phyper
 *
 * PHP hyperscript and component functions
 *
 * @version 0.1.0
 *
 * @author Warren Whipple <w@warrenwhipple.com>
 * @license ISC
 * @link https://github.com/warrenwhipple/phyper
 */

declare(strict_types=1);

namespace phyper {
    use function phyper\utils\{
        parse_tag,
        clsxArray,
        render_class,
        stlx,
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
                    $class = clsx($v);
                    if ($class) {
                        $render .= ' class="' . $class . '"';
                    }
                } elseif ($k === 'style') {
                    $style = stlx($v);
                    if ($style) {
                        $render .= ' style="' . $style . '"';
                    }
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

    function clsx(...$args): string {
        // Based on https://github.com/lukeed/clsx
        return clsxArray($args);
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

    function clsxArray(array $mix) {
        $render = '';
        foreach ($mix as $k => $v) {
            if (is_string($v)) {
                if ($v) {
                    if ($render) {
                        $render .= ' ';
                    }
                    $render .= $v;
                }
            } elseif (is_array($v)) {
                $v_render = clsxArray($v);
                if ($v_render) {
                    if ($render) {
                        $render .= ' ';
                    }
                    $render .= $v_render;
                }
            } elseif (is_bool($v) && $v && is_string($k) && $k) {
                if ($render) {
                    $render .= ' ';
                }
                $render .= $k;
            }
        }
        return $render;
    }

    function stlx($mix): string {
        if (is_array($mix)) {
            $styles = [];
            foreach ($mix as $k => $v) {
                if (is_string($k) && $v !== null && $v !== '') {
                    $styles[] = $k . ':' . $v;
                }
            }
            if (!empty($styles)) {
                return implode(';', $styles);
            }
        } elseif (is_string($mix)) {
            return $mix;
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
