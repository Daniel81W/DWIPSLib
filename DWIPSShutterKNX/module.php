<?php

declare(strict_types=1);
	class DWIPSShutterKNX extends IPSModule
	{

		//Properties für die Einstellungen
		private $properties = [
			0 => ["name" => "UpDownID", "type" => "id"],
			1 => ["name" => "StopID", "type" => "id"],
			2 => ["name" => "PositionID", "type" => "id"],
			3 => ["name" => "PositionRMID", "type" => "id"],
			4 => ["name" => "Preset12SetID", "type" => "id"],
			5 => ["name" => "Preset12ExID", "type" => "id"],
			6 => ["name" => "Preset34SetID", "type" => "id"],
			7 => ["name" => "Preset34ExID", "type" => "id"],
			8 => ["name" => "DrivingTimeID", "type" => "id"],
			9 => ["name" => "Value90", "type" => "int"],
			10 => ["name" => "Value75", "type" => "int"],
			11 => ["name" => "Value50", "type" => "int"],
			12 => ["name" => "Value25", "type" => "int"]
		];
		//Zu erstellende Variablen in der Instanz
		private $variables = [
			0 => ["name" => "Control", "type" => "int", "pos" => 1, "profile" => "Control"],
			1 => ["name" => "Action", "type" => "int", "pos" => 2, "profile" => "UpDownStop"],
			2 => ["name" => "Stop", "type" => "bool", "pos" => 3, "profile" => "Stop"],
			3 => ["name" => "Position", "type" => "int", "pos" => 4, "profile" => "Position"],
			4 => ["name" => "PositionSteps", "type" => "int", "pos" => 5, "profile" => "PositionSteps"],
			5 => ["name" => "Preset1", "type" => "int", "pos" => 6, "profile" => "Preset"],
			6 => ["name" => "Preset2", "type" => "int", "pos" => 7, "profile" => "Preset"],
			7 => ["name" => "Preset3", "type" => "int", "pos" => 8, "profile" => "Preset"],
			8 => ["name" => "Preset4", "type" => "int", "pos" => 9, "profile" => "Preset"],
			9 => ["name" => "Preset1Value", "type" => "int", "pos" => 10, "profile" => "Position"],
			10 => ["name" => "Preset2Value", "type" => "int", "pos" => 11, "profile" => "Position"],
			11 => ["name" => "Preset3Value", "type" => "int", "pos" => 12, "profile" => "Position"],
			12 => ["name" => "Preset4Value", "type" => "int", "pos" => 13, "profile" => "Position"],
			13 => ["name" => "DrivingTime", "type" => "bool", "pos" => 14, "profile" => "TriggerPro"],
			14 => ["name" => "EarliestUp", "type" => "int", "pos" => 15, "profile" => "_~UnixTimestampTime"],
			15 => ["name" => "LatestUp", "type" => "int", "pos" => 16, "profile" => "_~UnixTimestampTime"],
			16 => ["name" => "EarliestDown", "type" => "int", "pos" =>17, "profile" => "_~UnixTimestampTime"],
			17 => ["name" => "LatestDown", "type" => "int", "pos" => 18, "profile" => "_~UnixTimestampTime"],
			18 => ["name" => "AutomationMorningOnOff", "type" => "bool", "pos" => 19, "profile" => "SwitchActive"],
			19 => ["name" => "AutomationEveningOnOff", "type" => "bool", "pos" => 20, "profile" => "SwitchNotActive"]
		];
		public function Create()
		{
			//Never delete this line!
			parent::Create();

			//Properties für die Einstellungen anmelden
			foreach($this->properties as $prop){
				if($prop["type"] == "id"){
					$this->RegisterPropertyInteger($prop["name"], 0);
				}elseif($prop["type"] == "int"){
					$this->RegisterPropertyInteger($prop["name"], 0);
				}else{

				}
			}

			//Variable profiles. CHeck if existing. Else create.
			{
			if (!IPS_VariableProfileExists($this->Translate("DWIPS.Shutter.Control")))
			{
				IPS_CreateVariableProfile($this->Translate("DWIPS.Shutter.Control"), 1);
				IPS_SetVariableProfileAssociation($this->Translate("DWIPS.Shutter.Control"), 0, $this->Translate("Closed"), "", 0xAAAAAA);
				IPS_SetVariableProfileAssociation($this->Translate("DWIPS.Shutter.Control"), 4, "90%%", "", 0xAAAAAA);
				IPS_SetVariableProfileAssociation($this->Translate("DWIPS.Shutter.Control"), 5, "75%%", "", 0xAAAAAA);
				IPS_SetVariableProfileAssociation($this->Translate("DWIPS.Shutter.Control"), 6, "50%%", "", 0xAAAAAA);
				IPS_SetVariableProfileAssociation($this->Translate("DWIPS.Shutter.Control"), 7, "25%%", "", 0xAAAAAA);
				IPS_SetVariableProfileAssociation($this->Translate("DWIPS.Shutter.Control"), 8, $this->Translate("Opened"), "", 0xAAAAAA);
				IPS_SetVariableProfileAssociation($this->Translate("DWIPS.Shutter.Control"), 10, "  ", "", 0x000000);
				IPS_SetVariableProfileAssociation($this->Translate("DWIPS.Shutter.Control"), 11, $this->Translate("Up"), "", 0x00FF00);
				IPS_SetVariableProfileAssociation($this->Translate("DWIPS.Shutter.Control"), 13, $this->Translate("Stop"), "", 0xFF0000);
				IPS_SetVariableProfileAssociation($this->Translate("DWIPS.Shutter.Control"), 14, $this->Translate("Down"), "", 0x00FF00);
				IPS_SetVariableProfileIcon($this->Translate("DWIPS.Shutter.Control"), "Shutter");
			}
			if (!IPS_VariableProfileExists($this->Translate("DWIPS.Shutter.UpDownStop")))
			{
				IPS_CreateVariableProfile($this->Translate("DWIPS.Shutter.UpDownStop"), 1);
				IPS_SetVariableProfileAssociation($this->Translate("DWIPS.Shutter.UpDownStop"), 0, $this->Translate("Up"), "", 0x00FF00);
				IPS_SetVariableProfileAssociation($this->Translate("DWIPS.Shutter.UpDownStop"), 1, $this->Translate("Stop"), "", 0xFF0000);
				IPS_SetVariableProfileAssociation($this->Translate("DWIPS.Shutter.UpDownStop"), 2, $this->Translate("Down"), "", 0x00FF00);
				IPS_SetVariableProfileIcon($this->Translate("DWIPS.Shutter.UpDownStop"), "Shutter");
			}
			if (!IPS_VariableProfileExists($this->Translate("DWIPS.Shutter.Stop")))
			{
				IPS_CreateVariableProfile($this->Translate("DWIPS.Shutter.Stop"), 0);
				IPS_SetVariableProfileAssociation($this->Translate("DWIPS.Shutter.Stop"), 1, $this->Translate("Stop"), "", 0xFF0000);
				IPS_SetVariableProfileIcon($this->Translate("DWIPS.Shutter.Stop"), "Shutter");
			}
			if (!IPS_VariableProfileExists($this->Translate("DWIPS.Shutter.Position")))
			{
				IPS_CreateVariableProfile($this->Translate("DWIPS.Shutter.Position"), 1);
				IPS_SetVariableProfileText($this->Translate("DWIPS.Shutter.Position"), "", "%");
				IPS_SetVariableProfileValues($this->Translate("DWIPS.Shutter.Position"), 0, 100, 2);
				//IPS_SetVariableProfileAssociation($this->Translate("DWIPS.Shutter.Position"), 0, $this->Translate("Up"), "", 0x00FF00);
				//IPS_SetVariableProfileAssociation($this->Translate("DWIPS.Shutter.Position"), 1, $this->Translate("Stop"), "", 0xFF0000);
				//IPS_SetVariableProfileAssociation($this->Translate("DWIPS.Shutter.Position"), 2, $this->Translate("Down"), "", 0x00FF00);
			}
			if (!IPS_VariableProfileExists($this->Translate("DWIPS.Shutter.PositionSteps")))
			{
				IPS_CreateVariableProfile($this->Translate("DWIPS.Shutter.PositionSteps"), 1);
				IPS_SetVariableProfileText($this->Translate("DWIPS.Shutter.PositionSteps"), "", "");
				IPS_SetVariableProfileValues($this->Translate("DWIPS.Shutter.PositionSteps"), 0, 100, 0);
				IPS_SetVariableProfileAssociation($this->Translate("DWIPS.Shutter.PositionSteps"), 0, "0 %%", "", 0x00ffff);
				IPS_SetVariableProfileAssociation($this->Translate("DWIPS.Shutter.PositionSteps"), 20, "20 %%", "", 0x00ffff);
				IPS_SetVariableProfileAssociation($this->Translate("DWIPS.Shutter.PositionSteps"), 40, "40 %%", "", 0x00ffff);
				IPS_SetVariableProfileAssociation($this->Translate("DWIPS.Shutter.PositionSteps"), 60, "60 %%", "", 0x00ffff);
				IPS_SetVariableProfileAssociation($this->Translate("DWIPS.Shutter.PositionSteps"), 80, "80 %%", "", 0x00ffff);
				IPS_SetVariableProfileAssociation($this->Translate("DWIPS.Shutter.PositionSteps"), 100, "100 %%", "", 0x00ffff);
			}
			if (!IPS_VariableProfileExists("DWIPS.Shutter.Preset"))
			{
				IPS_CreateVariableProfile("DWIPS.Shutter.Preset", 1);
				IPS_SetVariableProfileAssociation("DWIPS.Shutter.Preset", 1, $this->Translate("Set"), "", -1);
				IPS_SetVariableProfileAssociation("DWIPS.Shutter.Preset", 2, $this->Translate("DriveTo"), "", 0x00FF00);
			}
			if (!IPS_VariableProfileExists($this->Translate("DWIPS.Shutter.TriggerPro")))
			{
				IPS_CreateVariableProfile($this->Translate("DWIPS.Shutter.TriggerPro"), 0);
				IPS_SetVariableProfileAssociation($this->Translate("DWIPS.Shutter.TriggerPro"), 1, $this->Translate("Trigger"), "", 0x00FF00);
			}
			if (!IPS_VariableProfileExists($this->Translate("DWIPS.Shutter.SwitchActive")))
			{
				IPS_CreateVariableProfile($this->Translate("DWIPS.Shutter.SwitchActive"), 0);
				IPS_SetVariableProfileAssociation($this->Translate("DWIPS.Shutter.SwitchActive"), 1, $this->Translate("Active"), "", 0x00FF00);
			}
			if (!IPS_VariableProfileExists($this->Translate("DWIPS.Shutter.SwitchNotActive")))
			{
				IPS_CreateVariableProfile($this->Translate("DWIPS.Shutter.SwitchNotActive"), 0);
				IPS_SetVariableProfileAssociation($this->Translate("DWIPS.Shutter.SwitchNotActive"), 0, $this->Translate("NotActive"), "", 0xFF0000);
			}
			}

			//Variables to control shutter in Webfront
						foreach($this->variables as $var){
				switch($var["type"]) {
					case "bool":
						$this->RegisterVariableBoolean($var["name"], $this->Translate($var["name"]),"DWIPS.Shutter.".$this->Translate($var["profile"]), $var["pos"]);
						$this->EnableAction($var["name"]);
						break;
					case "int":
						if(stripos($var["profile"], "_") === 0){
							$this->RegisterVariableInteger($var["name"], $this->Translate($var["name"]),substr($var["profile"], 1), $var["pos"]);
						}else{
							$this->RegisterVariableInteger($var["name"], $this->Translate($var["name"]),"DWIPS.Shutter.".$this->Translate($var["profile"]), $var["pos"]);
						}
						$this->EnableAction($var["name"]);
						break;
					case "float":
						$this->RegisterVariableFloat($var["name"], $this->Translate($var["name"]),"DWIPS.Shutter.".$this->Translate($var["profile"]), $var["pos"]);
						$this->EnableAction($var["name"]);
						break;
					case "string":
						$this->RegisterVariableString($var["name"], $this->Translate($var["name"]),"DWIPS.Shutter.".$this->Translate($var["profile"]), $var["pos"]);
						$this->EnableAction($var["name"]);
						break;
					default:
						throw new Exception("Invalid type");
				}
			}

		$this->RegisterTimer(
			"UpdatePosition",
			2000,
			"DWIPSSK_UpdatePosition(".$this->InstanceID.");"
		);
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

	public function RequestAction($Ident, $Value)
	{
		switch ($Ident)
		{
			case "Control":
				$this->ProcessControl($Ident, $Value);
				break;
			case "Action":
				$this->ProcessAction($Ident, $Value);
				break;
			case "Stop":
				$this->ProcessStop($Ident, $Value);
				break;
			case "Position":
				$this->ProcessPosition($Ident, $Value);
				break;
			case "Preset1":
				$this->ProcessPreset($Ident, $Value, 1);
				break;
			case "Preset2":
				$this->ProcessPreset($Ident, $Value, 2);
				break;
			case "Preset3":
				$this->ProcessPreset($Ident, $Value, 3);
				break;
			case "Preset4":
				$this->ProcessPreset($Ident, $Value, 4);
				break;
			case "DrivingTime":
				$this->ProcessDrivingTime($Ident, $Value);
				break;
			case "AutomationMorningOnOff":
				$this->ProcessAutomationOnOff($Ident, $Value);
				break;
			case "AutomationEveningOnOff":
				$this->ProcessAutomationOnOff($Ident, $Value);
				break;
			default:
				throw new Exception("Invalid Ident");
		}
	}

	private function ProcessControl($Ident, $Value)
	{
		switch ($Value){
			case 0:
				$this->SetValue($Ident, $Value);
				KNX_WriteDPT5($this->ReadPropertyInteger("PositionID"), 100);
				break;
			case 4:
				$this->SetValue($Ident, $Value);
				KNX_WriteDPT5($this->ReadPropertyInteger("PositionID"), $this->ReadPropertyInteger("Value90"));
				break;
			case 5:
				$this->SetValue($Ident, $Value);
				KNX_WriteDPT5($this->ReadPropertyInteger("PositionID"), $this->ReadPropertyInteger("Value75"));
				break;
			case 6:
				$this->SetValue($Ident, $Value);
				KNX_WriteDPT5($this->ReadPropertyInteger("PositionID"), $this->ReadPropertyInteger("Value50"));
				break;
			case 7:
				$this->SetValue($Ident, $Value);
				KNX_WriteDPT5($this->ReadPropertyInteger("PositionID"), $this->ReadPropertyInteger("Value25"));
				break;
			case 8:
				$this->SetValue($Ident, $Value);
				KNX_WriteDPT5($this->ReadPropertyInteger("PositionID"), 0);
				break;
			case 10:
				break;
			case 11:
				$this->SetValue($Ident, $Value);
				$this->SetValue("Action", 0);
				KNX_WriteDPT1($this->ReadPropertyInteger("UpDownID"), 0);
				break;
			case 13:
				$this->SetValue($Ident, $Value);
				$this->SetValue("Action", 1);
				$this->SetValue("Stop", 1);
				KNX_WriteDPT1($this->ReadPropertyInteger("StopID"), 1);
				break;
			case 14:
				$this->SetValue($Ident, $Value);
				$this->SetValue("Action", 2);
				KNX_WriteDPT1($this->ReadPropertyInteger("UpDownID"), 1);
				break;
				break;
			default:

		}
	}

	private function ProcessAction($Ident, $Value){
		$this->SetValue($Ident, $Value);
		switch ($Value)
		{
			case 0:
				$this->SetValue("Control", 11);
				KNX_WriteDPT1($this->ReadPropertyInteger("UpDownID"), 0);
				break;
			case 1:
				$this->SetValue("Control", 13);
				$this->SetValue("Stop", 1);
				KNX_WriteDPT1($this->ReadPropertyInteger("StopID"), 0);
				break;
			case 2:
				$this->SetValue("Control", 14);
				KNX_WriteDPT1($this->ReadPropertyInteger("UpDownID"), 1);
				break;
			default:
			throw new Exception("Invalid Value for Variable " . GetIDForIdent($Ident));
		}
	}

	private function ProcessStop($Ident, $Value){
		$this->SetValue($Ident, 1);
		switch ($Value)
		{
			case 1:
				$this->SetValue("Control", 13);
				$this->SetValue("Action", 1);
				KNX_WriteDPT1($this->ReadPropertyInteger("StopID"), 1);
				break;
			case 1:
				$this->SetValue("Control", 13);
				$this->SetValue("Action", 1);
				KNX_WriteDPT1($this->ReadPropertyInteger("StopID"), 1);
				break;
			default:
			throw new Exception("Invalid Value for Variable " . GetIDForIdent($Ident));
		}
	}

	private function ProcessPosition($Ident, $Value){
		$this->SetValue($Ident, $Value);
		KNX_WriteDPT5($this->ReadPropertyInteger("PositionID"), $Value);
	}

	private function ProcessPreset($Ident, $Value, $Preset){
		$this->SetValue($Ident, $Value);
		if ($Value == 1)
		{
			switch ($Preset)
			{
				case 1:
					KNX_WriteDPT1($this->ReadPropertyInteger("Preset12SetID"), 0);
					$this->SetValue("Preset1Value", GetValue(IPS_GetVariableIDByName("Wert",$this->ReadPropertyInteger("PositionRMID"))));
					break;
				case 2:
					KNX_WriteDPT1($this->ReadPropertyInteger("Preset12SetID"), 1);
					$this->SetValue("Preset2Value", GetValue(IPS_GetVariableIDByName("Wert",$this->ReadPropertyInteger("PositionRMID"))));
					break;
				case 3:
					KNX_WriteDPT1($this->ReadPropertyInteger("Preset34SetID"), 0);
					$this->SetValue("Preset3Value", GetValue(IPS_GetVariableIDByName("Wert",$this->ReadPropertyInteger("PositionRMID"))));
					break;
				case 4:
					KNX_WriteDPT1($this->ReadPropertyInteger("Preset34SetID"), 1);
					$this->SetValue("Preset4Value", GetValue(IPS_GetVariableIDByName("Wert",$this->ReadPropertyInteger("PositionRMID"))));
					break;
			}
		}elseif($Value == 2){
			switch ($Preset)
			{
				case 1:
					KNX_WriteDPT1($this->ReadPropertyInteger("Preset12ExID"), 0);
					break;
				case 2:
					KNX_WriteDPT1($this->ReadPropertyInteger("Preset12ExID"), 1);
					break;
				case 3:
					KNX_WriteDPT1($this->ReadPropertyInteger("Preset34ExID"), 0);
					break;
				case 4:
					KNX_WriteDPT1($this->ReadPropertyInteger("Preset34ExID"), 1);
					break;
			}

		}
		IPS_Sleep(500);
		$this->SetValue($Ident, 0);

	}

	private function ProcessDrivingTime($Ident, $Value){
		$this->SetValue($Ident, $Value);
		KNX_WriteDPT1($this->ReadPropertyInteger("DrivingTimeID"), $Value);
		IPS_Sleep(500);
		$this->SetValue($Ident, 0);

	}

	private function ProcessAutomationOnOff($Ident, $Value){
		$this->SetValue($Ident, !($Value));
		if ($Value)
		{
			IPS_SetVariableCustomProfile($this->GetIDForIdent($Ident), "DWIPS.Shutter." . $this->Translate("SwitchNotActive"));
		}else{
			IPS_SetVariableCustomProfile($this->GetIDForIdent($Ident), "DWIPS.Shutter." . $this->Translate("SwitchActive"));
		}
	}

	public function UpdatePosition(){
		$this->SetValue("Position", GetValue(IPS_GetVariableIDByName("Wert",$this->ReadPropertyInteger("PositionRMID"))));
		$this->SetValue("PositionSteps", GetValue(IPS_GetVariableIDByName("Wert",$this->ReadPropertyInteger("PositionRMID"))));
	}
}