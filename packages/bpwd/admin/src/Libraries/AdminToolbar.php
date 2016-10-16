<?php

class AdminToolbarHelper{

    private static $toolbar_slots = array();

    public static function addSlotForToolBar($slot)
    {
        array_push(static::$toolbar_slots, $slot);
    }

    public static function getSlotsForToolBar()
    {
        return static::$toolbar_slots;
    }
}