<?php

namespace App\Http\Controllers;

use App\Models\Design;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDesignRequest;
use App\Http\Requests\UpdateDesignRequest;
use App\Http\Resources\DesignResource;
use Illuminate\Support\Facades\Storage;

class DesignController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->role !== 'ADMIN') {
            abort(403, 'No tienes permiso para acceder a esta página.');
        }
        
        $query = Design::query();
        $designs = $query->paginate(10)->onEachSide(1);
        return inertia("Design/Index", [
            "designs" => DesignResource::collection($designs),
            'success' => session('success'),
        ]);
    }

    public function create()
    {
        $user = auth()->user();

        if ($user->role !== 'ADMIN') {
            abort(403, 'No tienes permiso para acceder a esta página.');
        }

        return inertia("Design/Create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDesignRequest $request)
    {
        $data = $request->validated();
        $image = null;
        $design = null;

        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('designs', 'public');
            $data['image'] = $image;
        }

        if ($request->has('price')) {
            $data['price'] = $request->input('price');
        }

        if ($request->has('technique')) {
            $data['technique'] = $request->input('technique');
        }

        $design = Design::create($data);

        if ($design) {
            return response()->json([
                'id' => $design->id,
                'price' => $request->input('price'),
                'image' => $image
            ], 200);
        } else {
            return response()->json([
                'message' => 'Error al crear el diseño.'
            ], 500);
        }
    }

    public function store_admin(StoreDesignRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('designs', 'public');
            $data['image'] = $image;
        }
        if ($request->has('price')) {
            $data['price'] = $request->input('price');
        }
        Design::create($data);
        return to_route('design.index')
            ->with('success', 'Diseño registrado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Design $design)
    {
        return $design;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDesignRequest $request, Design $design)
    {
        $design->update($request->validated());

        return $design;
    }

    public function destroy(Design $design)
    {
        $design->delete();

        return response()->noContent();
    }
}