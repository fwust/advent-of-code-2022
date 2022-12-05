<?php

namespace Puzzle;
use Entity\PuzzleBase;

class PuzzleDay05 extends PuzzleBase {

  private array $stakes = [];

    /**
   * @inheritDoc
   */
  public function __construct(bool $load_input = TRUE, string $input_delimiter = "\n") {
    $this->day = 5;
    parent::__construct($load_input, $input_delimiter);
  }

  /**
   * @inheritDoc
   */
  final public function processPart1(): void {
    $this->render($this->helper->printPart('one'));
    $this->stakes = $this->getStakesOfCrates();

    $reordered_stakes = $this->applyMoves($this->stakes);
    $result = $this->getTopCrates($reordered_stakes);

    $this->render("What crate ends up on top of each stack?? <br> <strong>$result</strong>");

    // Process the second part of the puzzle.
    $this->processPart2();
  }

  /**
   * @inheritDoc
   */
  final public function processPart2(): void {
    $this->render($this->helper->printPart('two'));

    $reordered_stakes = $this->applyMoves($this->stakes, true);
    $result = $this->getTopCrates($reordered_stakes);

    $this->render("What crate ends up on top of each stack?? <br> <strong>$result</strong>");
  }

  /**
   * Builds an array of stakes from raw input.
   *
   * @return array
   */
  private function getStakesOfCrates(): array {
    $stakes = [];

    foreach ($this->input as $k => $item) {
      if ($item === '') {
        $stakesMaxKey = $k; break;
      }
      $len_max = strlen($item);
      $stakes[$k] = [];
      for ($i = 0; $i < $len_max; $i++) {
        $v = 4 * $i + 2;
        if ($v < $len_max) {
            $stakes[$k][] = trim($item[$v - 1]);
        }
      }
    }

    // Cleaning
    array_splice($this->input, 0, ($stakesMaxKey ?? 0) + 1);
    // Remove the number line
    array_pop($stakes);

    // Transpose array (thanks https://github.com/nicoloye 🙏)
    $stakes = array_map(null, ...$stakes);
    $stakes = array_map(static fn($value): array => array_reverse($value), $stakes);
    return array_map(static fn($value): array => array_filter($value, static function($value ) { return ($value); }), $stakes);
  }

  /**
   * @param array $stakes
   * @param bool $rearrangement
   * @return array
   */
  private function applyMoves(array $stakes, bool $rearrangement = false): array {
    foreach ($this->input as $item) {
      preg_match_all('!\d+!', $item, $moves);

      $n_moves = $moves[0][0];
      $from_key = $moves[0][1] - 1;
      $to_key = $moves[0][2] - 1;

      $from = array_reverse($stakes[$from_key]);
      $crates_to_move = array_splice($from, 0, $n_moves);

      if ($rearrangement) {
        $crates_to_move = array_reverse($crates_to_move);
      }

      $stakes[$from_key] = array_reverse($from);
      $stakes[$to_key] = array_merge($stakes[$to_key], $crates_to_move);
    }

    return $stakes;
  }

  /**
   * Returns the top crates combined in one string
   *
   * @param array $stakes
   * @return string
   */
  private function getTopCrates(array $stakes): string {
    $return_str = "";
    foreach ($stakes as $stake) {
      $stake = array_values(array_reverse($stake));
      if (isset($stake[0])) {
        $return_str .= $stake[0];
      }
    }
    return $return_str;
  }

}