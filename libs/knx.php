<?php

    class DPT1 extends DPT{
        private string $dataID;
        private int $maingroup;
        private int $middlegroup;
        private int $subgroup;
        private bool $value;

        public function getValueAsBin(){
            return $this->encode();
        }

        public function setValueFromBin($data){
            $this->decode($data);
        }

        public function getValueAsInt(){
            return intval($this->value);
        }

        public function setValueFromInt($value){
            $this->value = boolval($value);
        }

        public function encode(){
            $val = dechex( intval($this->value)  + hexdec("c280"));
			return hex2bin($val);
        }

        public function decode($data){
			$val = bin2hex($data);
			$this->value = boolval(hexdec( $val) - hexdec("c280"));
		}

        public function DPT1(int $maingroup, int $middlegroup, int $subgroup){
            $this->dataID = "{42DFD4E4-5831-4A27-91B9-6FF1B2960260}";
            $this->maingroup = $maingroup;
            $this->middlegroup = $middlegroup;
            $this->subgroup = $subgroup;
            $this->value = false;
        }

        public function getJSONString(){
            $json = [ 
                "DataID" => $this->dataID,
                "GroupAddress1" => $this->maingroup,
                "GroupAddress2" => $this->middlegroup,
                "GroupAddress3" => $this->subgroup,
                "Data" => $this->encode()
            ];
            return json_encode($json);
        }
    }

    class DPT2 extends DPT{
        public static function encode($value){
            
        }

        public static function decode($data){
			
		}
    }

    class DPT3 extends DPT{
        public static function encode($value){
            
        }

        public static function decode($data){
			
		}
    }

    class DPT4 extends DPT{
        public static function encode($value){
            
        }

        public static function decode($data){
			
		}
    }

    class DPT5 extends DPT{
        public static function encode($value){
            $val = dechex( $value /100*255 + hexdec("c28000"));
			return hex2bin($val);
        }

        public static function decode($data){
			$val = bin2hex($data);
			$val = (hexdec( $val) - hexdec("c28000")) * 100 / 255;
			return $val;
		}
    }

    abstract class DPT{
        abstract public function encode($value);
        abstract public function decode($data);
    }

?>