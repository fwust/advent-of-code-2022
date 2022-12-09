<?php
declare(strict_types=1);

namespace Puzzle;

use Entity\PuzzleBase;
use function abs;
use function array_unique;
use function count;
use function explode;

class PuzzleDay09 extends PuzzleBase {

  private int $hx = 0;
  private int $hy = 0;
  private int $tx = 0;
  private int $ty = 0;
  private array $tail_visited = [];
  private array $knots = [];
  private array $move_matrix = [
    'R' => [1, 0],
    'L' => [-1, 0],
    'U' => [0, 1],
    'D' => [0, -1]
  ];

  /**
   * @inheritDoc
   */
  public function __construct(bool $load_input = TRUE, string $input_delimiter = "\n") {
    $this->day = 9;
    parent::__construct($load_input, $input_delimiter);
  }

  /**
   * @inheritDoc
   */
  final public function processPart1(): void {
    $this->render($this->helper->printPart('one'));

    $this->processMotions(0);
    $result = $this->calculateUniqueTailPositions();

    $this->render("The number of positions that the tail of the rope visit at least once is <br> <strong>$result</strong>");
    $this->processPart2();
  }

  /**
   * @inheritDoc
   */
  final public function processPart2(): void {
    $this->render($this->helper->printPart('two'));

    $this->processMotions(10);
    $result = $this->calculateUniqueTailPositions();

    $this->render("The number of positions that the tail of the rope visit at least once is <br> <strong>$result</strong>");
  }

  /**
   * @param int $x
   * @param int $y
   * @return void
   */
  private function doMove(int $x, int $y): void {
    if (empty($this->knots)) {
      $this->hx += $x;
      $this->hy += $y;
      $this->moveTail();
      $this->tail_visited[] = [$this->tx, $this->ty];
    } else {
      $this->knots[0][0] += $x;
      $this->knots[0][1] += $y;
      $n_knots = count($this->knots);
      for ($i = 1; $i < $n_knots; $i++) {
        [$this->hx, $this->hy] = $this->knots[$i - 1];
        [$this->tx, $this->ty] = $this->knots[$i];
        $this->moveTail();
        $this->knots[$i] = [$this->tx, $this->ty];
        $this->tail_visited[] = $this->knots[$n_knots-1];
      }
    }
  }

  /**
   * Checks if two positions are adjacent in all dimensions.
   * @param int $x1
   * @param int $y1
   * @param int $x2
   * @param int $y2
   * @return bool
   */
  private function isAdjacent(int $x1, int $y1, int $x2, int $y2): bool {
    return abs($x1 - $x2) <= 1 && abs($y1 - $y2) <= 1;
  }

  /**
   * Process the series of motions given in input.
   * @param int $n_knots
   * @return void
   */
  private function processMotions(int $n_knots): void {
    $this->initializeValues($n_knots);
    foreach ($this->input as $input) {
      [$op, $moves] = explode(' ', $input);
      [$x, $y] = $this->move_matrix[$op];
      for ($i = 0; $i < $moves; $i++) {
        $this->doMove($x, $y);
      }
    }
  }

  /**
   * Move the tail if it is not adjacent to the head.
   * @return void
   */
  private function moveTail(): void {
    if (!$this->isAdjacent($this->hx, $this->hy, $this->tx, $this->ty)) {
      $this->tx += ($this->hx === $this->tx) ? 0 : ($this->hx - $this->tx) / abs($this->hx - $this->tx);
      $this->ty += ($this->hy === $this->ty) ? 0 : ($this->hy - $this->ty) / abs($this->hy - $this->ty);
    }
  }

  /**
   * Reinitialize global variables for part 2.
   * @param int $n_knots
   * @return void
   */
  private function initializeValues(int $n_knots): void {
    $this->tail_visited = [];
    $this->knots = [];
    $this->hx = 0;
    $this->hy = 0;
    $this->tx = 0;
    $this->ty = 0;
    $this->tail_visited[] = [$this->tx, $this->ty];
    if ($n_knots > 0) {
      for ($i = 0; $i < $n_knots; $i++) {
        $this->knots[] = [0, 0];
      }
    }
  }

  /**
   * Return the number of positions that the tail of the rope visit at least once
   * @return int
   */
  private function calculateUniqueTailPositions(): int {
    $this->tail_visited = array_unique($this->tail_visited, SORT_REGULAR);
    return count($this->tail_visited);
  }

}