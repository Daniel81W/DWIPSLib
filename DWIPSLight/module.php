<?php

	//include_once("/var/lib/symcon/modules/DWIPSLib/libs/astro.php");
	class DWIPSLight extends IPSModule {

		public function Create()
		{
			//Never delete this line!
			parent::Create();

			$this->RegisterPropertyInteger("AvailableID", 0);
			$this->RegisterPropertyInteger("OnOffID", 0);
			$this->RegisterPropertyInteger("DimmValueID", 0);
			$this->RegisterPropertyInteger("ColorID", 0);
			
			// $webcat = ;
			// echo $webcat;
			if(@IPS_GetCategoryIDByName("Webfront", $this->InstanceID) === false ){
				$WebfrontCatID = IPS_CreateCategory();       // Kategorie anlegen
				IPS_SetName($WebfrontCatID, "Webfront"); // Kategorie benennen
				IPS_SetParent($WebfrontCatID, $this->InstanceID);
				IPS_SetPosition($WebfrontCatID, 99);
			}
			
			
			
			//IPS_SetParent($this->RegisterVariableString("ID2","ID2"),$WebfrontCatID);
			
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


			if($this->ReadPropertyInteger("AvailableID") == 0){
				$this->UnregisterVariable("AvailableVar");
			}else{
				$this->RegisterVariableBoolean("AvailableVar", "Available");
			}

			if($this->ReadPropertyInteger("OnOffID") == 0){
				$this->UnregisterVariable("OnOffVar");
			}else{
				$this->RegisterVariableBoolean("OnOffVar", "On / Off", "~Switch");
				$this->EnableAction("OnOffVar");
			}

			if($this->ReadPropertyInteger("DimmValueID") == 0){
				$this->UnregisterVariable("DimmValueVar");
			}else{
				$this->RegisterVariableInteger("DimmValueVar", "Dimm");
			}

			if($this->ReadPropertyInteger("ColorID") == 0){
				$this->UnregisterVariable("ColorVar");
				$this->UnregisterVariable("RedVar");
				$this->UnregisterVariable("GreenVar");
				$this->UnregisterVariable("BlueVar");
			}else{
				$this->RegisterVariableInteger("ColorVar", "Color", "~HexColor");
				$this->EnableAction("ColorVar");
				$this->RegisterVariableInteger("RedVar", "Red");
				$this->RegisterVariableInteger("GreenVar", "Green");
				$this->RegisterVariableInteger("BlueVar", "Blue");
			}
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
				case "OnOffVar":
					SetValue($this->GetIDForIdent($Ident), $Value);
					RequestAction($this->ReadPropertyInteger("OnOffID"), $Value);
					break;
				case "ColorVar":
					SetValue($this->GetIDForIdent($Ident), $Value);
					SetValue($this->GetIDForIdent("RedVar"), intdiv($Value, 256*256));
					RequestAction($this->ReadPropertyInteger("ColorID"), $Value);
					break;
				default:
					throw new Exception("Invalid Ident");
			}
		 
		}
	}
	?>