<?php
declare(strict_types=1);

namespace Puzzle;

use Entity\PuzzleBase;
use function array_count_values;
use function array_map;
use function array_merge;
use function array_product;
use function array_reverse;
use function array_slice;
use function count;
use function max;
use function str_split;

class PuzzleDay08 extends PuzzleBase {

  private int $map_size;
  private array $tree_height_matrix = [];
  private array $tree_visibility_matrix = [];
  private array $scenic_scores = [];

    /**
   * @inheritDoc
   */
  public function __construct(bool $load_input = TRUE, string $input_delimiter = "\n") {
    $this->day = 8;
    parent::__construct($load_input, $input_delimiter);
  }

  /**
   * @inheritDoc
   */
  final public function processPart1(): void {
    $this->render($this->helper->printPart('one'));

    $this->prepareData()->checkVisibility();

    $number_tree_visible = array_count_values(array_merge(...$this->tree_visibility_matrix))[1];
    $this->render("The number of trees that are visible from outside the grid is <br> <strong>$number_tree_visible</strong>");
    $this->processPart2();
  }

  /**
   * @inheritDoc
   */
  final public function processPart2(): void {
    $this->render($this->helper->printPart('two'));
    $highest_scenic_score = max($this->scenic_scores);
    $this->render("The highest scenic score possible for any tree is <br> <strong>$highest_scenic_score</strong>");
  }

  /**
   * Prepare the tree_visibility_matrix and tree_height_matrix from the input.
   * @return $this
   */
  private function prepareData(): self {
    $tree_row_length = count(str_split($this->input[0])) - 1;
    $this->map_size = count($this->input) - 1;
    foreach ($this->input as $x => $input) {
      $line_trees = str_split($input);
      foreach($line_trees as $y => $tree) {
        $this->tree_height_matrix[$x][$y] = $tree;
        if ($x === 0 || $y === 0 || $x === $tree_row_length || $y === $this->map_size) {
          // Trees that are on edge of the map are all visible
          $this->tree_visibility_matrix[$x][$y] = 1;
        } else {
          $this->tree_visibility_matrix[$x][$y] = 0;
        }
      }
    }
    return $this;
  }

  /**
   * Check the visibility from outside the grid for each tree and calculate it's scenic score.
   * @return void
   */
  private function checkVisibility(): void {
    foreach ($this->tree_visibility_matrix as $x => $row) {
      foreach ($row as $y => $is_visible) {
        if (!$is_visible) {
          $tree_height = $this->tree_height_matrix[$x][$y];
          // Transpose matrix in order to easily get the top and bottom trees
          $transpose_heights = array_map(static fn(...$col) => $col, ...$this->tree_height_matrix);
          // Retrieve the trees closest to the current one in all four directions
          $left_trees = array_slice($this->tree_height_matrix[$x], 0, $y);
          $top_trees = array_slice($transpose_heights[$y], 0, $x);
          $right_trees = array_slice($this->tree_height_matrix[$x], $y + 1, $this->map_size - $y);
          $bottom_trees = array_slice($transpose_heights[$y], $x + 1, $this->map_size - $x);
          // Set visibility to 1 if current tree is visible from outside the grid
          if ($tree_height > max($left_trees) || $tree_height > max($right_trees) || $tree_height > max($top_trees) || $tree_height > max($bottom_trees)) {
            $this->tree_visibility_matrix[$x][$y] = 1;
          }
          // Calculate the scenic score
          $score = $this->getTreeScore((int) $tree_height, array_reverse($left_trees), $right_trees, array_reverse($top_trees), $bottom_trees);
          $this->scenic_scores[] = array_product($score);
        }
      }
    }
  }

  /**
   * Returns the viewing distance score.
   * @param int $current_tree_height
   * @param array ...$trees_array
   * @return array
   */
  private function getTreeScore(int $current_tree_height, array ...$trees_array): array {
    $score = [];
    foreach ($trees_array as $trees) {
      foreach ($trees as $k => $tree) {
        if ($current_tree_height <= $tree) {
          $score[] = $k + 1;
          break;
        }
        if ($k === count($trees)-1) {
          $score[] = $k + 1;
        }
      }
    }
    return $score;
  }
}