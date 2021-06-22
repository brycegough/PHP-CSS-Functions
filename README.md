# PHP-CSS-Functions
Apply user defined functions to a block of CSS code


## Example

#### PHP
```PHP
include(__DIR__ . '/CSSFunctions.php');

$css = "
.some-param {
    margin: @@my_function();
}

@media(max-width: @@my_function(4)) {
    @@another_function(123, 456)
}";

$compiler = new CSSFunctions( $css, [

    'my_function' => function($args) {
        return ( $args[0] ?? '0' ) . 'px';
    },

    'another_function' => function($args) {
        return "/* Another function: " . implode('|', $args) . " */";
    }

] );

echo '<pre>' . $compiler->compile() . '</pre>';
```


### Output

```css
.some-param {
    margin: 0px;
}

@media(max-width: 4px) {
    /* Another function: 123|456 */
}
```
