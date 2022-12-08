<?php
declare(strict_types=1);

namespace Puzzle;
use Entity\PuzzleBase;
use function array_shift;
use function array_slice;
use function array_sum;
use function count;
use function explode;
use function ksort;
use function min;
use function str_starts_with;
use function uasort;

class PuzzleDay07 extends PuzzleBase {

  private int $current_dir = 0; // The value that corresponds to the current key in $directories_tree
  private int $current_depth = 1;
  private array $files_tree = [];
  private array $directories_tree = [
    [
      'parent' => 0,
      'name' => '/',
      'size' => 0,
      'depth' => 0,
    ],
  ];

    /**
   * @inheritDoc
   */
  public function __construct(bool $load_input = TRUE, string $input_delimiter = "\n") {
    $this->day = 7;
    parent::__construct($load_input, $input_delimiter);
  }

  /**
   * @inheritDoc
   */
  final public function processPart1(): void {
    $this->render($this->helper->printPart('one'));
    $this
      ->processInput()
      ->applySizes();

    $result = $this->getSizes(100000);
    $this->render("The sum of the directories sizes with a total of at most 100000 is <br> <strong>$result</strong>");

    $this->processPart2();
  }

  /**
   * @inheritDoc
   */
  final public function processPart2(): void {
    $this->render($this->helper->printPart('two'));
    $size_to_release = 30000000 - (70000000 - $this->directories_tree[0]['size']);

    $result = $this->getSizes($size_to_release, FALSE);
    $this->render("The total size of the smallest directory that, if deleted, would free up enough space is <br> <strong>$result</strong>");
  }

  /**
   * Convert the input into global arrays.
   * @return $this
   */
  private function processInput(): self {
    array_shift($this->input); // We remove first cd root command
    foreach ($this->input as $k => $cli_input) {
      if (str_starts_with($cli_input, '$ ls')) {
        $this->processTreeDiscovery($k);
      }
      if (str_starts_with($cli_input, '$ cd')) {
        $this->processDirectoryChange($cli_input);
      }
    }
    return $this;
  }

  /**
   * Create files and folders after an "$ ls".
   * @param int $current_cursor
   * @return void
   */
  private function processTreeDiscovery(int $current_cursor): void{
    $input_length = count($this->input);
    $slice_length = NULL;
    for ($i = $current_cursor + 1; $i < $input_length; $i++) {
      if (str_starts_with($this->input[$i], '$ ')) {
        $slice_length = $i - 1;
        break;
      }
      if ($i === $input_length - 1) {
        $slice_length = $i;
        break;
      }
    }
    $listing_output = array_slice($this->input, $current_cursor + 1, $slice_length - $current_cursor);
    foreach ($listing_output as $listing_element) {
      if (str_starts_with($listing_element, 'dir ')) {
        $this->directories_tree[] = [
          'parent' => $this->current_dir,
          'name' => explode('dir ', $listing_element)[1],
          'size' => 0,
          'depth' => $this->current_depth,
        ];
      }
      else {
        $this->files_tree[] = [
          'parent' => $this->current_dir,
          'name' => explode(' ', $listing_element)[1],
          'size' => explode(' ', $listing_element)[0],
          'depth' => $this->current_depth,
        ];
      }
    }
  }

  /**
   * Process the "$ cd" in order to update the current_dir and the current_depth.
   * @param string $cli_input
   * @return void
   */
  private function processDirectoryChange(string $cli_input): void {
    $dir = explode('$ cd ', $cli_input)[1];
    if ($dir === '..') {
      $this->current_depth--;
      $this->current_dir = $this->directories_tree[$this->current_dir]['parent'];
    } else {
      $this->current_depth++;
      foreach ($this->directories_tree as $k => $d) {
        if ($d['name'] === $dir && $d['parent'] === $this->current_dir) {
          $this->current_dir = $k;
        }
      }
    }
  }

  /**
   * Apply sizes to files and folders.
   * @return void
   */
  private function applySizes(): void {
    uasort($this->directories_tree, static fn($a, $b) => $b['depth'] <=> $a['depth']);
    foreach ($this->directories_tree as $d => $dir) {
      foreach ($this->files_tree as $file) {
        if ($file['parent'] === $d) {
          $this->directories_tree[$d]['size'] += $file['size'];
        }
      }
      if ($dir['depth'] > 0) {
        $this->directories_tree[$dir['parent']]['size'] += $this->directories_tree[$d]['size'];
      }
    }
    ksort($this->directories_tree);
  }

  /**
   * Retrieve sizes for puzzle answer.
   * @param int $max_size
   * @param bool $limit
   * @return int
   */
  private function getSizes(int $max_size, bool $limit = TRUE): int {
    $sizes = [];
    foreach ($this->directories_tree as $directory) {
      if ($limit && $directory['size'] < $max_size) {
        $sizes[] = $directory['size'];
      }
      if (!$limit && $directory['size'] >= $max_size){
        $sizes[] = $directory['size'];
      }
    }
    if ($limit) {
      return array_sum($sizes);
    }
    return min($sizes);
  }
}