<?php

//TODO Dauer Sonnenaufgang
//TODO Mond

class ASTROGEN{

    /**
     * 
     */
    public static function JulianDay(){
        $date = new DateTime();
       return ASTROGEN::JulianDayFromTimestamp($date->getTimestamp());
    }

    /**
     * 
     */
    public static function JulianDayFromTimestamp(int $timestamp){
        return ( $timestamp / 86400.0 ) + 2440587.5;
    }

    /**
     * 
     */
    public static function JulianDayFromDateTime(int $year, int $month, int $day, int $hour = 0, int $minute = 0, int $second = 0){
       $date = mktime($hour, $minute, $second , $month, $day, $year);
       return ASTROGEN::JulianDayFromTimestamp($date);
    }

    /**
     * 
     */
    public static function JulianCentury(float $julianDay){
        return ($julianDay - 2451545.0) / 36525.0;
    }
    
}

class ASTROSUN{

    /**
     * 
     */
    public static function MeanLongitude(float $julianCentury){
        return fmod( (280.46646 + $julianCentury * ( 36000.76983 + $julianCentury * 0.0003032 )) , 360);
    }

    /**
     * Mittlere Anomalie der Sonne
     */
    public static function MeanAnomaly(float $julianCentury){
        return 357.52911 + $julianCentury * (35999.05029 - 0.0001537 * $julianCentury);
    }

    /**
     * 
     */
    public static function EccentEarthOrbit(float $julianCentury){
        return 0.016708634 - $julianCentury * (0.000042037 + 0.0000001267 * $julianCentury);
    }
    
    /**
     * 
     */
    public static function SunEqOfCtr(float $julianCentury, float $geometricMeanAnomalySun){
        return sin( deg2rad($geometricMeanAnomalySun) ) * ( 1.914602 - $julianCentury * ( 0.004817 + 0.000014 * $julianCentury ) ) + sin( deg2rad( 2 * $geometricMeanAnomalySun ) ) * ( 0.019993 - 0.000101 * $julianCentury ) + sin( deg2rad( 3 * $geometricMeanAnomalySun ) ) * 0.000289;
    }

    /**
     * 
     */
    public static function EclipticLongitude(float $geometricMeanLongitudeSun, float $sunEqOfCtr){
        return $geometricMeanLongitudeSun + $sunEqOfCtr;
    }

    /**
     * 
     */
    public static function TrueAnomalySun(float $geometricMeanAnomalySun,float $sunEqOfCtr){
        return $geometricMeanAnomalySun + $sunEqOfCtr;
    }

    /**
     * 
     */
    public static function SunRadVector(float $eccentEarthOrbit, float $trueAnomalySun){
        return ( 1.000001018 * ( 1 - $eccentEarthOrbit * $eccentEarthOrbit ) ) / ( 1 + $eccentEarthOrbit * cos( deg2rad( $trueAnomalySun ) ) );
    }

    /**
     * 
     */
    public static function SunAppLong(float $trueLongitudeSun, float $julianCentury){
        return $trueLongitudeSun - 0.00569 - 0.00478 * sin( deg2rad( 125.04 - 1934.136 * $julianCentury ) );
    }

    /**
     * Mittlere Schiefe der Ekliptik (Achsneigung der Erde)
     * @param float $julianCentury Das Julianische Jahrhundert
     */
    public static function MeanObliquityOfEcliptic(float $julianCentury):float{
        return 23 + ( 26 + ( ( 21.448 - $julianCentury * ( 46.815 + $julianCentury * ( 0.00059 - $julianCentury * 0.001813 ) ) ) ) / 60 ) / 60;
    }

    /**
     * 
     */
    public static function ObliqCorrected(float $meanObliqEcliptic, float $julianCentury){
        return $meanObliqEcliptic + 0.00256 * cos( deg2rad( 125.04 - 1934.136 * $julianCentury ) );
    }

    /**
     * 
     */
    public static function RA(float $sunAppLong, float $obliqCorr){
        return rad2deg( atan2( cos( deg2rad( $sunAppLong ) ) , cos( deg2rad( $obliqCorr ) ) * sin( deg2rad( $sunAppLong ) ) ) );
    }

    /**
     * Deklination der Sonne
     */
    public static function Declination(float $sunAppLong, float $obliqCorr){
        return rad2deg( asin( sin( deg2rad( $obliqCorr ) ) * sin( deg2rad( $sunAppLong ) ) ) );
    }

    /**
     * 
     */
    public static function VarY(float $obliqCorr){
        return tan( deg2rad( $obliqCorr / 2 ) ) * tan( deg2rad( $obliqCorr / 2 ) );
    }

    /**
     * 
     */
    public static function EquationOfTime(float $meanLong, float $meanAnomaly, float $eccentEarthOrbit, float $varY){
        return 4 * rad2deg(
                $varY * sin(
                    2*deg2rad($meanLong)
                ) - 2 * $eccentEarthOrbit * sin(
                    deg2rad($meanAnomaly)
                ) + 4 * $eccentEarthOrbit * $varY * sin(
                    deg2rad($meanAnomaly)
                ) * cos(
                    2*deg2rad($meanLong)
                ) - 0.5 * $varY * $varY * sin(
                    4*deg2rad($meanLong)
                ) - 1.25 * $eccentEarthOrbit * $eccentEarthOrbit * sin(
                    2 * deg2rad($meanAnomaly)
                )
            );
    }

    /**
     * 
     */
    public static function HourAngleAtSunrise(float $latitude, float $declinaton){
        return rad2deg(acos(cos(deg2rad(90.833))/(cos(deg2rad($latitude))*cos(deg2rad($declinaton)))-tan(deg2rad($latitude))*tan(deg2rad($declinaton))));
    }

    public static function HourAngleAtElevation(float $sunElevation, float $latitude, float $declinaton){
        return rad2deg(acos(cos(deg2rad(90 - $sunElevation))/(cos(deg2rad($latitude))*cos(deg2rad($declinaton)))-tan(deg2rad($latitude))*tan(deg2rad($declinaton))));
    }

    /**
     * 
     */
    public static function SolarNoon(int $timezone, float $longitude, float $eqOfTime){
        if ($longitude >= -180 && $longitude <= 180) {
            return ( 720 - 4 * $longitude - $eqOfTime + $timezone * 60 ) / 1440;
        }elseif ($longitude < -180) {
            return ( 720 - 4 * (360 + $longitude) - $eqOfTime + $timezone * 60 ) / 1440;
        }elseif ($longitude > 180) {
            return ( 720 - 4 * (-360 + $longitude) - $eqOfTime + $timezone * 60 ) / 1440;
        }
    }
        
    /**
     * 
     */
    public static function Sunrise(float $solarNoon, float $hourAngleAtSunrise){
        return $solarNoon - $hourAngleAtSunrise / 360;
    }
    
    /**
     * 
     */
    public static function Sunset(float $solarNoon, float $hourAngleAtSunrise){
        return $solarNoon + $hourAngleAtSunrise / 360;
    }

    public static function SunlightDuration(float $hourAngleAtSunrise){
        return 8 * $hourAngleAtSunrise;
    }

    public static function TrueSolarTime(float $localTime, float $eqOfT, float $long, int $timezone){
        return fmod( $localTime * 1440 + $eqOfT + 4 * $long - 60 * $timezone , 1440);
    }

    /**
     * 
     */
    public static function HourAngle(float $trueSolarTime){
        if ($trueSolarTime / 4 < 0){
            return $trueSolarTime / 4 + 180;
        }else{
            return $trueSolarTime / 4 - 180;
        }
    }

    /**
     * 
     */
    public static function SolarZenith(float $declination, float $hourAngle, float $lat){
        return rad2deg(
            acos(sin(deg2rad($lat))*sin(deg2rad($declination))+cos(deg2rad($lat))*cos(deg2rad($declination))*cos(deg2rad($hourAngle)))
        );
    }

    /**
     * 
     */
    public static function SolarElevation(float $solarZenith){
        return 90 - $solarZenith;
    }

    /**
     * 
     */
    public static function SolarAzimut(float $declination, float $hourAngle, float $solarZenith,float $latitude){
        if ($hourAngle>0){
            return fmod(
                rad2deg(
                    acos(
                        (
                            (
                                sin(
                                    deg2rad($latitude)
                                ) * cos(
                                    deg2rad($solarZenith)
                                )
                            ) - sin(
                                deg2rad($declination)
                            )
                        ) / (
                            cos(
                                deg2rad($latitude)
                            ) * sin(
                                deg2rad($solarZenith)
                            )
                        )
                    )
                )+180,360
            );
        }else{
            return fmod(
                540 - rad2deg(
                    acos(
                        (
                            (
                                sin(
                                    deg2rad($latitude)
                                ) * cos(
                                    deg2rad($solarZenith)
                                )
                            ) - sin(
                                deg2rad($declination)
                            )
                        ) / (
                            cos(
                                deg2rad($latitude)
                            ) * sin(
                                deg2rad($solarZenith)
                            )
                        )
                    )
                ),360
            );
        }
    }


    public static function SunriseForDateAndLocation(int $year, int $month, int $day, float $lat, float $long, int $timezone){
        $jc = ASTROGEN::JulianCentury(ASTROGEN::JulianDayFromDateTime($year, $month, $day));

        
        $meanLong = ASTROSUN::MeanLongitude($jc);
        $meanAnomaly = ASTROSUN::MeanAnomaly($jc);
        $sunEqOfCtr = ASTROSUN::SunEqOfCtr($jc, $meanAnomaly);
        $trueLongitudeSun = ASTROSUN::EclipticLongitude($meanLong,$sunEqOfCtr);
        $meanObliqEcliptic = ASTROSUN::MeanObliquityOfEcliptic($jc);
        $obliqCorr = ASTROSUN::ObliqCorrected($meanObliqEcliptic, $jc);
        $meanLong = ASTROSUN::MeanLongitude($jc);
        $meanAnomaly = ASTROSUN::MeanAnomaly($jc);
        $eccentEarthOrbit = ASTROSUN::EccentEarthOrbit($jc);
        $varY = ASTROSUN::VarY($obliqCorr);
        $sunAppLong = ASTROSUN::SunAppLong($trueLongitudeSun, $jc);
        $eqOfT = ASTROSUN::EquationOfTime($meanLong, $meanAnomaly, $eccentEarthOrbit, $varY);
        $dec = ASTROSUN::Declination($sunAppLong, $obliqCorr);
        $solarnoon = ASTROSUN::SolarNoon($timezone, $long, $eqOfT);
        $HA = ASTROSUN::HourAngleAtSunrise($lat, $dec);
        $sunrise = ASTROSUN::Sunrise($solarnoon, $HA);
        $sunlight = ASTROSUN::SunlightDuration($HA);
        $trueSolarTime = ASTROSUN::TrueSolarTime(0.25, $eqOfT, $long, $timezone);
        
        return mktime(0,0,$sunrise*24*60*60,$month,$day,$year);
    }
}
?>