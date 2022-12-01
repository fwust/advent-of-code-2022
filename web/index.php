<?php

use Entity\PuzzleBase;

// Set error level.
error_reporting(E_ALL);
ini_set("display_errors", 1);

// Set a constant for root folders.
$project_root = explode(DIRECTORY_SEPARATOR, __DIR__);
array_pop($project_root);
$project_root = implode(DIRECTORY_SEPARATOR, $project_root);
define('PROJECT_ROOT', $project_root);

// Require autoloader.
require_once('autoload.php');

// Perform puzzles.
new PuzzleBase(FALSE);
for($i = 6; $i > 0; $i--) {
  $day = $i < 10 ? '0' . $i : $i;
  $class_name = 'Puzzle\PuzzleDay' . $day;
  switch ($i) {
    default :
      new $class_name();
      break;
  }
}

exit;