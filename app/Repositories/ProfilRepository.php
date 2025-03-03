<?php

namespace App\Repositories;

use App\Enums\StatusType;
use App\Models\Profil;
use App\Models\Status;
use Illuminate\Pagination\LengthAwarePaginator;

class ProfilRepository
{
    /**
     * Get all profils with pagination filtred by active status with hidden statuses if not authenticated user
     */
    public function getAllProfils(bool $isAuthenticated, int $perPage = 15): LengthAwarePaginator
    {
        $query = Profil::query();

        if (!$isAuthenticated) {
            $activeStatus = Status::where('name', StatusType::ACTIVE->value)->first();
            
            $query->where('status_id', $activeStatus->id)
                  ->select(['id', 'firstname', 'lastname']);
        } else {
            $query->join('statuses', 'profils.status_id', '=', 'statuses.id')
                  ->select('profils.*', 'statuses.label as status');
        }
        
        return $query->paginate($perPage);
    }

}
