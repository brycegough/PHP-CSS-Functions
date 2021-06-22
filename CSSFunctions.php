<?php
/**
 * This class provides a simple way to apply user defined functions to a block of CSS (or any string for that matter)
 *
 * @author Bryce Gough
 * @link https://github.com/brycegough/PHP-CSS-Functions/
 */

class CSSFunctions {

    private $functions = [];
    private $sheet = '';
    private $count = 0;

    public function __construct( $styles = '', $functions = [] ) {
        $this->sheet = $styles;
        $this->functions = $functions;
    }

    public function compile( $sheet = null ) {
        $this->count = 0;

        if ( is_string( $sheet ) ) {
            $this->set_stylesheet( $sheet );
        }

        $this->sheet = preg_replace_callback(
            '/@@([^(]*)\(([^)]*)\)/',
            [ $this, '_match' ],
            $this->sheet,
            -1,
            $this->count
        );

        return $this->sheet;

    }

    private function _match($match) {
        $fn = strtolower( $match[1] );

        if ( is_callable( $this->functions[ $fn ] ?? false ) ) {
            $args = preg_split('/(?<!\\\\),/', $match[2]);
            foreach ($args as &$arg) $arg = trim($arg);
            return $this->functions[ $fn ]( $args );
        }

        // Invalid function
        return $match[0];
    }

    public function set_stylesheet( $css ) {
        if ( ! is_string( $css ) ) return false;
        $this->count = 0;
        $this->sheet = $css;
        return true;
    }

    public function get_stylesheet() {
        return $this->sheet;
    }

    public function add_function($name, $callback) {
        if ( ! is_array( $this->functions ) ) {
            $this->functions = [];
        }
        $this->functions[$name] = $callback;
    }

    public function get_functions() {
        return $this->functions;
    }

    public function get_count() {
        return $this->count;
    }

}
