<?php
	//include_once("/var/lib/symcon/modules/DWIPSLib/libs/astro.php");
	class DWIPSSkyWindGen extends IPSModule {

		public function Create()
		{
			//Never delete this line!
			parent::Create();

			$this->RegisterPropertyInteger("GenCount", 0);
			$this->RegisterPropertyFloat("MaxGeneratorPower", 0);
			$this->RegisterPropertyInteger("WindSpID", 0);
			
			$genPowID = $this->RegisterVariableFloat("GeneratorPower", $this->Translate("GeneratorPower"), "Power.kW", 1);
			$delID = $this->RegisterVariableFloat("DeliveredEnergy", $this->Translate("DeliveredEnergy"), "Electricity.kWh", 2);
			
			
			$this->RegisterMessage($this->ReadPropertyInteger("WindSpID"),10603);
			
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
			$this->RegisterMessage($this->ReadPropertyInteger("WindSpID"),10603);
		}

		/**
        * Die folgenden Funktionen stehen automatisch zur Verfügung, wenn das Modul über die "Module Control" eingefügt wurden.
        * Die Funktionen werden, mit dem selbst eingerichteten Prefix, in PHP und JSON-RPC wiefolgt zur Verfügung gestellt:
        *
        * DWIPSShutter_UpdateSunrise($id);
        *
        */

		public function ReceiveData($JSONString) {
			
		}

		public function MessageSink($TimeStamp, $SenderID, $Message, $Data) {
	
			//IPS_LogMessage("MessageSink", "Message from SenderID ".$SenderID." with Message ".$Message."\r\n Data: ".print_r($Data, true));
			$Power = 0.0;
			$Wind = 0.0;
			$Wind = $Data[0];
			if($Wind < 4){
				$Power = 0.0;
			}elseif($Wind <= 17){
				$Power = -10 * ($Wind - 3) * pow(1.15,($Wind - 4));
			}elseif($Wind < 20){
				$Power = 0;
			}else{
				$Power = 0;
			}
			
			$Power = $Power*$this->ReadPropertyFloat("MaxGeneratorPower")/1000 / 1000;
			$Power = $Power*$this->ReadPropertyInteger("GenCount");
			$this->SetValue("GeneratorPower", $Power);

			$lastTimePeriod = 0.0;
			$lastTimePeriod = $Power * ($Data[3] - $Data[4]) / 3600;
			$this->SendDebug("En", $lastTimePeriod, 0);

			if($lastTimePeriod < 0){
				$this->SetValue("DeliveredEnergy", $this->GetValue("DeliveredEnergy") - $lastTimePeriod);
			}

		
			
		}
		
	}
	?>