<?php

namespace App\Service;

use Symfony\Component\HttpKernel\KernelInterface;

class AvatarGenerator
{
    private $publicDir;

    public function __construct(KernelInterface $kernel)
    {
        $this->publicDir = $kernel->getProjectDir().'/public';
    }

    public function generateAvatar(string $initials): string
    {
        $size = 200;
        $image = imagecreatetruecolor($size, $size);
        
        $bgColor = imagecolorallocate($image, rand(0, 200), rand(0, 200), rand(0, 200));
        imagefill($image, 0, 0, $bgColor);

        $textColor = imagecolorallocate($image, 255, 255, 255);
        
        $fontPath = $this->publicDir.'/fonts/Roboto-Regular.ttf';
        
        $fontSize = 80;
        $angle = 0;
        $bbox = imagettfbbox($fontSize, $angle, $fontPath, $initials);
        $textWidth = $bbox[2] - $bbox[0];
        $x = ($size - $textWidth) / 2;
        $y = ($size + ($bbox[1] - $bbox[7])) / 2;

        imagettftext($image, $fontSize, $angle, $x, $y, $textColor, $fontPath, $initials);

        $avatarDir = $this->publicDir.'/avatars';
        if (!file_exists($avatarDir)) {
            mkdir($avatarDir, 0755, true);
        }

        $filename = uniqid().'.png';
        $filePath = $avatarDir.'/'.$filename;
        imagepng($image, $filePath);
        imagedestroy($image);

        return '/avatars/'.$filename;
    }
}