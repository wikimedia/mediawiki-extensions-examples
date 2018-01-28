<?php

//////////////////////////////////////////////////////////////
// Example settings for activating the DataPages extension
//////////////////////////////////////////////////////////////

// load the extension
require_once $IP . '/extensions/examples/DataPages/DataPages.php';

// Define a custom namespace for XML.
// If your extension is going to use an extra namespace,
// you should list the suggested default at
// <http://www.mediawiki.org/wiki/Extension_default_namespaces>.
define( 'NS_XML', 234 );
define( 'NS_XML_TALK', NS_XML + 1 );

$wgExtraNamespaces[ NS_XML ] = 'XML';
$wgExtraNamespaces[ NS_XML_TALK ] = 'XML_Talk';

// Associate the XML namespace with the XML content model provided by the extension.
// CONTENT_MODEL_XML_DATA is a constant defined in DataPages.php.
$wgNamespaceContentModels[ NS_XML ] = CONTENT_MODEL_XML_DATA;
