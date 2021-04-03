<?php

	//include_once("/var/lib/symcon/modules/DWIPSLib/libs/astro.php");
	class DWIPSPhotovoltaicSystem extends IPSModule {

		public function Create()
		{
			//Never delete this line!
			parent::Create();

			$this->RegisterPropertyInteger("ModuleCount", 1);
			$this->RegisterPropertyInteger("ModulePower", 0);
			$this->RegisterPropertyInteger("ModuleWidth", 0);
			$this->RegisterPropertyInteger("ModuleLength", 0);
			
			$this->RegisterVariableInteger("CollectorPower", "CollectorPower");
			$this->RegisterVariableFloat("CollectorArea", "CollectorArea");
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
			$this->SetValue("CollectorPower", $this->ReadPropertyInteger("ModuleCount") * $this->ReadPropertyInteger("ModulePower"));
			$this->SetValue("CollectorArea", $this->ReadPropertyInteger("ModuleCount") * $this->ReadPropertyInteger("ModuleWidth") * $this->ReadPropertyInteger("ModuleLength") / 1000000 );
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

		
	}
	?>