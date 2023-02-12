<?php

	include_once __DIR__ . '/../libs/WebHookModule.php';

	class DWIPSGeofence extends WebHookModule {

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
		}


		/**
		* This function will be called by the hook control. Visibility should be protected!
		*/
		protected function ProcessHookData()
		{
			IPS_LogMessage("GeofenceOut Post", print_r($_SERVER['HOOK'], true));
		}
		/**
        * Die folgenden Funktionen stehen automatisch zur Verfügung, wenn das Modul über die "Module Control" eingefügt wurden.
        * Die Funktionen werden, mit dem selbst eingerichteten Prefix, in PHP und JSON-RPC wiefolgt zur Verfügung gestellt:
        *
        * DWIPSGeofence_ProcessHookData($id);
        *
        */
        public function UpdateSunrise() {
           }

		
	}
	?>