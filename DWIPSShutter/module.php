<?php

	//include_once("/var/lib/symcon/modules/DWIPSLib/libs/astro.php");
	class DWIPSShutter extends IPSModule {

		public function Create()
		{
			//Never delete this line!
			parent::Create();

			$this->RegisterPropertyInteger("UpDownInstanceID", 0);
			$this->RegisterPropertyInteger("StopInstanceID", 0);
			
			if (! IPS_VariableProfileExists($this->Translate("DWIPS.Shutter.UpDownStop"))) {
    			IPS_CreateVariableProfile($this->Translate("DWIPS.Shutter.UpDownStop"), 1);
				IPS_SetVariableProfileAssociation($this->Translate("DWIPS.Shutter.UpDownStop"), 0, $this->Translate("Up"), "", 0x00FF00);
				IPS_SetVariableProfileAssociation($this->Translate("DWIPS.Shutter.UpDownStop"), 1, $this->Translate("Stop"), "", 0xFF0000);
				IPS_SetVariableProfileAssociation($this->Translate("DWIPS.Shutter.UpDownStop"), 2, $this->Translate("Down"), "", 0x00FF00);
				IPS_SetVariableProfileIcon($this->Translate("DWIPS.Shutter.UpDownStop"),  "Shutter");
			}

			$this->RegisterVariableInteger($this->Translate("Action"), $this->Translate("Action"), $this->Translate("DWIPS.Shutter.UpDownStop"), 1);
			
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
        public function UpdateSunrise() {
           }

		
	}
	?>