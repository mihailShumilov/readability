<?php
    /**
     * Created by PhpStorm.
     * User: godson
     * Date: 4/5/15
     * Time: 21:29
     */

    namespace mihailshumilov;

    class Readabillity
    {

        private $url;
        private $data;
        private $dom;
        private $maxScore = 0;
        private $contentNode = false;

        private $badTags = [
            'header',
            'footer',
            'nav',
            'script',
            'sidebar',
            'noscript',
            'noindex',
//            'table',
//            'ul',
//            'form',
            'input',
            'button',
            'ol',
            'iframe',
            'style',
            'address',
            'dd',
            'dt',
//            'li',
            'time'
        ];

        private $baseBadCssSelector = [
            "//*[php:function('preg_match', '/comment/iu', string(@id))>0]",
            "//*[php:function('preg_match', '/coment/iu', string(@id))>0]",
            "//*[php:function('preg_match', '/comment/iu', string(@class))>0]",
            "//*[php:function('preg_match', '/coment/iu', string(@class))>0]",
            "//*[php:function('preg_match', '/footer/iu', string(@class))>0]",
            "//*[php:function('preg_match', '/footer/iu', string(@class))>0]",
            "//*[php:function('preg_match', '/header/iu', string(@class))>0]",
            "//*[php:function('preg_match', '/header/iu', string(@class))>0]",
            "//*[php:function('preg_match', '/menu/iu', string(@class))>0]",
            "//*[php:function('preg_match', '/menu/iu', string(@class))>0]",
//            "//*[php:function('preg_match', '/sidebar/iu', string(@id))>0]",
//            "//*[php:function('preg_match', '/sidebar/iu', string(@class))>0]"

        ];

        private $badCssSelector = [
            "//*[php:function('preg_match', '/comment/iu', string(@id))>0]",
            "//*[php:function('preg_match', '/comment/iu', string(@class))>0]",
            "//*[php:function('preg_match', '/sidebar/iu', string(@id))>0]",
            "//*[php:function('preg_match', '/sidebar/iu', string(@class))>0]",
            "//*[php:function('preg_match', '/tag/iu', string(@id))>0]",
            "//*[php:function('preg_match', '/tag/iu', string(@class))>0]",
            "//*[php:function('preg_match', '/error/iu', string(@id))>0]",
            "//*[php:function('preg_match', '/error/iu', string(@class))>0]"
        ];


        public function __construct( $url = false, $rawHtml = false )
        {

        	if(!$url && !$rawHtml){
        		throw new \Exception("At least one parameter should be set");
	        }
        	if($url) {
		        if ( filter_var( $url, FILTER_VALIDATE_URL ) ) {
			        $this->url = $url;
		        } else {
			        throw new \Exception( "Parameter `$url` not valid" );
		        }

		        $this->data = $this->loadAsUTF8( $this->url );
	        }
	        if($rawHtml){
        		$this->data = $this->prepareRawData($rawHtml);
	        }

        }


        private function prepareRawData($rawHtml){
        	$data = $rawHtml;
	        preg_match( '/<meta.*?charset="?([a-z\-0-9]*)"?/i', $data, $matches );
	        if (isset( $matches[1] )) {

		        if ($charset = $matches[1]) {
			        $data = mb_convert_encoding( $data, "UTF-8", strtolower( $charset ) );
		        }
	        } else {
		        $data = mb_convert_encoding( $data, "UTF-8" );
	        }
	        return $data;
        }

        public function getContent()
        {
            if ($this->data) {
//                return $this->data;
                $this->createDomObject();
                $this->clean();

                $this->calculateWeight();

                $this->clearContentNode();

                $Document = new \DOMDocument();
                $Document->appendChild( $Document->importNode( $this->contentNode, true ) );
                $this->data = $Document->saveHTML();


                $this->createDomObject();
                $this->calculateWeight();
                $this->clearByScore();

                $this->data = $this->dom->saveHTML();

                $this->createDomObject();
                $this->calculateWeight();
                $this->clearContentNode();
                $this->clearByScore();

                $this->cleanByCSS();


                return $this->dom->saveHTML();
            } else {
                return false;
            }
        }

        protected function createDomObject()
        {
            $this->data = mb_convert_encoding( $this->data, 'HTML-ENTITIES', "UTF-8" );
            $this->dom  = new \DOMDocument( "1.0", "utf-8" );
            libxml_use_internal_errors( true );
            $this->dom->preserveWhiteSpace = false;
            $this->dom->loadHTML( $this->data );
        }

        private function clean()
        {
            $xpath = new \DOMXpath( $this->dom );

            foreach ($this->badTags as $tag) {
                foreach ($xpath->query( '//' . $tag ) as $node) {
                    $node->parentNode->removeChild( $node );
                }
            }

            $xpath->registerNamespace( "php", "http://php.net/xpath" );
            $xpath->registerPHPFunctions();

            foreach ($this->baseBadCssSelector as $selector) {
                if ($nodeList = $xpath->query( $selector )) {
                    foreach ($nodeList as $node) {
                        $node->parentNode->removeChild( $node );
                    }
                }
            }

            return $this->dom->saveHTML();
        }

        private function cleanByCSS()
        {
            $xpath = new \DOMXpath( $this->dom );
            $xpath->registerNamespace( "php", "http://php.net/xpath" );
            $xpath->registerPHPFunctions();

            foreach ($this->badCssSelector as $selector) {
                if ($nodeList = $xpath->query( $selector )) {
                    foreach ($nodeList as $node) {
                        $node->parentNode->removeChild( $node );
                    }
                }
            }
            return $this->dom->saveHTML();
        }

        private function calculateWeight()
        {
            $body  = $this->dom->getElementsByTagName( 'body' )->item( 0 );
            $level = 0;
            $this->processNode( $body, $level );
            return $this->dom->saveHTML();
        }

        /**
         * @param DOMElement $node
         * @param int $level
         */
        private function processNode( $node, $level, &$position = 1 )
        {
            $level ++;
            $selfPosition = $position;

            $text = $node->nodeValue;
            preg_replace( "/\s+/", "", $text );
            $textLength = strlen( $text );
//            $score      = ( $textLength * $level ) / $position;
            $score = ( $textLength * $level );

            if ($this->maxScore < $score) {
                $this->maxScore    = $score;
                $this->contentNode = $node;
            }

            $linkCount = 1;


            foreach ($node->childNodes as $element) {
                $position ++;
                if ($element->childNodes) {
                    $linkCount += $this->processNode( $element, $level, $position );
                }
            }

            $ls        = $textLength / $linkCount;
            $linkScore = "good";
            if ($ls <= 10 && $ls > 0) {
                $linkScore = "bad";
            }
            if ("a" == $node->tagName) {
                if ($textLength <= 40) {
                    $linkScore = "good";
                } else {
                    $linkScore = "bad";
                }
            }
            $node->setAttribute( "level", $level );
            $node->setAttribute( "charcount", $textLength );
            $node->setAttribute( "score", $score );
            $node->setAttribute( "linkcount", $linkCount );
            $node->setAttribute( "linkscore", $linkScore );
            $node->setAttribute( "linkscorevalue", $ls );
            $node->setAttribute( "position", $selfPosition );

            if ("a" == $node->tagName) {
                $linkCount ++;
            }

            return $linkCount;
        }

        private function clearContentNode()
        {
            $this->tryDetectMainContentNode();
        }

        private function tryDetectMainContentNode()
        {
            foreach ($this->contentNode->childNodes as $element) {
                if (is_a( $element, 'DOMElement' )) {
                    if (( ( $element->getAttribute( "score" ) / $this->maxScore ) > 0.4 ) && ( "body" != $element->tagName )) {
                        $this->contentNode = $element;
                        $this->maxScore    = $element->getAttribute( "score" );
                        break;
                    }
                }
            }
        }

        /**
         * @param bool|DOMElement $node
         */
        private function clearByScore( $node = false )
        {
            $xpath = new \DOMXpath( $this->dom );

            foreach ($xpath->query( "//*[@linkscore='bad']" ) as $node) {
                $node->parentNode->removeChild( $node );
            }

            return $this->dom->saveHTML();
        }

        private function loadAsUTF8( $url, $postParams = false )
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
                    $data        = curl_exec( $ch );
                    $information = curl_getinfo( $ch, CURLINFO_CONTENT_TYPE );

                    preg_match( '/charset=([a-z\-0-9]+)/i', $information, $headerMatch );
                    if (isset( $headerMatch[1] )) {
                        $data = mb_convert_encoding( $data, "UTF-8", strtolower( $headerMatch[1] ) );
                    } else {
                        preg_match( '/<meta.*?charset="?([a-z\-0-9]*)"?/i', $data, $matches );
                        if (isset( $matches[1] )) {

                            if ($charset = $matches[1]) {
                                $data = mb_convert_encoding( $data, "UTF-8", strtolower( $charset ) );
                            }
                        } else {
                            $data = mb_convert_encoding( $data, "UTF-8" );
                        }
                    }
//                    $tidyConfig = [
//                        'clean'            => true,
//                        'drop-empty-paras' => true,
//                        'drop-font-tags'   => true,
//                        'fix-backslash'    => true,
//                        'fix-bad-comments' => true,
//                        'fix-uri'          => true,
//                        'hide-comments'    => true
//                    ];
//                    $tidy       = tidy_parse_string( $data, $tidyConfig, 'utf8' );
//                    $tidy->cleanRepair();
//                    $body = $tidy->html();
//                    return $body->value;

                    return $data;
                } catch ( Exception $e ) {
                    return false;
                }
            } else {
                throw new Exception( "No valid url: `{$url}`", 505 );
            }

        }

    }
