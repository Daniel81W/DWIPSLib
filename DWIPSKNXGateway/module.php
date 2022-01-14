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

			$this->SendDebug("SerialPort","New Frame", 0);
			$this->SendDebug("SerialPort",str_contains($currentdata, "C2"), 0);
/*
			while(str_contains($currentdata, "C2")){
				$this->SendDebug("SerialPort","C2", 0);
				$next = strpos($this->GetBuffer("KNXData"), "C2");
				$this->SendDebug("SerialPort",$next, 0);
				$torep = substr($this->GetBuffer("KNXData"), $next, 4);
				$this->SendDebug("SerialPort",$torep, 0);
				$this->SetBuffer("KNXData", str_replace($torep, dechex(hexdec($torep) - hexdec("C200")), $this->GetBuffer("KNXData")));

			}*/

			$this->SendDebug("SerialPort",$currentdata, 0);

			//Buffer beginnt mit 68****68
			if(str_starts_with($currentdata, "68") && strcmp(substr($currentdata, 6, 2), "68") == 0){
				

			// Buffer beginnt nicht mit 68, bedeutet es ist nicht der Anfang eines FT1.2 Frames. Es muss zuerst er nächste Anfang gefunden werden und dann alles davor gelöscht.
			}else{ 
				//Wenn nicht der Anfang des Frames dann muss es 1668 als Übergang zwischen den Frames geben. Position davon finden.
				$begin = strpos($currentdata,"1668");
				// Falls 1668 gefunden wurde
				if($begin !== false){
					// Index plus 2, also auf 68
					$begin += 2;
					// ob die Stellen 7 + 8 auch 68 sind (Muss im FT1.2 Frame)
					if(strcmp(substr($currentdata, $begin + 6, 2), "68") == 0 ){
						// String ab der ersten 68 als neuer Buffer
						$this->SetBuffer("KNXData", substr($currentdata, $begin));
					}
				}
			}
			
			if(strlen($currentdata)>100){
				$this->SetBuffer("KNXData","");
			}

			
			$this->SetBuffer("KNXData", $currentdata);
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