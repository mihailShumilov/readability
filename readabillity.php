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

        private $badTags = ['header', 'footer', 'nav', 'script'];

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
                return $this->clean();

            }else{
                return false;
            }
        }

        protected function createDomObject(){
            $this->data = mb_convert_encoding( $this->data, 'HTML-ENTITIES', "UTF-8" );
            $this->dom  = new \DOMDocument( "1.0", "utf-8" );
            libxml_use_internal_errors( true );
            $this->dom->preserveWhiteSpace = false;
            $this->dom->loadHTML( $this->data );
        }

        private function clean(){
            foreach($this->badTags as $tag) {
                $elements = $this->dom->getElementsByTagName( $tag );
                while ($el = $elements->item( 0 )) {
                    $el->parentNode->removeChild( $el );
                }
            }
            return $this->dom->saveHTML();
        }

    }