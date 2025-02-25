<?php

namespace App\Http\Controllers;

use App\Events\AlertAndMessagesEvent;
use App\Http\Requests\StoreJadwalTidakTersediaRequest;
use App\Http\Requests\UpdateJadwalTidakTersediaRequest;
use App\Models\JadwalTidakTersedia;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class JadwalTidakTersediaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:jadwal-tidak-tersedia.index')->only('index', 'list');
        $this->middleware('permission:jadwal-tidak-tersedia.create')->only('create', 'store');
        $this->middleware('permission:jadwal-tidak-tersedia.edit')->only('edit', 'update');
        $this->middleware('permission:jadwal-tidak-tersedia.destroy')->only('destroy');
    }

    public function list(Request $request)
    {
        $jadwalTidakTersedia = DB::table('jadwal_tidak_tersedias')
            ->leftJoin('ruangs', 'jadwal_tidak_tersedias.ruangs_id', '=', 'ruangs.id')
            ->leftJoin('jurusans', 'ruangs.jurusans_id', '=', 'jurusans.id')
            ->select('jadwal_tidak_tersedias.id', 'jadwal_tidak_tersedias.tanggal_mulai', 'jadwal_tidak_tersedias.tanggal_selesai', 'ruangs.nama_ruang as ruang_name', 'jurusans.nama_jurusan as jurusan_name');

        // Apply filter if jurusan_id is provided
        if ($request->has('jurusan_id') && $request->jurusan_id != '') {
            $jadwalTidakTersedia->where('ruangs.jurusans_id', $request->jurusan_id);
        }

        return DataTables::of($jadwalTidakTersedia)
            ->addIndexColumn()
            ->make(true);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jurusans = DB::table('jurusans')->select('nama_jurusan', 'id')->get();
        return view('jadwal-tidak-tersedia.index', compact('jurusans'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $getJurusan = DB::table('jurusans')->select('id', 'nama_jurusan')->get();
        return view('jadwal-tidak-tersedia.create', compact('getJurusan'));
    }

    public function getRuang($id)
    {
        $ruang = DB::table('ruangs')->where('jurusans_id', $id)->get();
        return response()->json($ruang);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreJadwalTidakTersediaRequest $request)
    {
        // mulai transaksi
        DB::beginTransaction();
        try {
            DB::table('jadwal_tidak_tersedias')->insert([
                'ruangs_id' => $request->ruangs_id,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'keterangan' => $request->keterangan ?? null
            ]);

            event(new AlertAndMessagesEvent(
                'Data Jadwal Tidak Tersedia',  // Title
                'Jadwal Tidak Tersedia baru telah berhasil ditambahkan.',  // Message
                auth()->user()->id  // User ID
            ));

            DB::commit();
            return redirect()->route('jadwal-tidak-tersedia.index')->with('success', 'Jadwal Tidak Tersedia berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menambahkan Jadwal Tidak Tersedia' . $e->getMessage());
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
        $jadwalTidakTersedia = JadwalTidakTersedia::findOrFail($id);
        // get jurusan
        $getJurusan = DB::table('jurusans')->select('id', 'nama_jurusan')->get();
        return view('jadwal-tidak-tersedia.edit', compact('jadwalTidakTersedia', 'getJurusan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateJadwalTidakTersediaRequest $request, string $id)
    {
        // mulai transaksi
        DB::beginTransaction();
        try {
            $jadwalTidakTersedia = JadwalTidakTersedia::findOrFail($id);

            $jadwalTidakTersedia->update([
                'ruangs_id' => $request->ruangs_id,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'keterangan' => $request->keterangan ?? null
            ]);

            // Dispatch event to handle the alert and message notifications
            event(new AlertAndMessagesEvent(
                'Data Ruang Diperbarui',  // Title
                'Ruang dengan nama ' . $jadwalTidakTersedia->ruang->nama_ruang . ' telah berhasil diperbarui.',  // Message
                auth()->user()->id  // User ID
            ));
            event(new AlertAndMessagesEvent(
                'Data Jadwal Tidak Tersedia',  // Title
                'Jadwal Tidak Tersedia baru telah berhasil diubah.',  // Message
                auth()->user()->id  // User ID
            ));

            DB::commit();
            return redirect()->route('jadwal-tidak-tersedia.index')->with('success', 'Jadwal Tidak Tersedia berhasil diubah');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengubah Jadwal Tidak Tersedia' . $e->getMessage());
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
            $jadwalTidakTersedia = JadwalTidakTersedia::findOrFail($id);

            // Dispatch event to handle the alert and message notifications
            event(new AlertAndMessagesEvent(
                'Data Jadwal Tidak Tersedia',  // Title
                'Jadwal Tidak Tersedia dengan nama ' . $jadwalTidakTersedia->ruang->nama_ruang . ' telah berhasil dihapus.',  // Message
                auth()->user()->id  // User ID
            ));

            $jadwalTidakTersedia->delete();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Jadwal Tidak Tersedia Deleted Successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete the Jadwal Tidak Tersedia. Please try again later.'
            ]);
        }
    }
}
