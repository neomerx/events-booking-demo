<?php declare(strict_types=1);

namespace App\Rules;

use App\EventSection;
use Illuminate\Contracts\Validation\Rule;
use function is_scalar;

class ActiveAndNonReservedEventSection implements Rule
{
    /**
     * Check if value is an array with valid User IDs.
     *
     * @param  string $attribute
     * @param  mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (is_scalar($value) === false) {
            return false;
        }

        $sectionId = (int)$value;

        /** @noinspection PhpUndefinedMethodInspection */
        /** @var EventSection $sectionOrNull */
        $sectionOrNull = EventSection::where('event_section_id', '=', $sectionId)->first();

        $isOk =
            $sectionOrNull !== null &&
            empty($sectionOrNull->company_name) === true &&
            $sectionOrNull->event->is_active === true;

        return $isOk;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute should be active and non-reserved section.';
    }
}
