<?php

namespace Entity;

/**
 * Interface PuzzleInterface.
 */
interface PuzzleInterface {

  /**
   * Preprocess any required input before processing the puzzle.
   *
   * @param bool $load_input
   *
   * @param string $input_delimiter
   *
   * @return mixed|void
   */
  public function preprocess(bool $load_input = TRUE, string $input_delimiter = "\n");

  /**
   * Process the puzzle input and calculate the intended results for part 1.
   *
   * @return mixed|void
   */
  public function processPart1();

  /**
   * Process the puzzle input and calculate the intended results for part 2.
   *
   * @return mixed|void
   */
  public function processPart2();

  /**
   * Output the results.
   *
   * @param string $message
   *
   * @return mixed|void
   */
  public function render(string $message);
}