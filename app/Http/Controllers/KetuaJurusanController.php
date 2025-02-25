<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKepalaJurusanRequest;
use App\Http\Requests\UpdateKepalaJurusanRequest;
use App\Models\Alert;
use App\Models\KetuaJurusan;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class KetuaJurusanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:ketua-jurusan.index')->only('index', 'list');
        $this->middleware('permission:ketua-jurusan.create')->only('create', 'store');
        $this->middleware('permission:ketua-jurusan.edit')->only('edit', 'update');
        $this->middleware('permission:ketua-jurusan.destroy')->only('destroy');
    }

    public function list(Request $request)
    {
        $query = DB::table('ketua_jurusans')
            ->join('jurusans', 'ketua_jurusans.jurusans_id', '=', 'jurusans.id')
            ->select('ketua_jurusans.id', 'ketua_jurusans.email_ketua_jurusan', 'ketua_jurusans.nama_ketua_jurusan', 'jurusans.nama_jurusan');

        // Apply the filter if a jurusan ID is provided
        if ($request->has('jurusan_id') && $request->jurusan_id != '') {
            $query->where('ketua_jurusans.jurusans_id', $request->jurusan_id);
        }

        $ketuaJurusan = $query->get();
        return DataTables::of($ketuaJurusan)
            ->addIndexColumn()
            ->make(true);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jurusans = DB::table('jurusans')->select('nama_jurusan', 'id')->get();
        return view('ketua-jurusan.index', compact('jurusans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jurusan = DB::table('jurusans')->get();

        // Mengecek apakah ada jurusan yang belum memiliki kepala jurusan
        $jurusanTanpaKepala = [];
        foreach ($jurusan as $j) {
            $existingKepalaJurusan = KetuaJurusan::where('jurusans_id', $j->id)->first();
            if (!$existingKepalaJurusan) {
                $jurusanTanpaKepala[] = $j;
            }
        }

        if (count($jurusanTanpaKepala) > 0) {
            // Jika ada jurusan tanpa kepala jurusan, tampilkan form create
            return view('ketua-jurusan.create', compact('jurusanTanpaKepala'));
        }

        // Jika semua jurusan sudah memiliki kepala jurusan
        return redirect()->route('ketua-jurusan.index')->with('error', 'Semua jurusan sudah memiliki kepala jurusan.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreKepalaJurusanRequest $request)
    {
        // Mulai transaksi database
        DB::beginTransaction();

        try {
            // Cek apakah sudah ada kepala jurusan untuk jurusan ini
            $existingKepalaJurusan = KetuaJurusan::where('jurusans_id', $request->jurusans_id)->first();

            if ($existingKepalaJurusan) {
                return redirect()->back()->with('error', 'Sudah ada Kepala Jurusan untuk jurusan ini.');
            }

            // Validasi dan menyimpan data Kepala Jurusan
            $validated = $request->validated();

            // Create the user and get the user ID
            $user = User::create([
                'name' => $validated['nama_pendek_ketua_jurusan'],
                'email' => $validated['email_ketua_jurusan'],
                'password' => Hash::make($validated['password_ketua_jurusan']),  // Hash password for user
            ]);

            $user->assignRole('Kajur');

            // Add the user_id to the validated data before saving the Dosen record
            $validated['users_id'] = $user->id;

            $validated['password_ketua_jurusan'] = Hash::make($validated['password_ketua_jurusan']);  // Hash password for Dosen
            // dd($validated);
            KetuaJurusan::create($validated);

            // Add alert notification
            Alert::create([
                'title' => 'Data Ketua Jurusan Ditambahkan',
                'message' => 'Ketua Jurusan baru telah berhasil ditambahkan.',
                'type' => 'info',
                'sended_at' => now(),
                'is_read' => false,
                'users_id' => auth()->user()->id
            ]);

            // Create message for the system
            Message::create([
                'sender' => 'System',
                'message' => 'Ketua Jurusan baru telah berhasil ditambahkan.',
                'status' => 'unread',
                'sended_time' => now(),
                'users_id' => auth()->user()->id
            ]);

            // Commit transaksi jika berhasil
            DB::commit();

            return redirect()->route('ketua-jurusan.index')->with('success', 'Kepala Jurusan berhasil ditambahkan.');
        } catch (\Exception $e) {
            // Rollback transaksi jika ada error
            DB::rollBack();

            // Log the error with additional context (like the message and the stack trace)
            Log::error('Error while adding Kepala Jurusan: ', [
                'message' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
            ]);
            // Show a general error message to the user
            return redirect()->route('ketua-jurusan.index')->with('error', 'Terjadi kesalahan saat menambahkan Kepala Jurusan. Mohon coba lagi.' . $e->getMessage());
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(KetuaJurusan $KetuaJurusan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KetuaJurusan $ketuaJurusan)
    {
        // lock jurusans_id

        return view('ketua-jurusan.edit')
            ->with('ketuaJurusan', $ketuaJurusan);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateKepalaJurusanRequest $request, KetuaJurusan $ketuaJurusan)
    {
        // Mulai transaksi database
        DB::beginTransaction();

        try {
            // Jangan biarkan jurusans_id berubah
            $validated = $request->validated();
            // Set 'jurusans_id' ke nilai lama (dari database), untuk mencegah perubahan
            $validated['jurusans_id'] = $ketuaJurusan->jurusans_id;

            // Validasi dan update data
            // Update user yang terkait dengan Ketua Jurusan ini
            $user = User::findOrFail($ketuaJurusan->users_id); // Find the user for this Ketua Jurusan

            $user->update([
                'name' => $validated['nama_pendek_ketua_jurusan'],
                'email' => $validated['email_ketua_jurusan'],
                'password' => $validated['password_ketua_jurusan'] ? Hash::make($validated['password_ketua_jurusan']) : $user->password, // Update password only if provided
            ]);

            if ($request->filled('password_ketua_jurusan')) {
                // Jika diisi, hash password sebelum disimpan
                $validated['password_ketua_jurusan'] = Hash::make($validated['password_ketua_jurusan']);
            } else {
                // Jika password tidak diubah, gunakan password lama, namun tetap di-hash
                $validated['password_ketua_jurusan'] = Hash::make($ketuaJurusan->password_ketua_jurusan);
            }

            // Update data Ketua Jurusan tanpa merubah jurusans_id
            $ketuaJurusan->update($validated);

            // Add alert notification
            Alert::create([
                'title' => 'Data Ketua Jurusan Diperbarui',
                'message' => 'Data Ketua Jurusan telah berhasil diperbarui.',
                'type' => 'info',
                'sended_at' => now(),
                'is_read' => false,
                'users_id' => auth()->user()->id
            ]);

            // Create message for the system
            Message::create([
                'sender' => 'System',
                'message' => 'Data Ketua Jurusan telah berhasil diperbarui.',
                'status' => 'unread',
                'sended_time' => now(),
                'users_id' => auth()->user()->id
            ]);

            // Commit transaksi jika berhasil
            DB::commit();

            return redirect()->route('ketua-jurusan.index')->with('success', 'Kepala Jurusan berhasil diperbarui.');
        } catch (\Exception $e) {
            // Rollback transaksi jika ada error
            DB::rollBack();

            // Log the error with additional context
            Log::error('Error while updating Kepala Jurusan: ', [
                'message' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
            ]);

            // Show a general error message to the user
            return redirect()->route('ketua-jurusan.index')->with('error', 'Terjadi kesalahan saat memperbarui Kepala Jurusan. Mohon coba lagi.' . $e->getMessage());
        }
    }




    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KetuaJurusan $KetuaJurusan)
    {
        // Mulai transaksi untuk memastikan semuanya terhapus atau tidak ada yang terhapus jika gagal
        DB::beginTransaction();

        try {

            // Hapus data dosen itu sendiri
            $KetuaJurusan->delete();

            // Tambahkan notifikasi alert bahwa data dosen dihapus
            Alert::create([
                'title' => 'Data Ketua Jurusan Dihapus',
                'message' => 'Data ketua jurusan dengan nama ' . $KetuaJurusan->nama_ketua_jurusan . ' telah berhasil dihapus.',
                'type' => 'info',
                'sended_at' => now(),
                'is_read' => false,
                'users_id' => auth()->user()->id
            ]);

            // Tambahkan pesan baru
            Message::create([
                'sender' => 'System',
                'message' => 'Data ketua jurusan dengan nama ' . $KetuaJurusan->nama_ketua_jurusan . ' telah berhasil dihapus.',
                'status' => 'unread',
                'sended_time' => now(),
                'users_id' => auth()->user()->id
            ]);

            // Commit transaksi jika semua operasi berhasil
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Ketua Jurusan Deleted Successfully'
            ]);
        } catch (\Exception $e) {
            // Rollback transaksi jika ada error
            DB::rollBack();

            // Log error untuk debugging (boleh dihilangkan di production)
            Log::error('Error deleting ketua jurusan: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete the ketua jurusan. Please try again later.'
            ]);
        }
    }
}
