<?php
namespace app\components;

Class Helpers
{
      public static function columnLetter($c){
        $c = intval($c);
        if ($c <= 0) return '';
        while($c != 0){
        $p = ($c - 1) % 26;
        $c = intval(($c - $p) / 26);
        $letter = chr(65 + $p) . $letter;
        }
        return $letter;
      }
}
