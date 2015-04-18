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

        private $badTags = [
            'header',
            'footer',
            'nav',
            'script',
            'sidebar',
            'noscript',
            'noindex',
            'table',
//            'ul',
            'form',
            'input',
            'button',
            'ol',
            'iframe',
            'style',
            'address',
            'ol',
            'dd',
            'dt',
//            'li'
        ];


        public function __construct( $url )
        {
            if (filter_var( $url, FILTER_VALIDATE_URL )) {
                $this->url = $url;
            } else {
                throw new \Exception( "Parameter `$url` not valid" );
            }
        }

        public function getContent()
        {
            if ($this->data = PageLoader::load( $this->url )) {
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

            $linkCount = 1;


            foreach ($node->childNodes as $element) {
                if ($element->childNodes) {
                    $linkCount += $this->processNode( $element, $level );
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
                    if (( $element->getAttribute( "score" ) / $this->maxScore ) > 0.4) {
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

    }