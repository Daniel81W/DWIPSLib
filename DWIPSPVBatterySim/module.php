<?php
	//include_once("/var/lib/symcon/modules/DWIPSLib/libs/astro.php");
	class DWIPSPVBatterySim extends IPSModule {

		public function Create()
		{
			//Never delete this line!
			parent::Create();

			$this->RegisterPropertyFloat("BatteryCapacity", 0);
			$this->RegisterPropertyFloat("GeneratorMaxPower", 0);
			$this->RegisterPropertyFloat("BatteryUsefulCap", 0);
			$this->RegisterPropertyInteger("MainPowerID", 0);
			
			$loadID = $this->RegisterVariableFloat("BatteryLoad", "BatteryLoad");
			$loadPercID = $this->RegisterVariableFloat("BatteryLoadPerc", "BatteryLoadPerc");

			$delID = $this->RegisterVariableFloat("DeliveredEnergy", "DeliveredEnergy");
			$genPowID = $this->RegisterVariableFloat("GeneratorPower", "GeneratorPower");

			
			$theoPowID = $this->RegisterVariableFloat("TheoraticalMainPower", "TheoraticalMainPower");

			$this->RegisterMessage($this->ReadPropertyInteger("MainPowerID"),10603);

			IPS_SetVariableCustomProfile($loadID, "Electricity.kWh");
			IPS_SetVariableCustomProfile($loadPercID, "Prozent");
			IPS_SetVariableCustomProfile($delID, "Electricity.kWh");
			IPS_SetVariableCustomProfile($genPowID, "Power.kW");
			IPS_SetVariableCustomProfile($theoPowID, "Power.kW");
			
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
			if(abs($Power) > $this->ReadPropertyFloat("GeneratorMaxPower")){
				$Power = -1 * $Power / abs($Power) * $this->ReadPropertyFloat("GeneratorMaxPower");
			}else{
				$Power = -1 * $Power;
			}

			if($Power > 0 && $this->GetValue("BatteryLoad") >= $this->ReadPropertyFloat("BatteryCapacity")){
				$Power = 0;
			}elseif($Power < 0 && $this->GetValue("BatteryLoad") <= (100 - $this->ReadPropertyFloat("BatteryUsefulCap"))/100 * $this->ReadPropertyFloat("BatteryCapacity")){
				$Power = 0;
			}
			$this->SetValue("GeneratorPower", $Power);

			$lastTimePeriod = 0.0;
			$lastTimePeriod = $Power * ($Data[3] - $Data[4]) / 3600;

			if($lastTimePeriod > 0){
				$lastTimePeriod *= 0.95;
			}elseif($lastTimePeriod < 0){
				$this->SetValue("DeliveredEnergy", $this->GetValue("DeliveredEnergy") - $lastTimePeriod);
				$lastTimePeriod *= 1.05;
			}

			$free = $this->ReadPropertyFloat("BatteryCapacity") - $this->GetValue("BatteryLoad");
			$abovemin = $this->ReadPropertyFloat("BatteryCapacity") - $this->GetValue("BatteryLoad");
			if($lastTimePeriod <> 0){
				if($lastTimePeriod < $free){
					$this->SetValue("BatteryLoad", $this->GetValue("BatteryLoad") + $lastTimePeriod);
				}else{
					$this->SetValue("BatteryLoad", $this->GetValue("BatteryLoad") + $free);
				}
			}
			$this->SetValue("BatteryLoadPerc", $this->GetValue("BatteryLoad") / $this->ReadPropertyFloat("BatteryCapacity") * 100);
			$this->SetValue("TheoraticalMainPower", $Data[0] / 1000 + $Power);


			
			$this->SendDebug("Ergebnis", $free, 0);
			
		}
		
	}
	?>