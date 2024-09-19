<?php

namespace App\Policies;

use App\Models\Property;
use App\Models\User;

class PropertyPolicy
{
    /**
     * Determine if the given property can be deleted by the user.
     */
    public function delete(User $user, Property $property)
    {
        // Example: Only allow deletion if the user owns the property
        return $user->id === $property->user_id;
    }
}

