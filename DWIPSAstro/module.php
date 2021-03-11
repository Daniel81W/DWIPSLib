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
			$this->RegisterVariableInteger("stopnastronomicaltwilight", "stopnastronomicaltwilight", "~UnixTimestamp", 11);
			$this->RegisterVariableFloat("sunlightduration", "sunlightduration", "", 12);
			$this->RegisterVariableFloat("sunazimut","sunazimut", "", 13);
			$this->RegisterVariableString("sundirection", "sundirection", "", 14);
			$this->RegisterVariableFloat("sunelevation","sunelevation", "", 15);
			$this->RegisterVariableFloat("sundeclination","sundeclination", "", 16);
			$this->RegisterVariableInteger("sundistance", "sundistance", "", 17);
			$this->RegisterVariableFloat("equationOfTime", "equationOfTime", "", 18);
			$this->RegisterVariableString("season", "season", "", 19);


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
        public function UpdateSunrise() {
           }

		public function Update(){
			$timezone = 1;
			$localTime = intval(date("G"))/24 + intval(date("i"))/1440 + intval(date("s")/86400);


			$latitude = $this->ReadPropertyFloat("Latitude");
			$longitude = $this->ReadPropertyFloat("Longitude");

			$jd = ASTROGEN::JulianDay();
			$jc = ASTROGEN::JulianCentury($jd);

			$eccentEarthOrbit = ASTROSUN::EccentEarthOrbit($jc);
			$meanAnomalySun = ASTROSUN::MeanAnomaly($jc);
			$sunEqOfCtr = ASTROSUN::SunEqOfCtr($jc);
			$trueAnomalySun = ASTROSUN::TrueAnomalySun($jc);
			$meanLongitudeSun = ASTROSUN::MeanLongitude($jc);
			$trueLongitudeSun = ASTROSUN::EclipticLongitude($jc);
			$sunAppLong = ASTROSUN::SunAppLong($jc);
			$meanObliqEcliptic = ASTROSUN::MeanObliquityOfEcliptic($jc);
			$obliqCorr = ASTROSUN::ObliqCorrected($meanObliqEcliptic, $jc);
			$declination = ASTROSUN::Declination($sunAppLong, $obliqCorr);
			$varY = ASTROSUN::VarY($obliqCorr);
			$eqOfTime = ASTROSUN::EquationOfTime($meanLongitudeSun, $meanAnomalySun, $eccentEarthOrbit, $varY);
			$trueSolarTime = ASTROSUN::TrueSolarTime($localTime, $eqOfTime, $longitude, $timezone);
			$hourAngle = ASTROSUN::HourAngle($trueSolarTime);
			$hourAngleAtSunriseStart = ASTROSUN::HourAngleAtElevation(-0.833, $latitude,  $declination);
			$hourAngleAtSunriseEnd = ASTROSUN::HourAngleAtElevation(0.833, $latitude,  $declination);
			$hourAngleAtCivilTwilight = ASTROSUN::HourAngleAtElevation(-6, $latitude,  $declination);
			$hourAngleAtNauticalTwilight = ASTROSUN::HourAngleAtElevation(-12, $latitude,  $declination);
			$hourAngleAtAstronomicalTwilight = ASTROSUN::HourAngleAtElevation(-18, $latitude,  $declination);
			$solarNoon = ASTROSUN::SolarNoon($timezone, $longitude, $eqOfTime);
			$solarZenith = ASTROSUN::SolarZenith($declination, $hourAngle, $latitude);
			$sunrise = mktime(0,0,ASTROSUN::Sunrise($solarNoon, $hourAngleAtSunriseStart)*24*60*60);
			$sunset = mktime(0,0,ASTROSUN::Sunset($solarNoon, $hourAngleAtSunriseStart)*24*60*60);
			$solarAzimut = ASTROSUN::SolarAzimut($declination, $hourAngle, $solarZenith, $latitude);

			$this->SetValue("juliandate", $jd);
			$this->SetValue("juliancentury", $jc);

			$this->SetValue("solarnoon", mktime(0,0,$solarNoon*24*60*60));
			$this->SetValue("sunazimut", $solarAzimut);
			$this->SetValue("sundeclination", $declination);
			$this->SetValue("sunelevation", ASTROSUN::SolarElevation($solarZenith));
			$this->SetValue("sundistance", ASTROSUN::SunRadVector($eccentEarthOrbit, $trueAnomalySun) * 149597870.7);
			$this->SetValue("equationOfTime", $eqOfTime);
			$this->SetValue("sundirection", ASTROSUN::SolarDirection($solarAzimut));
			$this->SetValue("sunlightduration", ($sunset - $sunrise)/60/60);
			$this->SetValue("season", ASTROSUN::Season());

			
			$this->SetValue("sunrise", $sunrise);
			$this->SetValue("sunset", $sunset);
			$this->SetValue("startciviltwilight", mktime(0,0,ASTROSUN::Sunrise($solarNoon, $hourAngleAtCivilTwilight)*24*60*60));
			$this->SetValue("stopciviltwilight", mktime(0,0,ASTROSUN::Sunset($solarNoon, $hourAngleAtCivilTwilight)*24*60*60));
			$this->SetValue("startnauticaltwilight", mktime(0,0,ASTROSUN::Sunrise($solarNoon, $hourAngleAtNauticalTwilight)*24*60*60));
			$this->SetValue("stopnauticaltwilight", mktime(0,0,ASTROSUN::Sunset($solarNoon, $hourAngleAtNauticalTwilight)*24*60*60));
			$this->SetValue("startastronomicaltwilight", mktime(0,0,ASTROSUN::Sunrise($solarNoon, $hourAngleAtAstronomicalTwilight)*24*60*60));
			$this->SetValue("stopnastronomicaltwilight", mktime(0,0,ASTROSUN::Sunset($solarNoon, $hourAngleAtAstronomicalTwilight)*24*60*60));
   
		}
	}
	?>