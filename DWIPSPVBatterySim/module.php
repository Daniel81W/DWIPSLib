<?php
	//include_once("/var/lib/symcon/modules/DWIPSLib/libs/astro.php");
	class DWIPSPVBatterySim extends IPSModule {

		public function Create()
		{
			//Never delete this line!
			parent::Create();

			$this->RegisterPropertyFloat("BatteryCapacity", 0);
			$this->RegisterPropertyFloat("GeneratorMaxPower", 0);
			$this->RegisterPropertyInteger("BatteryUsefulCap", 0);
			$this->RegisterPropertyInteger("MainPowerID", 0);
			
			$this->RegisterVariableFloat("BatteryLoad", "BateryLoad");
			$this->RegisterVariableFloat("BatteryLoadPerc", "BatteryLoadPerc");

			$this->RegisterVariableFloat("DeliveredEnergy", "DeliveredEnergy");
			$this->RegisterVariableFloat("GeneratorPower", "GeneratorPower");

			
			$this->RegisterVariableFloat("TheoraticalMainPower", "TheoraticalMainPower");

			$this->RegisterMessage($this->ReadPropertyInteger("MainPowerID"),10603);

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
			$this->RegisterMessage($this->ReadPropertyInteger("MainPowerID"),10603);
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
			$Power = $Data[0] / 1000;
			if($Power > $this->ReadPropertyFloat("GeneratorMaxPower")){

			}
			$this->SendDebug("Ergebnis", $Power, 0);
		}
		
	}
	?>