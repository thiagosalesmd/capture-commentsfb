<?php
    class Facebook {
        private $access_token;
        private $baseUrl = "https://graph.facebook.com/v13.0";
        
        public function __construct($access_token)
        {
            $this->access_token = $access_token;
        }

        public function api($url)
        {
            $ch = curl_init();
            
            curl_setopt($ch, CURLOPT_URL, $this->baseUrl. $url ."?access_token=$this->access_token");
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_NOBODY, 0); // remove bodyx`
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            $result = json_decode(curl_exec($ch), true);
            curl_close($ch);
            return $result;
        
        }
    }