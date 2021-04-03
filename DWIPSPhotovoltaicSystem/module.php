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

			$this->RequireParent("{6DC3D946-0D31-450F-A8C6-C42DB8D7D4F1}");
			$this->RegisterVariableString("data", "data");
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

		public function ReceiveData($JSONString) {
			$data = json_decode($JSONString, true);
			$data['Buffer'] = bin2hex($data['Buffer']);
			if(strpos($data['Buffer'], "1b1b1b1b1a")){
				$this->SetBuffer("serdata", $this->GetBuffer("serdata").substr($data['Buffer'],0,strpos($data['Buffer'], "1b1b1b1b1a")));
			}


			$this->SetValue("data", $this->GetBuffer("serdata"));
			//Im Meldungsfenster zu Debug zwecken ausgeben
			//IPS_LogMessage("DATA", print_r($data, true));
		}
		
	}
	?>