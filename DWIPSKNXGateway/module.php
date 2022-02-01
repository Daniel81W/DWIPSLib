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
			$this->RegisterVariableString("KNXAddress", "Physikalische Adresse");
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

			$this->SendDataToParent(json_encode([
				'DataID' => "{79827379-F36E-4ADA-8A95-5F8D1DC92FA9}",
				'Buffer' => utf8_encode(FT12Frame::getFT12HardwareAddressString()),
			]));
			$this->SendDebug("KNX", json_last_error(), 0);

		}
		public function RenewHardwareInformation()
		{
			$this->SendDataToParent(json_encode([
				'DataID' => "{79827379-F36E-4ADA-8A95-5F8D1DC92FA9}",
				'Buffer' => utf8_encode(FT12Frame::getFT12HardwareAddressString()),
			]));
		}

		public function ReceiveData($JSONString)
		{
			$this->ReceiveDataFT12($JSONString);
		}

		private function ReceiveDataFT12($JSONString)
		{
			//JSONString dekodieren
			$data = json_decode($JSONString, true);
			//Noch vorhandene Daten aus dem Buffer laden und neue anfügen
			$currentdata = $this->GetBuffer("KNXData") . bin2hex($data["Buffer"]);
			//$currentdata = $this->GetBuffer("KNXData") . "e5e510404016e510c0c016" . bin2hex($data["Buffer"]);

			// von UTF-8 hex auf Unicode Code point umwandeln (z.B. C3BF = FF)
			$currentdata = $this->correctDataForUTFCodes($currentdata);
			
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
					$this->SendDebug("KNX", $currentdata, 0);
					if(strpos($currentdata, "f0") === 10)
					{
						$this->SendDebug("KNX", $currentdata, 0);
					}
					else
					{
					$repeated = false;
					$prio = 4;
					$sourceaddr = "";
					$targettype = 0;
					$targetaddr = "";
					$ga3 = "";
					$ga2 = "";
					$len = 0;
					$apci = "";
					$knxdata = "";
					$framelen = hexdec(substr($currentdata,2,2)) * 2 + 12;					
					if(strlen($currentdata) >= $framelen && strcmp(substr($currentdata, $framelen - 2, 2),"16") == 0 )
					{
						$frame = substr($currentdata,0,$framelen);
						if($this->proofChecksum($frame))
						{
							$framedata = substr($frame, 10, $framelen - 14);
							if(strpos($framedata, "2900") === 0 || strpos($framedata, "2e00") === 0)
							{	
								$framedata = substr($framedata,4);
								//Extended Data Request (Normalfall)
								if(strpos($framedata, "b") === 0 || (strpos($framedata, "9") === 0))
								{
									if(strpos($framedata, "9") === 0)
									{
										$repeated = true;
									}
									$nexthex = substr($framedata,1,1);
    								$prio = hexdec($nexthex) >> 2;
									$sourceaddr = hexdec(substr($framedata,4,1)).".".hexdec(substr($framedata,5,1)).".".hexdec(substr($framedata,6,2));
									if(hexdec(substr($framedata,12,2)) < 128)
									{	
										$targettype = 0;
										$hex = substr($framedata,8,2);
										$i = hexdec($hex);
										if($i>127)
										{
											$i-= 128;
										}
										$hg = intdiv($i, 8);
										$mg = ($i - intdiv($i, 8) * 8);
										$ug = hexdec(substr($framedata,10,2));
										$ga3 = $hg . "/" . $mg . "/" . $ug;
										$ga2 = $hg . "/" . ($mg * 256 + $ug);
									}
									else
									{	
										$targettype = 1;
										$targetaddr = hexdec(substr($framedata,8,1)).".".hexdec(substr($framedata,9,1)).".".hexdec(substr($framedata,10,2));
									}
									//Length
									$len = hexdec(substr($framedata,13,1));
									//APCI
									$nexthex = substr($framedata,15,1);
									$i1 = (hexdec($nexthex) - (intdiv(hexdec($nexthex), 4) * 4)) * 4;
									$nexthex = substr($framedata,16,1);
									$i2 = intdiv(hexdec($nexthex), 4);
									$apci = $i1 + $i2;

									//Data
									$nexthex = substr($framedata,16,2);
									if($len == 1)
									{
										$knxdata = dechex(hexdec($nexthex) - intdiv(hexdec($nexthex), 64) * 64);
									}
									elseif($len > 1)
									{
										$knxdata = substr($framedata, 18);
									}

								}
								//Data Request
								elseif(strpos($framedata, "3") === 0 || (strpos($framedata, "1") === 0))
								{
									if(strpos($framedata, "1") === 0)
									{
										$repeated = true;
									}
									$nexthex = substr($framedata,1,1);
    								$prio = hexdec($nexthex) >> 2;
								}
							}
						}
						
						$json = [ 
							"DataID" => "{FF74DE4D-C871-3D0E-6D6A-1DA9E09B9A8F}",
							"Repeated" => $repeated,
							"Prio" => $prio,
							"Source" => $sourceaddr,
							"TargetType" => $targettype,
							"Target" => $targetaddr,
							"GA3" => $ga3,
							"GA2" => $ga2,
							"Length" => $len,
							"APCI" => $apci,
							"Data" => $knxdata
						];
						$json_enc = json_encode($json);
						$results = $this->SendDataToChildren($json_enc);
						foreach($results as $result) {
							$this->SendDebug("KNX", $result, 0);
						}
						$currentdata = substr($currentdata, $framelen);
					}
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

			$this->SetBuffer("KNXData", $currentdata);
		}

		public function ForwardData($JSONString)
		{
			$this->SendDebug("KNX", $JSONString, 0);
			$data = json_decode($JSONString, true);
			$this->SendDataToParent($data);
		}

		protected function SendDataToParent($data){
			$this->SendDataToParentFT12($data);
		}

		private function SendDataToParentFT12($data)
		{
			$knxdata = "2900BC";
			$ft12data = "68";

			$json = [
				"DataID" => "{79827379-F36E-4ADA-8A95-5F8D1DC92FA9}",
				"Buffer" => $ft12data
			];
			parent::SendDataToParent(json_encode($json));

		}

		private function checksum(string $data) : string
		{
			$computedChecksum = 0;
			$datalen = strlen($data) / 2;
			for($i = 0; $i < $datalen; $i++)
			{
				$computedChecksum += hexdec(substr($data, $i * 2, 2));
				if($computedChecksum > 255)
				{
					$computedChecksum -= 256;
				}
			}
			return $computedChecksum;
		}
			
		private function proofChecksum(string $frame) : bool
		{
			$framelen = hexdec(substr($frame, 2, 2));
			$framedata = substr($frame, 8, $framelen * 2);
			$checksum = substr($frame, 8 + $framelen * 2, 2);
			$computedChecksum = $this->checksum($framedata);
			return ($computedChecksum == hexdec($checksum));
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

	class KNXFrame
	{
		private string $source_addr = "";
		private string $target_addr = "";

		private string $framestring = "";

		public function __construct(){
            
        }

		public function getSourceAddress() : string
		{
			return $this->source_addr;
		}

		public function setSourceAddress(string $address)
		{
			$this->source_addr = $address;
			$this->updateFrameString();
		}

		public function getFrameAsString() : string
		{
			return $this->framestring;
		}

		private function updateFrameString() : void
		{
			$this->framestring = "2900";

			$this->framestring = "";
		}

		public function updateWithFrameString(string $frame) : void
		{

		}
	}

	class FT12Frame
	{	
		public static function getFT12HardwareAddressString()
		{
			return "6807076873f001001400017916";
		}

		public static function getFT12FirmwareString()
		{
			return "6807076873F001000300016816";

		}

		public function getFT12String()
		{

		}
	}
?>