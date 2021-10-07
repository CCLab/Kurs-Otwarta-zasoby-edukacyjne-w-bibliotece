<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Pesel checker.
 *
 * @package   local_pesel
 * @copyright 2020 INTERSIEC.com.pl
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();


/**
 * Class PeselChecker
 */
class PeselChecker {
	public static $pesel;
	public static $dataPodana;
	public static $response;
	
	static function verify($pesel){		
		// Kolejne metody zwracają kolejne cechy podanego numeru pesel

		self::$pesel=$pesel;
			
		self::is11Integer();	// sprawdzenie czy ma 11 cyfr
		self::checkSum();		// sprawdzanie sumy kontrolnej
		self::verifyBirtDate();// sprawdzanie daty urodzenia
		//self::getAge();		// obliczanie wieku
		//self::getSex();		// określenie płci
			
		$peselStatus = self::returnResponse();
		
		$result = true;
		foreach($peselStatus as $key => $peselStatu){
			if(!$peselStatu){
				$result = false;
				break;
			}
		}		
		return $result;	
	}

	static function is11Integer(){			
		// Czy składa sie z 11 cyfr

		self::$response['has11Digits']=true;
		if (!preg_match('/^[0-9]{11}$/', self::$pesel)) self::$response['has11Digits']=false;			
	}

	static function checkSum(){				
		// Klasyczna walidacja wg algorytmu Luhna z wagami stosowanymi przez MSWiA
		// Na podstawie: http://phpedia.pl/wiki/Walidacja_numeru_PESEL

		if (empty(self::$response['has11Digits'])) return;

		// tablica z odpowiednimi wagami

		$arrWagi = array(1, 3, 7, 9, 1, 3, 7, 9, 1, 3); 
		$intSum = 0;

		//mnożymy każdy ze znaków przez wagę i sumujemy wszystko

		for ($i = 0; $i < 10; $i++) {
			$intSum += $arrWagi[$i] * self::$pesel[$i]; 
		}

		//obliczamy sumę kontrolną i porównujemy ją z ostatnią cyfrą.

		$int = 10 - $intSum % 10; 
		$intControlNr = ($int == 10)?0:$int;

		//sprawdzamy czy taka sama suma kontrolna jest w ciągu

		if ($intControlNr == self::$pesel[10]){
			self::$response['isCheckSumOk']=true;
			return;
		}

		return  self::$response['isCheckSumOk']=false;
	}

	static function verifyBirtDate(){		
		// Walidacja daty urodzenia wg istnienia dnia w kalendarzu
		// Ze względu na działanie systemu PESEL na 5 stuleci
		// dopisuje się 80, 20, 40, 60 do daty w odpowiednich stuleciach

		if (empty(self::$response['has11Digits'])) return;
			
		//  Budowa tablicy możliwych wartości miesiąca	
			
		$miesiac=substr(self::$pesel,2,2);
		$arrMiesiace = Array('01','02','03','04','05','06','07','08','09','10','11','12');
		$arrDodatkoweMiesiace = Array(0,80,20,40,60);

		foreach ($arrDodatkoweMiesiace as $miesiacDodatkowy){
			$arrMiesiaceBazowe = range(1,12);
			foreach ($arrMiesiaceBazowe as $miesiacBazowy){
				$arrMiesiace[]=$miesiacDodatkowy+$miesiacBazowy;
			}
		}

		if (!in_array($miesiac,$arrMiesiace)){
			self::$response['isBirthDateValid']=false;
			return;
		}

		// Ustalanie faktycznego miesiąca i stulecia

		if (substr($miesiac,0,1)=='0' || substr($miesiac,0,1)=='1') $stulecie = 1900;
		if (substr($miesiac,0,1)=='8' || substr($miesiac,0,1)=='9') $stulecie = 1800;
		if (substr($miesiac,0,1)=='2' || substr($miesiac,0,1)=='3') $stulecie = 2000;
		if (substr($miesiac,0,1)=='5' || substr($miesiac,0,1)=='4') $stulecie = 2100;
		if (substr($miesiac,0,1)=='6' || substr($miesiac,0,1)=='7') $stulecie = 2200;

		if ($stulecie=='2000') $miesiac = $miesiac-20;
		if ($stulecie=='1800') $miesiac = $miesiac-80;
		if ($stulecie=='2100') $miesiac = $miesiac-40;
		if ($stulecie=='2200') $miesiac = $miesiac-60;

		// Walidacja liczby dni w miesiącu

		$rok=$stulecie+substr(self::$pesel,0,2);
		$maxDays = cal_days_in_month(CAL_GREGORIAN, $miesiac, $rok);

		$dzien = substr(self::$pesel,4,2);
		self::$dataPodana = "$rok-$miesiac-$dzien";

		if ($dzien > $maxDays){
			self::$response['isBirthDateValid']=false;
			return;
		}
		if ($dzien <=0){
			self::$response['isBirthDateValid']=false;
			return;
		}
		self::$response['isBirthDateValid']=true;
		self::$response['birthDate']=date_format(date_create(self::$dataPodana), 'Y-m-d');
	}

	static function getAge(){
		// Sprawdzenie wieku i daty urodzenia osoby, a także czy jest to osoba, która się jeszcze nie urodziła

		if (self::$response['has11Digits']<>true) return;
		if (empty(self::$response['isBirthDateValid'])) return;

		$dataAktualna	=date_create();
		$dataPodana		=date_create(self::$dataPodana);
		$interval 		=date_diff($dataAktualna, $dataPodana)->format('%R%a');	// ile dni

		if ($interval<0) {
			self::$response['bornAlready']=true;
			self::$response['personAgeYears']=-intval(date_diff($dataAktualna, $dataPodana)->format('%R%y'));
			self::$response['personAgeMonths']=-intval(date_diff($dataAktualna, $dataPodana)->format('%R%m'));
			self::$response['personAgeDays']=-intval(date_diff($dataAktualna, $dataPodana)->format('%R%d'));
			if (self::$response['personAgeYears']>=18) self::$response['fullAge']=true;
			if (self::$response['personAgeYears']<18)  self::$response['fullAge']=false;
		}
		if ($interval>0) {
			self::$response['bornAlready']=false;
			self::$response['possibleBornInYears']= intval(date_diff($dataAktualna, $dataPodana)->format('%y'));
			self::$response['possibleBornInMonths']=intval(date_diff($dataAktualna, $dataPodana)->format('%m'));
			self::$response['possibleBornInDays']=intval(date_diff($dataAktualna, $dataPodana)->format('%d'));
		}
	}

	static function getSex(){		
		// Sprawdzenie płci. 10 cyfra nieparzysta = mężczyzna, parzysta = kobieta
			
		if (self::$response['has11Digits']<>true) return;
		if (empty(self::$response['isBirthDateValid'])) return;
		$dziesiata = self::$pesel[9];
		if (($dziesiata % 2 == 0)==true) {
			self::$response['personSex']='F';
			return;
		}
		self::$response['personSex']='M';
	}

	static function returnResponse(){
		return self::$response;	
	}
}
