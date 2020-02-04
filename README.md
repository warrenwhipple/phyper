# Phyper

PHP hyperscript and component functions

## Examples

### Hyperscript

```php
use function phyper\h;

echo h(
    '.post',
    h('h1', 'My Title'),
    h(
        '.author',
        h('a', ['href' => 'https://mysite.com'], 'My Name')
    ),
    h('p.content', 'Lorem ipsum dolor sit amet...')
);
```

### Component

```php
use function phyper\{h, component_args};

function card(...$args): string {
    component_args($args, $props, $children);

    [
        'link' => $link,
        'title' => $title,
        'subtitle' => $subtitle,
    ] = $props;

    return h(
        ($link ? 'a' : '') . '.card',
        ['href' => $link],
        $title ? h('h2', $title) : null,
        $subtitle ? h('.subtitle', $subtitle) : null,
        h('p', $children)
    );
}

echo card(
    [
        'link' => 'https://mysite.com',
        'title' => 'My Title',
        'subtitle' => 'My subtitle',
    ],
    'Lorem ipsum dolor sit amet...'
);

echo card(
    ['title' => 'My Other Title'],
    'Consectetur adipiscing elit...'
);
```

## Testing

```
composer test
```

or with file watch

```
npm start
```

## License

ISC
