<style>
  body{
    font-family: sans-serif;
  }
  strong {
    font-size: 30px;
    background-color: rgba(178, 34, 34, 0.5);
    padding: 5px;
    border-radius: 8px;
    color: cornsilk;
  }
  div{
    margin-bottom: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 20px;
  }
  h1{
      color: firebrick;
  }
  h2 {
    margin-bottom: 0;
    display: flex;
    align-items: center;
    gap: 50px;
    color: firebrick;
  }
  h2:before,
  h2:after{
    content: '';
    display: block;
    width: 20vw;
    height: 1px;
    background-color: firebrick;
  }
  h3{
    font-weight: 100;
    font-size: 20px;
    margin-bottom: 0;
  }
  .kint-rich{
    display: block;
  }
</style>

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
for($i = 25; $i > 0; $i--) {
  $day = sprintf("%02d", $i);
  $class_name = 'Puzzle\PuzzleDay' . $day;
  if (class_exists($class_name)) {
    new $class_name();
  }
}

exit;