<?php

	//include_once("/var/lib/symcon/modules/DWIPSLib/libs/astro.php");
	class DWIPSShutter extends IPSModule {

		public function Create()
		{
			//Never delete this line!
			parent::Create();

			$this->RegisterPropertyInteger("UpDownInstanceID", 0);
			$this->RegisterPropertyInteger("StopInstanceID", 0);
			$this->RegisterPropertyInteger("PositionInstanceID", 0);
			$this->RegisterPropertyInteger("Preset12ExInstanceID", 0);
			$this->RegisterPropertyInteger("Preset34ExInstanceID", 0);
			$this->RegisterPropertyInteger("Preset12SetInstanceID", 0);
			$this->RegisterPropertyInteger("Preset34SetInstanceID", 0);
			$this->RegisterPropertyInteger("DrivingTimeInstanceID", 0);
			
			if (! IPS_VariableProfileExists($this->Translate("DWIPS.Shutter.UpDownStop"))) {
    			IPS_CreateVariableProfile($this->Translate("DWIPS.Shutter.UpDownStop"), 1);
				IPS_SetVariableProfileAssociation($this->Translate("DWIPS.Shutter.UpDownStop"), 0, $this->Translate("Up"), "", 0x00FF00);
				IPS_SetVariableProfileAssociation($this->Translate("DWIPS.Shutter.UpDownStop"), 1, $this->Translate("Stop"), "", 0xFF0000);
				IPS_SetVariableProfileAssociation($this->Translate("DWIPS.Shutter.UpDownStop"), 2, $this->Translate("Down"), "", 0x00FF00);
				IPS_SetVariableProfileIcon($this->Translate("DWIPS.Shutter.UpDownStop"),  "Shutter");
			}
			if (! IPS_VariableProfileExists("DWIPS.Shutter.Preset")) {
    			IPS_CreateVariableProfile("DWIPS.Shutter.Preset", 1);
				IPS_SetVariableProfileAssociation("DWIPS.Shutter.Preset", 1, $this->Translate("Set"), "", -1);
				IPS_SetVariableProfileAssociation("DWIPS.Shutter.Preset", 2, $this->Translate("DriveTo"), "", 0x00FF00);
			}
			if (! IPS_VariableProfileExists("DWIPS.Shutter.Trigger")) {
    			IPS_CreateVariableProfile("DWIPS.Shutter.Trigger", 1);
				IPS_SetVariableProfileAssociation("DWIPS.Shutter.Trigger", 2, $this->Translate("Trigger"), "", 0x00FF00);
			}

			$this->RegisterVariableInteger($this->Translate("Action"), $this->Translate("Action"), $this->Translate("DWIPS.Shutter.UpDownStop"), 1);
			$this->RegisterVariableInteger($this->Translate("Position"), $this->Translate("Position"), $this->Translate("DWIPS.Shutter.UpDownStop"), 2);
			$this->RegisterVariableInteger("Preset 1", "Preset1", $this->Translate("DWIPS.Shutter.Preset"), 3);
			$this->RegisterVariableInteger("Preset 2", "Preset2", $this->Translate("DWIPS.Shutter.Preset"), 4);
			$this->RegisterVariableInteger("Preset 3", "Preset3", $this->Translate("DWIPS.Shutter.Preset"), 5);
			$this->RegisterVariableInteger("Preset 4", "Preset4", $this->Translate("DWIPS.Shutter.Preset"), 6);
			$this->RegisterVariableInteger($this->Translate("DrivingTime"), $this->Translate("DrivingTime"), $this->Translate("DWIPS.Shutter.Trigger"), 7);
			
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