<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Speaker extends MyBaseModel
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'bio',
        'event_id',
        'account_id'
    ];

    /**
     * The event associated with the attendee.
     *
     * @return BelongsTo
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

}
