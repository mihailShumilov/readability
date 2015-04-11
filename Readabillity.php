<?php
    /**
     * Created by PhpStorm.
     * User: godson
     * Date: 4/5/15
     * Time: 21:29
     */

    namespace readability;

    class Readabillity
    {

        private $url;
        private $data;
        private $dom;
        private $maxScore = 0;
        private $contentNode = false;

        private $badTags = [ 'header', 'footer', 'nav', 'script', 'sidebar', 'noscript', 'noindex', 'table', 'ul' ];


        public function __construct( $url )
        {
            if (filter_var( $url, FILTER_VALIDATE_URL )) {
                $this->url = $url;
            } else {
                throw new Exception( "Parameter `$url` not valid" );
            }
        }

        public function getContent()
        {
            if ($this->data = PageLoader::load( $this->url )) {
                $this->createDomObject();
                $this->clean();
                $this->calculateWeight();

                $Document = new \DOMDocument();
                $Document->appendChild( $Document->importNode( $this->contentNode, true ) );
                return $Document->saveHTML();
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
            foreach ($this->badTags as $tag) {
                $elements = $this->dom->getElementsByTagName( $tag );
                while ($el = $elements->item( 0 )) {
                    $el->parentNode->removeChild( $el );
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
        private function processNode( $node, $level )
        {
            $level ++;

            $text = $node->nodeValue;
            preg_replace( "/\s+/", "", $text );
            $textLength = strlen( $text );
            $score      = $textLength * $level;

            if ($this->maxScore < $score) {
                $this->maxScore    = $score;
                $this->contentNode = $node;
            }

            $node->setAttribute( "level", $level );
            $node->setAttribute( "charcount", $textLength );
            $node->setAttribute( "score", $score );
            foreach ($node->childNodes as $element) {
                if ($element->childNodes) {
                    $this->processNode( $element, $level );
                }
            }
        }
    }