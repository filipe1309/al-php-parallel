<?php

use parallel\Channel;
use parallel\Runtime;
use Alura\Threads\Activity\Activity;
use Alura\Threads\Student\Student;
use Alura\Threads\Student\InMemoryStudentRepository;

require_once 'vendor/autoload.php';

$start = microtime(true);

$repository = new InMemoryStudentRepository();
$studentList = $repository->all();

$totalPoints = 0;
$runtimes = [];
$futures = [];
$channel = Channel::make('points');
foreach ($studentList as $i => $student) {
    $activities = $repository->activitiesInADay($student);

    $runtimes[$i] = new Runtime('vendor/autoload.php');

    $futures[$i] = $runtimes[$i]->run(function (array $activities, Student $student, Channel $channel) {
        $points = array_reduce(
            $activities,
            fn (int $total, Activity $activity) => $total + $activity->points(),
            0
        );

        $channel->send($points);

        printf('%s made %d points today%s', $student->fullName(), $points, PHP_EOL);

        return $points;
    }, [$activities, $student, $channel]);
}

$totalPointsWithChannel = 0;
for ($i = 0; $i < count($studentList); $i++) {
    $totalPointsWithChannel += $channel->recv();
}

$channel->close();

// Syncronize
foreach ($futures as $future) {
    $totalPoints += $future->value();
}

printf('We had a total of %d points today%s', $totalPoints, PHP_EOL);
printf('We had a total of %d points (channel) today%s', $totalPointsWithChannel, PHP_EOL);

$time_elapsed_secs = microtime(true) - $start;
echo $time_elapsed_secs . PHP_EOL;
