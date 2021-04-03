<?php

	include_once("/var/lib/symcon/modules/DWIPSLib/libs/astro.php");
	class DWIPSAstro extends IPSModule {

		public function Create()
		{
			//Never delete this line!
			parent::Create();
			$this->RegisterVariableFloat("juliandate",$this->Translate("juliandate"), "", 1);
			$this->RegisterVariableFloat("juliancentury",$this->Translate("juliancentury"), "", 2);
			$this->RegisterVariableInteger("startastronomicaltwilight", $this->Translate("startastronomicaltwilight"), "~UnixTimestamp", 3);
			$this->RegisterVariableInteger("startnauticaltwilight", $this->Translate("startnauticaltwilight"), "~UnixTimestamp", 4);
			$this->RegisterVariableInteger("startciviltwilight", $this->Translate("startciviltwilight"), "~UnixTimestamp", 5);
			$this->RegisterVariableInteger("sunrise", $this->Translate("sunrise"), "~UnixTimestamp", 6);
			$this->RegisterVariableInteger("solarnoon",$this->Translate("solarnoon"), "~UnixTimestamp", 7);
			$this->RegisterVariableInteger("sunset", $this->Translate("sunset"), "~UnixTimestamp", 8);
			$this->RegisterVariableInteger("stopciviltwilight", $this->Translate("stopciviltwilight"), "~UnixTimestamp", 9);
			$this->RegisterVariableInteger("stopnauticaltwilight", $this->Translate("stopnauticaltwilight"), "~UnixTimestamp", 10);
			$this->RegisterVariableInteger("stopastronomicaltwilight", $this->Translate("stopastronomicaltwilight"), "~UnixTimestamp", 11);
			$this->RegisterVariableFloat("sunlightduration", $this->Translate("sunlightduration"), "", 12);
			$this->RegisterVariableFloat("sunazimut", $this->Translate("sunazimut"), "", 13);
			$this->RegisterVariableString("sundirection", $this->Translate("sundirection"), "", 14);
			$this->RegisterVariableFloat("sunelevation", $this->Translate("sunelevation"), "", 15);
			$this->RegisterVariableFloat("sunelevationmin", $this->Translate("sunelevationmin"), "", 16);
			$this->RegisterVariableFloat("sunelevationmax", $this->Translate("sunelevationmax"), "", 17);
			$this->RegisterVariableFloat("sundeclination", $this->Translate("sundeclination"), "", 18);
			$this->RegisterVariableInteger("sundistance", $this->Translate("sundistance"), "", 19);
			$this->RegisterVariableFloat("equationOfTime", $this->Translate("equationOfTime"), "", 20);
			$this->RegisterVariableFloat("durationOfSunrise", $this->Translate("durationOfSunrise"), "", 21);
			$this->RegisterVariableString("season", $this->Translate("season"), "", 22);
			$this->RegisterVariableBoolean("day", $this->Translate("day"),"", 23);
			$this->RegisterVariableBoolean("insideCivilTwilight", $this->Translate("insideCivilTwilight"), "", 24);
			$this->RegisterVariableFloat("shadowLength", $this->Translate("shadowlength"), "", 25);
			$this->RegisterVariableFloat("solarirradiance", $this->Translate("solarirradiance"), "Astronomie.Radiant_Power", 26);
			
			
			$this->RegisterVariableString("moonphase", $this->Translate("moonphase"), "", 30);


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
			if(date('I')){
				$timezone = 2;
			}
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
			$sunelevation = ASTROSUN::SolarElevation($jc, $localTime, $latitude, $longitude, $timezone);
			$sundistance = ASTROSUN::SunRadVector($jc) * 149597870.7;
			$this->SetValue("juliandate", $jd);
			$this->SetValue("juliancentury", $jc);

			$this->SetValue("solarnoon", mktime(0,0,ASTROSUN::SolarNoon($timezone, $longitude, $jc)*24*60*60));
			$this->SetValue("sunazimut", $solarAzimut);
			$this->SetValue("sundeclination", ASTROSUN::Declination($jc));
			$this->SetValue("sunelevation", $sunelevation);
			$this->SetValue("sunelevationmin", -90 + $latitude + ASTROSUN::Declination($jc));
			$this->SetValue("sunelevationmax", 90 - $latitude + ASTROSUN::Declination($jc));
			$this->SetValue("sundistance", $sundistance);
			$this->SetValue("equationOfTime", ASTROSUN::EquationOfTime($jc));
			$this->SetValue("sundirection", ASTROSUN::SolarDirection($solarAzimut));
			$this->SetValue("sunlightduration", ($sunset - $sunrise)/60/60);
			$this->SetValue("season", $this->Translate(ASTROSUN::Season($jc, $latitude)));

			
			$this->SetValue("sunrise", $sunrise);
			$this->SetValue("sunset", $sunset);
			$this->SetValue("startciviltwilight", $beginCivilTwilight);
			$this->SetValue("stopciviltwilight", $endCivilTwilight);
			$this->SetValue("startnauticaltwilight", mktime(0,0,ASTROSUN::TimeForElevation(-12, $latitude, $longitude, $timezone, $jc, true)*24*60*60));
			$this->SetValue("stopnauticaltwilight", mktime(0,0,ASTROSUN::TimeForElevation(-12, $latitude, $longitude, $timezone, $jc, false)*24*60*60));
			$this->SetValue("startastronomicaltwilight", mktime(0,0,ASTROSUN::TimeForElevation(-18, $latitude, $longitude, $timezone, $jc, true)*24*60*60));
			$this->SetValue("stopastronomicaltwilight", mktime(0,0,ASTROSUN::TimeForElevation(-18, $latitude, $longitude, $timezone, $jc, false)*24*60*60));
			$this->SetValue("shadowLength", 1 / tan(deg2rad($sunelevation)));
			$this->SetValue("solarirradiance", 3.06531 * pow(10,19) / pow($sundistance, 2) * 0.75);
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

			$this->SetValue("moonphase", ASTROMOON::PhaseStr());
		}

		
	}
	?>