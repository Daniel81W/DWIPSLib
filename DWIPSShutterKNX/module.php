<?php

	//include_once("/var/lib/symcon/modules/DWIPSLib/libs/astro.php");
	class DWIPSShutterKNX extends IPSModule {

		private $parentID = "{1C902193-B044-43B8-9433-419F09C641B8}";
		private $properties = [
			0 => ["name" => "UpDown"],
			1 => ["name" => "Position"],
			2 => ["name" => "Preset12Set"],
			3 => ["name" => "Preset12Ex"],
			4 => ["name" => "Preset34Set"],
			5 => ["name" => "Preset34Ex"],
			6 => ["name" => "DrivingTime"]
		];
		private $variables = [
			0 => ["name" => "Action", "type" => "int", "pos" => 1],
			1 => ["name" => "Position", "type" => "int", "pos" => 2]
		];


		public function Create()
		{
			//Never delete this line!
			parent::Create();

			//Connect to EIBGateway
			$this->ConnectParent($this->parentID);
		
			//Register Properties for KNX group addresses
			foreach($this->properties as $prop){
				$this->RegisterPropertyInteger($prop["name"]."MainGroup", 0);
				$this->RegisterPropertyInteger($prop["name"]."MiddleGroup", 0);
				$this->RegisterPropertyInteger($prop["name"]."SubGroup", 0);
				$this->RegisterPropertyInteger($prop["name"]."DataPointType", 1);
				$this->RegisterPropertyInteger($prop["name"]."DataPointSubType", 1);
			}
			
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
			if (! IPS_VariableProfileExists($this->Translate("DWIPS.Shutter.PositionSteps"))) {
    			IPS_CreateVariableProfile($this->Translate("DWIPS.Shutter.PositionSteps"), 1);
				IPS_SetVariableProfileText($this->Translate("DWIPS.Shutter.PositionSteps"), "", "%");
				IPS_SetVariableProfileValues($this->Translate("DWIPS.Shutter.PositionSteps"), 0, 100, 2);
				IPS_SetVariableProfileAssociation($this->Translate("DWIPS.Shutter.PositionSteps"), 0, "", "", -1);
				IPS_SetVariableProfileAssociation($this->Translate("DWIPS.Shutter.PositionSteps"), 20, "", "", -1);
				IPS_SetVariableProfileAssociation($this->Translate("DWIPS.Shutter.PositionSteps"), 40, "", "", -1);
				IPS_SetVariableProfileAssociation($this->Translate("DWIPS.Shutter.PositionSteps"), 60, "", "", -1);
				IPS_SetVariableProfileAssociation($this->Translate("DWIPS.Shutter.PositionSteps"), 80, "", "", -1);
				IPS_SetVariableProfileAssociation($this->Translate("DWIPS.Shutter.PositionSteps"), 100, "", "", -1);
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
			
			foreach($this->variables as $var){
				switch($var["type"]) {
					case "bool":
						break;
					case "int":
						$this->RegisterVariableInteger($var["name"], $this->Translate($var["name"]),""/*$this->Translate($field["name"])*/, $var["pos"]);
						$this->EnableAction($var["name"]);
						break;
					case "float":
						break;
					case "string":
						break;
					default:
						throw new Exception("Invalid type");
				}
			}
	/*		
			$this->RegisterVariableInteger($this->Translate("PositionSteps"), $this->Translate("PositionSteps"),$this->Translate("DWIPS.Shutter.PositionSteps"), 3);
			$this->EnableAction($this->Translate("PositionSteps"));
			$this->RegisterVariableInteger("Preset1", "Preset 1", "DWIPS.Shutter.Preset", 4);
			$this->EnableAction("Preset1");
			$this->RegisterVariableInteger("Preset2", "Preset 2", "DWIPS.Shutter.Preset", 5);
			$this->EnableAction("Preset2");
			$this->RegisterVariableInteger("Preset3", "Preset 3", "DWIPS.Shutter.Preset", 6);
			$this->EnableAction("Preset3");
			$this->RegisterVariableInteger("Preset4", "Preset 4", "DWIPS.Shutter.Preset", 7);
			$this->EnableAction("Preset4");
			$this->RegisterVariableBoolean($this->Translate("DrivingTime"), $this->Translate("DrivingTime"), $this->Translate("DWIPS.Shutter.Trigger"), 8);
			$this->EnableAction($this->Translate("DrivingTime"));

			*/
			$this->RegisterVariableString("Test", "Test", "", 0);
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
			//$this->TestForm();

		}

		public function ReceiveData($JSONString) {
			$knxdata = json_decode($JSONString, true);
			if($knxdata["DataID"] == "{8A4D3B17-F8D7-4905-877F-9E69CEC3D579}"){
				if($knxdata["GroupAddress1"] == $this->ReadPropertyInteger("Hauptgruppe") and $knxdata["GroupAddress2"] == $this->ReadPropertyInteger("Mittelgruppe") and $knxdata["GroupAddress3"] == $this->ReadPropertyInteger("Untergruppe")){
					$hexval = bin2hex($knxdata["Data"]);
					$hexval = substr($hexval, 0);

					$Val = unpack( 'H*', $knxdata["Data"], 0 );
          			$result = intval( round( $Val[ 1 ] / 255 * 100 ) );
					$this->SendDebug("KNX", sizeof($Val), 0);
					$this->SendDebug("KNX", $Val[1], 0);
					//$this->SendDebug("KNX", $hexval, 0);
					//$this->SendDebug("KNX", bin2hex(pack( "CC", 0x80, 200 )), 0);

					SetValueInteger($this->GetIDForIdent($this->Translate("Position")), hexdec($hexval));
				}
			}
		}
		/**
        * Die folgenden Funktionen stehen automatisch zur Verfügung, wenn das Modul über die "Module Control" eingefügt wurden.
        * Die Funktionen werden, mit dem selbst eingerichteten Prefix, in PHP und JSON-RPC wiefolgt zur Verfügung gestellt:
        *
        * DWIPSShutter_UpdatePositionValue($id);
        *
        */
/*		public function UpdatePositionValue($Position){
			SetValue($this->GetIDForIdent($this->Translate("Position")), $Position);
		}

		public function UpdateActionValue($Sender, $Value){
			if($Sender == $this->ReadPropertyInteger("UpDownInstanceID")){
				if($Value = 0){
					SetValue($this->GetIDForIdent($this->Translate("Action")), 0);
				}else{
					SetValue($this->GetIDForIdent($this->Translate("Action")), 2);
				}
			}elseif ($Sender == $this->ReadPropertyInteger("StopInstanceID")) {
				SetValue($this->GetIDForIdent($this->Translate("Action")), 1);
			}
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
					
				case $this->Translate("PositionSteps"):
					SetValue($this->GetIDForIdent($Ident), $Value);
					//KNX oder EIB
					if(IPS_GetInstance($this->ReadPropertyInteger("PositionInstanceID"))['ModuleInfo']['ModuleName'] == "KNX DPT 5"){
						KNX_WriteDPT5($this->ReadPropertyInteger("PositionInstanceID"), $Value);
					}elseif(IPS_GetInstance($this->ReadPropertyInteger("PositionInstanceID"))['ModuleInfo']['ModuleName'] == "EIB Group"){
						EIB_Scale($this->ReadPropertyInteger("PositionInstanceID"), $Value);
					}
					break;

				case "Preset1":
					SetValue($this->GetIDForIdent($Ident), $Value);
					if($Value == 1){
						//KNX oder EIB
						if(IPS_GetInstance($this->ReadPropertyInteger("Preset12SetInstanceID"))['ModuleInfo']['ModuleName'] == "KNX DPT 1"){
							KNX_WriteDPT1($this->ReadPropertyInteger("Preset12SetInstanceID"), 0);
						}elseif(IPS_GetInstance($this->ReadPropertyInteger("Preset12SetInstanceID"))['ModuleInfo']['ModuleName'] == "EIB Group"){
							EIB_Switch($this->ReadPropertyInteger("Preset12SetInstanceID"), false);
						}
					}elseif($Value == 2){
						//KNX oder EIB
						if(IPS_GetInstance($this->ReadPropertyInteger("Preset12ExInstanceID"))['ModuleInfo']['ModuleName'] == "KNX DPT 1"){
							KNX_WriteDPT1($this->ReadPropertyInteger("Preset12ExInstanceID"), 0);
						}elseif(IPS_GetInstance($this->ReadPropertyInteger("Preset12ExInstanceID"))['ModuleInfo']['ModuleName'] == "EIB Group"){
							EIB_Switch($this->ReadPropertyInteger("Preset12ExInstanceID"), false);
						}
					}
					IPS_Sleep(2000);
					SetValue($this->GetIDForIdent($Ident), 0);
					break;

				case "Preset2":
					SetValue($this->GetIDForIdent($Ident), $Value);
					if($Value == 1){
						//KNX oder EIB
						if(IPS_GetInstance($this->ReadPropertyInteger("Preset12SetInstanceID"))['ModuleInfo']['ModuleName'] == "KNX DPT 1"){
							KNX_WriteDPT1($this->ReadPropertyInteger("Preset12SetInstanceID"), 1);
						}elseif(IPS_GetInstance($this->ReadPropertyInteger("Preset12SetInstanceID"))['ModuleInfo']['ModuleName'] == "EIB Group"){
							EIB_Switch($this->ReadPropertyInteger("Preset12SetInstanceID"), true);
						}
					}elseif($Value == 2){
						//KNX oder EIB
						if(IPS_GetInstance($this->ReadPropertyInteger("Preset12ExInstanceID"))['ModuleInfo']['ModuleName'] == "KNX DPT 1"){
							KNX_WriteDPT1($this->ReadPropertyInteger("Preset12ExInstanceID"), 1);
						}elseif(IPS_GetInstance($this->ReadPropertyInteger("Preset12ExInstanceID"))['ModuleInfo']['ModuleName'] == "EIB Group"){
							EIB_Switch($this->ReadPropertyInteger("Preset12ExInstanceID"), true);
						}
					}
					IPS_Sleep(2000);
					SetValue($this->GetIDForIdent($Ident), 0);	
					break;

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
		 
		}*/

		public function GetConfigurationForm() {
		//public function TestForm() {

			$elements = '';
			$actions = '';
			$status = '';

			foreach($this->properties as $prop){
				if (strlen($elements) >0){ $elements = $elements . ',';}
				$elements = $elements . '{ "type": "RowLayout","items": [ ' .
						'{"type": "Label","caption": "' . $prop["name"] . '","width": "100px"},'.
						'{"type": "NumberSpinner","name": "' . $prop["name"] . 'MainGroup","caption": "MainGroup","minimum": 0,"maximum": 255,"width": "100px"},'.
						'{"type": "NumberSpinner","name": "' . $prop["name"] . 'MiddleGroup","caption": "MiddleGroup","minimum": 0,"maximum": 255,"width": "100px"},'.
						'{"type": "NumberSpinner","name": "' . $prop["name"] . 'SubGroup","caption": "SubGroup","minimum": 0,"maximum": 255,"width": "100px"},'.
						'{"type": "Select","name": "' . $prop["name"] . 'DataPointType","caption": "DataPointType",'.
							'"options": ['.
								'{ "value": 1, "caption": "DPT 1"},'.
								'{ "value": 2, "caption": "DPT 2"},'.
								'{ "value": 3, "caption": "DPT 3"},'.
								'{ "value": 4, "caption": "DPT 4"},'.
								'{ "value": 5, "caption": "DPT 5"},'.
								'{ "value": 6, "caption": "DPT 6"},'.
								'{ "value": 7, "caption": "DPT 7"},'.
								'{ "value": 8, "caption": "DPT 8"},'.
								'{ "value": 9, "caption": "DPT 9"},'.
								'{ "value": 10, "caption": "DPT 10"}'.
							']'.
						'},'.
						'{"type": "Select","name": "' . $prop["name"] . 'DataPointSubType","caption": "DataPointSubType",'.
							'"options": ['.
								'{ "value": 1, "caption": "DPT 1"}'.
							']'.
						'}'.
					']}';
			}
			

			$ret = '{"elements": [' . $elements . '],"actions": [' . $actions . '],"status": [' . $status . ']}';
			$this->SendDebug("KNX", $ret, 0);
			//return $ret;
		}

		
		private $dpts = [
			1 => ["name" => "DPT 1", "descript" => "int", "sub" => 
				[ "name" => "1.001", "descript" => "int" ],
				[ "name" => "1.002", "descript" => "int" ]
			],
			2 => ["name" => "DPT 2", "descript" => "int", "sub" => 
				[ "name" => "2.001", "descript" => "int" ]
			]
		];
	}
	?>