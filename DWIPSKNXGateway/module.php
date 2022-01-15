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
	
		public function ReceiveData($JSONString)
		{
			$this->ReceiveDataFT12($JSONString);
		}

		public function ReceiveDataFT12($JSONString)
		{
			//JSONString dekodieren
			$data = json_decode($JSONString, true);
			//Noch vorhandene Daten aus dem Buffer laden und neue anfügen
			$currentdata = $this->GetBuffer("KNXData") . bin2hex($data["Buffer"]);
			//$currentdata = $this->GetBuffer("KNXData") . "e5e510404016e510c0c016" . bin2hex($data["Buffer"]);

$this->SendDebug("SerialPort","1. New Data", 0);
			// von UTF-8 hex auf Unicode Code point umwandeln (z.B. C3BF = FF)
			$currentdata = $this->correctDataForUTFCodes($currentdata);

$this->SendDebug("SerialPort","2. Data: " . $currentdata, 0);
			
			for($i = 10; $i > 0; $i--)
			{
				//Buffer beginnt mit E5 -> ACK
				if(strpos($currentdata, "e5") === 0)
				{
					$currentdata = substr($currentdata, 2);
				}
				//Reset Req
				if(strpos($currentdata, "10404016") === 0)
				{
					$currentdata = substr($currentdata, 8);
				}
				//Reset Ind
				if(strpos($currentdata, "10c0c016") === 0)
				{
					$currentdata = substr($currentdata, 8);
				}
				//Buffer beginnt mit 68****68 und die Bytes 2 und 3 sind gleich
				if(strpos($currentdata, "68") === 0 && strpos(substr($currentdata, 6, 2), "68") === 0 && strcmp(substr($currentdata, 2, 2), substr($currentdata, 4, 2)) == 0)
				{
					$framelen = hexdec(substr($currentdata,2,2)) * 2 + 12;					
					if(strlen($currentdata) >= $framelen && strcmp(substr($currentdata, $framelen - 2, 2),"16") == 0 )
					{
						$frame = substr($currentdata,0,$framelen);
						if($this->proofChecksum($frame))
						{
							$framedata = substr($frame, 10, $framelen - 14);
						}
						$currentdata = substr($currentdata, $framelen);
					}
				}
				// Buffer beginnt nicht mit 68, bedeutet es ist nicht der Anfang eines FT1.2 Frames. Es muss zuerst er nächste Anfang gefunden werden und dann alles davor gelöscht.
				if(strpos($currentdata, "1668") !== 0 || strpos($currentdata, "e568") !== 0)
				{ 
					//Wenn nicht der Anfang des Frames dann muss es 1668 als Übergang zwischen den Frames geben. Position davon finden.
					$begin = strpos($currentdata,"1668");
					if($begin === false)
					{
						$begin = strpos($currentdata,"e568");
					}
					// Falls 1668 oder e568 gefunden wurde
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
			}



			if(strlen($currentdata)>=150)
			{
				$currentdata = "";
			}

			$this->SetBuffer("KNXData", $currentdata);
		}
			
		private function proofChecksum(string $frame) : bool
		{
			return true;
		}

		private function correctDataForUTFCodes(string $frame) : string
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