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
            WFC_SendPopup(47530, "KNX", $val);
			$this->value = boolval(hexdec( $this->correctDataForUTFCodes($val)));
            WFC_SendPopup(47530, "KNX", $this->$value);
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
            $this->value = $value / 100 * 255;
        }

        public function encode(){
            $evnull = "";
            if($this->value<16){
                $evnull = "0";
            }
            $val = "c280" . $evnull . $this->getft12value($this->value);
			return hex2bin($val);
        }

        public function decode($data){
			$val = bin2hex($data);
            WFC_SendPopup(47530, "KNX", $val);
			$val = hexdec( substr($val,4));// * 100 / 255;
            WFC_SendPopup(47530, "KNX", $val);
			$this->value = $val * 100 / 255;
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

        protected function correctDataForUTFCodes(string $frame) : string
		{
			$data = $frame;
			$next = strpos($data, "c2");
			while($next !== false){
				$torep = substr($data, $next, 4);
				$data = str_replace($torep, dechex(hexdec($torep) - hexdec("C200")), $data);
				$next = strpos($data, "c2");
			}
			$next = strpos($data, "c3");
			while($next !== false){
				$torep = substr($data, $next, 4);
				$data = str_replace($torep, dechex(hexdec($torep) - hexdec("C2C0")), $data);
				$next = strpos($data, "c3");
			}
			return $data;
		}
    }



?>