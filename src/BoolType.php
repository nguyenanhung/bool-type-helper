<?php
/**
 * Project bool-type-helper
 * Created by PhpStorm
 * User: 713uk13m <dev@nguyenanhung.com>
 * Copyright: 713uk13m <dev@nguyenanhung.com>
 * Date: 03/02/2023
 * Time: 11:06
 */

namespace nguyenanhung\Libraries\BoolType;

use InvalidArgumentException;
use BadMethodCallException;

/**
 * Class BoolType - The boolean (aka, "bool") class
 *
 * @package   nguyenanhung\Classes\Helper
 * @author    713uk13m <dev@nguyenanhung.com>
 * @copyright 713uk13m <dev@nguyenanhung.com>
 */
class BoolType
{
    /**
     * Returns $bool value in the string $format
     *
     * I'll return a bool value as a true-false, yes-no, or on-off string.
     *
     * For example:
     *
     *     BoolType::boolToString(true);             // returns (string) 'true'
     *     BoolType::boolToString(true, 'yes-no');   // returns (string) 'true'
     *     BoolType::boolToString(false, 'on-off');  // returns (string) 'off'
     *
     * @since  0.1.0
     *
     * @param bool   $bool    the boolean value to convert
     * @param string $format  the string format to convert to (possible values are
     *                        't[/-]f', true[/-]false', 'y[/-]n', 'yes[/-]no', 'o[/-o]', and 'on[/-]off')
     *                        (case-insensitive) (optional; if omitted, defaults to 'true-false')
     *
     * @return  string  the string value
     *
     * @throws  \BadMethodCallException    if $bool is null
     * @throws  \InvalidArgumentException  if $bool is not a (bool) value
     * @throws  \InvalidArgumentException  if $format is not a string
     * @throws  \InvalidArgumentException  if $format is not a valid format
     */
    public static function boolToString($bool, $format = 'true-false')
    {
        $string = false;

        // if $bool and format are not null
        if ($bool !== null && $format !== null) {
            // if $bool is actually a bool
            if (is_bool($bool)) {
                // if $format is a string
                if (is_string($format)) {
                    // switch on the lower-case $format
                    switch (mb_strtolower($format)) {

                        case 'oo':
                        case 'o/o':
                        case 'o-o':
                        case 'onoff':
                        case 'on/off':
                        case 'on-off':
                            $string = $bool ? 'on' : 'off';
                            break;

                        case 'tf':
                        case 't/f':
                        case 't-f':
                        case 'truefalse':
                        case 'true/false':
                        case 'true-false':
                            $string = $bool ? 'true' : 'false';
                            break;

                        case 'yn':
                        case 'y/n':
                        case 'y-n':
                        case 'yesno':
                        case 'yes/no':
                        case 'yes-no':
                            $string = $bool ? 'yes' : 'no';
                            break;

                        default:
                            throw new InvalidArgumentException(__METHOD__ . "() expects parameter two, format, to be one of the following: " . "'t[/-]f', 'true[/-]false', 'y[/-]s', 'yes[/-]no', 'o[/-]o', or " . "'on[/-]off', '$format' given");
                    }
                } else {
                    throw new InvalidArgumentException(__METHOD__ . "() expects parameter two, format, to be a string");
                }
            } else {
                throw new InvalidArgumentException(__METHOD__ . "() expects parameter one, bool, to be a bool value given");
            }
        } else {
            throw new BadMethodCallException(__METHOD__ . "() expects one or two parameters, a bool value and a string format");
        }

        return $string;
    }

    /**
     * Returns the boolean value of $var
     *
     * PHP's native boolval() function is not available before PHP 5.5, and it does not
     * support the strings 'yes', 'no', 'on', or 'off'.
     *
     * I follow the following rules:
     *
     *     Strings
     *         The strings "yes", "true", "on", or "1" are considered true, and
     *         the strings "no", "false", "off", or "0" are considered false
     *         (case-insensitive). Any other non-empty string is true.
     *
     *     Numbers
     *         The numbers 0 or 0.0 are considered false. Any other number is
     *         considered true.
     *
     *     Array
     *         An empty array is considered false. Any other array (even an
     *         associative array with no values) is considered true.
     *
     *     Object
     *         Any object is considered true.
     *
     * For example...
     *
     *     BoolType::val("");              // returns (bool) false
     *     BoolType::val(true);            // returns (bool) true
     *     BoolType::val(0);               // returns (bool) false
     *     BoolType::val(0.0);             // returns (bool) false
     *     BoolType::val('0');             // returns (bool) false
     *     BoolType::val('abc');           // returns (bool) true
     *     BoolType::val('true');          // returns (bool) true
     *     BoolType::val('on');            // returns (bool) true
     *     BoolType::val('yes');           // returns (bool) true
     *     BoolType::val('off');           // returns (bool) false
     *     BoolType::val([]);              // returns (bool) false
     *     BoolType::val([1, 2]);          // returns (bool) true
     *     BoolType::val(new StdClass());  // returns (bool) true
     *
     * @since  0.1.0
     *
     * @param mixed $var the variable to test
     *
     * @return  bool|null  the bool value
     *
     * @see    http://www.php.net/manual/en/function.boolval.php  boolval() man page
     */
    public static function val($var)
    {
        $value = null;

        // if $var is not empty
        // any value considered empty by empty() is considered false
        // for example, "0", array(), "", etc
        //
        if (!empty($var)) {
            // if $var is not already a bool type
            if (!is_bool($var)) {
                // if $var is a string
                if (is_string($var)) {
                    // switch on the string
                    // the strings '1', 'on', 'yes', and 'true' are considered true
                    // the strings '0', 'no', 'off', and 'false' are considered false
                    // any other non-empty string is true
                    //
                    switch (mb_strtolower($var)) {

                        case '1':
                        case 'on':
                        case 'yes':
                        case 'true':
                            $value = true;
                            break;

                        case '0':
                        case 'no':
                        case 'off':
                        case 'false':
                            $value = false;
                            break;

                        default:
                            $value = !empty($var);
                    }
                } elseif (is_numeric($var)) {
                    // any non-zero integer or float is considered true
                    $value = ($var !== 0 && $var !== 0.0);
                } elseif (is_object($var)) {
                    // any object is considered true
                    $value = true;
                } elseif (is_array($var)) {
                    // any non-empty array is considered true
                    $value = !empty($var);
                }
            } else {
                $value = $var;
            }
        } else {
            $value = false;
        }

        return $value;
    }
}
