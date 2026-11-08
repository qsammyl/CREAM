<?php

namespace App\Support;

use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

/**
 * Server-side QR-code generator.
 *
 * Renders to SVG, which needs no imagick/gd extension and stays crisp at any
 * print size. Used for the printable "leave us feedback" card.
 */
class QrCode
{
    /**
     * Return an SVG document (as a string) encoding the given text/URL.
     *
     * @param  int  $size  Edge length of the rendered SVG, in pixels.
     */
    public function svg(string $data, int $size = 320): string
    {
        $renderer = new ImageRenderer(
            new RendererStyle($size, 1),
            new SvgImageBackEnd,
        );

        return (new Writer($renderer))->writeString($data);
    }

    /**
     * SVG with the XML prolog stripped, safe to inline inside an HTML page.
     */
    public function inlineSvg(string $data, int $size = 320): string
    {
        return preg_replace('/^<\?xml.*?\?>\s*/s', '', $this->svg($data, $size));
    }
}
