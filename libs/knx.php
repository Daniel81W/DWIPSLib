<?php

    class DPT1 extends DPT{
        private string $dataID;
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

        public function __construct(int $maingroup, int $middlegroup, int $subgroup){
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
        public function encode(){
            
        }

        public function decode($data){
			
		}
    }

    class DPT3 extends DPT{
        public function encode(){
            
        }

        public function decode($data){
			
		}
    }

    class DPT4 extends DPT{
        public function encode(){
            
        }

        public function decode($data){
			
		}
    }

    class DPT5 extends DPT{
        private string $dataID;
        private int $value;
        private IPSModule $mod;

        public function getValueAsBin(){
            return $this->encode();
        }

        public function setValueFromBin($data){
            $this->decode($data);
        }

        public function getValueAsInt() : int{
            return $this->value;
        }

        public function setValueFromInt($value){
            $this->value = $value;
        }

        public function encode(){
            $val = "c280" . $this->getft12value($this->value/100*255);
			return hex2bin($val);
        }

        public function decode($data){
			$val = bin2hex($data);
            WFC_SendPopup(47530, "Test", $val);
            if(! is_null($this->mod)){
                $this->mod->SendDebug("KNX DPT5", $val, 0);
            }
			$val = (hexdec( $val) - hexdec("c28000"));// * 100 / 255;
			$this->value = $val;
		}

        public function setModule(IPSModule $module){
            $this->mod = $module;
        }

        public function __construct(int $maingroup, int $middlegroup, int $subgroup){
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
            /*if(json_encode($json) == false){                
                WFC_SendPopup (47530, "DEBUG", json_last_error());
            }
            WFC_SendPopup (47530, "DEBUG", json_encode($json));*/
            return json_encode($json);
        }
    }

    abstract class DPT{
        private int $maingroup;
        private int $middlegroup;
        private int $subgroup;

        abstract public function encode();
        abstract public function decode($data);

        public function getft12value(int $value){
            $val = $value;
            if($value >= hexdec("80")){
                if($value <= hexdec("BF")){
                    $val = $value - hexdec("80") + hexdec("c280");
                }elseif($val <= hexdec("FF")){
                    $val = $value - hexdec("C0") + hexdec("c380");
                }
            }
            return dechex($val);
        }
    }



?>