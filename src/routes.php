<?php

require_once "Manuscript.php";
require_once "url-helper.php";
use Transcriba\Manuscript\Manuscript;

// # Routes

// ## No Parameters: Expose Transcriba API information
$router->get([], function($context) {
  $api = array(
    "apiVersion" => 2,
    "name" => "TranscribaServe",
    "description" => "This is a generic repository which does serve random manuscripts",
    "capabilities" => [
      "synchronisation" => false
    ],
    "manuscriptUrl" => getManuscriptUrl($context),
    "browseUrl" => getBrowseUrl($context),
    "logoUrl" => getLogoUrl($context),
    "projectUrl" => ""
  );
  header('Content-Type: application/json');
  echo json_encode($api);
  exit;
});

// ## Provide image file
$router->get(['id', 'download'], function($context) {
  $id = $context['_get']['id'];
  $fileLocation = getManuscriptDirectory().$id.'.jpg';

  // id must have a valid format (avoid abuse)
  if(!preg_match("/\w+/i", $id)){
    throw new Exception('id contains non-alphanumeric characters');
  }

  if(!file_exists($fileLocation)){
    throw new Exception('file doesn not exist');
  }

  // FIXME: allow other image types too
  header('Content-Type: image/jpeg');
  readfile($fileLocation);
  exit;
});

// ## Get Specific Manuscript Metadata
$router->get(['id'], function($context) {
  $id = $context['_get']['id'];

  // id must have a valid format (avoid abuse)
  if(!preg_match("/\w+/i", $id)) {
    throw new Exception('id contains non-alphanumeric characters');
  }

  $manuscript = Manuscript::loadOne($id, getManuscriptDirectory());

  $imageMetadata = array(
    "title" => $manuscript->title,
    "id" => $id,
    "mainAuthor" => $manuscript->mainAuthor,
    "imageUrl" => getBaseUrl($context).'/index.php?id='.$id.'&download=true'
    // "creationDate" => "unknown",
    // "copyright" => "unknown",
    // "license" => "unknown",
    // "linkUrl" => "unknown"
  );
  header('Content-Type: application/json');
  echo json_encode($imageMetadata);
  exit;
});

// ## Get Specific Manuscript Metadata
$router->get(['page', 'search'], function($context) {
  header('Content-Type: application/json');
  $manuscripts =  Manuscript::loadAll(getManuscriptDirectory());
  $result = array(
    "manuscripts" => $manuscripts,
    "itemsInTotal" => count($manuscripts),
    "nextPage" => 2
  );
  echo json_encode($manuscripts);
  exit;
});

// ## Provide logo image
$router->get(['logo'], function($context) {
  $fileLocation = 'logo.png';
  header('Content-Type: image/png');
  readfile($fileLocation);
  exit;
});
