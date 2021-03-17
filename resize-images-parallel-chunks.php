<?php

use parallel\Runtime;
use Alura\Threads\Student\InMemoryStudentRepository;

require_once 'vendor/autoload.php';

$start = microtime(true);

$repository = new InMemoryStudentRepository();
$studentList = $repository->all();

const CORES_NUMBER = 4;
$studentChunks = array_chunk($studentList, ceil(count($studentList) / CORES_NUMBER));

$runtimes = [];
foreach ($studentChunks as $i => $studentChunk) {

    $runtimes[$i] = new Runtime('vendor/autoload.php');
    $runtimes[$i]->run(function (array $students) {

        foreach ($students as $student) {
            echo 'Resizing ' . $student->fullName() . ' profile picture' . PHP_EOL;

            $profilePicturePath = $student->profilePicturePath();
            [$width, $height] = getimagesize($profilePicturePath);

            $ratio = $height / $width;

            $newWidth = 200;
            $newHeight = 200 * $ratio;

            $sourceImage = imagecreatefromjpeg($profilePicturePath);
            $destinationImage = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresampled($destinationImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

            imagejpeg($destinationImage, __DIR__ . '/storage/resized/' . basename($profilePicturePath));

            echo 'Finishing resizing ' . $student->fullName() . ' profile picture' . PHP_EOL;
        }
    }, [$studentChunk]);
}

foreach ($runtimes as $runtime) {
    $runtime->close();
}

echo 'Finishing main thread' . PHP_EOL;

$time_elapsed_secs = microtime(true) - $start;
echo $time_elapsed_secs . PHP_EOL;
