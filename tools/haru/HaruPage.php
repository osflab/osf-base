<?php 

class HaruPage
{
    const GMODE_PAGE_DESCRIPTION = 1;
    const GMODE_TEXT_OBJECT = 4;
    const GMODE_PATH_OBJECT = 2;
    const GMODE_CLIPPING_PATH = 8;
    const GMODE_SHADING = 16;
    const GMODE_INLINE_IMAGE = 32;
    const GMODE_EXTERNAL_OBJECT = 64;
    const BUTT_END = 0;
    const ROUND_END = 1;
    const PROJECTING_SCUARE_END = 2;
    const MITER_JOIN = 0;
    const ROUND_JOIN = 1;
    const BEVEL_JOIN = 2;
    const FILL = 0;
    const STROKE = 1;
    const FILL_THEN_STROKE = 2;
    const INVISIBLE = 3;
    const FILL_CLIPPING = 4;
    const STROKE_CLIPPING = 5;
    const FILL_STROKE_CLIPPING = 6;
    const CLIPPING = 7;
    const TALIGN_LEFT = 0;
    const TALIGN_RIGHT = 1;
    const TALIGN_CENTER = 2;
    const TALIGN_JUSTIFY = 3;
    const SIZE_LETTER = 0;
    const SIZE_LEGAL = 1;
    const SIZE_A3 = 2;
    const SIZE_A4 = 3;
    const SIZE_A5 = 4;
    const SIZE_B4 = 5;
    const SIZE_B5 = 6;
    const SIZE_EXECUTIVE = 7;
    const SIZE_US4x6 = 8;
    const SIZE_US4x8 = 9;
    const SIZE_US5x7 = 10;
    const SIZE_COMM10 = 11;
    const PORTRAIT = 0;
    const LANDSCAPE = 1;
    const TS_WIPE_LIGHT = 0;
    const TS_WIPE_UP = 1;
    const TS_WIPE_LEFT = 2;
    const TS_WIPE_DOWN = 3;
    const TS_BARN_DOORS_HORIZONTAL_OUT = 4;
    const TS_BARN_DOORS_HORIZONTAL_IN = 5;
    const TS_BARN_DOORS_VERTICAL_OUT = 6;
    const TS_BARN_DOORS_VERTICAL_IN = 7;
    const TS_BOX_OUT = 8;
    const TS_BOX_IN = 9;
    const TS_BLINDS_HORIZONTAL = 10;
    const TS_BLINDS_VERTICAL = 11;
    const TS_DISSOLVE = 12;
    const TS_GLITTER_RIGHT = 13;
    const TS_GLITTER_DOWN = 14;
    const TS_GLITTER_TOP_LEFT_TO_BOTTOM_RIGHT = 15;
    const TS_REPLACE = 16;
    const NUM_STYLE_DECIMAL = 0;
    const NUM_STYLE_UPPER_ROMAN = 1;
    const NUM_STYLE_LOWER_ROMAN = 2;
    const NUM_STYLE_UPPER_LETTERS = 3;
    const NUM_STYLE_LOWER_LETTERS = 4;

    public function drawImage($image, $x, $y, $width, $height) {}
    public function setLineWidth($width) {}
    public function setLineCap($cap) {}
    public function setLineJoin($join) {}
    public function setMiterLimit($limit) {}
    public function setFlatness($flatness) {}
    public function setDash($pattern, $phase) {}
    public function Concat($a, $b, $c, $d, $x, $y) {}
    public function getTransMatrix() {}
    public function setTextMatrix($a, $b, $c, $d, $x, $y) {}
    public function getTextMatrix() {}
    public function moveTo($x, $y) {}
    public function stroke($close_path) {}
    public function fill() {}
    public function eofill() {}
    public function lineTo($x, $y) {}
    public function curveTo($x1, $y1, $x2, $y2, $x3, $y3) {}
    public function curveTo2($x2, $y2, $x3, $y3) {}
    public function curveTo3($x1, $y1, $x3, $y3) {}
    public function rectangle($x, $y, $width, $height) {}
    public function arc($x, $y, $ray, $ang1, $ang2) {}
    public function circle($x, $y, $ray) {}
    public function showText($text) {}
    public function showTextNextLine($text) {}
    public function textOut($x, $y, $text) {}
    public function beginText() {}
    public function endText() {}
    public function setFontAndSize($font, $size) {}
    public function setCharSpace($char_space) {}
    public function setWordSpace($word_space) {}
    public function setHorizontalScaling($scaling) {}
    public function setTextLeading($text_leading) {}
    public function setTextRenderingMode($mode) {}
    public function setTextRise($rise) {}
    public function moveTextPos($x, $y, $set_leading) {}
    public function fillStroke($close_path) {}
    public function eoFillStroke($close_path) {}
    public function closePath() {}
    public function endPath() {}
    public function ellipse($x, $y, $xray, $yray) {}
    public function textRect($left, $top, $right, $bottom, $text, $align) {}
    public function moveToNextLine() {}
    public function setGrayFill($value) {}
    public function setGrayStroke($value) {}
    public function setRGBFill($r, $g, $b) {}
    public function setRGBStroke($r, $g, $b) {}
    public function setCMYKFill($c, $m, $y, $k) {}
    public function setCMYKStroke($c, $m, $y, $k) {}
    public function setWidth($width) {}
    public function setHeight($height) {}
    public function setSize($size, $direction) {}
    public function setRotate($angle) {}
    public function getWidth() {}
    public function getHeight() {}
    public function createDestination() {}
    public function createTextAnnotation($rectangle, $text, $encoder) {}
    public function createLinkAnnotation($rectangle, $destination) {}
    public function createURLAnnotation($rectangle, $url) {}
    public function getTextWidth($text) {}
    public function MeasureText($text, $width, $wordwrap) {}
    public function getGMode() {}
    public function getCurrentPos() {}
    public function getCurrentTextPos() {}
    public function getCurrentFont() {}
    public function getCurrentFontSize() {}
    public function getLineWidth() {}
    public function getLineCap() {}
    public function getLineJoin() {}
    public function getMiterLimit() {}
    public function getDash() {}
    public function getFlatness() {}
    public function getCharSpace() {}
    public function getWordSpace() {}
    public function getHorizontalScaling() {}
    public function getTextLeading() {}
    public function getTextRenderingMode() {}
    public function getTextRise() {}
    public function getRGBFill() {}
    public function getRGBStroke() {}
    public function getCMYKFill() {}
    public function getCMYKStroke() {}
    public function getGrayFill() {}
    public function getGrayStroke() {}
    public function getFillingColorSpace() {}
    public function getStrokingColorSpace() {}
    public function setSlideShow($type, $disp_time, $trans_time) {}
    public function setZoom($zoom) {}
}
