<?php

namespace App\Support;

class IndianCurrency
{
    /**
     * Convert amount to Indian Rupee words, e.g.
     * "Indian Rupee Fifty Thousand Nine Hundred Seventy-Six Only"
     */
    public static function toWords(float $amount): string
    {
        $amount = round($amount, 2);
        $rupees = (int) floor($amount);
        $paise = (int) round(($amount - $rupees) * 100);

        $words = 'Indian Rupee ' . self::numberToWords($rupees);
        if ($paise > 0) {
            $words .= ' and ' . self::numberToWords($paise) . ' Paise';
        }

        return $words . ' Only';
    }

    protected static function numberToWords(int $number): string
    {
        if ($number === 0) {
            return 'Zero';
        }

        $ones = [
            '', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine',
            'Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen',
            'Seventeen', 'Eighteen', 'Nineteen',
        ];
        $tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];

        $parts = [];

        $crore = (int) floor($number / 10000000);
        if ($crore > 0) {
            $parts[] = self::numberToWords($crore) . ' Crore';
            $number %= 10000000;
        }

        $lakh = (int) floor($number / 100000);
        if ($lakh > 0) {
            $parts[] = self::numberToWords($lakh) . ' Lakh';
            $number %= 100000;
        }

        $thousand = (int) floor($number / 1000);
        if ($thousand > 0) {
            $parts[] = self::numberToWords($thousand) . ' Thousand';
            $number %= 1000;
        }

        $hundred = (int) floor($number / 100);
        if ($hundred > 0) {
            $parts[] = $ones[$hundred] . ' Hundred';
            $number %= 100;
        }

        if ($number > 0) {
            if ($number < 20) {
                $parts[] = $ones[$number];
            } else {
                $ten = (int) floor($number / 10);
                $one = $number % 10;
                $parts[] = trim($tens[$ten] . ($one ? '-' . $ones[$one] : ''));
            }
        }

        return implode(' ', $parts);
    }
}
