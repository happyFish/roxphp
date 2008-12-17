<?php
/**
 * RoxPHP
 *
 * Copyright (C) 2008 Ramon Torres
 *
 * This Software is released under the MIT License.
 * See license.txt for more details.
 *
 * @package Rox
 * @author Ramon Torres
 * @copyright Copyright (c) 2008 Ramon Torres (http://roxphp.com)
 * @license http://roxphp.com/static/license.html
 * @version $Id$
 */

/**
 *  Validator class
 *
 * @package Rox
 * @copyright Copyright (c) 2008 Ramon Torres
 * @license http://roxphp.com/static/license.html
 */
class Validator {

	const VALID_EMAIL_PATTERN = '/^([A-Z0-9._%+-]+@[A-Z0-9.-]+\.(?:[A-Z]{2}|com|org|net|gov|mil|biz|info|mobi|name|aero|jobs|museum))$/i';

	/**
	 * Validator::regexMatch()
	 *
	 * @param string $pattern
	 * @param string $subject
	 * @return boolean
	 */
	public static function regexMatch($pattern, $subject) {
		return preg_match($pattern, $subject) == 1;
	}

	/**
	 * Validator::email()
	 *
	 * @param string $subject
	 * @return boolean
	 */
	public static function email($subject) {
		return Validator::regexMatch(Validator::VALID_EMAIL_PATTERN, $subject);
	}

	/**
	 * Validator::minLength()
	 *
	 * @param string $subject
	 * @param integer $min
	 * @return boolean
	 */
	public static function minLength($subject, $min) {
		return strlen($subject) >= $min;
	}

	/**
	 * Validator::maxLength()
	 *
	 * @param string $subject
	 * @param integer $max
	 * @return boolean
	 */
	public static function maxLength($subject, $max) {
		return strlen($subject) <= $max;
	}

	/**
	 * Validator::notEmpty()
	 *
	 * @param string $subject
	 * @return boolean
	 */
	public static function notEmpty($subject) {
		$subject = trim($subject);
		return !empty($subject);
	}

	/**
	 * Validator::between()
	 *
	 * @param string $subject
	 * @param integer $min
	 * @param integer $max
	 * @return boolean
	 */
	public static function between($subject, $min, $max) {
		$len = strlen($subject);
		return (($len > $min) && ($len < $max));
	}

	/**
	 * Verifies a number using the Luhn algorithm
	 *
	 * @link http://en.wikipedia.org/wiki/Luhn_algorithm
	 * @link http://www.pat2pdf.org/pat2pdf/foo.pl?number=2950048
	 * @param string $number
	 * @return boolean
	 */
	public static function luhn($number) {
		$digits = array_reverse(str_split($number));
		$totalDigits = count($digits);

		$sum = 0;
		$alternate = false;
		for ($i=0; $i<$totalDigits; $i++) {
			$digit = $digits[$i];
			if ($alternate) {
				$digit = $digit * 2;
				if ($digit > 9) {
					$digit = $digit - 9;
				}
			}

			$sum += $digit;
			$alternate = !$alternate;
		}

		return ($sum % 10 == 0);
	}

	/**
	 * Validates a credit card number
	 *
	 * If you want to restrict the credit card type you can pass it as the
	 * seccond argument:
	 *
	 * Validator::creditCard(..., 'VISA')
	 * Validator::creditCard(..., array('VISA', 'AMEX'))
	 *
	 * @param string $number
	 * @param string|array $type
	 * @return boolean
	 */
	public static function creditCard($number, $type = null) {
		$ccTypesRegEx = array(
			'MASTERCARD' => '/^5[1-5][0-9]{14}$/',
			'VISA'       => '/^4[0-9]{12}([0-9]{3})?$/',
			'AMEX'       => '/^3[4|7][0-9]{13}$/',
			'DISCOVER'   => '/^(?:6011|644[0-9]|65[0-9]{2})[0-9]{12}$/'
		);

		if (is_string($type)) {
			$type = array($type);
		}

		if (is_array($type)) {
			$ccTypesRegEx = array_intersect_key($ccTypesRegEx, array_flip($type));
		}

		foreach ($ccTypesRegEx as $type => $regEx) {
			if (self::regexMatch($regEx, $number)) {
				return self::luhn($number);
			}
		}

		return false;
	}
}