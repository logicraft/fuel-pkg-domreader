<?php

namespace DOMReader;

class DOMReaderXml {


    public static function fairing($document) {
        $document   = preg_replace('/[\x00-\x1f]/', '', $document);
        $document   = preg_replace('/(?:(?: +)(xmlns[:=]))/', " $1", $document);
        $document   = str_replace('"xml', '" xml', $document);
        $document   = str_replace('rdf:resource', 'resource', $document);
        $document   = str_replace('&', '&amp;', $document);
        return  $document;
    }


    public static function get_dom($document) {
        libxml_use_internal_errors(true);
        $dom    = new \DOMDocument();
        if ( !$dom->loadXML($document) ) {
            $error  = libxml_get_last_error();
            libxml_clear_errors();
            throw new DOMReaderException($error->message, $error);
        }
        libxml_clear_errors();

        return  $dom;
    }


//    public static function namespace() {
//
//    }


}