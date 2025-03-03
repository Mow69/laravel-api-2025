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
        $user = Auth::guard("sanctum")->user();

        $perPage = $request->input('per_page', 15);
        $profils = $this->profilRepository->getAllProfils(
            $user instanceof User,
            $perPage
        );
        
        return response()->json($profils);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProfilRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Profil $profil)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Profil $profil)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProfilRequest $request, Profil $profil)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Profil $profil)
    {
        //
    }
}
