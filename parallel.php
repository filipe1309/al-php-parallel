<?php

use parallel\Runtime;

$start = microtime(true);

$runtime = new Runtime();

// Create another thread
$runtime->run(function () {
  // var_dump('Tarefa 2', debug_backtrace());
  echo 'Executando tarefa demorada 2' . PHP_EOL;
  sleep(8);
  echo 'Finalizando tarefa demorada 2' . PHP_EOL;
});

// Create another thread
$runtime->run(function () {
  // var_dump('Tarefa 3', debug_backtrace());
  echo 'Executando tarefa demorada 3' . PHP_EOL;
  sleep(10);
  echo 'Finalizando tarefa demorada 3' . PHP_EOL;
});

// var_dump('Tarefa 1', debug_backtrace());
echo 'Executando tarefa demorada 1' . PHP_EOL;
sleep(3);
echo 'Finalizando tarefa demorada 1' . PHP_EOL;

$time_elapsed_secs = microtime(true) - $start;
echo $time_elapsed_secs . PHP_EOL;
