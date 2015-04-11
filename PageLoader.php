<?php
    /**
     * Created by PhpStorm.
     * User: godson
     * Date: 26.11.14
     * Time: 06:41
     */
    namespace readability;

    class PageLoader
    {
        public static function load( $url, $postParams = false )
        {
            if (filter_var( $url, FILTER_VALIDATE_URL )) {
                try {
                    $ch      = curl_init();
                    $timeout = 30;
                    curl_setopt( $ch, CURLOPT_URL, $url );
                    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
                    curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
                    curl_setopt( $ch, CURLOPT_TIMEOUT, $timeout );
                    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
                    curl_setopt(
                        $ch,
                        CURLOPT_USERAGENT,
                        'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru-RU; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13'
                    );
                    curl_setopt( $ch, CURLOPT_REFERER, "http://nagg.in.ua/" );
                    curl_setopt( $ch, CURLOPT_ENCODING, 'UTF-8' );
                    if (isset( $postParams ) && ! empty( $postParams )) {
                        curl_setopt( $ch, CURLOPT_HTTP_VERSION, '1.1' );
                        curl_setopt( $ch, CURLOPT_POST, 1 );
                        curl_setopt( $ch, CURLOPT_POSTFIELDS, $postParams );
                    }
                    $data = curl_exec( $ch );
                    curl_close( $ch );
                    return $data;
                } catch ( Exception $e ) {
                    return false;
                }
            } else {
                throw new Exception( "No valid url: `{$url}`", 505 );
            }
        }

    }