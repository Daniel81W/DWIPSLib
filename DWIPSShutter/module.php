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
    			IPS_CreateVariableProfile("DWIPS.Shutter.Trigger", 0);
				IPS_SetVariableProfileAssociation("DWIPS.Shutter.Trigger", 1, $this->Translate("Trigger"), "", 0x00FF00);
			}

			$this->RegisterVariableInteger($this->Translate("Action"), $this->Translate("Action"), $this->Translate("DWIPS.Shutter.UpDownStop"), 1);
			$this->EnableAction($this->Translate("Action"));
			$this->RegisterVariableInteger($this->Translate("Position"), $this->Translate("Position"),"", 2);
			$this->EnableAction($this->Translate("Position"));
			$this->RegisterVariableInteger("Preset1", "Preset 1", "DWIPS.Shutter.Preset", 3);
			$this->EnableAction("Preset1");
			$this->RegisterVariableInteger("Preset2", "Preset 2", "DWIPS.Shutter.Preset", 4);
			$this->EnableAction("Preset2");
			$this->RegisterVariableInteger("Preset3", "Preset 3", "DWIPS.Shutter.Preset", 5);
			$this->EnableAction("Preset3");
			$this->RegisterVariableInteger("Preset4", "Preset 4", "DWIPS.Shutter.Preset", 6);
			$this->EnableAction("Preset4");
			$this->RegisterVariableBoolean($this->Translate("DrivingTime"), $this->Translate("DrivingTime"), $this->Translate("DWIPS.Shutter.Trigger"), 7);
			$this->EnableAction($this->Translate("DrivingTime"));
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

		public function RequestAction($Ident, $Value) {
 
			switch($Ident) {
				case $this->Translate("Action"):
					//Hier würde normalerweise eine Aktion z.B. das Schalten ausgeführt werden
					//Ausgaben über 'echo' werden an die Visualisierung zurückgeleitet
		 
					//Neuen Wert in die Statusvariable schreiben
					SetValue($this->GetIDForIdent($Ident), $Value);
					break;
				case $this->Translate("Position"):
					//Hier würde normalerweise eine Aktion z.B. das Schalten ausgeführt werden
					//Ausgaben über 'echo' werden an die Visualisierung zurückgeleitet
		 
					//Neuen Wert in die Statusvariable schreiben
					SetValue($this->GetIDForIdent($Ident), $Value);
					break;
				case "Preset1":
					//Hier würde normalerweise eine Aktion z.B. das Schalten ausgeführt werden
					//Ausgaben über 'echo' werden an die Visualisierung zurückgeleitet
		 
					//Neuen Wert in die Statusvariable schreiben
					SetValue($this->GetIDForIdent($Ident), $Value);
					break;
				case "Preset2":
					//Hier würde normalerweise eine Aktion z.B. das Schalten ausgeführt werden
					//Ausgaben über 'echo' werden an die Visualisierung zurückgeleitet
		 
					//Neuen Wert in die Statusvariable schreiben
					SetValue($this->GetIDForIdent($Ident), $Value);
					break;
				case "Preset3":
					//Hier würde normalerweise eine Aktion z.B. das Schalten ausgeführt werden
					//Ausgaben über 'echo' werden an die Visualisierung zurückgeleitet
		 
					//Neuen Wert in die Statusvariable schreiben
					SetValue($this->GetIDForIdent($Ident), $Value);
					break;
				case "Preset4":
					//Hier würde normalerweise eine Aktion z.B. das Schalten ausgeführt werden
					//Ausgaben über 'echo' werden an die Visualisierung zurückgeleitet
		 
					//Neuen Wert in die Statusvariable schreiben
					SetValue($this->GetIDForIdent($Ident), $Value);
					break;
				case $this->Translate("DrivingTime"):
					//Hier würde normalerweise eine Aktion z.B. das Schalten ausgeführt werden
					//Ausgaben über 'echo' werden an die Visualisierung zurückgeleitet
		 
					//Neuen Wert in die Statusvariable schreiben
					if($Value = 1){
						SetValue($this->GetIDForIdent($Ident), $Value);
						//KNX oder EIB
						KNX_WriteDPT1($this->ReadPropertyInteger("DrivingTimeInstanceID"), 1);
						SetValue($this->GetIDForIdent($Ident), 0);
						//KNX oder EIB
						KNX_WriteDPT1($this->ReadPropertyInteger("DrivingTimeInstanceID"), 0);

					}
					break;
				default:
					throw new Exception("Invalid Ident");
			}
		 
		}
	}
	?>