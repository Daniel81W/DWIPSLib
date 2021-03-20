<?php

	//include_once("/var/lib/symcon/modules/DWIPSLib/libs/astro.php");
	class DWIPSLight extends IPSModule {

		public function Create()
		{
			//Never delete this line!
			parent::Create();

			$this->RegisterPropertyInteger("OnOffID", 0);
			$this->RegisterPropertyInteger("DimmID", 0);
			$this->RegisterPropertyInteger("DimmValueID", 0);

			if(!IPS_GetCategoryIDByName("Webfront", $this->InstanceID)){
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
			if($this->ReadPropertyInteger("OnOffID") == 0){
				$this->UnregisterVariable("OnOffVar");
			}else{
				$this->RegisterVariableBoolean("OnOffVar", "On / Off");
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

		
	}
	?>