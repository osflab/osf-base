<?php 

class HaruFont
{
    public function getFontName() {}
    public function getEncodingName() {}
    public function getUnicodeWidth($character) {}
    public function getAscent() {}
    public function getDescent() {}
    public function getXHeight() {}
    public function getCapHeight() {}
    public function getTextWidth($text) {}
    public function MeasureText($text, $width, $font_size, $char_space, $word_space, $word_wrap) {}
}
