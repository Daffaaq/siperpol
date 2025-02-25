<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrganisasiRequest;
use App\Models\OrganisasiIntra;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class OrganisasiIntraController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:organisasi.index')->only('index', 'list');
        $this->middleware('permission:organisasi.create')->only('create', 'store');
        $this->middleware('permission:organisasi.edit')->only('edit', 'update');
        $this->middleware('permission:organisasi.destroy')->only('destroy');
    }

    public function list(Request $request)
    {
        $query = DB::table('organisasi_intras')
            ->leftJoin('jurusans', 'organisasi_intras.jurusans_id', '=', 'jurusans.id')
            ->select(
                'organisasi_intras.id',
                'organisasi_intras.nama_organisasi_intra',
                'organisasi_intras.is_active',
                'organisasi_intras.kode_organisasi_intra',
                'organisasi_intras.tipe_organisasi_intra',
                'jurusans.nama_jurusan',
                'organisasi_intras.users_id'
            );

        // Filter by jurusan_id if provided
        if ($request->has('jurusan_id') && $request->jurusan_id != '') {
            $query->where('organisasi_intras.jurusans_id', $request->jurusan_id);
        }

        // Filter by tipe_organisasi_intra if provided
        if ($request->has('tipe_organisasi_intra') && $request->tipe_organisasi_intra != '') {
            $query->where('organisasi_intras.tipe_organisasi_intra', $request->tipe_organisasi_intra);
        }

        $oki = $query->get();

        return DataTables::of($oki)
            ->addIndexColumn()
            ->make(true);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jurusans = DB::table('jurusans')->select('nama_jurusan', 'id')->get();
        $tipeOrganisasi = ['jurusan', 'non-jurusan', 'lembaga-tinggi'];  // Enum values for tipe_organisasi_intra
        return view('organisasi.index', compact('jurusans', 'tipeOrganisasi'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        $getJurusan = DB::table('jurusans')->select('id', 'nama_jurusan')->get();
        return view('organisasi.create', compact('getJurusan', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrganisasiRequest $request)
    {
        // mulai transaksi
        DB::beginTransaction();
        try {
            // Create User only if ketua_umum data is provided
            $user = null;
            if ($request->has('nama_ketua_umum') && $request->has('email_ketua_umum') && $request->has('password_ketua_umum') && $request->password_ketua_umum) {
                $user = User::create([
                    'name' => $request->nama_ketua_umum,
                    'email' => $request->email_ketua_umum,
                    'password' => Hash::make($request->password_ketua_umum), // Only hash if password is present
                ]);
                $userRole = Role::where('id', $request->roles)->get();

                // Mengassign role ke user
                $user->syncRoles($userRole);
            }

            // Set password for OrganisasiIntra if password is provided, otherwise leave it null
            $passwordKetuaUmum = $request->filled('password_ketua_umum') ? Hash::make($request->password_ketua_umum) : null;

            // Create the OrganisasiIntra and associate it with the User (if exists)
            OrganisasiIntra::create([
                'jurusans_id' => $request->jurusans_id ?? null,
                'nama_organisasi_intra' => $request->nama_organisasi_intra,
                'is_active' => $request->is_active,
                'kode_organisasi_intra' => $request->kode_organisasi_intra,
                'tipe_organisasi_intra' => $request->tipe_organisasi_intra,
                'password_ketua_umum' => $passwordKetuaUmum, // Only set password if provided
                'nama_ketua_umum' => $request->nama_ketua_umum ?? null,
                'email_ketua_umum' => $request->email_ketua_umum ?? null,
                'users_id' => $user ? $user->id : null, // Only set users_id if user is created
            ]);

            DB::commit();
            return redirect()->route('organisasi.index')->with('success', 'Organisasi Intra Created Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Organisasi Intra Created Failed');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(OrganisasiIntra $organisasiIntra)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Get the current OrganisasiIntra data by its ID
        $organisasi = OrganisasiIntra::findOrFail($id);

        $user = User::find($organisasi->users_id);

        // Get the list of jurusans
        $getJurusan = DB::table('jurusans')->select('id', 'nama_jurusan')->get();
        $roles = Role::all();

        // Return the edit view with the current data
        return view('organisasi.edit', compact('organisasi', 'getJurusan', 'roles', 'user'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Begin transaction
        DB::beginTransaction();
        try {
            // Find the existing OrganisasiIntra by ID
            $organisasi = OrganisasiIntra::findOrFail($id);

            // Update User only if new 'ketua_umum' data is provided
            $user = null;
            if ($request->has('nama_ketua_umum') && $request->has('email_ketua_umum') && $request->has('password_ketua_umum') && $request->password_ketua_umum) {
                // If the user's email exists, update the user's details
                $user = User::find($organisasi->users_id);
                if ($user) {
                    $user->update([
                        'name' => $request->nama_ketua_umum,
                        'email' => $request->email_ketua_umum,
                        'password' => Hash::make($request->password_ketua_umum), // Only hash if password is present
                    ]);
                    $userRole = Role::where('id', $request->roles)->get();

                    // Mengassign role ke user
                    $user->syncRoles($userRole);
                }
            }

            // Set password for OrganisasiIntra if password is provided, otherwise leave it unchanged
            $passwordKetuaUmum = $request->filled('password_ketua_umum') ? Hash::make($request->password_ketua_umum) : $organisasi->password_ketua_umum;

            // Update the OrganisasiIntra record
            $organisasi->update([
                'jurusans_id' => $request->jurusans_id ?? $organisasi->jurusans_id,
                'nama_organisasi_intra' => $request->nama_organisasi_intra,
                'is_active' => $request->is_active,
                'kode_organisasi_intra' => $request->kode_organisasi_intra,
                'tipe_organisasi_intra' => $request->tipe_organisasi_intra,
                'password_ketua_umum' => $passwordKetuaUmum, // Only set password if provided
                'nama_ketua_umum' => $request->nama_ketua_umum ?? $organisasi->nama_ketua_umum,
                'email_ketua_umum' => $request->email_ketua_umum ?? $organisasi->email_ketua_umum,
                'users_id' => $user ? $user->id : $organisasi->users_id, // Only update users_id if user was created
            ]);

            // Commit the transaction
            DB::commit();
            return redirect()->route('organisasi.index')->with('success', 'Organisasi Intra Updated Successfully');
        } catch (\Exception $e) {
            // Rollback the transaction if something goes wrong
            DB::rollBack();
            return redirect()->back()->with('error', 'Organisasi Intra Update Failed');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Mulai transaksi
        DB::beginTransaction();

        try {
            // Cari OrganisasiIntra berdasarkan ID
            $organisasiIntra = OrganisasiIntra::find($id);

            // Cek apakah objek organisasi ditemukan
            if ($organisasiIntra) {
                Log::info("Attempting to delete Organisasi Intra with ID: " . $organisasiIntra->id);

                // Cek apakah ada users_id terkait
                if ($organisasiIntra->users_id) {
                    // Hapus pengguna terkait jika ada
                    $user = User::find($organisasiIntra->users_id);
                    if ($user) {
                        $user->delete();  // Pastikan pengguna dihapus dengan benar
                        Log::info("User with ID: " . $user->id . " deleted.");
                    }
                } else {
                    Log::info("No associated user to delete for Organisasi Intra with ID: " . $organisasiIntra->id);
                }

                // Hapus Organisasi Intra itu sendiri
                $organisasiIntra->delete();
                Log::info("Organisasi Intra with ID: " . $organisasiIntra->id . " deleted.");

                // Commit transaksi
                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Organisasi Intra Deleted Successfully'
                ]);
            } else {
                // Organisasi Intra tidak ditemukan
                Log::warning("Organisasi Intra with ID: " . $id . " not found.");
                return response()->json([
                    'success' => false,
                    'message' => 'Organisasi Intra not found'
                ]);
            }
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi error
            DB::rollBack();

            // Log error untuk debugging
            Log::error('Failed to delete Organisasi Intra: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete the Organisasi Intra. Please try again later.'
            ]);
        }
    }
}
