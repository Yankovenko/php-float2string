# Float 2 string

It was surprising that this feature was not standard. 
And in general, I haven’t found anything similar on the Internet, although everything seems simple.

The main problem solved by the function is the rejection of short recording,
such as `1.234E-10`, without conversion.

So, use it without restrictions.

```php

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

```
