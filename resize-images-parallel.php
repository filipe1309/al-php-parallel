<?php

use parallel\Runtime;
use Alura\Threads\Student\Student;
use Alura\Threads\Student\InMemoryStudentRepository;

require_once 'vendor/autoload.php';

$repository = new InMemoryStudentRepository();
$studentList = $repository->all();

$runtimes = [];
foreach ($studentList as $i => $student) {
  
  $runtimes[$i] = new Runtime('vendor/autoload.php');
  $runtimes[$i]->run(function (Student $student) {
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
  }, [$student]);
}

foreach ($runtimes as $runtime) {
  $runtime->close();
}

echo 'Finishing main thread' . PHP_EOL;