<?php

// Define autoloader.
$autoloader = function($full_class_name) {
  $path = str_replace('\\', DIRECTORY_SEPARATOR, __DIR__ . '/src/' . $full_class_name . '.php');

  if (is_file($path)) {
    include $path;
    return TRUE;
  } else {
    return FALSE;
  }
};

spl_autoload_register($autoloader);