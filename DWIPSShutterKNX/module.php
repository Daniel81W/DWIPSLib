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

		private $dpts = [
			1 => [
				"name" => "DPT 1",
				"descript" => "int",
				"value" => 1, 
				"sub" => [
					1 => [ 
						"name" => "1.001", 
						"descript" => "int"
					],
					2 => [ 
						"name" => "1.002", 
						"descript" => "int" 
					]
				]
			],
			2 => [
				"name" => "DPT 2",
				"descript" => "int",
				"value" => 2,
				"sub" => [ 
					1 => [
						"name" => "2.001", 
						"descript" => "int" 
					]
				]
			],
			3 => [
				"name" => "DPT 3",
				"descript" => "int",
				"value" => 3,
				"sub" => [ 
					1 => [
						"name" => "3.001", 
						"descript" => "int" 
					]
				]
			],
			4 => [
				"name" => "DPT 4",
				"descript" => "int",
				"value" => 4,
				"sub" => [ 
					1 => [
						"name" => "4.001", 
						"descript" => "int" 
					]
				]
			],
			5 => [
				"name" => "DPT 5",
				"descript" => "int",
				"value" => 5,
				"sub" => [ 
					1 => [
						"name" => "5.001", 
						"descript" => "int" 
					]
				]
			],
			6 => [
				"name" => "DPT 6",
				"descript" => "int",
				"value" => 6,
				"sub" => [ 
					1 => [
						"name" => "6.001", 
						"descript" => "int" 
					]
				]
			],
			7 => [
				"name" => "DPT 7",
				"descript" => "int",
				"value" => 7,
				"sub" => [ 
					1 => [
						"name" => "7
						.001", 
						"descript" => "int" 
					]
				]
			],
			8 => [
				"name" => "DPT 8",
				"descript" => "int",
				"value" => 8,
				"sub" => [ 
					1 => [
						"name" => "8.001", 
						"descript" => "int" 
					]
				]
			],
			9 => [
				"name" => "DPT 9",
				"descript" => "int",
				"value" => 9,
				"sub" => [ 
					1 => [
						"name" => "9.001", 
						"descript" => "int" 
					]
				]
			],
			10 => [
				"name" => "DPT 10",
				"descript" => "int",
				"value" => 10,
				"sub" => [ 
					1 => [
						"name" => "10.001", 
						"descript" => "int" 
					]
				]
			],
			11 => [
				"name" => "DPT 11",
				"descript" => "int",
				"value" => 11,
				"sub" => [ 
					1 => [
						"name" => "11.001", 
						"descript" => "int" 
					]
				]
			],
			12 => [
				"name" => "DPT 12",
				"descript" => "int",
				"value" => 12,
				"sub" => [ 
					1 => [
						"name" => "12.001", 
						"descript" => "int" 
					]
				]
			],
			13 => [
				"name" => "DPT 13",
				"descript" => "int",
				"value" => 13,
				"sub" => [ 
					1 => [
						"name" => "13.001", 
						"descript" => "int" 
					]
				]
			],
			14 => [
				"name" => "DPT 14",
				"descript" => "int",
				"value" => 14,
				"sub" => [ 
					1 => [
						"name" => "14.001", 
						"descript" => "int" 
					]
				]
			],
			15 => [
				"name" => "DPT 15",
				"descript" => "int",
				"value" => 15,
				"sub" => [ 
					1 => [
						"name" => "15.001", 
						"descript" => "int" 
					]
				]
			],
			17 => [7
				"name" => "DPT 17",
				"descript" => "int",
				"value" => 17,
				"sub" => [ 
					1 => [
						"name" => "17.001", 
						"descript" => "int" 
					]
				]
			],
			18 => [
				"name" => "DPT 18",
				"descript" => "int",
				"value" => 18,
				"sub" => [ 
					1 => [
						"name" => "18.001", 
						"descript" => "int" 
					]
				]
			],
			19 => [
				"name" => "DPT 19",
				"descript" => "int",
				"value" => 19,
				"sub" => [ 
					1 => [
						"name" => "19.001", 
						"descript" => "int" 
					]
				]
			],
			20 => [
				"name" => "DPT 20",
				"descript" => "int",
				"value" => 20,
				"sub" => [ 
					1 => [
						"name" => "20.001", 
						"descript" => "int" 
					]
				]
			],
			21 => [
				"name" => "DPT 21",
				"descript" => "int",
				"value" => 21,
				"sub" => [ 
					1 => [
						"name" => "21.001", 
						"descript" => "int" 
					]
				]
			],
			22 => [
				"name" => "DPT 22",
				"descript" => "int",
				"value" => 22,
				"sub" => [ 
					1 => [
						"name" => "22.001", 
						"descript" => "int" 
					]
				]
			],
			23 => [
				"name" => "DPT 23",
				"descript" => "int",
				"value" => 23,
				"sub" => [ 
					1 => [
						"name" => "23.001", 
						"descript" => "int" 
					]
				]
			],
			25 => [
				"name" => "DPT 25",
				"descript" => "int",
				"value" => 25,
				"sub" => [ 
					1 => [
						"name" => "25.001", 
						"descript" => "int" 
					]
				]
			],
			26 => [
				"name" => "DPT 26",
				"descript" => "int",
				"value" => 26,
				"sub" => [ 
					1 => [
						"name" => "26.001", 
						"descript" => "int" 
					]
				]
			],
			27 => [
				"name" => "DPT 27",
				"descript" => "int",
				"value" => 27,
				"sub" => [ 
					1 => [
						"name" => "27.001", 
						"descript" => "int" 
					]
				]
			],
			29 => [
				"name" => "DPT 29",
				"descript" => "int",
				"value" => 29,
				"sub" => [ 
					1 => [
						"name" => "29.001", 
						"descript" => "int" 
					]
				]
			],
			30 => [
				"name" => "DPT 30",
				"descript" => "int",
				"value" => 30,
				"sub" => [ 
					1 => [
						"name" => "30.001", 
						"descript" => "int" 
					]
				]
			],
			31 => [
				"name" => "DPT 31",
				"descript" => "int",
				"value" => 31,
				"sub" => [ 
					1 => [
						"name" => "31.001", 
						"descript" => "int" 
					]
				]
			],
			200 => [
				"name" => "DPT 200",
				"descript" => "int",
				"value" => 200,
				"sub" => [ 
					1 => [
						"name" => "200.001", 
						"descript" => "int" 
					]
				]
			],
			201 => [
				"name" => "DPT 201",
				"descript" => "int",
				"value" => 201,
				"sub" => [ 
					1 => [
						"name" => "201.001", 
						"descript" => "int" 
					]
				]
			]
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
							'"options": [';
				$first = true;
				foreach($this->dpts as $dpt){
					if (!$first){ $elements = $elements . ',';}
					$elements = $elements . '{ "value": '. $dpt["value"] . ', "caption": "'. $dpt["name"] . '"}';
					$first = false;
				}
				$elements = $elements . ']'.
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
			return $ret;
		}


	
		
	}
	?>