<?php

namespace App\Http\Controllers;

use App\Events\AlertAndMessagesEvent;
use App\Http\Requests\StoreJurusanRequest;
use App\Http\Requests\UpdateJurusanRequest;
use App\Models\Alert;
use App\Models\Jurusan;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;

class JurusanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:jurusan.index')->only('index', 'list');
        $this->middleware('permission:jurusan.create')->only('create', 'store');
        $this->middleware('permission:jurusan.edit')->only('edit', 'update');
        $this->middleware('permission:jurusan.destroy')->only('destroy');
    }

    public function list(Request $request)
    {
        $jurusan = DB::table('jurusans')
            ->select('nama_jurusan', 'id', 'kode_jurusan', 'is_active');

        return DataTables::of($jurusan)
            ->addIndexColumn()
            ->make(true);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('jurusan.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('jurusan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreJurusanRequest $request)
    {
        // Validasi request
        $validated = $request->validated();

        // Mulai transaksi database
        DB::beginTransaction();

        try {
            // Menyimpan data jurusan
            Jurusan::create($validated);

            // Dispatch the event for alert and messages
            event(new AlertAndMessagesEvent(
                'Data Jurusan Ditambahkan',  // Title
                'Jurusan baru telah berhasil ditambahkan.',  // Message
                auth()->user()->id  // User ID
            ));

            // Commit transaksi jika berhasil
            DB::commit();

            // Redirect dengan pesan sukses
            return redirect()->route('jurusan.index')->with('success', 'Data jurusan berhasil ditambahkan');
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi error
            DB::rollBack();

            // Log error jika perlu
            Log::error($e->getMessage());

            // Redirect dengan pesan error
            return redirect()->route('jurusan.index')->with('error', 'Gagal menambahkan data jurusan');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Jurusan $jurusan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Jurusan $jurusan)
    {
        return view('jurusan.edit')
            ->with('jurusan', $jurusan);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateJurusanRequest $request, $id)
    {
        // Mulai transaksi database
        DB::beginTransaction();

        try {
            $jurusan = Jurusan::findOrFail($id);
            // Validasi request
            $validated = $request->validated();
            // Menyimpan data jurusan
            $jurusan->update($validated);
            event(new AlertAndMessagesEvent(
                'Data Jurusan Diperbarui',  // Title
                'Data jurusan dengan nama ' . $jurusan->nama_jurusan . ' telah berhasil diperbarui.',  // Message
                auth()->user()->id  // User ID
            ));

            // Commit transaksi jika berhasil
            DB::commit();

            // Redirect dengan pesan sukses
            return redirect()->route('jurusan.index')->with('success', 'Data jurusan berhasil diubah');
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi error
            DB::rollBack();

            // Log error jika perlu
            Log::error($e->getMessage());

            // Redirect dengan pesan error
            return redirect()->route('jurusan.index')->with('error', 'Gagal mengubah data jurusan');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Jurusan $jurusan)
    {
        // Mulai transaksi untuk memastikan semuanya terhapus atau tidak ada yang terhapus jika gagal
        DB::beginTransaction();

        try {

            // Hapus data dosen itu sendiri
            $jurusan->delete();

            // Dispatch the event for alert and messages
            event(new AlertAndMessagesEvent(
                'Data Jurusan Dihapus',  // Title
                'Data jurusan dengan nama ' . $jurusan->nama_jurusan . ' telah berhasil dihapus.',  // Message
                auth()->user()->id  // User ID
            ));

            // Commit transaksi jika semua operasi berhasil
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Jurusan Deleted Successfully'
            ]);
        } catch (\Exception $e) {
            // Rollback transaksi jika ada error
            DB::rollBack();

            // Log error untuk debugging (boleh dihilangkan di production)
            Log::error('Error deleting jurusan: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete the jurusan. Please try again later.'
            ]);
        }
    }
}
