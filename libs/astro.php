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
    public static function SunEqOfCtr(float $julianCentury){
        return sin( deg2rad(MeanAnomaly($julianCentury)) ) * ( 1.914602 - $julianCentury * ( 0.004817 + 0.000014 * $julianCentury ) ) + sin( deg2rad( 2 * MeanAnomaly($julianCentury) ) ) * ( 0.019993 - 0.000101 * $julianCentury ) + sin( deg2rad( 3 * MeanAnomaly($julianCentury) ) ) * 0.000289;
    }

    /**
     * 
     */
    public static function EclipticLongitude(float $julianCentury){
        return MeanLongitude($julianCentury) + SunEqOfCtr( $julianCentury);
    }

    /**
     * 
     */
    public static function TrueAnomalySun(float $julianCentury){
        return MeanAnomaly($julianCentury) + SunEqOfCtr($julianCentury);
    }

    /**
     * 
     */
    public static function SunRadVector(float $julianCentury){
        return ( 1.000001018 * ( 1 - EccentEarthOrbit($julianCentury) * EccentEarthOrbit($julianCentury) ) ) / ( 1 + EccentEarthOrbit($julianCentury) * cos( deg2rad( TrueAnomalySun($julianCentury) ) ) );
    }

    /**
     * 
     */
    public static function SunAppLong(float $julianCentury){
        return EclipticLongitude($julianCentury) - 0.00569 - 0.00478 * sin( deg2rad( 125.04 - 1934.136 * $julianCentury ) );
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
    public static function ObliqCorrected(float $julianCentury){
        return MeanObliquityOfEcliptic($julianCentury) + 0.00256 * cos( deg2rad( 125.04 - 1934.136 * $julianCentury ) );
    }

    /**
     * 
     */
    public static function RA(float $julianCentury){
        return rad2deg( atan2( cos( deg2rad( SunAppLong( $julianCentury) ) ) , cos( deg2rad( ObliqCorrected($julianCentury) ) ) * sin( deg2rad( SunAppLong( $julianCentury) ) ) ) );
    }

    /**
     * Deklination der Sonne
     */
    public static function Declination(float $julianCentury){
        return rad2deg( asin( sin( deg2rad( ObliqCorrected($julianCentury) ) ) * sin( deg2rad( SunAppLong( $julianCentury) ) ) ) );
    }

    /**
     * 
     */
    public static function VarY(float $julianCentury){
        return tan( deg2rad( ObliqCorrected($julianCentury) / 2 ) ) * tan( deg2rad( ObliqCorrected($julianCentury) / 2 ) );
    }

    /**
     * 
     */
    public static function EquationOfTime(float $julianCentury){
        return 4 * rad2deg(
            VarY( $julianCentury) * sin(
                    2*deg2rad(MeanLongitude($julianCentury))
                ) - 2 * EccentEarthOrbit($julianCentury) * sin(
                    deg2rad(MeanAnomaly($julianCentury))
                ) + 4 * EccentEarthOrbit($julianCentury) * VarY( $julianCentury) * sin(
                    deg2rad(MeanAnomaly($julianCentury))
                ) * cos(
                    2*deg2rad(MeanLongitude($julianCentury))
                ) - 0.5 * VarY( $julianCentury) * VarY( $julianCentury) * sin(
                    4*deg2rad(MeanLongitude($julianCentury))
                ) - 1.25 * EccentEarthOrbit($julianCentury) * EccentEarthOrbit($julianCentury) * sin(
                    2 * deg2rad(MeanAnomaly($julianCentury))
                )
            );
    }

    public static function HourAngleAtElevation(float $sunElevation, float $latitude, float $julianCentury){
        return rad2deg(acos(cos(deg2rad(90 - $sunElevation))/(cos(deg2rad($latitude))*cos(deg2rad(Declination($julianCentury))))-tan(deg2rad($latitude))*tan(deg2rad(Declination($julianCentury)))));
    }

    /**
     * 
     */
    public static function SolarNoon(int $timezone, float $longitude, float $julianCentury){
        if ($longitude >= -180 && $longitude <= 180) {
            return ( 720 - 4 * $longitude - EquationOfTime($julianCentury) + $timezone * 60 ) / 1440;
        }elseif ($longitude < -180) {
            return ( 720 - 4 * (360 + $longitude) - EquationOfTime($julianCentury) + $timezone * 60 ) / 1440;
        }elseif ($longitude > 180) {
            return ( 720 - 4 * (-360 + $longitude) - EquationOfTime($julianCentury) + $timezone * 60 ) / 1440;
        }
    }
        
    /**
     * 
     */
    public static function TimeForElevation(float $sunElevation, float $latitude, float $longitude, float $timezone, float $julianCentury, bool $beforeNoon){
        if ($beforeNoon){
            return SolarNoon($timezone, $longitude, $julianCentury) - HourAngleAtElevation(float $sunElevation, float $latitude, float $julianCentury) / 360;
        }else{
            return SolarNoon($timezone, $longitude, $julianCentury) + HourAngleAtElevation(float $sunElevation, float $latitude, float $julianCentury) / 360;
        }
    }

    public static function SunlightDuration(float $latitude, float $julianCentury){
        return 8 * HourAngleAtElevation(-0.833, $latitude, $julianCentury);
    }

    public static function TrueSolarTime(float $localTime, float $julianCentury, float $long, int $timezone){
        return fmod( $localTime * 1440 + EquationOfTime($julianCentury) + 4 * $long - 60 * $timezone , 1440);
    }

    /**
     * 
     */
    public static function HourAngle(float $localTime, float $julianCentury, float $long, int $timezone){
        $trueSolarTime = TrueSolarTime(float $localTime, float $julianCentury, float $long, int $timezone);
        if ($trueSolarTime / 4 < 0){
            return $trueSolarTime / 4 + 180;
        }else{
            return $trueSolarTime / 4 - 180;
        }
    }

    /**
     * 
     */
    public static function SolarZenith(float $julianCentury, float $localTime, float $lat, float $long, float $timezone){
        $declination = Declination( $julianCentury);
        $hourAngle = HourAngle( $localTime,  $julianCentury,  $long,  $timezone);
        return rad2deg(
            acos(sin(deg2rad($lat))*sin(deg2rad($declination))+cos(deg2rad($lat))*cos(deg2rad($declination))*cos(deg2rad($hourAngle)))
        );
    }

    /**
     * 
     */
    public static function SolarElevation(float $julianCentury, float $localTime, float $lat, float $long, float $timezone){
        return 90 - SolarZenith($julianCentury, $localTime, $lat, $long, $timezone);
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

    public static function SolarDirection(float $solarAzimut){
        $sector = intdiv($solarAzimut, 22.5);

        switch ($sector) {
            case 0:
                return "N";
            case 1:
                return "NNE";
            case 2:
                return "NE";
            case 3:
                return "ENE";
            case 4:
                return "E";
            case 5:
                return "ESE";
            case 6:
                return "SE";
            case 7:
                return "SSE";
            case 8:
                return "S";
            case 9:
                return "SSW";
            case 10:
                return "SW";
            case 11:
                return "WSW";
            case 12:
                return "W";
            case 13:
                return "WNW";
            case 14:
                return "NW";
            case 15:
                return "NNW";
            default:
                return "";
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

    public static function Season(float $declination, float $julianCentury, $latitude){
        if($declination>=0){
            if()
        }else{

        }
    }
}
?>