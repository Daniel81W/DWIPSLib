<?php

	//include_once("/var/lib/symcon/modules/DWIPSLib/libs/astro.php");
	class DWIPSShutter extends IPSModule {

		public function Create()
		{
			//Never delete this line!
			parent::Create();

			//Instances for control of the shutter (KNX, EIB)
			$this->RegisterPropertyInteger("UpDownInstanceID", 0);
			$this->RegisterPropertyInteger("StopInstanceID", 0);
			$this->RegisterPropertyInteger("PositionInstanceID", 0);
			$this->RegisterPropertyInteger("Preset12ExInstanceID", 0);
			$this->RegisterPropertyInteger("Preset34ExInstanceID", 0);
			$this->RegisterPropertyInteger("Preset12SetInstanceID", 0);
			$this->RegisterPropertyInteger("Preset34SetInstanceID", 0);
			$this->RegisterPropertyInteger("DrivingTimeInstanceID", 0);
			
			//Variable profiles. CHeck if existing. Else create.
			if (! IPS_VariableProfileExists($this->Translate("DWIPS.Shutter.UpDownStop"))) {
    			IPS_CreateVariableProfile($this->Translate("DWIPS.Shutter.UpDownStop"), 1);
				IPS_SetVariableProfileAssociation($this->Translate("DWIPS.Shutter.UpDownStop"), 0, $this->Translate("Up"), "", 0x00FF00);
				IPS_SetVariableProfileAssociation($this->Translate("DWIPS.Shutter.UpDownStop"), 1, $this->Translate("Stop"), "", 0xFF0000);
				IPS_SetVariableProfileAssociation($this->Translate("DWIPS.Shutter.UpDownStop"), 2, $this->Translate("Down"), "", 0x00FF00);
				IPS_SetVariableProfileIcon($this->Translate("DWIPS.Shutter.UpDownStop"),  "Shutter");
			}
			if (! IPS_VariableProfileExists($this->Translate("DWIPS.Shutter.Position"))) {
    			IPS_CreateVariableProfile($this->Translate("DWIPS.Shutter.Position"), 1);
				IPS_SetVariableProfileText($this->Translate("DWIPS.Shutter.Position"), "", "%");
				IPS_SetVariableProfileValues($this->Translate("DWIPS.Shutter.Position"), 0, 100, 2);
				//IPS_SetVariableProfileAssociation($this->Translate("DWIPS.Shutter.Position"), 0, $this->Translate("Up"), "", 0x00FF00);
				//IPS_SetVariableProfileAssociation($this->Translate("DWIPS.Shutter.Position"), 1, $this->Translate("Stop"), "", 0xFF0000);
				//IPS_SetVariableProfileAssociation($this->Translate("DWIPS.Shutter.Position"), 2, $this->Translate("Down"), "", 0x00FF00);
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

			//Variables to control shutter in Webfront
			$this->RegisterVariableInteger($this->Translate("Action"), $this->Translate("Action"), $this->Translate("DWIPS.Shutter.UpDownStop"), 1);
			$this->EnableAction($this->Translate("Action"));
			$this->RegisterVariableInteger($this->Translate("Position"), $this->Translate("Position"),$this->Translate("DWIPS.Shutter.Position"), 2);
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
			
			$PosScriptID = IPS_CreateScript(0);
			IPS_SetParent($PosScriptID, IPS_GetChildrenIDs($this->ReadPropertyInteger("PositionInstanceID"))[0]);
			IPS_SetName($PosScriptID, "DWIPS_ActionScript");
			echo "SetValue($_IPS['VARIABLE'], $_IPS['VALUE']);DWIPSShutter_UpdatePositionValue(".$this->InstanceID.", $_IPS['VALUE']);";
			//IPS_SetScriptContent($PosScriptID, "SetValue($_IPS['VARIABLE'], $_IPS['VALUE']);DWIPSShutter_UpdatePositionValue(".$this->InstanceID.", $_IPS['VALUE']);");
			IPS_SetVariableCustomAction(IPS_GetChildrenIDs($this->ReadPropertyInteger("PositionInstanceID"))[0], $PosScriptID);
			
			
			/*
			$TriggerID = @IPS_GetEventIDByName("DWIPSShutterActionTrig", $this->GetIDForIdent($this->Translate("Action")));
			if($TriggerID === false){
				$eid = IPS_CreateEvent(0);
				IPS_SetParent($eid, $this->GetIDForIdent($this->Translate("Action")));
				IPS_SetEventTrigger($eid, 1, @IPS_GetVariableIDByName("Wert", $this->ReadPropertyInteger("UpDownInstanceID")));
				IPS_SetEventActive($eid, true);
				IPS_SetEventTriggerSubsequentExecution($eid, true);
				IPS_SetName($eid, "DWIPSShutterActionTrig");
			}
			*/
		}

		/**
        * Die folgenden Funktionen stehen automatisch zur Verfügung, wenn das Modul über die "Module Control" eingefügt wurden.
        * Die Funktionen werden, mit dem selbst eingerichteten Prefix, in PHP und JSON-RPC wiefolgt zur Verfügung gestellt:
        *
        * DWIPSShutter_UpdatePositionValue($id);
        *
        */
		public function UpdatePositionValue($Position){
			SetValue($this->GetIDForIdent($this->Translate("Position")), $Position);
		}

		public function RequestAction($Ident, $Value) {
 
			switch($Ident) {
				case $this->Translate("Action"):
					SetValue($this->GetIDForIdent($Ident), $Value);
					if($Value == 0){
						if(IPS_GetInstance($this->ReadPropertyInteger("UpDownInstanceID"))['ModuleInfo']['ModuleName'] == "KNX DPT 1"){
							KNX_WriteDPT1($this->ReadPropertyInteger("UpDownInstanceID"), 0);
						}elseif(IPS_GetInstance($this->ReadPropertyInteger("UpDownInstanceID"))['ModuleInfo']['ModuleName'] == "EIB Group"){
							EIB_Switch($this->ReadPropertyInteger("UpDownInstanceID"), false);
						}
					}elseif($Value == 1){
						if(IPS_GetInstance($this->ReadPropertyInteger("StopInstanceID"))['ModuleInfo']['ModuleName'] == "KNX DPT 1"){
							KNX_WriteDPT1($this->ReadPropertyInteger("StopInstanceID"), 1);
						}elseif(IPS_GetInstance($this->ReadPropertyInteger("StopInstanceID"))['ModuleInfo']['ModuleName'] == "EIB Group"){
							EIB_Switch($this->ReadPropertyInteger("UpDownInsStopInstanceIDtanceID"), true);
						}
					}elseif($Value == 2){
						if(IPS_GetInstance($this->ReadPropertyInteger("UpDownInstanceID"))['ModuleInfo']['ModuleName'] == "KNX DPT 1"){
							KNX_WriteDPT1($this->ReadPropertyInteger("UpDownInstanceID"), 1);
						}elseif(IPS_GetInstance($this->ReadPropertyInteger("UpDownInstanceID"))['ModuleInfo']['ModuleName'] == "EIB Group"){
							EIB_Switch($this->ReadPropertyInteger("UpDownInstanceID"), true);
						}
					}
					break;

				case $this->Translate("Position"):
					SetValue($this->GetIDForIdent($Ident), $Value);
					//KNX oder EIB
					if(IPS_GetInstance($this->ReadPropertyInteger("PositionInstanceID"))['ModuleInfo']['ModuleName'] == "KNX DPT 5"){
						KNX_WriteDPT5($this->ReadPropertyInteger("PositionInstanceID"), $Value);
					}elseif(IPS_GetInstance($this->ReadPropertyInteger("PositionInstanceID"))['ModuleInfo']['ModuleName'] == "EIB Group"){
						EIB_Scale($this->ReadPropertyInteger("PositionInstanceID"), $Value);
					}
					break;
				case "Preset1":
				case "Preset2":
				case "Preset3":
					SetValue($this->GetIDForIdent($Ident), $Value);
					if($Value == 1){
						//KNX oder EIB
						if(IPS_GetInstance($this->ReadPropertyInteger("Preset34SetInstanceID"))['ModuleInfo']['ModuleName'] == "KNX DPT 1"){
							KNX_WriteDPT1($this->ReadPropertyInteger("Preset34SetInstanceID"), 0);
						}elseif(IPS_GetInstance($this->ReadPropertyInteger("Preset34SetInstanceID"))['ModuleInfo']['ModuleName'] == "EIB Group"){
							EIB_Switch($this->ReadPropertyInteger("Preset34SetInstanceID"), false);
						}
					}elseif($Value == 2){
						//KNX oder EIB
						if(IPS_GetInstance($this->ReadPropertyInteger("Preset34ExInstanceID"))['ModuleInfo']['ModuleName'] == "KNX DPT 1"){
							KNX_WriteDPT1($this->ReadPropertyInteger("Preset34ExInstanceID"), 0);
						}elseif(IPS_GetInstance($this->ReadPropertyInteger("Preset34ExInstanceID"))['ModuleInfo']['ModuleName'] == "EIB Group"){
							EIB_Switch($this->ReadPropertyInteger("Preset34ExInstanceID"), false);
						}
					}
					IPS_Sleep(2000);
					SetValue($this->GetIDForIdent($Ident), 0);
					break;

				case "Preset4":
					SetValue($this->GetIDForIdent($Ident), $Value);
					if($Value == 1){
						//KNX oder EIB
						if(IPS_GetInstance($this->ReadPropertyInteger("Preset34SetInstanceID"))['ModuleInfo']['ModuleName'] == "KNX DPT 1"){
							KNX_WriteDPT1($this->ReadPropertyInteger("Preset34SetInstanceID"), 1);
						}elseif(IPS_GetInstance($this->ReadPropertyInteger("Preset34SetInstanceID"))['ModuleInfo']['ModuleName'] == "EIB Group"){
							EIB_Switch($this->ReadPropertyInteger("Preset34SetInstanceID"), true);
						}
					}elseif($Value == 2){
						//KNX oder EIB
						if(IPS_GetInstance($this->ReadPropertyInteger("Preset34ExInstanceID"))['ModuleInfo']['ModuleName'] == "KNX DPT 1"){
							KNX_WriteDPT1($this->ReadPropertyInteger("Preset34ExInstanceID"), 1);
						}elseif(IPS_GetInstance($this->ReadPropertyInteger("Preset34ExInstanceID"))['ModuleInfo']['ModuleName'] == "EIB Group"){
							EIB_Switch($this->ReadPropertyInteger("Preset34ExInstanceID"), true);
						}
					}
					IPS_Sleep(2000);
					SetValue($this->GetIDForIdent($Ident), 0);	
					break;

				case $this->Translate("DrivingTime"):
					if($Value == 1){
						SetValue($this->GetIDForIdent($Ident), $Value);
						//KNX oder EIB
						if(IPS_GetInstance($this->ReadPropertyInteger("DrivingTimeInstanceID"))['ModuleInfo']['ModuleName'] == "KNX DPT 1"){
							KNX_WriteDPT1($this->ReadPropertyInteger("DrivingTimeInstanceID"), 1);
						}elseif(IPS_GetInstance($this->ReadPropertyInteger("DrivingTimeInstanceID"))['ModuleInfo']['ModuleName'] == "EIB Group"){
							EIB_Switch($this->ReadPropertyInteger("DrivingTimeInstanceID"), true);
						}
						IPS_Sleep(2000);
						SetValue($this->GetIDForIdent($Ident), 0);
						//KNX oder EIB
						if(IPS_GetInstance($this->ReadPropertyInteger("DrivingTimeInstanceID"))['ModuleInfo']['ModuleName'] == "KNX DPT 1"){
							KNX_WriteDPT1($this->ReadPropertyInteger("DrivingTimeInstanceID"), 0);
						}elseif(IPS_GetInstance($this->ReadPropertyInteger("DrivingTimeInstanceID"))['ModuleInfo']['ModuleName'] == "EIB Group"){
							EIB_Switch($this->ReadPropertyInteger("DrivingTimeInstanceID"), false);
						}
					}
					break;
				default:
					throw new Exception("Invalid Ident");
			}
		 
		}
	}
	?>