<?php

require 'vendor/autoload.php';

use EasyRdf\Graph;

Flight::route('GET /search/@name', function($tekst) {
  $foaf = Graph::newAndLoad('http://oziz.ffos.hr/nastava20202021/vdezic_20/Ontologija/RDF/vdezic.rdf');
  $resourceMatching = $foaf->resourcesMatching('foaf:name', ucfirst($tekst));
  $resource = null;

  foreach ($resourceMatching as $uri) {
    $resource = $foaf->resource($uri);
    break;
  }

  $podaci = [];

  if ($resource == null) {
    return $podaci;
  }

  foreach ($resource->properties() as $property) {

    $vrijednost = (string) $resource->get($property);

    if ($property==='rdf:type') {
      $podaci['type'] = $vrijednost;
      continue;
    }

    if(isset($podaci['properties'])) {
      $podaci['properties'] .= $property . " = " . $vrijednost . "\n";
    } else {
      $podaci['properties'] = $property . " = " . $vrijednost . "\n";
    }
  }

  echo json_encode(array($podaci));

});

Flight::start();