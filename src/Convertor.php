<?php

/*
 * This file is part of the Convertor package.
 *
 * (c) Oliver Folkerd <oliver.folkerd@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Olifolkerd\Convertor;

use Olifolkerd\Convertor\Exceptions\ConvertorDifferentTypeException;
use Olifolkerd\Convertor\Exceptions\ConvertorException;
use Olifolkerd\Convertor\Exceptions\ConvertorInvalidUnitException;

class Convertor
{
    private $value = null; //value to convert
    private $baseUnit = false; //base unit of value

    //array to hold unit conversion functions
    private $units = array();


    /**
     * setup units conversion array
     */
    function defineUnits()
    {

        $this->units = array(
            ///////Units Of Length///////
            "m" => array("base" => "m", "conversion" => 1), //meter - base unit for distance
            "km" => array("base" => "m", "conversion" => 1000), //kilometer
            "dm" => array("base" => "m", "conversion" => 0.1), //decimeter
            "cm" => array("base" => "m", "conversion" => 0.01), //centimeter
            "mm" => array("base" => "m", "conversion" => 0.001), //milimeter
            "µm" => array("base" => "m", "conversion" => 0.000001), //micrometer
            "nm" => array("base" => "m", "conversion" => 0.000000001), //nanometer
            "pm" => array("base" => "m", "conversion" => 0.000000000001), //picometer
            "in" => array("base" => "m", "conversion" => 0.0254), //inch
            "ft" => array("base" => "m", "conversion" => 0.3048), //foot
            "yd" => array("base" => "m", "conversion" => 0.9144), //yard
            "mi" => array("base" => "m", "conversion" => 1609.344), //mile
            "h" => array("base" => "m", "conversion" => 0.1016), //hand
            "ly" => array("base" => "m", "conversion" => 9460730472580800), //lightyear
            "au" => array("base" => "m", "conversion" => 149597870700), //astronomical unit
            "pc" => array("base" => "m", "conversion" => 30856775814913672.789139379577965), //parsec


            ///////Units Of Area///////
            "m**2" => array("base" => "m**2", "conversion" => 1), //meter square - base unit for area
            "km**2" => array("base" => "m**2", "conversion" => 1000000), //kilometer square
            "cm**2" => array("base" => "m**2", "conversion" => 0.0001), //centimeter square
            "mm**2" => array("base" => "m**2", "conversion" => 0.000001), //milimeter square
            "ft**2" => array("base" => "m**2", "conversion" => 0.092903), //foot square
            "mi**2" => array("base" => "m**2", "conversion" => 2589988.11), //mile square
            "ac" => array("base" => "m**2", "conversion" => 4046.86), //acre
            "ha" => array("base" => "m**2", "conversion" => 10000), //hectare

            ///////Units Of Volume///////
            "dm3" => array("base" => "l", "conversion" => 1), //cubic decimeter - litre
            "l" => array("base" => "l", "conversion" => 1), //litre - base unit for volume
            "ml" => array("base" => "l", "conversion" => 0.001), //mililitre
            "cm3" => array("base" => "l", "conversion" => 0.001), //cubic centimeter - mililitre
            "hl" => array("base" => "l", "conversion" => 100), //hectolitre
            "kl" => array("base" => "l", "conversion" => 1000), //kilolitre
            "m3" => array("base" => "l", "conversion" => 1000), //meters cubed - kilolitre
            "pt" => array("base" => "l", "conversion" => 0.56826125), //pint
            "gal" => array("base" => "l", "conversion" => 4.405), //gallon
            "qt" => array("base" => "l", "conversion" => 1.1365225), //quart
            "ft3" => array("base" => "l", "conversion" => 28.316846592), //cubic feet
            "in3" => array("base" => "l", "conversion" => 0.016387064), //cubic inches

            ///////Units Of Weight///////
            "kg" => array("base" => "kg", "conversion" => 1), //kilogram - base unit for weight
            "g" => array("base" => "kg", "conversion" => 0.001), //gram
            "mg" => array("base" => "kg", "conversion" => 0.000001), //miligram
            "N" => array("base" => "kg", "conversion" => 1 / 9.80665002863885), //Newton (based on earth gravity)
            "st" => array("base" => "kg", "conversion" => 6.35029), //stone
            "lb" => array("base" => "kg", "conversion" => 0.453592), //pound
            "oz" => array("base" => "kg", "conversion" => 0.0283495), //ounce
            "t" => array("base" => "kg", "conversion" => 1000), //metric tonne
            "ukt" => array("base" => "kg", "conversion" => 1016.047), //UK Long Ton
            "ust" => array("base" => "kg", "conversion" => 907.1847), //US short Ton

            //////Units Of Speed///////
            "mps" => array("base" => "mps", "conversion" => 1), //meter per seond - base unit for speed
            "kph" => array("base" => "mps", "conversion" => 0.44704), //kilometer per hour
            "mph" => array("base" => "mps", "conversion" => 0.277778), //kilometer per hour

            ///////Units Of Rotation///////
            "deg" => array("base" => "deg", "conversion" => 1), //degrees - base unit for rotation
            "rad" => array("base" => "deg", "conversion" => 57.2958), //radian

            ///////Units Of Temperature///////
            "k" => array("base" => "k", "conversion" => 1), //kelvin - base unit for distance
            "c" => array("base" => "k", "conversion" => function ($val, $tofrom) {
                return $tofrom ? $val - 273.15 : $val + 273.15;
            }), //celsius
            "f" => array("base" => "k", "conversion" => function ($val, $tofrom) {
                return $tofrom ? ($val * 9 / 5 - 459.67) : (($val + 459.67) * 5 / 9);
            }), //Fahrenheit

            ///////Units Of Pressure///////
            "pa" => array("base" => "pa", "conversion" => 1), //Pascal - base unit for Pressure
            "hpa" => array("base" => "pa", "conversion" => 100), //hpa
            "kpa" => array("base" => "pa", "conversion" => 1000), //kilopascal
            "mpa" => array("base" => "pa", "conversion" => 1000000), //megapascal
            "bar" => array("base" => "pa", "conversion" => 100000), //bar
            "mbar" => array("base" => "pa", "conversion" => 100), //milibar
            "psi" => array("base" => "pa", "conversion" => 6894.76), //pound-force per square inch

            ///////Units Of Time///////
            "s" => array("base" => "s", "conversion" => 1), //second - base unit for time
            "year" => array("base" => "s", "conversion" => 31536000), //year - standard year
            "month" => array("base" => "s", "conversion" => 18748800), //month - 31 days
            "week" => array("base" => "s", "conversion" => 604800), //week
            "day" => array("base" => "s", "conversion" => 86400), //day
            "hr" => array("base" => "s", "conversion" => 3600), //hour
            "min" => array("base" => "s", "conversion" => 60), //minute
            "ms" => array("base" => "s", "conversion" => 0.001), //milisecond
            "μs" => array("base" => "s", "conversion" => 0.000001), //microsecond
            "ns" => array("base" => "s", "conversion" => 0.000000001), //nanosecond

            ///////Units Of Power///////
            "j" => array("base" => "j", "conversion" => 1), //joule - base unit for energy
            "kj" => array("base" => "j", "conversion" => 1000), //kilojoule
            "mj" => array("base" => "j", "conversion" => 1000000), //megajoule
            "cal" => array("base" => "j", "conversion" => 4184), //calorie
            "Nm" => array("base" => "j", "conversion" => 1), //newton meter
            "ftlb" => array("base" => "j", "conversion" => 1.35582), //foot pound
            "whr" => array("base" => "j", "conversion" => 3600), //watt hour
            "kwhr" => array("base" => "j", "conversion" => 3600000), //kilowatt hour
            "mwhr" => array("base" => "j", "conversion" => 3600000000), //megawatt hour
            "mev" => array("base" => "j", "conversion" => 0.00000000000000016), //mega electron volt

            ///////Area density///////
            "kg m**-2" => array("base" => "kg m**-2", "conversion" => 1),
            //vary area
            "kg km**-2" => array("base" => "kg m**-2", "conversion" => 0.000001),
            "kg cm**-2" => array("base" => "kg m**-2", "conversion" => 1e4),
            "kg mm**-2" => array("base" => "kg m**-2", "conversion" => 1e6),
            //vary weight
            "g m**-2" => array("base" => "kg m**-2", "conversion" => 0.001), //gram
            "mg m**-2" => array("base" => "kg m**-2", "conversion" => 0.000001), //miligram
            "st m**-2" => array("base" => "kg m**-2", "conversion" => 6.35029), //stone
            "lb m**-2" => array("base" => "kg m**-2", "conversion" => 0.453592), //pound
            "oz m**-2" => array("base" => "kg m**-2", "conversion" => 0.0283495), //ounce
            //todo: add your density conversions here if you need them.

        );
    }


    /**
     * Convertor constructor.
     * @param $value - to convert
     * @param $unit - base unit
     */
    function __construct($value = null, $unit = null)
    {//

        //create units array
        $this->defineUnits();

        //unit optional
        if (!is_null($value) && !is_null($unit)) {

            //set from unit
            $this->from($value, $unit);
        }

    }

    /**
     * Set from conversion value / unit
     *
     * @param    number $value -  a numeric value to base conversions on
     * @param    string $unit (optional) - the unit symbol for the start value
     * @return   mixed
     */
    public function from($value, $unit)
    {

        //check if value has been set
        if (is_null($value)) {
            throw new ConvertorException("Value Not Set");
        }

        if ($unit) {
            //check that unit exists
            if (array_key_exists($unit, $this->units)) {
                if (isset($this->units[$unit]))
                    $unitLookup = $this->units[$unit];

                if (isset($unitLookup)) {

                    //convert unit to base unit for this unit type
                    $this->baseUnit = $unitLookup["base"];
                    $this->value = $this->convertToBase($value, $unitLookup);
                }
            } else {
                throw new ConvertorInvalidUnitException("Conversion from Unit u=$unit not possible - unit does not exist.");
            }
        } else {
            $this->value = $value;
        }
    }

    /**
     * Convert from value to new unit
     *
     * @param    mixed $unit -  the unit symbol (or array of symbols) for the conversion unit
     * @param    int $decimals (optional, default-null) - the decimal precision of the conversion result
     * @param    boolean $round (optional, default-true) - round or floor the conversion result
     * @return   mixed
     */
    public function to($unit, $decimals = null, $round = true)
    {

        //check if from value is set
        if (is_null($this->value)) {
            throw new ConvertorException("From Value Not Set.");
        }

        //check if to unit is set
        if (!$unit) {
            throw new ConvertorException("Unit Not Set");
        }

        //if unit is array, iterate through each unit
        if (is_array($unit)) {
            return $this->toMany($unit, $decimals, $round);
        } else {
            //check unit symbol exists
            if (array_key_exists($unit, $this->units)) {
                $unitLookup = $this->units[$unit];

                $result = 0;

                //if from unit not provided, assume base unit of to unit type
                if ($this->baseUnit) {
                    if ($unitLookup["base"] != $this->baseUnit) {
                        throw new ConvertorDifferentTypeException("Cannot Convert Between Units of Different Types");
                    }
                } else {
                    $this->baseUnit = $unitLookup["base"];
                }

                //calculate converted value
                if (is_callable($unitLookup["conversion"])) {
                    // if unit has a conversion function, run value through it
                    $result = $unitLookup["conversion"]($this->value, true);
                } else {
                    $result = $this->value / $unitLookup["conversion"];
                }

                //result precision and rounding
                if (!is_null($decimals)) {
                    if ($round) {
                        //round to the specifidd number of decimals
                        $result = round($result, $decimals);
                    } else {
                        //truncate to the nearest number of decimals
                        $shifter = $decimals ? pow(10, $decimals) : 1;
                        $result = floor($result * $shifter) / $shifter;
                    }
                }

                return $result;
            } else {
                throw new ConvertorInvalidUnitException("Conversion to Unit u=$unit not possible - unit does not exist.");
            }
        }
    }

    /**
     * Itterate through multiple unit conversions
     *
     * @param    string[] $unit -  the array of symblos for the conversion units
     * @param    int $decimals (optional, default-null) - the decimal precision of the conversion result
     * @param    boolean $round (optional, default-true) - round or floor the conversion result
     * @return   array - results of the coversions
     */
    private function toMany($unitList = [], $decimals = null, $round = true)
    {

        $resultList = array();

        foreach ($unitList as $key) {
            //convert units for each element in the array
            $resultList[$key] = $this->to($key, $decimals, $round);
        }

        return $resultList;
    }


    /**
     * Convert from value to all compatable units
     *
     * @param    int $decimals (optional, default-null) - the decimal precision of the conversion result
     * @param    boolean $round (optional, default-true) - round or floor the conversion result
     * @return   array - results of conversion to all units with matching base units
     */
    public function toAll($decimals = null, $round = true)
    {

        //ensure the from value has been set correctly
        if (is_null($this->value)) {
            throw new ConvertorException("From Value Not Set");
        }

        //ensure the base unit has been set correctly
        if ($this->baseUnit) {

            $unitList = array();

            //build array of units that share the same base unit.
            foreach ($this->units as $key => $values) {
                if ($values["base"] == $this->baseUnit) {
                    array_push($unitList, $key);
                }
            }

            //convert units for all matches
            return $this->toMany($unitList, $decimals, $round);

        } else {
            throw new ConvertorException("No From Unit Set");
        }

    }


    /**
     * Add Conversion Unit
     *
     * @param    string $unit - the symbol for the new unit
     * @param    string $base - the symbol for the base unit of this unit
     * @param    number /function() - the conversion ration or conversion function from this unit to its base unit
     * @return   boolean - true - if successfull
     */
    public function addUnit($unit, $base, $conversion)
    {

        //check that the new unit does not ealread exist
        if (array_key_exists($unit, $this->units)) {
            throw new ConvertorException("Unit Already Exists");
        } else {
            //make sure the base unit for the new unit exists or that the new unit is a base unit itself
            if (!array_key_exists($base, $this->units) && $base != $unit) {
                throw new ConvertorException("Base Unit Does Not Exist");
            } else {
                //add unit to units array
                $this->units[$unit] = array("base" => $base, "conversion" => $conversion);
                return true;
            }
        }

    }


    /**
     * Remove Conversion Unit
     *
     * @param    string $unit - the symbol for the unit to be removed
     * @return   boolean - true - if successful
     */
    public function removeUnit($unit)
    {
        //check unit exists
        if (array_key_exists($unit, $this->units)) {

            //if unit is base unit remove all dependant units
            if ($this->units[$unit]["base"] == $unit) {
                foreach ($this->units as $key => $values) {
                    if ($values["base"] == $unit) {
                        //remove unit
                        unset($this->units[$key]);
                    }
                }

            } else {
                //remove unit
                unset($this->units[$unit]);
            }

            return true;

        } else {
            throw new ConvertorInvalidUnitException("Removal of Unit u=$unit not possible - unit does not exist.");
        }
    }

    /**
     * List all available conversion units for given unit
     *
     * @param    string $unit - the symbol to search for available conversion units
     * @return   array - list of all available conversion units
     */
    public function getUnits($unit)
    {
        //check that unit exists
        if (array_key_exists($unit, $this->units)) {
            //find base unit
            $baseUnit = $this->units[$unit]["base"];

            $unitList = array();
            //find all units that are linked to the base unit
            foreach ($this->units as $key => $values) {
                if ($values["base"] == $baseUnit) {
                    array_push($unitList, $key);
                }
            }

            return $unitList;
        } else {
            throw new ConvertorInvalidUnitException("Unit u=$unit Does Not Exist");
        }
    }

    /**
     * Convert from value to its base unit
     *
     * @param    number $value - from value
     * @param    array $unitArray - unit array from object units array
     * @return   number - converted value
     */
    private function convertToBase($value, $unitArray)
    {

        if (is_callable($unitArray["conversion"])) {
            // if unit has a conversion function, run value through it
            return $unitArray["conversion"]($value, false);
        } else {
            return $value * $unitArray["conversion"];
        }
    }
}
