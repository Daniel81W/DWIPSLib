<?php

class DWIPSGeofence extends IPSModule {

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
		//IPS_LogMessage("GeofenceOut Post", print_r($_POST, true));
		$hook = $_SERVER['HOOK'];
		$hookid = @$this->GetIDForIdent(str_replace('/', '0', $hook));
		if(! $hookid){
			$hookid = IPS_CreateInstance("{485D0419-BE97-4548-AA9C-C083EB82E61E}");
			IPS_SetIdent($hookid, str_replace('/', '0', $hook));
			IPS_SetParent($hookid , $this->InstanceID);
			IPS_SetName($hookid, $hook);
		}
		//IPS_LogMessage("GeofenceOut Post", print_r($_POST['device'], true));
		$device = $_POST['device'];
		$deviceident = str_replace('-', '', $device);
		$deviceid = @$this->GetIDForIdent($deviceident);
		if (!$deviceid)
		{
			$deviceid = IPS_CreateInstance("{485D0419-BE97-4548-AA9C-C083EB82E61E}");
			IPS_SetIdent($deviceid, $deviceident);
			IPS_SetParent($deviceid , $hookid);
			IPS_SetName($deviceid, $device);
			$this->RegisterVariableBoolean($deviceident."presence", $this->Translate("Presence"));
			IPS_SetParent($deviceid , $hookid);
		}

		if($_POST['trigger'] == 'enter'){
			$this->SetValue($deviceident . "presence", true);
		}else if($_POST['trigger'] == 'exit'){
			$this->SetValue($deviceident . "presence", false);
		}
		
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