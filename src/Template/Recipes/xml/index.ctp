// src/Template/Recipes/xml/index.ctp
// Do some formatting and manipulation on
// the $recipes array.

$xml = Xml::fromArray(['response' => $recipes]);
echo $xml->asXML();
