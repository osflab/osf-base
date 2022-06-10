<?php 

class HaruAnnotation
{
    const NO_HIGHLIGHT = 0;
    const INVERT_BOX = 1;
    const INVERT_BORDER = 2;
    const DOWN_APPEARANCE = 3;
    const ICON_COMMENT = 0;
    const ICON_KEY = 1;
    const ICON_NOTE = 2;
    const ICON_HELP = 3;
    const ICON_NEW_PARAGRAPH = 4;
    const ICON_PARAGRAPH = 5;
    const ICON_INSERT = 6;

    public function setHighlightMode($mode) {}
    public function setBorderStyle($width, $dash_on, $dash_off) {}
    public function setIcon($icon) {}
    public function setOpened($opened) {}
}
