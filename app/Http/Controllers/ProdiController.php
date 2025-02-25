<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProdiRequest;
use App\Http\Requests\UpdateProdiRequest;
use App\Models\Alert;
use App\Models\Message;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;

class ProdiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:prodi.index')->only('index', 'list');
        $this->middleware('permission:prodi.create')->only('create', 'store');
        $this->middleware('permission:prodi.edit')->only('edit', 'update');
        $this->middleware('permission:prodi.destroy')->only('destroy');
    }

    public function list(Request $request)
    {
        $prodi = DB::table('prodis')
            ->leftJoin('jurusans', 'prodis.jurusans_id', '=', 'jurusans.id')
            ->select('prodis.id', 'prodis.nama_prodi', 'prodis.kode_prodi', 'prodis.is_active', 'jurusans.nama_jurusan');

        // Apply filtering by jurusan_id if available
        if ($request->has('jurusan_id') && !empty($request->jurusan_id)) {
            $prodi = $prodi->where('prodis.jurusans_id', $request->jurusan_id);
        }

        $prodi = $prodi->get();

        return DataTables::of($prodi)
            ->addIndexColumn()
            ->make(true);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jurusans = DB::table('jurusans')->select('nama_jurusan', 'id')->get();
        return view('prodi.index', compact('jurusans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jurusans = DB::table('jurusans')->select('nama_jurusan', 'id')->get();
        return view('prodi.create', compact('jurusans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProdiRequest $request)
    {
        $validated = $request->validated();

        // Mulai transaksi database
        DB::beginTransaction();

        try {
            // Menyimpan data jurusan
            Prodi::create($validated);

            // Add alert notification
            Alert::create([
                'title' => 'Data Prodi Ditambahkan',
                'message' => 'Prodi baru telah berhasil ditambahkan.',
                'type' => 'info',
                'sended_at' => now(),
                'is_read' => false,
                'users_id' => auth()->user()->id
            ]);

            // Create message for the system
            Message::create([
                'sender' => 'System',
                'message' => 'Prodi baru telah berhasil ditambahkan.',
                'status' => 'unread',
                'sended_time' => now(),
                'users_id' => auth()->user()->id
            ]);

            // Commit transaksi
            DB::commit();

            return redirect()->route('prodi.index')->with('success', 'Prodi berhasil ditambahkan.');
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();
            Log::error($e->getMessage());
            return redirect()->route('prodi.index')->with('error', 'Terjadi kesalahan saat menambahkan prodi.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Prodi $prodi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Prodi $prodi)
    {
        $jurusans = DB::table('jurusans')->select('nama_jurusan', 'id')->get();
        return view('prodi.edit')
            ->with('prodi', $prodi)
            ->with('jurusans', $jurusans); // Menyertakan data jurusan ke view
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProdiRequest $request, $id)
    {

        // dd($validated);

        // Mulai transaksi database
        DB::beginTransaction();

        try {
            $prodi = Prodi::findOrFail($id);

            $validated = $request->validated();
            // Menyimpan data jurusan
            $prodi->update($validated);

            // Add alert notification
            Alert::create([
                'title' => 'Data Prodi Diperbarui',
                'message' => 'Data prodi dengan nama ' . $prodi->nama_prodi . ' telah berhasil diperbarui.',
                'type' => 'info',
                'sended_at' => now(),
                'is_read' => false,
                'users_id' => auth()->user()->id
            ]);

            // Create message for the system
            Message::create([
                'sender' => 'System',
                'message' => 'Data prodi dengan nama ' . $prodi->nama_prodi . ' telah berhasil diperbarui.',
                'status' => 'unread',
                'sended_time' => now(),
                'users_id' => auth()->user()->id
            ]);

            // Commit transaksi
            DB::commit();

            return redirect()->route('prodi.index')->with('success', 'Prodi berhasil diubah.');
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();
            Log::error($e->getMessage());
            return redirect()->route('prodi.index')->with('error', 'Terjadi kesalahan saat mengubah prodi.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Prodi $prodi)
    {
        // Mulai transaksi database
        DB::beginTransaction();

        try {
            // Hapus data prodi itu sendiri
            $prodi->delete();

            // Tambahkan notifikasi alert bahwa data prodi dihapus
            Alert::create([
                'title' => 'Data Prodi Dihapus',
                'message' => 'Data prodi dengan nama ' . $prodi->nama_prodi . ' telah berhasil dihapus.',
                'type' => 'info',
                'sended_at' => now(),
                'is_read' => false,
                'users_id' => auth()->user()->id
            ]);

            // Tambahkan pesan baru 
            Message::create([
                'sender' => 'System',
                'message' => 'Data prodi dengan nama ' . $prodi->nama_prodi . ' telah berhasil dihapus.',
                'status' => 'unread',
                'sended_time' => now(),
                'users_id' => auth()->user()->id
            ]);

            // Commit transaksi
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Prodi Deleted Successfully'
            ]);
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();
            Log::error($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete the prodi. Please try again later.'
            ]);
        }
    }
}
