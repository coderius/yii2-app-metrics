<?php

namespace common\helpers;

use DateTime;
use Yii;
use yii\base\InvalidArgumentException;

class UtilHelper
{

    /** Get the start and end dates of a week by its ordinal number in the year
     * @param array $arrDates
     * @return string
     */
    public static function getWeekDatesByWeekNumber( array $arrDates ): string
    {
        $res = '';
        foreach ( $arrDates as $k => $v ) {
            $tmpArr = explode( '-', $v );
            $weekStart = new DateTime();
            $weekStart->setISODate( $tmpArr[ 0 ], $tmpArr[ 1 ] );

            $res .= $v . ' ( from ' . $weekStart->format( 'Y-m-d' ) . ' to ' . $weekStart->modify( '+6 day' )->format( 'Y-m-d' ) . ')<br />';
        }

        return $res;
    }

    /**
     * Custom sorting
     * $sort = field-order string
     * $rows = array of associative arrays
     * @param $rows array
     * @param $sort string
     * @return mixed
     */
    public static function customTableSorting( $rows, $sort )
    {
        $arr = explode( '-', $sort );
        if ( !empty( $arr[ 0 ] ) && !empty( $arr[ 1 ] ) ){
            $order = $arr[ 1 ];
            $field = $arr[ 0 ];
            usort( $rows, function ( $item1, $item2 ) use ( $order, $field ) {
                if ( $order == 'asc' ){
                    return $item1[ $field ] < $item2[ $field ];
                } else {
                    return $item1[ $field ] > $item2[ $field ];
                }
            } );
        }

        return [ 'rows' => $rows, 'order' => !empty( $order ) ? $order : '', 'field' => !empty( $field ) ? $field : '' ];
    }

    public static function isTimeAfterHour( $hour )
    {
        return Yii::$app->formatter->asDatetime( 'NOW', 'H' ) >= $hour;
    }


    /**
     * @return string
     */
    public static function generateApiKey()
    {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        $pass = []; //remember to declare $pass as an array
        $alphaLength = strlen( $alphabet ) - 1; //put the length -1 in cache
        for ( $i = 0; $i < 10; $i++ ) {
            $n = rand( 0, $alphaLength );
            $pass[] = $alphabet[ $n ];
        }
        $pass[] = '!';

        return md5( implode( $pass ) );
    }

    /** Shuffle assoc array
     * @param $my_array
     * @return array
     */
    public static function shuffleAssoc( $my_array )
    {
        $keys = array_keys( $my_array );

        shuffle( $keys );

        foreach ( $keys as $key ) {
            $new[ $key ] = $my_array[ $key ];
        }

        return $new;
    }


    public static function getColors()
    {
        return [
            "#EB8258", "#9BC53D", "#5BC0EB", "#F77F00", "#5603AD", "#FECDAA", "#335145", "#A5243D", "#9DB9DB", "#29339B",
            "#B57F50", "#6C6EA0", "#DA2C38", "#FF7B9C", "#6A4C93", "#0C7C59", "#2D7DD2", "#026C7C", "#88A0A8", "#05A8AA",
            "#EB8258", "#9BC53D", "#5BC0EB", "#F77F00", "#5603AD", "#FECDAA", "#335145", "#A5243D", "#9DB9DB", "#29339B",
            "#B57F50", "#6C6EA0", "#DA2C38", "#FF7B9C", "#6A4C93", "#0C7C59", "#2D7DD2", "#026C7C", "#88A0A8", "#05A8AA",
            "#EB8258", "#9BC53D", "#5BC0EB", "#F77F00", "#5603AD", "#FECDAA", "#335145", "#A5243D", "#9DB9DB", "#29339B",
            "#B57F50", "#6C6EA0", "#DA2C38", "#FF7B9C", "#6A4C93", "#0C7C59", "#2D7DD2", "#026C7C", "#88A0A8", "#05A8AA",
            "#EB8258", "#9BC53D", "#5BC0EB", "#F77F00", "#5603AD", "#FECDAA", "#335145", "#A5243D", "#9DB9DB", "#29339B",
            "#B57F50", "#6C6EA0", "#DA2C38", "#FF7B9C", "#6A4C93", "#0C7C59", "#2D7DD2", "#026C7C", "#88A0A8", "#05A8AA",
            "#EB8258", "#9BC53D", "#5BC0EB", "#F77F00", "#5603AD", "#FECDAA", "#335145", "#A5243D", "#9DB9DB", "#29339B",
            "#B57F50", "#6C6EA0", "#DA2C38", "#FF7B9C", "#6A4C93", "#0C7C59", "#2D7DD2", "#026C7C", "#88A0A8", "#05A8AA",
        ];
    }


    /**
     * @param $email
     * @return string
     */
    public static function obfuscateEmail( $email )
    {
        $em = explode( "@", $email );
        $name = implode( '@', array_slice( $em, 0, count( $em ) - 1 ) );
        $len = floor( strlen( $name ) / 2 );

        return substr( $name, 0, $len ) . str_repeat( '*', $len ) . "@" . end( $em );
    }

    /**
     * Format a number with grouped thousands
     * @param int|string $num number or numeric string for formatting
     * @param int $to sets the number of decimal digits. If 0, the decimal_separator is omitted from the return value.
     * @param string $decimal_separator sets the separator for the decimal point.
     * @param string $thousands_separator sets the thousands separator.
     * @return string return formatted number or input value if not numeric param set to $num arg
     */
    public static function numberFormate( $num, $to = 2, $decimal_separator = ".", $thousands_separator = "" )
    {
        $num = trim( $num );

        return is_numeric( $num ) ? number_format( $num, $to, $decimal_separator, $thousands_separator ) : $num;
    }


    /**
     * @param string $string
     * @param int $maxlen
     * @param string $suffix
     * @return string
     */
    public static function truncateString( string $string, int $maxlen = 500, string $suffix = '...' ): string
    {
        $len = (mb_strlen( $string ) > $maxlen)
            ? mb_strripos( mb_substr( $string, 0, $maxlen ), ' ' )
            : $maxlen;
        $cutStr = mb_substr( $string, 0, $len );

        return (mb_strlen( $string ) > $maxlen) ? $cutStr . $suffix : $cutStr;
    }


    /**
     * @param string $string
     * @param $count
     * @param string $suffix
     * @return string
     */
    public static function truncateWords( string $string, $count, string $suffix = '...' ): string
    {
        $words = preg_split( '/(\s+)/u', trim( $string ), null, PREG_SPLIT_DELIM_CAPTURE );
        if ( count( $words ) / 2 > $count ){
            return implode( '', array_slice( $words, 0, ($count * 2) - 1 ) ) . $suffix;
        }

        return $string;
    }

}