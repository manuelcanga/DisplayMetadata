<?php
declare(strict_types=1);

/**
 * @overridings of functions
 */
$test_functions_values = [];

function get_current_user_id()
{
    global $test_functions_values;

    return $test_functions_values['get_current_user_id'];
}

function sanitize_key( $key ) {
    $sanitized_key = '';

    if ( is_scalar( $key ) ) {
        $sanitized_key = strtolower( $key );
        $sanitized_key = preg_replace( '/[^a-z0-9_\-]/', '', $sanitized_key );
    }

    return $sanitized_key;
}

function esc_html( string $string ) {
    return htmlspecialchars( $string, ENT_QUOTES );;
}

function make_clickable(string $string): string
{
    global $test_functions_values;

    return $test_functions_values['make_clickable'] ?? $string;
}

function is_serialized( $data, $strict = true ) {
    // If it isn't a string, it isn't serialized.
    if ( ! is_string( $data ) ) {
        return false;
    }
    $data = trim( $data );
    if ( 'N;' === $data ) {
        return true;
    }
    if ( strlen( $data ) < 4 ) {
        return false;
    }
    if ( ':' !== $data[1] ) {
        return false;
    }
    if ( $strict ) {
        $lastc = substr( $data, -1 );
        if ( ';' !== $lastc && '}' !== $lastc ) {
            return false;
        }
    } else {
        $semicolon = strpos( $data, ';' );
        $brace     = strpos( $data, '}' );
        // Either ; or } must exist.
        if ( false === $semicolon && false === $brace ) {
            return false;
        }
        // But neither must be in the first X characters.
        if ( false !== $semicolon && $semicolon < 3 ) {
            return false;
        }
        if ( false !== $brace && $brace < 4 ) {
            return false;
        }
    }
    $token = $data[0];
    switch ( $token ) {
        case 's':
            if ( $strict ) {
                if ( '"' !== substr( $data, -2, 1 ) ) {
                    return false;
                }
            } elseif ( ! str_contains( $data, '"' ) ) {
                return false;
            }
        // Or else fall through.
        case 'a':
        case 'O':
        case 'E':
            return (bool) preg_match( "/^{$token}:[0-9]+:/s", $data );
        case 'b':
        case 'i':
        case 'd':
            $end = $strict ? '$' : '';
            return (bool) preg_match( "/^{$token}:[0-9.E+-]+;$end/", $data );
    }
    return false;
}


function maybe_unserialize( $data ) {
    if ( is_serialized( $data ) ) { // Don't attempt to unserialize data that wasn't serialized going in.
        return @unserialize( trim( $data ) );
    }

    return $data;
}

function __(string $string)
{
    return $string;
}