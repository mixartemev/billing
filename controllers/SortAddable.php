<?php

namespace app\controllers;

trait SortAddable
{
    /**
     * Func for adding sort attributes without override
     *
     * @param $sourceAttributes array link
     * @param $newAttributes array
     */
    static function addSort(&$sourceAttributes, $newAttributes){
        foreach ($newAttributes as $newAttribute){
            $sourceAttributes[$newAttribute] = ['asc' => [$newAttribute => SORT_ASC], 'desc' => [$newAttribute => SORT_DESC]];
        }
    }
}