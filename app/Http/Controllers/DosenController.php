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
            ->select('id', 'nama_dosen', 'email_dosen', 'jenis_kelamin_dosen');

        return DataTables::of($dosen)
            ->addIndexColumn()
            ->make(true);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dosen.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dosen.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDosenRequest $request)
    {
        $validatedData = $request->validated();

        // Create the user and get the user ID
        $user = User::create([
            'name' => $validatedData['nama_dosen'],
            'email' => $validatedData['email_dosen'],
            'password' => bcrypt($validatedData['password_dosen']),
        ]);

        // Add the user_id to the validated data before saving the Dosen record
        $validatedData['users_id'] = $user->id;

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

        Message::create([
            'sender' => 'System',
            'message' => 'Dosen baru telah berhasil ditambahkan.',
            'status' => 'unread',
            'sended_time' => now(),
            'users_id' => auth()->user()->id
        ]);

        // Redirect atau beri respon setelah data berhasil disimpan
        return redirect()->route('dosen.index')->with('success', 'Data dosen berhasil disimpan!');
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
        return view('dosen.edit')
            ->with('dosen', $dosen);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDosenRequest $request, $id)
    {
        // Ambil data dosen berdasarkan ID
        $dosen = Dosen::findOrFail($id);

        // Validasi input dari request
        $validatedData = $request->validated();

        // Cek apakah email atau password perlu diupdate
        if ($request->filled('email_dosen') || $request->filled('password_dosen')) {
            // Cari user terkait dosen
            $user = User::find($dosen->users_id);
            $user->name = $validatedData['nama_dosen'];
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
        // Tambahkan notifikasi alert
        Alert::create([
            'title' => 'Data Dosen Diperbarui',
            'message' => 'Data dosen dengan nama ' . $dosen->nama_dosen . ' telah berhasil diperbarui.',
            'type' => 'info',
            'sended_at' => now(),
            'is_read' => false,
            'users_id' => auth()->user()->id
        ]);

        // Tambahkan pesan baru
        Message::create([
            'sender' => 'System',
            'message' => 'Data dosen dengan nama ' . $dosen->nama_dosen . ' telah berhasil diperbarui.',
            'status' => 'unread',
            'sended_time' => now(),
            'users_id' => auth()->user()->id
        ]);

        // Redirect atau beri respon setelah data berhasil diupdate
        return redirect()->route('dosen.index')->with('success', 'Data dosen berhasil diperbarui!');
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
