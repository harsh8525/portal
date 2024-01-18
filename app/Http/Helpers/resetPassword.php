<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;

function resetPassword($password){

                    
        $minPassLength = Setting::where('config_key', 'passwordSecurity|minimumPasswordLength')->get('value')[0]['value'];
        $minDigitsLength = Setting::where('config_key', 'passwordSecurity|numericCharacter')->get('value')[0]['value'];
        $minSpecialcharLength = Setting::where('config_key', 'passwordSecurity|specialCharacter')->get('value')[0]['value'];
        $minUppercharLength = Setting::where('config_key', 'passwordSecurity|uppercaseCharacter')->get('value')[0]['value'];
        $minLowercharLength = Setting::where('config_key', 'passwordSecurity|lowercaseCharacter')->get('value')[0]['value'];
        $minAlphanumericcharLength = Setting::where('config_key', 'passwordSecurity|alphanumericCharacter')->get('value')[0]['value'];
        $response = [
            'valid' => false,
        ];
        $matches = [];
        //validate pass length based on setting value
        if ($minPassLength > strlen($password['password'])) {
            $response['valid'] = false;
            $response['message'] = "The ".$password['key']." should contain atleast " . $minPassLength . " charcters";
        }
        //validate pass that should contain digits
        else if (preg_match_all("/\d/", $password['password'], $matches) < $minDigitsLength) {
            $response['valid'] = false;
            $response['message'] = "The ".$password['key']." should contain at least " . $minDigitsLength . " digit";
        }
        //validate pass that should contain special character
        else if (preg_match_all("/\W/", $password['password'], $matches) < $minSpecialcharLength) {
            $response['valid'] = false;
            $response['message'] = "The ".$password['key']." should contain at least " . $minSpecialcharLength . " special character";
        }
        //validate pass that should contain capital letter
        else if (!preg_match('/^(.*?[A-Z]){' . $minUppercharLength . '}/', $password['password'])) {
            $response['valid'] = false;
            $response['message'] = "The ".$password['key']." should contain at least " . $minUppercharLength . " Capital Letter";
        }
        //validate pass that should contain small letter
        else if (!preg_match('/^(.*?[a-z]){' . $minLowercharLength . '}/', $password['password'])) {
            $response['valid'] = false;
            $response['message'] = "The ".$password['key']." should contain at least " . $minLowercharLength . " small Letter";
        }
        //validate pass that shoult contain alphanumeric
        else if (preg_match_all("/[a-zA-Z0-9]/", $password['password'], $matches) < $minAlphanumericcharLength) {
            $response['valid'] = false;
            $response['message'] = "The ".$password['key']." should contain at least " . $minAlphanumericcharLength . " alphanumeric character";
        } else {
            $response['valid'] = true;
        }

        return $response;
            
       
}