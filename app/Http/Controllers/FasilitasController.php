<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFasilitasRequest;
use App\Http\Requests\UpdateFasilitasRequest;
use App\Models\Fasilitas;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class FasilitasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:fasilitas.index')->only('index', 'list');
        $this->middleware('permission:fasilitas.create')->only('create', 'store');
        $this->middleware('permission:fasilitas.edit')->only('edit', 'update');
        $this->middleware('permission:fasilitas.destroy')->only('destroy');
    }

    public function list(Request $request)
    {
        $fasilitas = DB::table('fasilitas')->select('id', 'nama_fasilitas');

        return DataTables::of($fasilitas)
            ->addIndexColumn()
            ->make(true);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('fasilitas.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('fasilitas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFasilitasRequest $request)
    {
        // mulai transaksi
        DB::beginTransaction();
        try {
            $validated = $request->validated();

            foreach ($validated['nama_fasilitas'] as $namaFasilitas) {
                Fasilitas::create([
                    'nama_fasilitas' => $namaFasilitas,
                ]);
            }

            DB::commit();
            return redirect()->route('fasilitas.index')->with('success', 'Fasilitas berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return back()->withInput()->with('error', 'Terjadi kesalahan saat membuat fasilitas baru.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // find id
        $fasilitas = Fasilitas::find($id);

        return view('fasilitas.edit', compact('fasilitas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFasilitasRequest $request, string $id)
    {
    
        // mulai transaksi
        DB::beginTransaction();
        try {
            // find id
            $fasilitas = Fasilitas::find($id);
            $validated = $request->validated();

            $fasilitas->update([
                'nama_fasilitas' => $validated['nama_fasilitas'],
            ]);

            DB::commit();
            return redirect()->route('fasilitas.index')->with('success', 'Fasilitas berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui fasilitas.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // mulai transaksi
        DB::beginTransaction();
        try {
            
            // find id
            $fasilitas = Fasilitas::find($id);
    
            // delete
            $fasilitas->delete();

            DB::commit();
            // return json
            return response()->json([
                'success' => true,
                'message' => 'Fasilitas Deleted Successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            // Log error untuk debugging (boleh dihilangkan di production)
            Log::error('Error deleting Fasilitas: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete the fasilitas. Please try again later.'
            ]);
        }
    }
}
