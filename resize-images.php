<?php

use Alura\Threads\Student\InMemoryStudentRepository;

require_once 'vendor/autoload.php';

$start = microtime(true);

$repository = new InMemoryStudentRepository();
$studentList = $repository->all();

foreach ($studentList as $student) {
  echo 'Resizing ' . $student->fullName() . ' profile picture' . PHP_EOL;

  resizeTo200PixelsWidth($student->profilePicturePath());

  echo 'Finishing resizing ' . $student->fullName() . ' profile picture' . PHP_EOL;
}

function resizeTo200PixelsWidth($imagePath)
{
  [$width, $height] = getimagesize($imagePath);

  $ratio = $height / $width;

  $newWidth = 200;
  $newHeight = 200 * $ratio;

  $sourceImage = imagecreatefromjpeg($imagePath);
  $destinationImage = imagecreatetruecolor($newWidth, $newHeight);
  imagecopyresampled($destinationImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

  imagejpeg($destinationImage, __DIR__ . '/storage/resized/' . basename($imagePath));
}

$time_elapsed_secs = microtime(true) - $start;
echo $time_elapsed_secs . PHP_EOL;
