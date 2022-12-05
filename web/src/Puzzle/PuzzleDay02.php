<?php

namespace Puzzle;

use Entity\PuzzleBase;
use function array_search;
use function explode;

class PuzzleDay02 extends PuzzleBase {

  private array $shape_points = [1, 2, 3]; // Rock, Paper, Scissors
  private array $defeat_matrix = [2, 0, 1]; // Rock defeats Scissors, Scissors defeats Paper, and Paper defeats Rock
  private array $outcome_points = [0, 3, 6]; // Lost, draw, win
  private array $villain_shapes = ['A', 'B', 'C'];
  private array $hero_shapes = ['X', 'Y', 'Z'];

    /**
   * @inheritDoc
   */
  public function __construct(bool $load_input = TRUE, string $input_delimiter = "\n") {
    $this->day = 2;
    parent::__construct($load_input, $input_delimiter);
  }

  /**
   * @inheritDoc
   */
  final public function processPart1(): void {
    $this->render($this->helper->printPart('one'));
    $score = $this->getTotalScore();
    $this->render("The total score is <br> <strong>$score</strong>");

    // Process the second part of the puzzle.
    $this->processPart2();
  }

  /**
   * @inheritDoc
   */
  final public function processPart2(): void {
    $this->render($this->helper->printPart('two'));
    $score = $this->getTotalScore(true);
    $this->render("The total score is <br> <strong>$score</strong>");
  }

  private function getTotalScore(bool $alternative_shape = false): int {
    $score = 0;
    foreach ($this->input as $round) {
      $villain_shape = $this->getVillainShape($round);
      $hero_shape = $this->getHeroShape($round, $alternative_shape);

      $score += $this->shape_points[$hero_shape];
      if ($this->defeat_matrix[$villain_shape] === $hero_shape) {
        // Lost
        $score += $this->outcome_points[0];
      }
      else if ($villain_shape === $hero_shape) {
        // Draw
        $score += $this->outcome_points[1];
      }
      else {
        // Win
        $score += $this->outcome_points[2];
      }
    }
    return $score;
  }

  private function getVillainShape(string $round): int {
    return array_search(explode(' ', $round)[0], $this->villain_shapes, true);
  }

  private function getHeroShape(string $round, bool $alternative_shape = false): int {
    $hero_shape = array_search(explode(' ', $round)[1], $this->hero_shapes, true);
    if ($alternative_shape) {
      $villain_shape = $this->getVillainShape($round);
      if ($hero_shape === 0) {
        return $this->defeat_matrix[$villain_shape]; // returns the shape that defeats villain
      }
      if ($hero_shape === 1) {
        return $villain_shape; // return the same shape in order to do a draw
      }
      return array_search($villain_shape, $this->defeat_matrix, true); // returns the shape that beats villain
    }
    return $hero_shape;
  }
}