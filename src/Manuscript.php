<?php

namespace Transcriba\Manuscript;

class Manuscript {

  /**
   * Loads all manuscripts from a directory
   * NOTE: filename without file extension is considered as the id
   *
   * @param string directory
   * @return array
   */
  static public function loadAll(string $directory) {
    $directoryItems = scandir($directory);

    // clean directory from .. and . (as in linux environments)
    $directoryItems = array_diff($directoryItems, array('..', '.'));

    // for each manuscript there must be a corresponding image and json file
    // let's check that by pushing filenames into keys of an assoc. array
    // and setting a counter as value. We expect two files with the same name
    // each (< 2 will be ignored)
    $hashArray = array();
    foreach($directoryItems as $item){
      $pathParts = pathinfo($item); // get filename among others
      $filename = $pathParts['filename']; // save it for convenience

      if(isset($hashArray[$filename])) {
        // to find more than two files with the same name
        //  is not expected
        if(count($hashArray[$filename]) == 2) {
          throw new \Exception('too many files with the same name found');
        }
        // add file
        $hashArray[$filename][] = $item;
      }else{
        // initiate array
        $hashArray[$filename] = array($item);
      }
    }

    // finally load the manuscript data
    $manuscripts = [];
    foreach($hashArray as $filename => $files) {
      $numOfFiles = count($files);
      if($numOfFiles == 2 && in_array($filename.'.json', $files)){
        $manuscripts[] = self::loadOne($filename, $directory);
      }
    }

    return $manuscripts;
  }

  static public function loadOne($id, $directory) {
    $fileLocation = $directory.$id.'.json';

    if(!file_exists($fileLocation)){
      throw new \Exception('json file not found');
    }

    $fileContents = file_get_contents($fileLocation);

    if($fileContents == false) {
      throw new \Exception('json file was not readable');
    }

    $fileData = json_decode($fileContents);

    $manuscript = new Manuscript();
    $manuscript->id = $id;
    $manuscript->title = $fileData->title;
    $manuscript->mainAuthor = $fileData->mainAuthor;
    return $manuscript;
  }

  public $id;
  public $title;
  public $mainAuthor;
}
