<?php

use Alura\Threads\Student\InMemoryStudentRepository;
use parallel\Runtime;

require_once 'vendor/autoload.php';

$repository = new InMemoryStudentRepository();
$studentList = $repository->all();

$runtimes = [];
foreach ($studentList as $i => $student) {
  echo 'Resizing ' . $student->fullName() . ' profile picture' . PHP_EOL;

  $runtimes[$i] = new Runtime();
  $runtimes[$i]->run(function (string $imagePath) {
    [$width, $height] = getimagesize($imagePath);

    $ratio = $height / $width;

    $newWidth = 200;
    $newHeight = 200 * $ratio;

    $sourceImage = imagecreatefromjpeg($imagePath);
    $destinationImage = imagecreatetruecolor($newWidth, $newHeight);
    imagecopyresampled($destinationImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

    imagejpeg($destinationImage, __DIR__ . '/storage/resized/' . basename($imagePath));
  }, [$student->profilePicturePath()]);

  echo 'Finishing resizing ' . $student->fullName() . ' profile picture' . PHP_EOL;
}
