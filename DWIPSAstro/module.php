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
			$this->RegisterTimer("Update", 60000, "Update();");
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
        * Die folgenden Funktionen stehen automatisch zur Verfügung, wenn das Modul über die "Module Control" eingefügt wurden.
        * Die Funktionen werden, mit dem selbst eingerichteten Prefix, in PHP und JSON-RPC wiefolgt zur Verfügung gestellt:
        *
        * DWIPSASTRO_UpdateSunrise($id);
        *
        */
        public function UpdateSunrise() {
           }

		public function Update(){
			$jd = ASTROGEN::JulianDay();
			$declination = 23;//ASTROSUN::Declination($sunAppLong, $obliqCorr);
			$hourangleAtSunriseStart = ASTROSUN::HourAngleAtElevation(-0.833, $this->ReadPropertyFloat("Latitude"),  $declinaton);
			$hourangleAtSunriseEnd = ASTROSUN::HourAngleAtElevation(0.833, $this->ReadPropertyFloat("Latitude"),  $declinaton);
			$hourangleAtCivilTwilight = ASTROSUN::HourAngleAtElevation(6, $this->ReadPropertyFloat("Latitude"),  $declinaton);
			$hourangleAtNauticalTwilight = ASTROSUN::HourAngleAtElevation(12, $this->ReadPropertyFloat("Latitude"),  $declinaton);
			$hourangleAtAstronomicalTwilight = ASTROSUN::HourAngleAtElevation(18, $this->ReadPropertyFloat("Latitude"),  $declinaton);

			$this->SetValue("juliandate", $jd);
			$this->SetValue("juliandate", ASTROGEN::JulianCentury($jd));

			//$this->SetValue("solarnoon", ASTROSUN::SolarNoon($timezone, $longitude, $eqOfTime));
			//$this->SetValue("sunazimut", ASTROSUN::SolarAzimut($declination, $hourAngle, $solarZenith, $latitude));
			//$this->SetValue("sundeclination", $declination);
			//$this->SetValue("sunelevation", ASTROSUN::SolarElevation($solarZenith));
			//$this->SetValue("sundistance", ASTROSUN::SunRadVector($eccentEarthOrbit, $trueAnomalySun) * 149597870.7);
			//$this->SetValue("equationOfTime", ASTROSUN::EquationOfTime($meanLong, $meanAnomaly, $eccentEarthOrbit, $varY));
			//$this->SetValue("sundirection", );
			//$this->SetValue("sunlightduration", );
			//$this->SetValue("season", "season");

			
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