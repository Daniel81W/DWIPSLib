<?php

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
        $meanAnom = ASTROSUN::MeanAnomaly($julianCentury);
        return sin( deg2rad($meanAnom) ) * ( 1.914602 - $julianCentury * ( 0.004817 + 0.000014 * $julianCentury ) ) + sin( deg2rad( 2 * $meanAnom ) ) * ( 0.019993 - 0.000101 * $julianCentury ) + sin( deg2rad( 3 * $meanAnom ) ) * 0.000289;
    }

    /**
     * 
     */
    public static function EclipticLongitude(float $julianCentury){
        return ASTROSUN::MeanLongitude($julianCentury) + ASTROSUN::SunEqOfCtr( $julianCentury);
    }

    /**
     * 
     */
    public static function TrueAnomalySun(float $julianCentury){
        return ASTROSUN::MeanAnomaly($julianCentury) + ASTROSUN::SunEqOfCtr($julianCentury);
    }

    /**
     * 
     */
    public static function SunRadVector(float $julianCentury){
        $eeo = ASTROSUN::EccentEarthOrbit($julianCentury);
        return ( 1.000001018 * ( 1 - $eeo * $eeo ) ) / ( 1 + $eeo * cos( deg2rad( ASTROSUN::TrueAnomalySun($julianCentury) ) ) );
    }

    /**
     * 
     */
    public static function SunAppLong(float $julianCentury){
        return ASTROSUN::EclipticLongitude($julianCentury) - 0.00569 - 0.00478 * sin( deg2rad( 125.04 - 1934.136 * $julianCentury ) );
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
        return ASTROSUN::MeanObliquityOfEcliptic($julianCentury) + 0.00256 * cos( deg2rad( 125.04 - 1934.136 * $julianCentury ) );
    }

    /**
     * 
     */
    public static function RA(float $julianCentury){
        return rad2deg( atan2( cos( deg2rad( ASTROSUN::SunAppLong( $julianCentury) ) ) , cos( deg2rad( ASTROSUN::ObliqCorrected($julianCentury) ) ) * sin( deg2rad( ASTROSUN::SunAppLong( $julianCentury) ) ) ) );
    }

    /**
     * Deklination der Sonne
     */
    public static function Declination(float $julianCentury){
        return rad2deg( asin( sin( deg2rad( ASTROSUN::ObliqCorrected($julianCentury) ) ) * sin( deg2rad( ASTROSUN::SunAppLong( $julianCentury) ) ) ) );
    }

    /**
     * 
     */
    public static function VarY(float $julianCentury){
        return tan( deg2rad( ASTROSUN::ObliqCorrected($julianCentury) / 2 ) ) * tan( deg2rad( ASTROSUN::ObliqCorrected($julianCentury) / 2 ) );
    }

    /**
     * 
     */
    public static function EquationOfTime(float $julianCentury){
        return 4 * rad2deg(
            ASTROSUN::VarY( $julianCentury) * sin(
                    2*deg2rad(ASTROSUN::MeanLongitude($julianCentury))
                ) - 2 * ASTROSUN::EccentEarthOrbit($julianCentury) * sin(
                    deg2rad(ASTROSUN::MeanAnomaly($julianCentury))
                ) + 4 * ASTROSUN::EccentEarthOrbit($julianCentury) * ASTROSUN::VarY( $julianCentury) * sin(
                    deg2rad(ASTROSUN::MeanAnomaly($julianCentury))
                ) * cos(
                    2*deg2rad(ASTROSUN::MeanLongitude($julianCentury))
                ) - 0.5 * ASTROSUN::VarY( $julianCentury) * ASTROSUN::VarY( $julianCentury) * sin(
                    4*deg2rad(ASTROSUN::MeanLongitude($julianCentury))
                ) - 1.25 * ASTROSUN::EccentEarthOrbit($julianCentury) * ASTROSUN::EccentEarthOrbit($julianCentury) * sin(
                    2 * deg2rad(ASTROSUN::MeanAnomaly($julianCentury))
                )
            );
    }

    public static function HourAngleAtElevation(float $sunElevation, float $latitude, float $julianCentury){
        return rad2deg(acos(cos(deg2rad(90 - $sunElevation))/(cos(deg2rad($latitude))*cos(deg2rad(ASTROSUN::Declination($julianCentury))))-tan(deg2rad($latitude))*tan(deg2rad(ASTROSUN::Declination($julianCentury)))));
    }

    /**
     * 
     */
    public static function SolarNoon(int $timezone, float $longitude, float $julianCentury){
        if ($longitude >= -180 && $longitude <= 180) {
            return ( 720 - 4 * $longitude - ASTROSUN::EquationOfTime($julianCentury) + $timezone * 60 ) / 1440;
        }elseif ($longitude < -180) {
            return ( 720 - 4 * (360 + $longitude) - ASTROSUN::EquationOfTime($julianCentury) + $timezone * 60 ) / 1440;
        }elseif ($longitude > 180) {
            return ( 720 - 4 * (-360 + $longitude) - ASTROSUN::EquationOfTime($julianCentury) + $timezone * 60 ) / 1440;
        }
    }
        
    /**
     * 
     */
    public static function TimeForElevation(float $sunElevation, float $latitude, float $longitude, float $timezone, float $julianCentury, bool $beforeNoon){
        if ($beforeNoon){
            return ASTROSUN::SolarNoon($timezone, $longitude, $julianCentury) - ASTROSUN::HourAngleAtElevation($sunElevation, $latitude, $julianCentury) / 360;
        }else{
            return ASTROSUN::SolarNoon($timezone, $longitude, $julianCentury) + ASTROSUN::HourAngleAtElevation($sunElevation, $latitude, $julianCentury) / 360;
        }
    }

    public static function SunlightDuration(float $latitude, float $julianCentury){
        return 8 * ASTROSUN::HourAngleAtElevation(-0.833, $latitude, $julianCentury);
    }

    public static function TrueSolarTime(float $julianCentury, float $localTime, float $long, int $timezone){
        return fmod( $localTime * 1440 + ASTROSUN::EquationOfTime($julianCentury) + 4 * $long - 60 * $timezone , 1440);
    }

    /**
     * 
     */
    public static function HourAngle(float $julianCentury, float $localTime, float $long, int $timezone){
        $trueSolarTime = ASTROSUN::TrueSolarTime($localTime, $julianCentury, $long, $timezone);
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
        $declination = ASTROSUN::Declination( $julianCentury);
        $hourAngle = ASTROSUN::HourAngle( $localTime,  $julianCentury,  $long,  $timezone);
        return rad2deg(
            acos(sin(deg2rad($lat))*sin(deg2rad($declination))+cos(deg2rad($lat))*cos(deg2rad($declination))*cos(deg2rad($hourAngle)))
        );
    }

    /**
     * 
     */
    public static function SolarElevation(float $julianCentury, float $localTime, float $lat, float $long, float $timezone){
        return 90 - ASTROSUN::SolarZenith($julianCentury, $localTime, $lat, $long, $timezone);
    }

    /**
     * 
     */
    public static function SolarAzimut(float $julianCentury, float $localTime, float $latitude, float $longitude, int $timezone){
        $declination = ASTROSUN::Declination($julianCentury);
        $hourAngle = ASTROSUN::HourAngle($localTime, $julianCentury, $longitude, $timezone);
        $solarZenith = ASTROSUN::SolarZenith($julianCentury, $localTime, $latitude, $longitude, $timezone);
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

    public static function Season(float $julianCentury, float $latitude){
        $declination = ASTROSUN::Declination($julianCentury);
        $declinationBef = ASTROSUN::Declination($julianCentury) - 0.00000002;
        if($declination>=0){
            if($declination > $declinationBef){
                if($latitude > 0){
                    return "Spring";
                }else{
                    return "Fall";
                }
            }else{
                if($latitude > 0){
                    return "Summer";
                }else{
                    return "Winter";
                }
            }
        }else{
            if($declination > $declinationBef){
                if($latitude > 0){
                    return "Winter";
                }else{
                    return "Summer";
                }
            }else{
                if($latitude > 0){
                    return "Fall";
                }else{
                    return "Spring";
                }
            }
        }
    }

    public static function DurationOfSunrise(float $latitude, float $longitude, float $julianCentury){
        return TimeForElevation(0.833, $latitude, $longitude, 1, $julianCentury, true) - TimeForElevation(-0.833, $latitude, $longitude, 1, $julianCentury, true);
    }
}

class ASTROMOON{
    public static function Phase(){


        int $year;
        float $now, $vm, $diff, $anz;
        float $syn = 29.530588;
    
        int $phase = 1;
        $now = time();
        $year = date("Y", $now);
        if($year < 1900) { 
            $year += 1900; 
        }
        if($year >= 2010){
            $vm = mktime(20,12,36,11,31,2009) / 86400;
            $now = $now / 86400;
            $diff = $now - $vm;
            $anz = $diff / $syn;
            $phase = round($anz,2);
            $phase = floor(($phase - floor($phase)) * 100);
            if($phase == 0){
                $phase = 100;
            }
        }
        return $phase;
    }

    public static function PhaseStr(){
        $phase = ASTROMOON::Phase();
        string $text;
        if($phase == 0){
            $text = "Vollmond (2. Viertel)";
        }else if($phase < 25 or  ($phase > 25 and $phase < 50)){
            $text = "Abnehmender Mond";
        }else if($phase == 25) {
            $text = "Halbmond (3. Viertel)";
        }else if($phase == 50) {
            $ext = "Neumond (4. Viertel)";
        }else if(($phase > 50 and $phase < 75) or ($phase > 75 and $phase < 100)){
            $text = "Zunehmender Mond";
        }else if($phase == 75) {
            $text = "Halbmond (1. Viertel)";
        }
    }
}
?>