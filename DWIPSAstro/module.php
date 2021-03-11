<?php

	include_once("/var/lib/symcon/modules/DWIPSLib/libs/astro.php");
	class DWIPSAstro extends IPSModule {

		public function Create()
		{
			//Never delete this line!
			parent::Create();
			$this->RegisterVariableFloat("juliandate","juliandate");
			$this->RegisterVariableFloat("juliancentury","juliancentury");
			$this->RegisterVariableInteger("solarnoon","solarnoon");
			$this->RegisterVariableFloat("sunazimut","sunazimut");
			$this->RegisterVariableFloat("sundeclination","sundeclination");
			$this->RegisterVariableFloat("sunelevation","sunelevation");
			$this->RegisterVariableInteger("sundistance", "sundistance");
			$this->RegisterVariableFloat("sunlightduration", "sunlightduration");
			$this->RegisterVariableFloat("equationOfTime", "equationOfTime");
			$this->RegisterVariableString("sundirection", "sundirection");
			$this->RegisterVariableString("season", "season");

			$this->RegisterVariableInteger("sunrise", "sunrise");
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
			$hourangleAtSunriseStart = ASTROSUN::HourAngleAtElevation(-0.833, $this->ReadPropertyFloat("Latitude"),  $declination);
			$hourangleAtSunriseEnd = ASTROSUN::HourAngleAtElevation(0.833, $this->ReadPropertyFloat("Latitude"),  $declination);
			$hourangleAtCivilTwilight = ASTROSUN::HourAngleAtElevation(6, $this->ReadPropertyFloat("Latitude"),  $declination);
			$hourangleAtNauticalTwilight = ASTROSUN::HourAngleAtElevation(12, $this->ReadPropertyFloat("Latitude"),  $declination);
			$hourangleAtAstronomicalTwilight = ASTROSUN::HourAngleAtElevation(18, $this->ReadPropertyFloat("Latitude"),  $declination);
			$varY = ASTROSUN::VarY($obliqCorr);
			$eqOfTime = ASTROSUN::EquationOfTime($meanLongitudeSun, $meanAnomalySun, $eccentEarthOrbit, $varY);
			$solarNoon = ASTROSUN::SolarNoon(1/*$timezone*/, $longitude, $eqOfTime);
			$this->SetValue("juliandate", $jd);
			$this->SetValue("juliancentury", $jc);

			$this->SetValue("solarnoon", mktime(0,0,$solarNoon*24*60*60));
			//$this->SetValue("sunazimut", ASTROSUN::SolarAzimut($declination, $hourAngle, $solarZenith, $latitude));
			$this->SetValue("sundeclination", $declination);
			//$this->SetValue("sunelevation", ASTROSUN::SolarElevation($solarZenith));
			$this->SetValue("sundistance", ASTROSUN::SunRadVector($eccentEarthOrbit, $trueAnomalySun) * 149597870.7);
			$this->SetValue("equationOfTime", $eqOfTime);
			//$this->SetValue("sundirection", );
			//$this->SetValue("sunlightduration", );
			$this->SetValue("season", mktime(0,0,$solarNoon*24*60*60));

			
			//$this->SetValue("sunrise", ASTROSUN::Sunrise($solarNoon, $hourAngleAtSunriseStart));
			//$this->SetValue("sunset", ASTROSUN::Sunset($solarNoon, $hourAngleAtSunriseStart));
			//$this->SetValue("startciviltwilight", ASTROSUN::Sunrise($solarNoon, $hourangleAtCivilTwilight));
			//$this->SetValue("stopciviltwilight", ASTROSUN::Sunset($solarNoon, $hourangleAtCivilTwilight));
			//$this->SetValue("startnauticaltwilight", ASTROSUN::Sunrise($solarNoon, $hourangleAtNauticalTwilight));
			//$this->SetValue("stopnauticaltwilight", ASTROSUN::Sunset($solarNoon, $hourangleAtNauticalTwilight));
			//$this->SetValue("startastronomicaltwilight", ASTROSUN::Sunrise($solarNoon, $hourangleAtAstronomicalTwilight));
			//$this->SetValue("stopnastronomicaltwilight", ASTROSUN::Sunset($solarNoon, $hourangleAtAstronomicalTwilight));
   
		}
	}
	?>