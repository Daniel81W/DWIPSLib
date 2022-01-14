<?php

	include_once("/var/lib/symcon/modules/DWIPSLib/libs/knx.php");
	
	class DWIPSKNXGateway extends IPSModule {

		private $parentID = "{6DC3D946-0D31-450F-A8C6-C42DB8D7D4F1}";

		public function Create()
		{
			//Never delete this line!
			parent::Create();

			//Connect to EIBGateway
			$this->ConnectParent($this->parentID);
		

		}

		public function Destroy()
		{
			//Never delete this line!
			parent::Destroy();
		}

		public function ApplyChanges()
		{
			//Never delete this line!
			parent::ApplyChanges();

		}
	
		public function ReceiveData($JSONString) {
			$this->ReceiveDataFT12($JSONString);
		}

		public function ReceiveDataFT12($JSONString) {
			$data = json_decode($JSONString, true);

			$currentdata = $this->GetBuffer("KNXData") . bin2hex($data["Buffer"]);

			$this->SendDebug("SerialPort","1. New Frame", 0);
			
			$next = strpos($currentdata, "c2");
			while($next !== false){
				$torep = substr($currentdata, $next, 4);
				$currentdata = str_replace($torep, dechex(hexdec($torep) - hexdec("C200")), $currentdata);
				$next = strpos($currentdata, "c2");
			}
			$next = strpos($currentdata, "c3");
			while($next !== false){
				$torep = substr($currentdata, $next, 4);
				$currentdata = str_replace($torep, dechex(hexdec($torep) - hexdec("C2C0")), $currentdata);
				$next = strpos($currentdata, "c3");
			}

			$this->SendDebug("SerialPort","2. - " . $currentdata, 0);

			//Buffer beginnt mit E5 -> ACK
			if(strpos($currentdata, "e5") == 0){
				$currentdata = substr($currentdata,2);
			}
			$this->SendDebug("SerialPort","A", 0);
			//Reset Req
			if(strpos($currentdata, "10404016") == 0){
				$currentdata = substr($currentdata,8);
			}
			$this->SendDebug("SerialPort","B", 0);
			// Reset Ind
			if(strpos($currentdata, "10C0C016") == 0){
				$currentdata = substr($currentdata,8);
			}
			$this->SendDebug("SerialPort","C", 0);
			//Buffer beginnt mit 68****68
			if(strpos($currentdata, "68") == 0){
				$this->SendDebug("SerialPort","D", 0);
				if(strpos(substr($currentdata, 6, 2), "68") == 0){
					$this->SendDebug("SerialPort","E", 0);
					if(strcmp(substr($currentdata, 2, 2), substr($currentdata, 4, 2)) == 0){	
						$this->SendDebug("SerialPort","F", 0);
				$framelen = hexdec(substr($currentdata, 2, 2)) * 2 + 12;
				$this->SendDebug("SerialPort","3. Framelen: " . $framelen, 0);
				if(strlen($currentdata) >= $framelen){
					$frame = substr($currentdata, 0, $framelen);
					if($this->proofChecksum($frame)){
						$framedata = substr($frame, 10, $framelen - 14);
						$this->SendDebug("SerialPort","4. Ganz: " . $framedata, 0);
					}
					$currentdata = substr($currentdata, $framelen);
					$this->SendDebug("SerialPort","5. Ganz: " . $currentdata, 0);
				}
			
			}}}
			// Buffer beginnt nicht mit 68, bedeutet es ist nicht der Anfang eines FT1.2 Frames. Es muss zuerst er nächste Anfang gefunden werden und dann alles davor gelöscht.
			else{ 
				//Wenn nicht der Anfang des Frames dann muss es 1668 als Übergang zwischen den Frames geben. Position davon finden.
				$begin = strpos($currentdata,"1668");
				// Falls 1668 gefunden wurde
				if($begin !== false){
					// Index plus 2, also auf 68
					$begin += 2;
					// ob die Stellen 7 + 8 auch 68 sind (Muss im FT1.2 Frame)
					if(strcmp(substr($currentdata, $begin + 6, 2), "68") == 0 ){
						// String ab der ersten 68 als neuer Buffer
						$currentdata = substr($currentdata, $begin);
					}
				}
			}
			
			if(strlen($currentdata)>100){
				$currentdata = "";
			}

			
			$this->SetBuffer("KNXData", $currentdata);
		}

		private function proofChecksum(string $frame) : bool{
			return true;
		}

		/**
        * Die folgenden Funktionen stehen automatisch zur Verfügung, wenn das Modul über die "Module Control" eingefügt wurden.
        * Die Funktionen werden, mit dem selbst eingerichteten Prefix, in PHP und JSON-RPC wiefolgt zur Verfügung gestellt:
        *
        * DWIPSShutter_UpdatePositionValue($id);
        *
        */

		public function RequestAction($Ident, $Value) {
 
		 
		}
	}

?>