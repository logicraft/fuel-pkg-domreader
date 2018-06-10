<?php

/**
 * Fuel is a fast, lightweight, community driven PHP5 framework.
 *
 * @package    Fuel-DOMReader
 * @version    1.0
 * @author     Miura Daisuke
 * @link
 */

Autoloader::add_core_namespace('DOMReader');

Autoloader::add_classes(array(
	'DOMReader\\DOMReader'          => __DIR__.'/classes/domreader.php',
	'DOMReader\\DOMReaderClient'    => __DIR__.'/classes/reader/client.php',
	'DOMReader\\DOMReaderHtml'      => __DIR__.'/classes/reader/html.php',
	'DOMReader\\DOMReaderXml'       => __DIR__.'/classes/reader/xml.php',
	'DOMReader\\DOMReaderException' => __DIR__.'/classes/exception.php',
));

/* End of file bootstrap.php */