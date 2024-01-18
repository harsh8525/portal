<?php

use Illuminate\Support\Facades\Validator;
use App\Models\CurrencyExchangeRates;
use App\Models\Currency;

/**
 * 
 * Convert your price into your given Currency Code
 * 
 * @param float $rate Price which you want to convert into your $formCurrencyCode to $toCurrencyCode
 * @param string $from Pass source Currency Code
 * @param string $to (optional) Pass destination Currency Code. Default is <b>USD.</b>
 * @param array $options Pass additional information into $options array
 * @return array Return the response of currency conversion
 */
function convertCurrencyExchangeRate(float $rate, string $from, string $to = 'USD', array $options = [])
{
    $response['status'] = false;
    $response['message'] = "Rate could not be converted to your given currency.";
    $response['data'] = [];

    try {
        if (empty(trim($to))) {
            $to = 'USD';
        }

        $conditions['fromCurrencyCode'] = $from;
        $conditions['toCurrencyCode'] = $to;

        $currency_exchange_rate_info = CurrencyExchangeRates::where('from_currency_code', $from)->where('to_currency_code', $to)->get()->first();

        if (!empty($currency_exchange_rate_info)) {
            $margined_exchange_rate = $currency_exchange_rate_info->exchange_rate;

            if ((float) $currency_exchange_rate_info->margin > 0) {
                $margined_exchange_rate = (($currency_exchange_rate_info->exchange_rate * $currency_exchange_rate_info->margin) / 100) + $currency_exchange_rate_info->exchange_rate;
                $converted_rate = round($rate * $margined_exchange_rate);
            } else {
                if ($from == $to) {
                    $converted_rate = $rate;
                }else{
                    $converted_rate = round($rate * $currency_exchange_rate_info->exchange_rate);
                }
            }

            $response['status'] = true;
            $response['message'] = "Rate has been converted to your given currency.";

            $response['data']['fromCurrencyCode'] = $currency_exchange_rate_info->from_currency_code;
            $response['data']['toCurrencyCode'] = $currency_exchange_rate_info->to_currency_code;
            $response['data']['rate'] = $rate;
            $response['data']['exchangeRate'] = $currency_exchange_rate_info->exchange_rate;
            $response['data']['margin'] = $currency_exchange_rate_info->margin;
            $response['data']['marginedExchangeRate'] = $margined_exchange_rate;

            $response['data']['convertedRate'] = $converted_rate;

            $response['data']['formattedPrice'] = $converted_rate;
            $response['data']['toDecimalSeparator'] = '';
            $response['data']['toThousandSeparator'] = '';
            $response['data']['symbol'] = '';
            // get toCurrencyCode currency information
            $c_conditions['code'] = $currency_exchange_rate_info->to_currency_code;
            $options['return_type'] = 'first';
            $currency_info = getCurrency($c_conditions, $options);

            if (!empty($currency_info)) {
                $response['data']['formattedPrice'] = number_format($converted_rate, DECIMAL_POINT_LENGTH, $currency_info->decimalSeparator, $currency_info->thousandSeparator);
                $response['data']['toDecimalSeparator'] = $currency_info->decimalSeparator;
                $response['data']['toThousandSeparator'] = $currency_info->thousandSeparator;
                $response['data']['symbol'] = $currency_info->symbol;
            }
        }
    } catch (\Exception $error) {
        $response['message'] = $error->getCode() . " :: " . $error->getMessage() . " :: On Line Number (" . $error->getLine() . ")";
    }


    return $response;
}
