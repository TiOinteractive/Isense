<?php

namespace App\Validation;

class CustomRules {

    public function empty(?string $str = null): bool
    {
        return empty($str);
    }
    
    public function valid_phone(?string $str = null): bool
    {
        if(empty($str)) {
            return true;
        }
        if (preg_match('/^[+][0-9]/', $str)) {
            $count = 1;
            $str = str_replace(['+'], '', $str, $count);
        }
        $str = str_replace([' ', '.', '-', '(', ')'], '', $str); 
        return (bool) preg_match('/^[0-9]{7,14}\z/', $str);
    }
    
    public function valid_nip(?string $str = null): bool
    {
        if(empty($str)) {
            return true;
        }
        $str = preg_replace("/-/", "", $str);
        $reg = '/^[0-9]{10}$/';
        if(preg_match($reg, $str) == false) {
            return false;
        } else {
            $digits = str_split($str);
            $checksum = (6*intval($digits[0]) + 5*intval($digits[1]) + 7*intval($digits[2]) + 2*intval($digits[3]) + 3*intval($digits[4]) + 4*intval($digits[5]) + 5*intval($digits[6]) + 6*intval($digits[7]) + 7*intval($digits[8]))%11;

            return (intval($digits[9]) == $checksum);
        }
    }
    
    public function valid_pesel(?string $str = null): bool
    {
        if(empty($str)) {
            return true;
        }
        $reg = '/^[0-9]{11}$/';
        if(preg_match($reg, $str) == false) {
            return false;
        } else {
            $digits = str_split($str);
            if ((intval(substr($str, 4, 2)) > 31)||(intval(substr($str, 2, 2)) > 12))
                return false;
            $checksum = (1*intval($digits[0]) + 3*intval($digits[1]) + 7*intval($digits[2]) + 9*intval($digits[3]) + 1*intval($digits[4]) + 3*intval($digits[5]) + 7*intval($digits[6]) + 9*intval($digits[7]) + 1*intval($digits[8]) + 3*intval($digits[9]))%10;
            if($checksum == 0) 
                $checksum = 10;
            $checksum = 10 - $checksum;

            return (intval($digits[10]) == $checksum);
        }
    }
    
    public function valid_regon(?string $str = null): bool
    {
        if(empty($str)) {
            return true;
        }
        $reg = '/^[0-9]{9}$/';
        if(preg_match($reg, $str) == false) {
            return false;
        } else {
            $digits = str_split($str);
            $checksum = (8*intval($digits[0]) + 9*intval($digits[1]) + 2*intval($digits[2]) + 3*intval($digits[3]) + 4*intval($digits[4]) + 5*intval($digits[5]) + 6*intval($digits[6]) + 7*intval($digits[7]))%11;
            if($checksum == 10) 
                $checksum = 0;

            return (intval($digits[8]) == $checksum);
        }
    }
    
    public function valid_zip_code(?string $str = null): bool
    {
        if(empty($str)) {
            return true;
        }
        return (bool) preg_match('/^[0-9]{2}-?[0-9]{3}$/Du', $str);
    }
	
	public function check_unique($str,$num) {
		if($num>0) {
		 return false;	
		}	
	}	

}
