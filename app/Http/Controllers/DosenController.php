<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDosenRequest;
use App\Http\Requests\UpdateDosenRequest;
use App\Models\Alert;
use App\Models\Dosen;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class DosenController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:dosen.index')->only('index', 'list');
        $this->middleware('permission:dosen.create')->only('create', 'store');
        $this->middleware('permission:dosen.edit')->only('edit', 'update');
        $this->middleware('permission:dosen.destroy')->only('destroy');
    }

    public function list(Request $request)
    {
        $dosen = DB::table('dosens')
            ->leftJoin('jurusans', 'dosens.jurusans_id', '=', 'jurusans.id')
            ->select('dosens.id', 'dosens.nama_dosen', 'dosens.email_dosen', 'dosens.jenis_kelamin_dosen', 'jurusans.nama_jurusan');

        // Apply filter if jurusan_id is provided
        if ($request->has('jurusan_id') && $request->jurusan_id != '') {
            $dosen->where('dosens.jurusans_id', $request->jurusan_id);
        }

        return DataTables::of($dosen)
            ->addIndexColumn()
            ->make(true);
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jurusans = DB::table('jurusans')->select('nama_jurusan', 'id')->get();
        return view('dosen.index', compact('jurusans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jurusans = DB::table('jurusans')->select('id', 'nama_jurusan')->get();
        return view('dosen.create', compact('jurusans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDosenRequest $request)
    {
        DB::beginTransaction(); // Start the transaction

        try {
            $validatedData = $request->validated();

            // Create the user and get the user ID
            $user = User::create([
                'name' => $validatedData['nama_panggilan_dosen'],
                'email' => $validatedData['email_dosen'],
                'password' => Hash::make($validatedData['password_dosen']),  // Hash password for user
            ]);

            $user->assignRole('dosen');

            // Add the user_id to the validated data before saving the Dosen record
            $validatedData['users_id'] = $user->id;
            $validatedData['password_dosen'] = Hash::make($validatedData['password_dosen']);  // Hash password for Dosen

            // Save the Dosen data with the associated user_id
            Dosen::create($validatedData);

            // Add alert notification
            Alert::create([
                'title' => 'Data Dosen Ditambahkan',
                'message' => 'Dosen baru telah berhasil ditambahkan.',
                'type' => 'info',
                'sended_at' => now(),
                'is_read' => false,
                'users_id' => auth()->user()->id
            ]);

            // Create message for the system
            Message::create([
                'sender' => 'System',
                'message' => 'Dosen baru telah berhasil ditambahkan.',
                'status' => 'unread',
                'sended_time' => now(),
                'users_id' => auth()->user()->id
            ]);

            // Commit the transaction
            DB::commit();

            return redirect()->route('dosen.index')->with('success', 'Data dosen berhasil disimpan!');
        } catch (\Exception $e) {
            // If any error occurs, rollback the transaction
            DB::rollBack();

            // Optionally, you can log the exception message for debugging
            Log::error('Error occurred while storing dosen: ' . $e->getMessage());

            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Dosen $dosen)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dosen $dosen)
    {
        $jurusans = DB::table('jurusans')->select('id', 'nama_jurusan')->get();
        return view('dosen.edit')
            ->with('dosen', $dosen)
            ->with('jurusans', $jurusans);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDosenRequest $request, $id)
    {
        DB::beginTransaction(); // Start the transaction

        try {
            // Ambil data dosen berdasarkan ID
            $dosen = Dosen::findOrFail($id);

            // Validasi input dari request
            $validatedData = $request->validated();

            // Cek apakah email atau password perlu diupdate
            if ($request->filled('email_dosen') || $request->filled('password_dosen')) {
                // Cari user terkait dosen
                $user = User::find($dosen->users_id);
                $user->name = $validatedData['nama_panggilan_dosen'];
                $user->email = $validatedData['email_dosen'];

                // Jika password diisi, hash password terlebih dahulu
                if ($request->filled('password_dosen')) {
                    $user->password = Hash::make($validatedData['password_dosen']);
                }

                // Simpan perubahan data user
                $user->save();
            }

            if ($request->filled('password_dosen')) {
                // Jika diisi, hash password sebelum disimpan
                $validatedData['password_dosen'] = Hash::make($validatedData['password_dosen']);
            } else {
                // Jika password tidak diubah, gunakan password lama, namun tetap di-hash
                $validatedData['password_dosen'] = Hash::make($dosen->password_dosen);
            }

            // Update data dosen dengan data yang telah divalidasi (tanpa password_dosen jika tidak diubah)
            $dosen->update($validatedData);

            // Add alert notification
            Alert::create([
                'title' => 'Data Dosen Diperbarui',
                'message' => 'Data dosen dengan nama ' . $dosen->nama_dosen . ' telah berhasil diperbarui.',
                'type' => 'info',
                'sended_at' => now(),
                'is_read' => false,
                'users_id' => auth()->user()->id
            ]);

            // Add message notification
            Message::create([
                'sender' => 'System',
                'message' => 'Data dosen dengan nama ' . $dosen->nama_dosen . ' telah berhasil diperbarui.',
                'status' => 'unread',
                'sended_time' => now(),
                'users_id' => auth()->user()->id
            ]);

            // Commit the transaction
            DB::commit();

            // Redirect or send a response after data is successfully updated
            return redirect()->route('dosen.index')->with('success', 'Data dosen berhasil diperbarui!');
        } catch (\Exception $e) {
            // If any error occurs, rollback the transaction
            DB::rollBack();

            // Optionally, you can log the exception message for debugging
            Log::error('Error occurred while updating dosen: ' . $e->getMessage());

            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dosen $dosen)
    {
        // Mulai transaksi untuk memastikan semuanya terhapus atau tidak ada yang terhapus jika gagal
        DB::beginTransaction();

        try {
            // Hapus data user yang terkait dengan dosen
            $user = User::findOrFail($dosen->users_id);
            $user->delete();

            // Hapus data dosen itu sendiri
            $dosen->delete();

            // Tambahkan notifikasi alert bahwa data dosen dihapus
            Alert::create([
                'title' => 'Data Dosen Dihapus',
                'message' => 'Data dosen dengan nama ' . $dosen->nama_dosen . ' telah berhasil dihapus.',
                'type' => 'info',
                'sended_at' => now(),
                'is_read' => false,
                'users_id' => auth()->user()->id
            ]);

            // Tambahkan pesan baru
            Message::create([
                'sender' => 'System',
                'message' => 'Data dosen dengan nama ' . $dosen->nama_dosen . ' telah berhasil dihapus.',
                'status' => 'unread',
                'sended_time' => now(),
                'users_id' => auth()->user()->id
            ]);

            // Commit transaksi jika semua operasi berhasil
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'dosen Deleted Successfully'
            ]);
        } catch (\Exception $e) {
            // Rollback transaksi jika ada error
            DB::rollBack();

            // Log error untuk debugging (boleh dihilangkan di production)
            Log::error('Error deleting dosen: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete the dosen. Please try again later.'
            ]);
        }
    }
}
