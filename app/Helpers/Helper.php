<?

namespace App\Helpers;

use App\Models\PeripheralAlert;
use InvalidArgumentException;

class Helper
{
    public static function compare(float $a, float $b, string $operator) {
        switch ($operator) {
            case '==':
                return $a == $b;
            case '!=':
                return $a != $b;
            case '<':
                return $a < $b;
            case '>':
                return $a > $b;
            case '<=':
                return $a <= $b;
            case '>=':
                return $a >= $b;
            default:
                throw new InvalidArgumentException("Invalid comparison operator: $operator");
        }
    }
}