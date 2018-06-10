<?php

namespace DOMReader;

class DOMReaderHtml {


    public static function fairing($document) {
        $document   = preg_replace('/[\x00-\x1f]/', '', trim($document));
        $document   = mb_convert_encoding($document, 'HTML-ENTITIES', 'UTF-8');
        $document   = preg_replace('/<\s*meta\s+charset\s*=\s*["\'](.+)["\']\s*\/?\s*>/i', '<meta charset="${1}"><meta http-equiv="Content-Type" content="text/html; charset=${1}">', $document);
        return  $document;
    }


    public static function get_dom($document) {
        libxml_use_internal_errors(true);
        $dom    = new \DOMDocument();
        if ( !$dom->loadHTML($document)) {
            $error  = libxml_get_last_error();
            libxml_clear_errors();
            throw new DOMReaderException($error->message, $error);
        }
        libxml_clear_errors();

        return  $dom;
    }


}