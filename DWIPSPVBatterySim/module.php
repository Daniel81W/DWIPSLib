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
			
			$this->RegisterVariableFloat("BatteryLoad", "BateryLoad");
			$this->RegisterVariableFloat("BatteryLoadPerc", "BatteryLoadPerc");

			$this->RegisterVariableFloat("DeliveredEnergy", "DeliveredEnergy");
			$this->RegisterVariableFloat("GeneratorPower", "GeneratorPower");

			
			$this->RegisterVariableFloat("TheoraticalMainPower", "TheoraticalMainPower");

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

		/**
        * Die folgenden Funktionen stehen automatisch zur Verfügung, wenn das Modul über die "Module Control" eingefügt wurden.
        * Die Funktionen werden, mit dem selbst eingerichteten Prefix, in PHP und JSON-RPC wiefolgt zur Verfügung gestellt:
        *
        * DWIPSShutter_UpdateSunrise($id);
        *
        */
        public function Update() {
           }

		public function ReceiveData($JSONString) {
			
		}
		
	}
	?>