<?php

namespace DOMReader;

class DOMReaderClient {

    private $instance;

    public static function instance($format) {
        if ( !class_exists( 'DOMReader'.$format ) ) {
            throw new \DOMReaderException(strtoupper($format).' load object does not exist.');
        }

        return  new static($format);
    }


    public function __construct($format) {
        $class  = 'DOMReader'.$format;
        $this->instance = new $class();
    }


    public function load($path) {
        try {
            $document   = file_exists($path)
                        ? file_get_contents($path)
                        : $this->file_get_contents($path);
        } catch (\Exception $e) {
            throw new DOMReaderException($e->getMessage(), $e->getCode(), $e->getPrevious(), $e->getFile(), $e->getLine());
        }

        return  $this->instance->fairing($document);
    }


    public function get_dom($document) {
        return  $this->instance->get_dom($document);
    }


    protected function file_get_contents($url) {
        $curl_handle    = curl_init();
        foreach ($this->config($url) as $key => $value) {
            $key    = 'CURLOPT_'.strtoupper($key);
            if ( !is_bool($value) and \Validation::_empty($value) or !defined($key) ) {
                continue;
            }

            curl_setopt($curl_handle, constant($key), $value);
        }

        if( false === $result = curl_exec($curl_handle)) {
            trigger_error(curl_error($curl_handle));
        }
        curl_close($curl_handle);

        return  $result;
    }


    protected function config($url) {
        \Config::load('domreader', true);
        $config = \Arr::merge(\Config::get('domreader.curl'), [
            'url'               => $url,
            'returntransfer'    => true,
            'followlocation'    => true,
            'maxredirs'         => 5,
            'autoreferer'       => true,
            'header'            => false,
            'nobody'            => false,
        ]);

        return  $config;
    }


}