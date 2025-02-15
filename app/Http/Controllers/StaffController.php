<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStaffRequest;
use App\Http\Requests\UpdateStaffRequest;
use App\Models\Alert;
use App\Models\Message;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class StaffController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:staff.index')->only('index', 'list');
        $this->middleware('permission:staff.create')->only('create', 'store');
        $this->middleware('permission:staff.edit')->only('edit', 'update');
        $this->middleware('permission:staff.destroy')->only('destroy');
    }

    public function list(Request $request)
    {
        $staff = DB::table('staff')
            ->select('id', 'nama_staff', 'email_staff', 'jenis_kelamin_staff');

        return DataTables::of($staff)
            ->addIndexColumn()
            ->make(true);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('staff.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('staff.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStaffRequest $request)
    {
        DB::beginTransaction(); // Start the transaction

        try {
            $validatedData = $request->validated();

            // Create the user and get the user ID
            $user = User::create([
                'name' => $validatedData['nama_panggilan_staff'],
                'email' => $validatedData['email_staff'],
                'password' => Hash::make($validatedData['password_staff']),  // Hash password for user
            ]);

            $user->assignRole('staff');

            $validatedData['users_id'] = $user->id;
            $validatedData['password_staff'] = Hash::make($validatedData['password_staff']);

            // Create the staff record
            Staff::create($validatedData);

            // Add alert notification
            Alert::create([
                'title' => 'Data Staff Ditambahkan',
                'message' => 'Staff baru telah berhasil ditambahkan.',
                'type' => 'info',
                'sended_at' => now(),
                'is_read' => false,
                'users_id' => auth()->user()->id,
            ]);

            // Add message notification
            Message::create([
                'sender' => 'System',
                'message' => 'Staff baru telah berhasil ditambahkan.',
                'users_id' => auth()->user()->id,
            ]);

            // Commit the transaction
            DB::commit();

            return redirect()->route('staff.index')->with('success', 'Data berhasil ditambahkan');
        } catch (\Exception $e) {
            // If any error occurs, rollback the transaction
            DB::rollBack();

            // Optionally, you can log the exception message for debugging
            Log::error('Error occurred while adding staff: ' . $e->getMessage());

            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Staff $staff)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Staff $staff)
    {
        return view('staff.edit')
            ->with('staff', $staff);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStaffRequest $request, $id)
    {
        DB::beginTransaction(); // Start the transaction

        try {
            $staff = Staff::findOrFail($id);

            // Validasi input dari request
            $validatedData = $request->validated();
            // Cek apakah email atau password perlu diupdate
            if ($request->filled('email_staff') || $request->filled('password_staff') || $request->filled('nama_panggilan_staff')) {
                // Cari user terkait staff
                $user = User::find($staff->users_id);
                $user->name = $validatedData['nama_panggilan_staff'];
                $user->email = $validatedData['email_staff'];
                $user->password = Hash::make($validatedData['password_staff']);
                $user->save();
            }

            if ($request->filled('password_staff')) {
                // Jika diisi, hash password sebelum disimpan
                $validatedData['password_staff'] = Hash::make($validatedData['password_staff']);
            } else {
                // Jika password tidak diubah, gunakan password lama, namun tetap di-hash
                $validatedData['password_staff'] = Hash::make($staff->password_staff);
            }
            // Update data staff
            $staff->update($validatedData);

            // Add alert notification
            Alert::create([
                'title' => 'Data Staff Diubah',
                'message' => 'Data staff telah berhasil diubah.',
                'type' => 'info',
                'sended_at' => now(),
                'is_read' => false,
                'users_id' => auth()->user()->id,
            ]);

            // Add message notification
            Message::create([
                'sender' => 'System',
                'message' => 'Data staff telah berhasil diubah.',
                'users_id' => auth()->user()->id,
            ]);

            // Commit the transaction
            DB::commit();

            return redirect()->route('staff.index')->with('success', 'Data berhasil diubah');
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
    public function destroy(Staff $staff)
    {
        // Mulai transaksi untuk memastikan semuanya terhapus atau tidak ada yang terhapus jika gagal
        DB::beginTransaction();

        try {
            // Hapus data user yang terkait dengan staff
            $user = User::findOrFail($staff->users_id);
            $user->delete();

            // Hapus data staff itu sendiri
            $staff->delete();

            // Tambahkan notifikasi alert bahwa data staff dihapus
            Alert::create([
                'title' => 'Data Staff Dihapus',
                'message' => 'Data staff dengan nama ' . $staff->nama_staff . ' telah berhasil dihapus.',
                'type' => 'info',
                'sended_at' => now(),
                'is_read' => false,
                'users_id' => auth()->user()->id
            ]);

            // Tambahkan pesan baru
            Message::create([
                'sender' => 'System',
                'message' => 'Data staff dengan nama ' . $staff->nama_staff . ' telah berhasil dihapus.',
                'users_id' => auth()->user()->id
            ]);

            // Commit the transaction
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Staff Deleted Successfully'
            ]);
        } catch (\Exception $e) {

            // Rollback transaksi jika ada error
            DB::rollBack();
            // If there's an error, return a failure response
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete the staff. Please try again later.'
            ]);
        }
    }
}
