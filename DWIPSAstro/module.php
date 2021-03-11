<?php

	include_once("/var/lib/symcon/modules/DWIPSLib/libs/astro.php");
	class DWIPSAstro extends IPSModule {

		public function Create()
		{
			//Never delete this line!
			parent::Create();
			$this->RegisterVariableFloat("juliandate","juliandate", "", 1);
			$this->RegisterVariableFloat("juliancentury","juliancentury", "", 2);
			$this->RegisterVariableInteger("solarnoon","solarnoon", "~UnixTimestamp", 0);
			$this->RegisterVariableFloat("sunazimut","sunazimut");
			$this->RegisterVariableFloat("sundeclination","sundeclination");
			$this->RegisterVariableFloat("sunelevation","sunelevation");
			$this->RegisterVariableInteger("sundistance", "sundistance");
			$this->RegisterVariableFloat("sunlightduration", "sunlightduration");
			$this->RegisterVariableFloat("equationOfTime", "equationOfTime");
			$this->RegisterVariableString("sundirection", "sundirection");
			$this->RegisterVariableString("season", "season");

			$this->RegisterVariableInteger("sunrise", "sunrise", "~UnixTimestamp", 0);
			$this->RegisterVariableInteger("sunset", "sunset");
			$this->RegisterVariableInteger("startciviltwilight", "startciviltwilight");
			$this->RegisterVariableInteger("stopciviltwilight", "stopciviltwilight");
			$this->RegisterVariableInteger("startnauticaltwilight", "startnauticaltwilight");
			$this->RegisterVariableInteger("stopnauticaltwilight", "stopnauticaltwilight");
			$this->RegisterVariableInteger("startastronomicaltwilight", "startastronomicaltwilight");
			$this->RegisterVariableInteger("stopnastronomicaltwilight", "stopnastronomicaltwilight");

			$this->RegisterPropertyFloat("Latitude", 50.0);
			$this->RegisterPropertyFloat("Longitude", 9.0);

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
			$localTime = 0.5;


			$latitude = $this->ReadPropertyFloat("Latitude");
			$longitude = $this->ReadPropertyFloat("Longitude");
			$jd = ASTROGEN::JulianDay();
			$jc = ASTROGEN::JulianCentury($jd);

			$eccentEarthOrbit = ASTROSUN::EccentEarthOrbit($jc);
			$meanAnomalySun = ASTROSUN::MeanAnomaly($jc);
			$sunEqOfCtr = ASTROSUN::SunEqOfCtr($jc, $meanAnomalySun);
			$trueAnomalySun = ASTROSUN::TrueAnomalySun($meanAnomalySun, $sunEqOfCtr);
			$meanLongitudeSun = ASTROSUN::MeanLongitude($jc);
			$trueLongitudeSun = ASTROSUN::EclipticLongitude($meanLongitudeSun, $sunEqOfCtr);
			$sunAppLong = ASTROSUN::SunAppLong($trueLongitudeSun, $jc);
			$meanObliqEcliptic = ASTROSUN::MeanObliquityOfEcliptic($jc);
			$obliqCorr = ASTROSUN::ObliqCorrected($meanObliqEcliptic, $jc);
			$declination = ASTROSUN::Declination($sunAppLong, $obliqCorr);
			$varY = ASTROSUN::VarY($obliqCorr);
			$eqOfTime = ASTROSUN::EquationOfTime($meanLongitudeSun, $meanAnomalySun, $eccentEarthOrbit, $varY);
			$trueSolarTime = ASTROSUN::TrueSolarTime($localTime, $eqOfTime, $longitude, $timezone);
			$hourAngle = ASTROSUN::HourAngle($trueSolarTime);
			$hourAngleAtSunriseStart = ASTROSUN::HourAngleAtElevation(-0.833, $latitude,  $declination);
			$hourAngleAtSunriseEnd = ASTROSUN::HourAngleAtElevation(0.833, $latitude,  $declination);
			$hourAngleAtCivilTwilight = ASTROSUN::HourAngleAtElevation(6, $latitude,  $declination);
			$hourAngleAtNauticalTwilight = ASTROSUN::HourAngleAtElevation(12, $latitude,  $declination);
			$hourAngleAtAstronomicalTwilight = ASTROSUN::HourAngleAtElevation(18, $latitude,  $declination);
			$solarNoon = ASTROSUN::SolarNoon($timezone, $longitude, $eqOfTime);
			$solarZenith = ASTROSUN::SolarZenith($declination, $hourAngle, $latitude);
			
			$this->SetValue("juliandate", $jd);
			$this->SetValue("juliancentury", $jc);

			$this->SetValue("solarnoon", mktime(0,0,$solarNoon*24*60*60));
			$this->SetValue("sunazimut", ASTROSUN::SolarAzimut($declination, $hourAngle, $solarZenith, $latitude));
			$this->SetValue("sundeclination", $declination);
			$this->SetValue("sunelevation", ASTROSUN::SolarElevation($solarZenith));
			$this->SetValue("sundistance", ASTROSUN::SunRadVector($eccentEarthOrbit, $trueAnomalySun) * 149597870.7);
			$this->SetValue("equationOfTime", $eqOfTime);
			//$this->SetValue("sundirection", );
			//$this->SetValue("sunlightduration", );
			$this->SetValue("season", $hourAngleAtSunriseStart);

			
			$this->SetValue("sunrise", ASTROSUN::Sunrise($solarNoon, $hourAngleAtSunriseStart));
			$this->SetValue("sunset", mktime(0,0,ASTROSUN::Sunset($solarNoon, $hourAngleAtSunriseStart)*24+60*60));
			$this->SetValue("startciviltwilight", ASTROSUN::Sunrise($solarNoon, $hourAngleAtCivilTwilight));
			$this->SetValue("stopciviltwilight", ASTROSUN::Sunset($solarNoon, $hourAngleAtCivilTwilight));
			$this->SetValue("startnauticaltwilight", ASTROSUN::Sunrise($solarNoon, $hourAngleAtNauticalTwilight));
			$this->SetValue("stopnauticaltwilight", ASTROSUN::Sunset($solarNoon, $hourAngleAtNauticalTwilight));
			$this->SetValue("startastronomicaltwilight", ASTROSUN::Sunrise($solarNoon, $hourAngleAtAstronomicalTwilight));
			$this->SetValue("stopnastronomicaltwilight", ASTROSUN::Sunset($solarNoon, $hourAngleAtAstronomicalTwilight));
   
		}
	}
	?>