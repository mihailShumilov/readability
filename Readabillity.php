<?php
    namespace readability;

    use JonnyW\PhantomJs\Client;

    class Readabillity {

        private $url;
        private $rawSource;

        public function __construct($url){
            if(filter_var($url, FILTER_VALIDATE_URL)){
                $this->url = $url;
                $this->loadHtmlData();

            }else{
                throw new ReadabilityException("Bad url format", 505);
            }
        }

        protected function loadHtmlData(){
            if($this->url){
                $client = Client::getInstance();
                $request  = $client->getMessageFactory()->createRequest();
                $response = $client->getMessageFactory()->createResponse();
                $request->setMethod('GET');
                $request->setUrl($this->url);
                $client->send($request, $response);
                $this->rawSource = $response;
                return true;
            }else{
                return false;
            }
        }

        public function getContent(){
            return $this->rawSource->content;
        }
    }