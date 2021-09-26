<?php

namespace Vnnit\Core\Support;

use Psy\Exception\ParseErrorException;

class CarbonInterval extends \Carbon\CarbonInterval
{
    /**
     * @var array
     */
    protected static $formats = [
        'y' => 'y',
        'Y' => 'y',
        'o' => 'y',
        'm' => 'm',
        'n' => 'm',
        'W' => 'weeks',
        'd' => 'd',
        'j' => 'd',
        'z' => 'd',
        'h' => 'h',
        'g' => 'h',
        'H' => 'h',
        'G' => 'h',
        'i' => 'i',
        's' => 's',
        'u' => 'micro',
        'v' => 'milli',
    ];
    /**
     * Parse a string into a new CarbonInterval object according to the specified format.
     *
     * @example
     * ```
     * echo Carboninterval::createFromFormat('H:i', '1:30');
     * ```
     *
     * @param string $format   Format of the $interval input string
     * @param string $interval Input string to convert into an interval
     *
     * @throws Exception when the $interval cannot be parsed as an interval.
     *
     * @return static
     */
    public static function createFromFormat(string $format, ?string $interval)
    {
        $instance = new static(0);
        $length = mb_strlen($format);

        if (preg_match('/s([,.])([uv])$/', $format, $match)) {
            $interval = explode($match[1], $interval);
            $index = count($interval) - 1;
            $interval[$index] = str_pad($interval[$index], $match[2] === 'v' ? 3 : 6, '0');
            $interval = implode($match[1], $interval);
        }

        for ($index = 0; $index < $length; $index++) {
            $expected = mb_substr($format, $index, 1);
            $nextCharacter = mb_substr($interval, 0, 1);
            $unit = static::$formats[$expected] ?? null;

            if ($unit) {
                if (!preg_match('/^-?\d+/', $interval, $match)) {
                    throw new ParseErrorException('number', $nextCharacter);
                }

                $interval = mb_substr($interval, mb_strlen($match[0]));
                $instance->$unit += intval($match[0]);

                continue;
            }

            if ($nextCharacter !== $expected) {
                throw new ParseErrorException(
                    "'$expected'",
                    $nextCharacter,
                    'Allowed substitutes for interval formats are '.implode(', ', array_keys(static::$formats))."\n".
                    'See https://www.php.net/manual/en/function.date.php for their meaning'
                );
            }

            $interval = mb_substr($interval, 1);
        }

        if ($interval !== '') {
            throw new ParseErrorException(
                'end of string',
                $interval
            );
        }

        return $instance;
    }
    /**
     * @param $date1
     * @param $date2
     * @return \Carbon\CarbonInterval
     */
    public static function createFromDiff($date1, $date2)
    {
        if (!$date1 || !$date2) {
            return new static(0);
        }
        return static::instance((new Carbon($date1))->diffAsCarbonInterval(new Carbon($date2), false));
    }

    public static function createFromDiffWithBoundary($start, $end, $boundaryStart, $boundaryEnd)
    {
        $empty = new static(0);

        if (!$start || !$end) {
            return $empty;
        }

        $start = new Carbon($start);
        $end = new Carbon($end);
        $boundaryStart = new Carbon($boundaryStart);
        $boundaryEnd = new Carbon($boundaryEnd);

        if (($boundaryStart && $boundaryEnd) && ($start > $boundaryEnd || $end < $boundaryStart)) {
            return $empty;
        }

        if (($boundaryStart && !$boundaryEnd) || (!$boundaryStart && $boundaryEnd)) {
            return $empty;
        }

        if ($boundaryStart === null || $start->between($boundaryStart, $boundaryEnd)) {
            $from = $start->copy();
        } else {
            $from = $boundaryStart->copy();
        }
        if ($boundaryEnd === null || $end->between($boundaryStart, $boundaryEnd)) {
            $to = $end->copy();
        } else {
            $to = $boundaryEnd->copy();
        }
        return (CarbonInterval::createFromDiff($from, $to));
    }

    public static function createFromTimeString($time)
    {
        if (!$time) {
            return static::create(0);
        }

        $parts = explode(':', $time);
        switch (count($parts)) {
            case 2:
                return static::create(0, 0, 0, 0, $parts[0], $parts[1]);
            case 3:
                return static::create(0, 0, 0, 0, $parts[0], $parts[1], $parts[2]);
            default:
                throw new \InvalidArgumentException('Format of value "' . $time . '" is unknown. Only support H:i or H:i:s');
        }
    }

    public function toHourMinuteString()
    {
        $cascades = static::getCascadeFactors();
        CarbonInterval::setCascadeFactors([
            'minute' => [60, 'seconds'],
            'hour' => [60, 'minutes'],
        ]);

        $result = $this->cascade()->format('%H:%I');

        static::setCascadeFactors($cascades);

        return $result;
    }
}
