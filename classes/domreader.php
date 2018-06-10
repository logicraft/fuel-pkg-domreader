<?php

namespace DOMReader;

class DOMReader {

    private $instance;
    private $format;
    private $document;
    private $dom;


    protected function __construct($format, $url) {
        $this->format   = $format;
        $this->instance = DOMReaderClient::instance($format);
        $this->document = $this->instance->load($url);
    }


    /**
     *
     * @param  string $url
     * @return \static
     */
    public static function html($url) {
        return  new static('html', $url);
    }


    /**
     *
     * @param  string $url
     * @return \static
     */
    public static function xml($url) {
        return  new static('xml', $url);
    }


    /**
     *
     * @return array
     */
    public function to_array() {
        if ( $this->format === 'xml' ) {
            $data   = $this->document;
        }
        else {
            $this->_dom_load();
            $data   = $this->dom['document']->saveXML();
        }

        try {
            return  \Format::forge($data, 'xml:ns')->to_array();
        } catch ( \PhpErrorException $e ) {
            throw new DOMReaderException($e->getMessage(), $e->getCode(), $e->getPrevious(), $e->getFile(), $e->getLine());
        }
    }

    /*
     *
     * @param  string  $xpath
     * @param  integer $item
     * @return \DOMNodeList | \DOMElement
     */
    public function xpath( $xpath, int $item = null ) {
        if ( !isset( $this->dom['xpath'] ) ) {
            $this->_dom_load();
            $this->dom['xpath'] = new \DOMXPath($this->dom['document']);

            if ( $root_ns = $this->dom['document']->lookupNamespaceUri($this->dom['document']->namespaceURI) ) {
                $this->dom['xpath']->registerNamespace(pathinfo($root_ns, PATHINFO_FILENAME), $root_ns);
            }
            unset($root_ns);
        }

        $node   = $this->dom['xpath']->query($xpath);
        return  \Validation::_empty($item) ? $node : $node->item($item);
    }


    /**
     *
     * @param  string $xpath
     * @param  string $attribute
     * @return string
     */
    public function xpath_attribute( $xpath, $attribute ) {
        $node   = $this->xpath($xpath, 0);
        return  $node ? $node->getAttribute($attribute) : $node;
    }


    /**
     *
     * @param  string  $xpath
     * @return string
     */
    public function xpath_value( $xpath ) {
        $node   = $this->xpath($xpath, 0);
        return  $node ? $node->nodeValue : $node;
    }


    public function _dom_load() {
        if ( !isset( $this->dom['document'] ) ) {
            $this->dom['document']  = $this->instance->get_dom( $this->document );
            unset( $this->instance );
        }
    }


}