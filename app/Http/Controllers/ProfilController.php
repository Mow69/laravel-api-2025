<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProfilRequest;
use App\Http\Requests\UpdateProfilRequest;
use App\Models\Profil;
use App\Models\User;
use App\Repositories\ProfilRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ProfilController
{
    public function __construct(
        private ProfilRepository $profilRepository
    ) {}

    /**
     * Display a listing of the resource with pagination (by default, per_page = 15)
     */
    public function index(Request $request): JsonResponse
    {
        Gate::authorize('viewAny', Profil::class);
        $user = Auth::guard("sanctum")->user();

        $perPage = $request->input('per_page', 15);
        $profils = $this->profilRepository->getAllProfils(
            $user instanceof User,
            $perPage
        );
        
        return response()->json($profils);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProfilRequest $request): JsonResponse
    {
        Gate::authorize('create', Profil::class);

        $profil = $this->profilRepository->createProfil($request->validated());
        
        return response()->json([
            'profil' => $profil
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProfilRequest $request, Profil $profil): JsonResponse
    {
        Gate::authorize('update', $profil);

        $updatedProfil = $this->profilRepository->updateProfil($profil, $request->validated());
        
        return response()->json([
            'profil' => $updatedProfil
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Profil $profil): JsonResponse
    {
        Gate::authorize('delete', $profil);
        
        $this->profilRepository->deleteProfil($profil);
        
        return response()->json([], 204); 
    }
}
