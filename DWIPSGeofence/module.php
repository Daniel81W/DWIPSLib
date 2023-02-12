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
		if (IPS_GetKernelRunlevel() == KR_READY) {
            $this->RegisterHook("/hook/test");
        }
		
	}


	/**
	* This function will be called by the hook control. Visibility should be protected!
	*/
	protected function ProcessHookData()
	{
		IPS_LogMessage("GeofenceOut Post", print_r($_SERVER['HOOK'], true));
	}

	private function RegisterHook($WebHook) 
	{ 
		$ids = IPS_GetInstanceListByModuleID("{015A6EB8-D6E5-4B93-B496-0D3F77AE9FE1}"); 
		if(count($ids) > 0) { 
			$hooks = json_decode(IPS_GetProperty($ids[0], "Hooks"), true); 
			$found = false; 
			foreach($hooks as $index => $hook) { 
				if($hook['Hook'] == $WebHook) { 
					if($hook['TargetID'] == $this->InstanceID) 
						return; 
					$hooks[$index]['TargetID'] = $this->InstanceID; 
					$found = true; 
				} 
			} 
			if(!$found) { 
				$hooks[] = Array("Hook" => $WebHook, "TargetID" => $this->InstanceID); 
			} 
			IPS_SetProperty($ids[0], "Hooks", json_encode($hooks)); 
			IPS_ApplyChanges($ids[0]); 
		} 
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