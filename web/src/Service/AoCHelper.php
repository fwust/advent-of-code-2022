<?php

namespace Service;

/**
 * Class AoCHelper, contains helper functions.
 *
 * @package Service
 */
class AoCHelper {

  /**
   * Retrieves the puzzle input for a given day.
   *
   * @param $index
   *
   * @param $delimiter
   *
   * @return false|string[]
   */
  public function getInput($index, $delimiter) {
    $path = PROJECT_ROOT . '/data/input' . $index;
    $input = file_get_contents($path);
    return explode($delimiter, $input);
  }

  /**
   * Print the day title.
   *
   * @param $day
   *
   * @return string
   */
  public function printDay($day) {
    return '<h2>DAY ' . $day . '</h2>';
  }

  /**
   * Print the part title.
   *
   * @param $part
   *
   * @return string
   */
  public function printPart($part) {
    return '<h3>Part ' . $part . '</h3>';
  }
}