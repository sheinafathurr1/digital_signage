<?php

namespace Database\Seeders;

use App\Models\Content;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $slides = [
            ['title' => 'Selamat Datang', 'text' => 'Selamat Datang', 'bg' => [37, 99, 235]],
            ['title' => 'Promo Kantin', 'text' => "Promo Kantin\nDiskon 20%", 'bg' => [22, 163, 74]],
            ['title' => 'Jam Layanan', 'text' => "Jam Layanan\n08.00 - 17.00 WIB", 'bg' => [217, 119, 6]],
        ];

        foreach ($slides as $index => $slide) {
            Content::create([
                'title' => $slide['title'],
                'type' => 'image',
                'file_path' => $this->generatePlaceholderImage($slide['text'], $slide['bg'], $index),
                'duration' => 8,
                'order' => $index,
                'is_active' => true,
                'is_priority' => false,
            ]);
        }

        Content::create([
            'title' => 'Pengumuman Pemeliharaan Sistem',
            'type' => 'text',
            'text_body' => "Pemeliharaan sistem akan dilakukan pada hari Sabtu,\npukul 22.00 - 24.00 WIB.\nMohon maaf atas ketidaknyamanannya.",
            'duration' => 10,
            'order' => 3,
            'is_active' => true,
            'is_priority' => false,
        ]);

        Content::create([
            'title' => 'Info Darurat Tangga',
            'type' => 'text',
            'text_body' => "PERHATIAN\nMohon gunakan tangga darurat di sisi timur gedung\nselama perbaikan lift berlangsung.",
            'duration' => 8,
            'order' => 4,
            'is_active' => true,
            'is_priority' => true,
        ]);
    }

    /**
     * @param  array{0:int,1:int,2:int}  $bg
     */
    private function generatePlaceholderImage(string $text, array $bg, int $index): string
    {
        $width = 1920;
        $height = 1080;

        $image = imagecreatetruecolor($width, $height);
        $bgColor = imagecolorallocate($image, $bg[0], $bg[1], $bg[2]);
        imagefill($image, 0, 0, $bgColor);

        $white = imagecolorallocate($image, 255, 255, 255);
        $fontPath = '/mnt/skills/examples/canvas-design/canvas-fonts/BricolageGrotesque-Bold.ttf';
        $lines = explode("\n", $text);
        $fontSize = 90;
        $lineHeight = 120;
        $startY = ($height - (count($lines) * $lineHeight)) / 2 + $fontSize;

        foreach ($lines as $i => $line) {
            if (is_file($fontPath)) {
                $box = imagettfbbox($fontSize, 0, $fontPath, $line);
                $textWidth = abs($box[4] - $box[0]);
                $x = (int) (($width - $textWidth) / 2);
                $y = (int) ($startY + $i * $lineHeight);
                imagettftext($image, $fontSize, 0, $x, $y, $white, $fontPath, $line);
            } else {
                $x = (int) (($width - strlen($line) * imagefontwidth(5)) / 2);
                $y = (int) ($height / 2) + $i * 20;
                imagestring($image, 5, $x, $y, $line, $white);
            }
        }

        $path = "contents/seed-placeholder-{$index}.png";
        ob_start();
        imagepng($image);
        $contents = ob_get_clean();
        imagedestroy($image);

        Storage::disk('public')->put($path, $contents);

        return $path;
    }
}
