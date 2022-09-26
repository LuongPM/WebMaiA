<?php

/**
 * Based on https://www.php.net/manual/en/function.array-column.php#123045
 *
 * For PHP < 5.5.0
 */
function array_column_ext( $array, $column_key, $index_key = null ) {
    $result = array();
    foreach ( $array as $subarray => $value ) {
        if ( array_key_exists( $column_key, $value ) ) {
            $val = $value[ $column_key ];
        } else if ( $column_key === null ) {
            $val = $value;
        } else {
            continue;
        }

        if ( $index_key === null ) {
            $result[] = $val;
        } elseif ( $index_key == - 1 || array_key_exists( $index_key, $value ) ) {
            $result[ ( $index_key == - 1 ) ? $subarray : $value[ $index_key ] ] = $val;
        }
    }

    return $result;
}

/**
 * For PHP < 5.5.0
 */
if ( ! function_exists( 'boolval' ) ) {
    function boolval( $val ) {
        return (bool) $val;
    }
}

/**
 * For WordPress < 4.0
 */
if ( ! function_exists( 'wp_generate_uuid4' ) ) {
    function wp_generate_uuid4() {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            wp_rand( 0, 0xffff ),
            wp_rand( 0, 0xffff ),
            wp_rand( 0, 0xffff ),
            wp_rand( 0, 0x0fff ) | 0x4000,
            wp_rand( 0, 0x3fff ) | 0x8000,
            wp_rand( 0, 0xffff ),
            wp_rand( 0, 0xffff ),
            wp_rand( 0, 0xffff )
        );
    }
}

/**
 * For WordPress < 5.3.0
 */
if ( ! function_exists( 'wp_timezone_string' ) ) {
    function wp_timezone_string() {
        $timezone_string = get_option( 'timezone_string' );

        if ( $timezone_string ) {
            return $timezone_string;
        }

        $offset  = (float) get_option( 'gmt_offset' );
        $hours   = (int) $offset;
        $minutes = ( $offset - $hours );

        $sign      = ( $offset < 0 ) ? '-' : '+';
        $abs_hour  = abs( $hours );
        $abs_mins  = abs( $minutes * 60 );
        $tz_offset = sprintf( '%s%02d:%02d', $sign, $abs_hour, $abs_mins );

        return $tz_offset;
    }
}
