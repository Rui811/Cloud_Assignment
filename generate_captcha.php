<?php
session_start();

// Generate 5-character CAPTCHA text
$captchaText = substr(str_shuffle("ABCDEFGHJKLMNPQRSTUVWXYZ23456789"), 0, 5);
$_SESSION['captcha_text'] = $captchaText;

// Create image
$width = 130;
$height = 50;
$image = imagecreatetruecolor($width, $height);

// Define colors
$bgColor = imagecolorallocate($image, 255, 255, 255); // white background
$textColor = imagecolorallocate($image, 255, 105, 180); // pink text
$lineColor = imagecolorallocate($image, 255, 192, 203); // light pink lines

// Fill background
imagefilledrectangle($image, 0, 0, $width, $height, $bgColor);

// Add some noise lines
for ($i = 0; $i < 3; $i++) {
    imageline($image, 0, rand(0, $height), $width, rand(0, $height), $lineColor);
}

// Add the CAPTCHA text using built-in font (no font file needed)
imagestring($image, 5, 25, 15, $captchaText, $textColor);

// Set content type header
header("Content-type: image/png");

// Output the image
imagepng($image);
imagedestroy($image);
