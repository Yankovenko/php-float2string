<?php

ini_set('serialize_precision', 17);
ini_set('precision', 17);

$tests = [
    ['float' => 1.2345678901234E+20, 'string' => "123456789012340000000"], // 20-13 = 7 => add right
    ['float' => 1.2345678901234E-20, 'string' => "0.000000000000000000012345678901234"], // -20-13 = -33 => add left 0.0..., length = 35
    ['float' => 1234567890123456789, 'string' => "1234567890123456789"],
    ['float' => 999999999999999999, 'string' => "999999999999999999"],
    ['float' => 0.99999999999999, 'string' => "0.99999999999999"],
    ['float' => 99999999999999E-13, 'string' => "9.9999999999999"],
    ['float' => 99999999999999E+13, 'string' => "999999999999990000000000000"], // 13 => add right 0
    ['float' => 9.9999999999999E+13, 'string' => "99999999999999"], // 13-13 = 0 =>

    ['float' => 0.49305709162072797, 'string' => "0.49305709162072797"], //
    ['float' => 1.9999999999999, 'string' => "1.9999999999999"], //
    ['float' => 140011.26650111, 'string' => "140011.26650111"], //
];

var_dump($tests);
$i = 0;
foreach ($tests as $test) {
    $float = $test['float'];
    $string = $test['string'];
    echo 'test: ' . (++$i) . PHP_EOL;
    $r = float2string($float);
    echo $string . ' - ' . $r . ' => ' . ($r === $string ? 'OK' : 'FAIL') . PHP_EOL;
}

function float2string($float): string
{
    $strFloat = (string)$float;
    if (stripos($strFloat, 'e') && preg_match('/^(\d+)(\.(\d+))?E(\+|-)?(\d+)$/i', $strFloat, $match)) {
        $dotPosition = ($match[4] === '-' ? -1 : +1) * (int)$match[5] - strlen($match[3]);
        $strFloat = $match[1] . $match[3];
        if ($dotPosition > 0) {
            $strFloat .= str_repeat('0', $dotPosition);
        } elseif (strlen($strFloat) + $dotPosition < 0) {
            $strFloat = '0.' . str_repeat('0', -strlen($strFloat) - $dotPosition) . $strFloat;
        } else {
            $strFloat = substr($strFloat, 0, $dotPosition) . 'tests' . substr($strFloat, $dotPosition);
        }
    }
    return $strFloat;
}