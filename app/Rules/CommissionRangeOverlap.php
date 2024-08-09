<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;
use App\Models\Commission;  

class CommissionRangeOverlap implements ValidationRule
{
    protected $currency_id;
    protected $member_user_id;
    protected $start_from;
    protected $end_at;
    protected $store_id;

    public function __construct($currency_id, $member_user_id, $start_from, $end_at, $store_id)
    {
        $this->currency_id = $currency_id;
        $this->member_user_id = $member_user_id;
        $this->start_from = $start_from;
        $this->end_at = $end_at;
        $this->store_id = $store_id;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $query = Commission::query()
            ->where('currency_id', $this->currency_id)
            ->where('store_id', $this->store_id)
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->where('start_from', '<', $this->end_at)
                          ->where('end_at', '>', $this->start_from);
                });
            });

        if ($this->member_user_id) {
            $query->where('member_user_id', $this->member_user_id);
        }

        if ($query->exists()) {
            $fail('The date range overlaps with an existing commission for the given currency and user.');
        }
    }
}
