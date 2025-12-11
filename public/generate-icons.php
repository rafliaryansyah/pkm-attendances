<?php
/**
 * PWA Icon Generator Script
 * Run this script once to generate PNG icons from SVG
 * Usage: php generate-icons.php
 */

$sizes = [192, 512];
$svgPath = __DIR__ . '/images/icon.svg';
$outputDir = __DIR__ . '/images/';

// Simple SVG to PNG conversion using GD (if available)
// For production, use tools like Inkscape, ImageMagick, or online converters

echo "PWA Icon Generator\n";
echo "==================\n\n";

// Check if GD is available
if (!extension_loaded('gd')) {
    echo "GD extension not available.\n";
    echo "Please manually create PNG icons or use online converter.\n\n";
    echo "Required icons:\n";
    foreach ($sizes as $size) {
        echo "- icon-{$size}.png ({$size}x{$size}px)\n";
    }
    echo "\nYou can use these online tools:\n";
    echo "1. https://realfavicongenerator.net/\n";
    echo "2. https://www.pwabuilder.com/imageGenerator\n";
    echo "3. https://maskable.app/editor\n";
    exit(1);
}

// Create simple placeholder icons using GD
foreach ($sizes as $size) {
    $image = imagecreatetruecolor($size, $size);
    
    // Enable alpha
    imagesavealpha($image, true);
    
    // Background color (indigo #4F46E5)
    $bg = imagecolorallocate($image, 79, 70, 229);
    imagefill($image, 0, 0, $bg);
    
    // White color for icon
    $white = imagecolorallocate($image, 255, 255, 255);
    
    // Draw a simple clock icon
    $centerX = $size / 2;
    $centerY = $size / 2 - ($size * 0.05);
    $radius = $size * 0.3;
    
    // Clock circle
    imagesetthickness($image, max(2, $size / 50));
    imageellipse($image, $centerX, $centerY, $radius * 2, $radius * 2, $white);
    
    // Clock hands
    // Hour hand
    imageline($image, $centerX, $centerY, $centerX, $centerY - $radius * 0.5, $white);
    // Minute hand
    imageline($image, $centerX, $centerY, $centerX + $radius * 0.35, $centerY + $radius * 0.15, $white);
    
    // Center dot
    imagefilledellipse($image, $centerX, $centerY, $size * 0.06, $size * 0.06, $white);
    
    // Checkmark badge
    $badgeX = $centerX + $radius * 0.7;
    $badgeY = $centerY - $radius * 0.7;
    $badgeR = $size * 0.12;
    
    // Green badge background
    $green = imagecolorallocate($image, 16, 185, 129);
    imagefilledellipse($image, $badgeX, $badgeY, $badgeR * 2, $badgeR * 2, $green);
    
    // Simple text at bottom
    $fontSize = max(1, $size / 100);
    
    $outputPath = $outputDir . "icon-{$size}.png";
    imagepng($image, $outputPath);
    imagedestroy($image);
    
    echo "Created: icon-{$size}.png\n";
}

echo "\nIcons generated successfully!\n";
echo "Note: For better quality icons, consider using proper SVG to PNG conversion.\n";
