<?php

	include_once("/var/lib/symcon/modules/DWIPSLib/libs/astro.php");
	class DWIPSAstro extends IPSModule {

		public function Create()
		{
			//Never delete this line!
			parent::Create();
			$this->RegisterVariableFloat("juliandate","juliandate", "", 1);
			$this->RegisterVariableFloat("juliancentury","juliancentury", "", 2);
			$this->RegisterVariableInteger("startastronomicaltwilight", "startastronomicaltwilight", "~UnixTimestamp", 3);
			$this->RegisterVariableInteger("startnauticaltwilight", "startnauticaltwilight", "~UnixTimestamp", 4);
			$this->RegisterVariableInteger("startciviltwilight", "startciviltwilight", "~UnixTimestamp", 5);
			$this->RegisterVariableInteger("sunrise", "sunrise", "~UnixTimestamp", 6);
			$this->RegisterVariableInteger("solarnoon","solarnoon", "~UnixTimestamp", 7);
			$this->RegisterVariableInteger("sunset", "sunset", "~UnixTimestamp", 8);
			$this->RegisterVariableInteger("stopciviltwilight", "stopciviltwilight", "~UnixTimestamp", 9);
			$this->RegisterVariableInteger("stopnauticaltwilight", "stopnauticaltwilight", "~UnixTimestamp", 10);
			$this->RegisterVariableInteger("stopastronomicaltwilight", "stopastronomicaltwilight", "~UnixTimestamp", 11);
			$this->RegisterVariableFloat("sunlightduration", "sunlightduration", "", 12);
			$this->RegisterVariableFloat("sunazimut","sunazimut", "", 13);
			$this->RegisterVariableString("sundirection", "sundirection", "", 14);
			$this->RegisterVariableFloat("sunelevation","sunelevation", "", 15);
			$this->RegisterVariableFloat("sundeclination","sundeclination", "", 16);
			$this->RegisterVariableInteger("sundistance", "sundistance", "", 17);
			$this->RegisterVariableFloat("equationOfTime", "equationOfTime", "", 18);
			$this->RegisterVariableFloat("durationOfSunrise", "durationOfSunrise", "", 19);
			$this->RegisterVariableString("season", "season", "", 20);
			$this->RegisterVariableBoolean("day", "day","", 21);
			$this->RegisterVariableBoolean("insideCivilTwilight", "insideCivilTwilight", "", 22);
			
			$this->RegisterVariableString("moonphase", "moonphase", "", 30);


			$this->RegisterPropertyFloat("Latitude", 50.0);
			$this->RegisterPropertyFloat("Longitude", 9);

			$this->RegisterPropertyInteger("UpdateInterval", 1);
			$this->RegisterTimer("Update", 60000, "DWIPSASTRO_Update($this->InstanceID);");
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
			$this->SetTimerInterval("Update", $this->ReadPropertyInteger("UpdateInterval")*60*1000);

			DWIPSASTRO_Update($this->InstanceID);
		}

		/**
        * Die folgenden Funktionen stehen automatisch zur Verfügung, wenn das Modul über die "Module Control" eingefügt wurden.
        * Die Funktionen werden, mit dem selbst eingerichteten Prefix, in PHP und JSON-RPC wiefolgt zur Verfügung gestellt:
        *
        * DWIPSASTRO_UpdateSunrise($id);
        *
        */
		public function Update(){
			$timezone = 1;
			$localTime = intval(date("G"))/24 + intval(date("i"))/1440 + intval(date("s")/86400);

			$latitude = $this->ReadPropertyFloat("Latitude");
			$longitude = $this->ReadPropertyFloat("Longitude");

			$jd = ASTROGEN::JulianDay();
			$jc = ASTROGEN::JulianCentury($jd);

			$solarZenith = ASTROSUN::SolarZenith($jc, $localTime, $latitude, $longitude, $timezone);
			$sunrise = mktime(0,0,ASTROSUN::TimeForElevation(-0.833, $latitude, $longitude, $timezone, $jc, true)*24*60*60);
			$sunset = mktime(0,0,ASTROSUN::TimeForElevation(-0.833, $latitude, $longitude, $timezone, $jc, false)*24*60*60);
			$solarAzimut = ASTROSUN::SolarAzimut($jc, $localTime, $latitude, $longitude, $timezone);
			$beginCivilTwilight = mktime(0,0,ASTROSUN::TimeForElevation(-6, $latitude, $longitude, $timezone, $jc, true)*24*60*60);
			$endCivilTwilight = mktime(0,0,ASTROSUN::TimeForElevation(-6, $latitude, $longitude, $timezone, $jc, false)*24*60*60);

			$this->SetValue("juliandate", $jd);
			$this->SetValue("juliancentury", $jc);

			$this->SetValue("solarnoon", mktime(0,0,ASTROSUN::SolarNoon($timezone, $longitude, $jc)*24*60*60));
			$this->SetValue("sunazimut", $solarAzimut);
			$this->SetValue("sundeclination", ASTROSUN::Declination($jc));
			$this->SetValue("sunelevation", ASTROSUN::SolarElevation($jc, $localTime, $latitude, $longitude, $timezone));
			$this->SetValue("sundistance", ASTROSUN::SunRadVector($jc) * 149597870.7);
			$this->SetValue("equationOfTime", ASTROSUN::EquationOfTime($jc));
			$this->SetValue("sundirection", ASTROSUN::SolarDirection($solarAzimut));
			$this->SetValue("sunlightduration", ($sunset - $sunrise)/60/60);
			$this->SetValue("season", ASTROSUN::Season($jc, $latitude));

			
			$this->SetValue("sunrise", $sunrise);
			$this->SetValue("sunset", $sunset);
			$this->SetValue("startciviltwilight", $beginCivilTwilight);
			$this->SetValue("stopciviltwilight", $endCivilTwilight);
			$this->SetValue("startnauticaltwilight", mktime(0,0,ASTROSUN::TimeForElevation(-12, $latitude, $longitude, $timezone, $jc, true)*24*60*60));
			$this->SetValue("stopnauticaltwilight", mktime(0,0,ASTROSUN::TimeForElevation(-12, $latitude, $longitude, $timezone, $jc, false)*24*60*60));
			$this->SetValue("startastronomicaltwilight", mktime(0,0,ASTROSUN::TimeForElevation(-18, $latitude, $longitude, $timezone, $jc, true)*24*60*60));
			$this->SetValue("stopastronomicaltwilight", mktime(0,0,ASTROSUN::TimeForElevation(-18, $latitude, $longitude, $timezone, $jc, false)*24*60*60));
			
			$this->SetValue("durationOfSunrise", ASTROSUN::DurationOfSunrise($latitude, $longitude, $jc));
			
			$ts = time();
			if($sunrise <= $ts and $ts <= $sunset){
				$this->SetValue("day", true);
			}else{
				$this->SetValue("day", false);
			}
			if($beginCivilTwilight <= $ts and $ts <= $endCivilTwilight){
				$this->SetValue("insideCivilTwilight", true);
			}else{
				$this->SetValue("insideCivilTwilight", false);
			}

			$this->SetValue("moonPhase", ASTROMOON::PhaseStr());
		}

		
	}
	?>