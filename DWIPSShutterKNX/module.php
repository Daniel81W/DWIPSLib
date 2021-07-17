<?php

	//include_once("/var/lib/symcon/modules/DWIPSLib/libs/astro.php");
	class DWIPSShutterKNX extends IPSModule {

		private $parentID = "{1C902193-B044-43B8-9433-419F09C641B8}";

		private $properties = [
			0 => ["name" => "UpDown"],
			1 => ["name" => "Stop"],
			2 => ["name" => "Position"],
			3 => ["name" => "Preset12Set"],
			4 => ["name" => "Preset12Ex"],
			5 => ["name" => "Preset34Set"],
			6 => ["name" => "Preset34Ex"],
			7 => ["name" => "DrivingTime"]
		];
		private $variables = [
			0 => ["name" => "Action", "type" => "int", "pos" => 1, "profile" => "UpDownStop"],
			1 => ["name" => "Position", "type" => "int", "pos" => 2, "profile" => "Position"],
			2 => ["name" => "PositionSteps", "type" => "int", "pos" => 3, "profile" => "PositionSteps"],
			3 => ["name" => "Preset1", "type" => "int", "pos" => 4, "profile" => "Preset"],
			4 => ["name" => "Preset2", "type" => "int", "pos" => 5, "profile" => "Preset"],
			5 => ["name" => "Preset3", "type" => "int", "pos" => 6, "profile" => "Preset"],
			6 => ["name" => "Preset4", "type" => "int", "pos" => 7, "profile" => "Preset"],
			7 => ["name" => "DrivingTime", "type" => "bool", "pos" => 8, "profile" => "TriggerPro"]
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
			if (! IPS_VariableProfileExists($this->Translate("DWIPS.Shutter.TriggerPro"))) {
    			IPS_CreateVariableProfile($this->Translate("DWIPS.Shutter.TriggerPro"), 0);
				IPS_SetVariableProfileAssociation($this->Translate("DWIPS.Shutter.TriggerPro"), 1, $this->Translate("Trigger"), "", 0x00FF00);
			}

			//Variables to control shutter in Webfront
			
			foreach($this->variables as $var){
				switch($var["type"]) {
					case "bool":
						$this->RegisterVariableBoolean($var["name"], $this->Translate($var["name"]),"DWIPS.Shutter.".$this->Translate($var["profile"]), $var["pos"]);
						$this->EnableAction($var["name"]);
						break;
					case "int":
						$this->RegisterVariableInteger($var["name"], $this->Translate($var["name"]),"DWIPS.Shutter.".$this->Translate($var["profile"]), $var["pos"]);
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
				if($knxdata["GroupAddress1"] == $this->ReadPropertyInteger("UpDownMainGroup") and $knxdata["GroupAddress2"] == $this->ReadPropertyInteger("UpDownMiddleGroup") and $knxdata["GroupAddress3"] == $this->ReadPropertyInteger("UpDownSubGroup")){
					$val = $this->DecodeDPT1($knxdata["Data"]);
					SetValueInteger($this->GetIDForIdent("Action"), $val * 2);
					/*if($val == 0){
						SetValueInteger($this->GetIDForIdent("Action"), 0);
					}elseif($val == 1){
						SetValueInteger($this->GetIDForIdent("Action"), 2);
						$this->SendDebug("KNX", $val, 0);
					}*/
					
					/*$hexval = bin2hex($knxdata["Data"]);
					$decval = hexdec( $hexval) - hexdec("c280");

					$Val = unpack( 'H*', $knxdata["Data"], 0 );
          			$result = intval( round( $Val[ 1 ] / 255 * 100 ) );
					//$this->SendDebug("KNX", sizeof($Val), 0);
					//$this->SendDebug("KNX", $Val[1], 0);
					$this->SendDebug("KNX", $decval, 0);
					//$this->SendDebug("KNX", bin2hex(pack( "CC", 0x80, 200 )), 0);

					SetValueInteger($this->GetIDForIdent($this->Translate("Position")), hexdec($hexval));*/
				}elseif($knxdata["GroupAddress1"] == $this->ReadPropertyInteger("StopMainGroup") and $knxdata["GroupAddress2"] == $this->ReadPropertyInteger("StopMiddleGroup") and $knxdata["GroupAddress3"] == $this->ReadPropertyInteger("StopSubGroup")){
					$val = $this->DecodeDPT1($knxdata["Data"]);
					SetValueInteger($this->GetIDForIdent("Action"), $val);
				}elseif($knxdata["GroupAddress1"] == $this->ReadPropertyInteger("DrivingTimeMainGroup") and $knxdata["GroupAddress2"] == $this->ReadPropertyInteger("DrivingTimeMiddleGroup") and $knxdata["GroupAddress3"] == $this->ReadPropertyInteger("DrivingTimeSubGroup")){
					$val = $this->DecodeDPT1($knxdata["Data"]);
					SetValueInteger($this->GetIDForIdent("Action"), $val);
				}				
			}
		}

		public function DecodeDPT1($data){
			$val = bin2hex($data);
			$val = hexdec( $val) - hexdec("c280");
			return $val;
		}
		
		public function EncodeDPT1($value){
			
			$val = dechex( $val + hexdec("c280"));
			return $val;
		}

		public function DecodeDPT5($data){
			$val = bin2hex($data);
			$val = hexdec( $val) - hexdec("c280");
			return $val;
		}
		public function EncodeDPT5($data){
			
		}
		/**
        * Die folgenden Funktionen stehen automatisch zur Verfügung, wenn das Modul über die "Module Control" eingefügt wurden.
        * Die Funktionen werden, mit dem selbst eingerichteten Prefix, in PHP und JSON-RPC wiefolgt zur Verfügung gestellt:
        *
        * DWIPSShutter_UpdatePositionValue($id);
        *
        */

		public function RequestAction($Ident, $Value) {
 
			switch($Ident) {
				case "Action":
					SetValue($this->GetIDForIdent($Ident), $Value);
					if($Value == 0){
						$json = [ 
							"DataID" => "{42DFD4E4-5831-4A27-91B9-6FF1B2960260}",
							"GroupAddress1" => $this->ReadPropertyInteger("UpDownMainGroup"),
							"GroupAddress2" => $this->ReadPropertyInteger("UpDownMiddleGroup"),
							"GroupAddress3" => $this->ReadPropertyInteger("UpDownSubGroup"),
							"Data" => $this->EncodeDPT1(0)// hex2bin("c280")
						];
						$this->SendDataToParent(json_encode($json));
					}elseif($Value == 1){
						$json = [ 
							"DataID" => "{42DFD4E4-5831-4A27-91B9-6FF1B2960260}",
							"GroupAddress1" => $this->ReadPropertyInteger("StopMainGroup"),
							"GroupAddress2" => $this->ReadPropertyInteger("StopMiddleGroup"),
							"GroupAddress3" => $this->ReadPropertyInteger("StopSubGroup"),
							"Data" => hex2bin("c281")
						];
						$this->SendDataToParent(json_encode($json));
					}elseif($Value == 2){
						$json = [ 
							"DataID" => "{42DFD4E4-5831-4A27-91B9-6FF1B2960260}",
							"GroupAddress1" => $this->ReadPropertyInteger("UpDownMainGroup"),
							"GroupAddress2" => $this->ReadPropertyInteger("UpDownMiddleGroup"),
							"GroupAddress3" => $this->ReadPropertyInteger("UpDownSubGroup"),
							"Data" => $this->EncodeDPT1(1)//hex2bin("c281")
						];
						$this->SendDataToParent(json_encode($json));
					}
					break;
				case "Preset1":
					SetValue($this->GetIDForIdent($Ident), $Value);
					if($Value == 1){
						$json = [ 
							"DataID" => "{42DFD4E4-5831-4A27-91B9-6FF1B2960260}",
							"GroupAddress1" => $this->ReadPropertyInteger("Preset12SetMainGroup"),
							"GroupAddress2" => $this->ReadPropertyInteger("Preset12SetMiddleGroup"),
							"GroupAddress3" => $this->ReadPropertyInteger("Preset12SetSubGroup"),
							"Data" => hex2bin("c280")
						];
						$this->SendDataToParent(json_encode($json));
						IPS_Sleep(2000);
						SetValue($this->GetIDForIdent($Ident), 0);
					}elseif($Value == 2){
						$json = [ 
							"DataID" => "{42DFD4E4-5831-4A27-91B9-6FF1B2960260}",
							"GroupAddress1" => $this->ReadPropertyInteger("Preset12ExMainGroup"),
							"GroupAddress2" => $this->ReadPropertyInteger("Preset12ExMiddleGroup"),
							"GroupAddress3" => $this->ReadPropertyInteger("Preset12ExSubGroup"),
							"Data" => hex2bin("c280")
						];
						$this->SendDataToParent(json_encode($json));
						IPS_Sleep(2000);
						SetValue($this->GetIDForIdent($Ident), 0);
					}
					break;
				case "Preset2":
					SetValue($this->GetIDForIdent($Ident), $Value);
					if($Value == 1){
						$json = [ 
							"DataID" => "{42DFD4E4-5831-4A27-91B9-6FF1B2960260}",
							"GroupAddress1" => $this->ReadPropertyInteger("Preset12SetMainGroup"),
							"GroupAddress2" => $this->ReadPropertyInteger("Preset12SetMiddleGroup"),
							"GroupAddress3" => $this->ReadPropertyInteger("Preset12SetSubGroup"),
							"Data" => hex2bin("c281")
						];
						$this->SendDataToParent(json_encode($json));
						IPS_Sleep(2000);
						SetValue($this->GetIDForIdent($Ident), 0);
					}elseif($Value == 2){
						$json = [ 
							"DataID" => "{42DFD4E4-5831-4A27-91B9-6FF1B2960260}",
							"GroupAddress1" => $this->ReadPropertyInteger("Preset12ExMainGroup"),
							"GroupAddress2" => $this->ReadPropertyInteger("Preset12ExMiddleGroup"),
							"GroupAddress3" => $this->ReadPropertyInteger("Preset12ExSubGroup"),
							"Data" => hex2bin("c281")
						];
						$this->SendDataToParent(json_encode($json));
						IPS_Sleep(2000);
						SetValue($this->GetIDForIdent($Ident), 0);
					}
					break;
				case "Preset3":
					SetValue($this->GetIDForIdent($Ident), $Value);
					if($Value == 1){
						$json = [ 
							"DataID" => "{42DFD4E4-5831-4A27-91B9-6FF1B2960260}",
							"GroupAddress1" => $this->ReadPropertyInteger("Preset34SetMainGroup"),
							"GroupAddress2" => $this->ReadPropertyInteger("Preset34SetMiddleGroup"),
							"GroupAddress3" => $this->ReadPropertyInteger("Preset34SetSubGroup"),
							"Data" => hex2bin("c280")
						];
						$this->SendDataToParent(json_encode($json));
						IPS_Sleep(2000);
						SetValue($this->GetIDForIdent($Ident), 0);
					}elseif($Value == 2){
						$json = [ 
							"DataID" => "{42DFD4E4-5831-4A27-91B9-6FF1B2960260}",
							"GroupAddress1" => $this->ReadPropertyInteger("Preset34ExMainGroup"),
							"GroupAddress2" => $this->ReadPropertyInteger("Preset34ExMiddleGroup"),
							"GroupAddress3" => $this->ReadPropertyInteger("Preset34ExSubGroup"),
							"Data" => hex2bin("c280")
						];
						$this->SendDataToParent(json_encode($json));
						IPS_Sleep(2000);
						SetValue($this->GetIDForIdent($Ident), 0);
					}
					break;
				case "Preset4":
					SetValue($this->GetIDForIdent($Ident), $Value);
					if($Value == 1){
						$json = [ 
							"DataID" => "{42DFD4E4-5831-4A27-91B9-6FF1B2960260}",
							"GroupAddress1" => $this->ReadPropertyInteger("Preset34SetMainGroup"),
							"GroupAddress2" => $this->ReadPropertyInteger("Preset34SetMiddleGroup"),
							"GroupAddress3" => $this->ReadPropertyInteger("Preset34SetSubGroup"),
							"Data" => hex2bin("c281")
						];
						$this->SendDataToParent(json_encode($json));
						IPS_Sleep(2000);
						SetValue($this->GetIDForIdent($Ident), 0);
					}elseif($Value == 2){
						$json = [ 
							"DataID" => "{42DFD4E4-5831-4A27-91B9-6FF1B2960260}",
							"GroupAddress1" => $this->ReadPropertyInteger("Preset34ExMainGroup"),
							"GroupAddress2" => $this->ReadPropertyInteger("Preset34ExMiddleGroup"),
							"GroupAddress3" => $this->ReadPropertyInteger("Preset34ExSubGroup"),
							"Data" => hex2bin("c281")
						];
						$this->SendDataToParent(json_encode($json));
						IPS_Sleep(2000);
						SetValue($this->GetIDForIdent($Ident), 0);
					}
					break;
				case "DrivingTime":
					SetValue($this->GetIDForIdent($Ident), $Value);
					if($Value == 1){
						$json = [ 
							"DataID" => "{42DFD4E4-5831-4A27-91B9-6FF1B2960260}",
							"GroupAddress1" => $this->ReadPropertyInteger("DrivingTimeMainGroup"),
							"GroupAddress2" => $this->ReadPropertyInteger("DrivingTimeMiddleGroup"),
							"GroupAddress3" => $this->ReadPropertyInteger("DrivingTimeSubGroup"),
							"Data" => hex2bin("c281")
						];
						$this->SendDataToParent(json_encode($json));
						IPS_Sleep(2000);
						SetValue($this->GetIDForIdent($Ident), 0);
					}
					break;
				default:
					throw new Exception("Invalid Ident");
			}
		 
		}

		public function GetConfigurationForm() {
		

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
			17 => [
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
			],
			202 => [
				"name" => "DPT 202",
				"descript" => "int",
				"value" => 202,
				"sub" => [ 
					1 => [
						"name" => "202.001", 
						"descript" => "int" 
					]
				]
			],
			203 => [
				"name" => "DPT 203",
				"descript" => "int",
				"value" => 203,
				"sub" => [ 
					1 => [
						"name" => "203.001", 
						"descript" => "int" 
					]
				]
			],
			204 => [
				"name" => "DPT 204",
				"descript" => "int",
				"value" => 204,
				"sub" => [ 
					1 => [
						"name" => "204.001", 
						"descript" => "int" 
					]
				]
			],
			205 => [
				"name" => "DPT 205",
				"descript" => "int",
				"value" => 205,
				"sub" => [ 
					1 => [
						"name" => "205.001", 
						"descript" => "int" 
					]
				]
			],
			206 => [
				"name" => "DPT 206",
				"descript" => "int",
				"value" => 206,
				"sub" => [ 
					1 => [
						"name" => "206.001", 
						"descript" => "int" 
					]
				]
			],
			207 => [
				"name" => "DPT 207",
				"descript" => "int",
				"value" => 207,
				"sub" => [ 
					1 => [
						"name" => "207.001", 
						"descript" => "int" 
					]
				]
			],
			209 => [
				"name" => "DPT 209",
				"descript" => "int",
				"value" => 209,
				"sub" => [ 
					1 => [
						"name" => "209.001", 
						"descript" => "int" 
					]
				]
			],
			210 => [
				"name" => "DPT 210",
				"descript" => "int",
				"value" => 210,
				"sub" => [ 
					1 => [
						"name" => "210.001", 
						"descript" => "int" 
					]
				]
			],
			211 => [
				"name" => "DPT 211",
				"descript" => "int",
				"value" => 211,
				"sub" => [ 
					1 => [
						"name" => "211.001", 
						"descript" => "int" 
					]
				]
			],
			212 => [
				"name" => "DPT 212",
				"descript" => "int",
				"value" => 212,
				"sub" => [ 
					1 => [
						"name" => "212.001", 
						"descript" => "int" 
					]
				]
			],
			213 => [
				"name" => "DPT 213",
				"descript" => "int",
				"value" => 213,
				"sub" => [ 
					1 => [
						"name" => "213.001", 
						"descript" => "int" 
					]
				]
			],
			214 => [
				"name" => "DPT 214",
				"descript" => "int",
				"value" => 214,
				"sub" => [ 
					1 => [
						"name" => "214.001", 
						"descript" => "int" 
					]
				]
			],
			215 => [
				"name" => "DPT 215",
				"descript" => "int",
				"value" => 215,
				"sub" => [ 
					1 => [
						"name" => "215.001", 
						"descript" => "int" 
					]
				]
			],
			216 => [
				"name" => "DPT 216",
				"descript" => "int",
				"value" => 216,
				"sub" => [ 
					1 => [
						"name" => "216.001", 
						"descript" => "int" 
					]
				]
			],
			217 => [
				"name" => "DPT 217",
				"descript" => "int",
				"value" => 217,
				"sub" => [ 
					1 => [
						"name" => "217.001", 
						"descript" => "int" 
					]
				]
			],
			218 => [
				"name" => "DPT 218",
				"descript" => "int",
				"value" => 218,
				"sub" => [ 
					1 => [
						"name" => "218.001", 
						"descript" => "int" 
					]
				]
			],
			219 => [
				"name" => "DPT 219",
				"descript" => "int",
				"value" => 219,
				"sub" => [ 
					1 => [
						"name" => "219.001", 
						"descript" => "int" 
					]
				]
			],
			220 => [
				"name" => "DPT 220",
				"descript" => "int",
				"value" => 220,
				"sub" => [ 
					1 => [
						"name" => "220.001", 
						"descript" => "int" 
					]
				]
			],
			221 => [
				"name" => "DPT 221",
				"descript" => "int",
				"value" => 221,
				"sub" => [ 
					1 => [
						"name" => "221.001", 
						"descript" => "int" 
					]
				]
			],
			222 => [
				"name" => "DPT 222",
				"descript" => "int",
				"value" => 222,
				"sub" => [ 
					1 => [
						"name" => "222.001", 
						"descript" => "int" 
					]
				]
			],
			223 => [
				"name" => "DPT 223",
				"descript" => "int",
				"value" => 223,
				"sub" => [ 
					1 => [
						"name" => "223.001", 
						"descript" => "int" 
					]
				]
			],
			224 => [
				"name" => "DPT 224",
				"descript" => "int",
				"value" => 224,
				"sub" => [ 
					1 => [
						"name" => "224.001", 
						"descript" => "int" 
					]
				]
			],
			225 => [
				"name" => "DPT 225",
				"descript" => "int",
				"value" => 225,
				"sub" => [ 
					1 => [
						"name" => "225.001", 
						"descript" => "int" 
					]
				]
			],
			229 => [
				"name" => "DPT 229",
				"descript" => "int",
				"value" => 229,
				"sub" => [ 
					1 => [
						"name" => "229.001", 
						"descript" => "int" 
					]
				]
			],
			230 => [
				"name" => "DPT 230",
				"descript" => "int",
				"value" => 230,
				"sub" => [ 
					1 => [
						"name" => "230.001", 
						"descript" => "int" 
					]
				]
			],
			231 => [
				"name" => "DPT 231",
				"descript" => "int",
				"value" => 231,
				"sub" => [ 
					1 => [
						"name" => "231.001", 
						"descript" => "int" 
					]
				]
			],
			232 => [
				"name" => "DPT 232",
				"descript" => "int",
				"value" => 232,
				"sub" => [ 
					1 => [
						"name" => "232.001", 
						"descript" => "int" 
					]
				]
			],
			234 => [
				"name" => "DPT 234",
				"descript" => "int",
				"value" => 234,
				"sub" => [ 
					1 => [
						"name" => "234.001", 
						"descript" => "int" 
					]
				]
			],
			235 => [
				"name" => "DPT 235",
				"descript" => "int",
				"value" => 235,
				"sub" => [ 
					1 => [
						"name" => "235.001", 
						"descript" => "int" 
					]
				]
			],
			236 => [
				"name" => "DPT 236",
				"descript" => "int",
				"value" => 236,
				"sub" => [ 
					1 => [
						"name" => "236.001", 
						"descript" => "int" 
					]
				]
			],
			237 => [
				"name" => "DPT 237",
				"descript" => "int",
				"value" => 237,
				"sub" => [ 
					1 => [
						"name" => "237.001", 
						"descript" => "int" 
					]
				]
			],
			238 => [
				"name" => "DPT 238",
				"descript" => "int",
				"value" => 238,
				"sub" => [ 
					1 => [
						"name" => "238.001", 
						"descript" => "int" 
					]
				]
			],
			239 => [
				"name" => "DPT 239",
				"descript" => "int",
				"value" => 239,
				"sub" => [ 
					1 => [
						"name" => "239.001", 
						"descript" => "int" 
					]
				]
			],
			240 => [
				"name" => "DPT 240",
				"descript" => "int",
				"value" => 240,
				"sub" => [ 
					1 => [
						"name" => "240.001", 
						"descript" => "int" 
					]
				]
			],
			241 => [
				"name" => "DPT 241",
				"descript" => "int",
				"value" => 241,
				"sub" => [ 
					1 => [
						"name" => "241.001", 
						"descript" => "int" 
					]
				]
			]
		];
		
	}
	?>