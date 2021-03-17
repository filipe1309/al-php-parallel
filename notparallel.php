<?php

$start = microtime(true);

function tarefa2()
{
  // var_dump('Tarefa 2', debug_backtrace());
  echo 'Executando tarefa demorada 2' . PHP_EOL;
  sleep(2);
  echo 'Finalizando tarefa demorada 2' . PHP_EOL;
}

// var_dump('Tarefa 1', debug_backtrace());
echo 'Executando tarefa demorada 1' . PHP_EOL;
sleep(3);
echo 'Finalizando tarefa demorada 1' . PHP_EOL;

tarefa2();

$time_elapsed_secs = microtime(true) - $start;
echo $time_elapsed_secs . PHP_EOL;
