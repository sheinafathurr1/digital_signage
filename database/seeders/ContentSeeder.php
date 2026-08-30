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
        $lines = explode("\n", $text);
        $fontPath = $this->findTrueTypeFont();

        if ($fontPath !== null) {
            $image = imagecreatetruecolor($width, $height);
            imagefill($image, 0, 0, imagecolorallocate($image, $bg[0], $bg[1], $bg[2]));
            $white = imagecolorallocate($image, 255, 255, 255);

            $fontSize = 90;
            $lineHeight = 120;
            $startY = ($height - (count($lines) * $lineHeight)) / 2 + $fontSize;

            foreach ($lines as $i => $line) {
                $box = imagettfbbox($fontSize, 0, $fontPath, $line);
                $x = (int) (($width - abs($box[4] - $box[0])) / 2);
                imagettftext($image, $fontSize, 0, $x, (int) ($startY + $i * $lineHeight), $white, $fontPath, $line);
            }
        } else {
            // No TrueType font on this machine — common on shared hosting. Draw
            // with PHP's built-in bitmap font on a small canvas, then scale up so
            // the placeholder is still readable instead of microscopic.
            $scale = 4;
            $small = imagecreatetruecolor((int) ($width / $scale), (int) ($height / $scale));
            imagefill($small, 0, 0, imagecolorallocate($small, $bg[0], $bg[1], $bg[2]));
            $white = imagecolorallocate($small, 255, 255, 255);

            $lineHeight = 22;
            $startY = (int) ((imagesy($small) - (count($lines) * $lineHeight)) / 2);

            foreach ($lines as $i => $line) {
                $x = (int) ((imagesx($small) - strlen($line) * imagefontwidth(5)) / 2);
                imagestring($small, 5, $x, $startY + $i * $lineHeight, $line, $white);
            }

            $scaled = imagescale($small, $width, $height);
            imagedestroy($small);

            $image = $scaled !== false ? $scaled : $small;
        }

        $path = "contents/seed-placeholder-{$index}.png";
        ob_start();
        imagepng($image);
        $contents = ob_get_clean();
        imagedestroy($image);

        Storage::disk('public')->put($path, $contents);

        return $path;
    }

    /**
     * Locate a bold TrueType font, checking the usual Linux locations before
     * the one that only exists in the original development sandbox.
     */
    private function findTrueTypeFont(): ?string
    {
        $candidates = [
            '/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf',
            '/usr/share/fonts/dejavu/DejaVuSans-Bold.ttf',
            '/usr/share/fonts/truetype/liberation/LiberationSans-Bold.ttf',
            '/usr/share/fonts/liberation-sans/LiberationSans-Bold.ttf',
            '/usr/share/fonts/truetype/freefont/FreeSansBold.ttf',
            '/mnt/skills/examples/canvas-design/canvas-fonts/BricolageGrotesque-Bold.ttf',
        ];

        foreach ($candidates as $candidate) {
            if (is_file($candidate)) {
                return $candidate;
            }
        }

        return null;
    }
}
