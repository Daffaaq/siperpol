<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTipeDokumenPeminjamanRequest;
use App\Models\Alert;
use App\Models\Message;
use App\Models\TipeDokumenPeminjaman;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;
use App\Events\AlertAndMessagesEvent;
use App\Http\Requests\UpdateTipeDokumenPeminjamanRequest;
use Illuminate\Http\Request;

class TipeDokumenPeminjamanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:tipe-dokumen-peminjaman.index')->only('index', 'list');
        $this->middleware('permission:tipe-dokumen-peminjaman.create')->only('create', 'store');
        $this->middleware('permission:tipe-dokumen-peminjaman.edit')->only('edit', 'update');
        $this->middleware('permission:tipe-dokumen-peminjaman.destroy')->only('destroy');
    }

    public function list(Request $request)
    {
        $tipe_dokumen_peminjaman = DB::table('tipe_dokumen_peminjaman')->select('id', 'tipe_dokumen', 'is_active')->get();
        // dd($tipe_dokumen_peminjaman);
        return DataTables::of($tipe_dokumen_peminjaman)
            ->addIndexColumn()
            ->make(true);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('tipe-dokumen-peminjaman.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tipe-dokumen-peminjaman.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTipeDokumenPeminjamanRequest $request)
    {
        // mulai transaksi
        DB::beginTransaction();
        try {
            $validated = $request->validated();
            $tipe_dokumen_peminjaman = TipeDokumenPeminjaman::create($validated);
            // dd($tipe_dokumen_peminjaman);
            // Dispatch the event for alert and messages
            event(new AlertAndMessagesEvent(
                'Data Tipe Dokumen Peminjaman',  // Title
                'Tipe Dokumen Peminjaman baru telah berhasil ditambahkan.',  // Message
                auth()->user()->id  // User ID
            ));

            DB::commit();
            return redirect()->route('tipe-dokumen-peminjaman.index')->with('success', 'Data berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data');
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
        $tipe_dokumen_peminjaman = TipeDokumenPeminjaman::find($id);

        return view('tipe-dokumen-peminjaman.edit', compact('tipe_dokumen_peminjaman'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTipeDokumenPeminjamanRequest $request, string $id)
    {
        // Mulai transaksi database
        DB::beginTransaction();

        try {
            // Find the resource by ID (e.g., TipeDokumenPeminjaman)
            $resource = TipeDokumenPeminjaman::findOrFail($id);

            // Validate the incoming request data
            $validated = $request->validated();

            // Update the resource with the validated data
            $resource->update($validated);

            // Dispatch event to handle the alert and message notifications
            event(new AlertAndMessagesEvent(
                'Data Tipe Dokumen Peminjaman Diperbarui',  // Title
                'Tipe Dokumen Peminjaman dengan tipe ' . $resource->tipe_dokumen . ' telah berhasil diperbarui.',  // Message
                auth()->user()->id  // User ID
            ));

            // Commit transaction if everything is successful
            DB::commit();

            // Redirect with success message
            return redirect()->route('tipe-dokumen-peminjaman.index')->with('success', 'Data Tipe Dokumen Peminjaman berhasil diperbarui');
        } catch (\Exception $e) {
            // Rollback transaction if something goes wrong
            DB::rollBack();

            // Log the error for debugging
            Log::error($e->getMessage());

            // Redirect with an error message
            return redirect()->route('tipe-dokumen-peminjaman.index')->with('error', 'Gagal memperbarui data Tipe Dokumen Peminjaman');
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
            $tipe_dokumen_peminjaman = TipeDokumenPeminjaman::find($id);
            $tipe_dokumen_peminjaman->delete();

            // Dispatch the event for alert and messages
            event(new AlertAndMessagesEvent(
                'Data Tipe Dokumen Peminjaman Dihapus',  // Title
                'Tipe Dokumen Peminjaman dengan tipe ' . $tipe_dokumen_peminjaman->tipe_dokumen . ' telah berhasil dihapus.',  // Message
                auth()->user()->id  // User ID
            ));
            DB::commit();
            // return json
            return response()->json([
                'success' => true,
                'message' => 'Tipe Dokumen Peminjaman Deleted Successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            // Log error untuk debugging (boleh dihilangkan di production)
            Log::error('Error deleting Tipe Dokumen Peminjaman: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete the tipe dokumen peminjaman. Please try again later.'
            ]);
        }
    }
}
