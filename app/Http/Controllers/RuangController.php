<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRuangRequest;
use App\Models\Ruang;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class RuangController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:ruang.index')->only('index', 'list');
        $this->middleware('permission:ruang.create')->only('create', 'store');
        $this->middleware('permission:ruang.edit')->only('edit', 'update');
        $this->middleware('permission:ruang.destroy')->only('destroy');
    }

    public function list(Request $request)
    {
        $ruang = DB::table('ruangs')
            ->leftJoin('jurusans', 'ruangs.jurusans_id', '=', 'jurusans.id')
            ->select('ruangs.id', 'ruangs.nama_ruang', 'ruangs.kode_ruang', 'ruangs.is_active', 'jurusans.nama_jurusan')
            ->get();

        return DataTables::of($ruang)
            ->addIndexColumn()
            ->make(true);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('ruang.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jurusans = DB::table('jurusans')->select('nama_jurusan', 'id')->get();
        $fasilitas = DB::table('fasilitas')->select('nama_fasilitas', 'id')->get();
        return view('ruang.create', compact('jurusans', 'fasilitas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRuangRequest $request)
    {
        // Start transaction
        DB::beginTransaction();

        try {
            // Get validated data from the request
            $validated = $request->validated();

            // Create Ruang instance
            $ruang = Ruang::create($validated);

            // Handle Image upload (if any)
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('images/ruangs', 'public');
                $ruang->image = $imagePath;
                $ruang->save(); // Don't forget to save the image path!
            }
            // dd($ruang);
            // Attach the selected Fasilitas
            if ($request->has('fasilitas_id')) {
                $ruang->fasilitas()->attach($request->fasilitas_id);
            }

            // Commit transaction if everything is fine
            DB::commit();

            // Redirect with success message
            return redirect()->route('ruang.index')->with('success', 'Ruang berhasil ditambahkan.');
        } catch (\Exception $e) {
            // Rollback transaction if any error occurs
            DB::rollBack();
            Log::error($e);

            // Return back with error message
            return back()->withInput()->with('error', 'Terjadi kesalahan saat membuat ruang baru.');
        }
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Find the Ruang by ID, or fail if not found
        $ruang = Ruang::with('fasilitas')->findOrFail($id);

        // Pass the Ruang and its related Fasilitas to the view
        return view('ruang.show', compact('ruang'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ruang $ruang)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ruang $ruang)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ruang $ruang)
    {
        //
    }
}
