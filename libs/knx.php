<?php

    class DPT1 extends DPT{
        public static function encode($value){
            $val = dechex( $value + hexdec("c280"));
			return hex2bin($val);
        }

        public static function decode($data){
			$val = bin2hex($data);
			$val = hexdec( $val) - hexdec("c280");
			return $val;
		}
    }

    abstract class DPT{
        abstract public static function encode($value);
        abstract public static function decode($data);
    }

?>