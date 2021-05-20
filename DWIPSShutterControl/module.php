<?php

	//include_once("/var/lib/symcon/modules/DWIPSLib/libs/astro.php");
	class DWIPSShutter extends IPSModule {

		public function Create()
		{
			//Never delete this line!
			parent::Create();
			
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
			$guid = "{48FCFDC1-11A5-4309-BB0B-A0DB8042A969}";
			//Auflisten
			print_r(IPS_GetInstanceListByModuleID($guid));
			//echo IPS_GetInstanceListByModuleID("{0B8709EA-127A-29C7-61DA-916BA9B2ED02}");
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