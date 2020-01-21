<?php
declare(strict_types=1);
namespace phyper\core {
    use function phyper\core\utils\{
        renderChildren,
        renderClass,
        renderStyle,
        renderAttribute,
        isVoidHtmlTag
    };

    /** Phyper hyperscript function */
    function h($component, $props = null, ...$children): string {
        if (!is_array($props) || array_key_exists(0, $props)) {
            array_unshift($children, $props);
            $props = null;
        }
        if ($component === null) {
            return renderChildren(
                count($children) ? $children : $props['children']
            );
        } elseif (is_string($component)) {
            $render = '<' . $component;
            if (is_array($props)) {
                foreach ($props as $k => $v) {
                    if ($k === 'class') {
                        $render .= renderClass($v);
                    } elseif ($k === 'style') {
                        $render .= renderStyle($v);
                    } elseif ($k !== 'children') {
                        $render .= renderAttribute($k, $v);
                    }
                }
            }
            if (isVoidHtmlTag($component)) {
                return $render . '>';
            }
            return $render .
                '>' .
                renderChildren(
                    count($children) ? $children : $props['children']
                ) .
                '</' .
                $component .
                '>';
        } elseif (is_callable($component)) {
            if (count($children)) {
                if (is_array($props)) {
                    $props['children'] = $children;
                } else {
                    $props = ['children' => $children];
                }
            }
            return strval($component($props));
        }
        throw new Exception(
            'phyper/core/h() first argument must be string or callable or null.'
        );
    }
}

namespace phyper\core\utils {
    function renderChildren($children): string {
        if (is_array($children)) {
            $render = '';
            foreach ($children as $child) {
                $render .= renderChildren($child);
            }
            return $render;
        }
        return strval($children);
    }

    function renderClass($class): string {
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

    function renderStyle($style): string {
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

    function renderAttribute($k, $v): string {
        if (is_string($k)) {
            if ($v === true) {
                return ' ' . $k;
            } elseif ($v === 0 || $v) {
                return ' ' . $k . '="' . $v . '"';
            }
        }
        return '';
    }

    function isVoidHtmlTag($tag) {
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
