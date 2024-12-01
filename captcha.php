
// Generate a new CAPTCHA image
function generateCaptchaImage()
{
    $captcha_code = rand(1000, 9999);
    $_SESSION['captcha'] = $captcha_code;

    // Create an image
    $image = imagecreatetruecolor(120, 40);

    // Colors
    $bg_color = imagecolorallocate($image, 255, 255, 255); // White background
    $text_color = imagecolorallocate($image, 0, 0, 0); // Black text
    $noise_color = imagecolorallocate($image, 100, 100, 100); // Gray noise

    // Fill background
    imagefilledrectangle($image, 0, 0, 120, 40, $bg_color);

    // Add noise
    for ($i = 0; $i < 100; $i++) {
        imagesetpixel($image, rand(0, 120), rand(0, 40), $noise_color);
    }

    // Add text
    $font = __DIR__ . '/fonts/apercumovistarbold.ttf';
    imagettftext($image, 20, rand(-10, 10), 10, 30, $text_color, $font, $captcha_code);

    // Save image to file
    ob_start();
    imagepng($image);
    $data = ob_get_clean();
    imagedestroy($image);

    return 'data:image/png;base64,' . base64_encode($data);
}