<?php
declare(strict_types=1);
namespace phyper {
    use function phyper\utils\{
        render_children,
        render_class,
        render_style,
        render_attribute,
        is_void_html_tag
    };

    /** Phyper hyperscript function */
    function h($component, $props = null, ...$children): string {
        if (!is_array($props) || array_key_exists(0, $props)) {
            array_unshift($children, $props);
            $props = null;
        }
        if ($component === null) {
            return render_children(
                count($children) ? $children : $props['children']
            );
        } elseif (is_string($component)) {
            $render = '<' . $component;
            if (is_array($props)) {
                foreach ($props as $k => $v) {
                    if ($k === 'class') {
                        $render .= render_class($v);
                    } elseif ($k === 'style') {
                        $render .= render_style($v);
                    } elseif ($k !== 'children') {
                        $render .= render_attribute($k, $v);
                    }
                }
            }
            if (is_void_html_tag($component)) {
                return $render . '>';
            }
            return $render .
                '>' .
                render_children(
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

namespace phyper\utils {
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
