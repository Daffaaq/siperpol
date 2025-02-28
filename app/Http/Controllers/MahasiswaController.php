<?php

namespace App\Http\Controllers;

use App\Events\AlertAndMessagesEvent;
use App\Http\Requests\ImportMahasiswaRequest;
use App\Http\Requests\StoreMahasiswaRequest;
use App\Http\Requests\UpdateMahasiswaRequest;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\MahasiswaImport;
use App\Models\Prodi;

class MahasiswaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:mahasiswa.index')->only('index', 'list');
        $this->middleware('permission:mahasiswa.create')->only('create', 'store');
        $this->middleware('permission:mahasiswa.edit')->only('edit', 'update');
        $this->middleware('permission:mahasiswa.destroy')->only('destroy');
    }

    public function list(Request $request)
    {
        $mahasiswa = DB::table('mahasiswas')
            ->leftJoin('prodis', 'mahasiswas.prodis_id', '=', 'prodis.id')
            ->leftJoin('jurusans', 'prodis.jurusans_id', '=', 'jurusans.id')
            ->select('mahasiswas.id', 'mahasiswas.nama_mahasiswa', 'prodis.nama_prodi', 'jurusans.nama_jurusan');

        // Filter berdasarkan jurusan_id jika ada (jurusan ada di tabel prodis)
        if ($request->has('jurusan_id') && $request->jurusan_id != '') {
            $mahasiswa->where('prodis.jurusans_id', $request->jurusan_id);
        }

        // Filter berdasarkan prodi_id jika ada
        if ($request->has('prodi_id') && $request->prodi_id != '') {
            $mahasiswa->where('prodis.id', $request->prodi_id);
        }

        // Return datatables response
        return datatables::of($mahasiswa)
            ->addIndexColumn()
            ->make(true);
    }



    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('mahasiswa.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $getJurusan = DB::table('jurusans')->select('id', 'nama_jurusan')->get();
        return view('mahasiswa.create', compact('getJurusan'));
    }

    public function getJurusan()
    {
        $jurusan = DB::table('jurusans')->get();
        return response()->json($jurusan);
    }

    public function getProdi($id)
    {
        $prodi = DB::table('prodis')->where('jurusans_id', $id)->get();
        return response()->json($prodi);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMahasiswaRequest $request)
    {
        DB::beginTransaction(); // Start the transaction

        try {
            $validatedData = $request->validated();

            // Create the user and get the user ID
            $user = User::create([
                'name' => $validatedData['nama_mahasiswa'],
                'email' => $validatedData['email_mahasiswa'],
                'password' => Hash::make($validatedData['password_mahasiswa']),  // Hash password for user
            ]);

            $user->assignRole('mahasiswa');  // Assign the 'mahasiswa' role

            // Add the user_id to the validated data before saving the Mahasiswa record
            $validatedData['users_id'] = $user->id;
            $validatedData['password_mahasiswa'] = Hash::make($validatedData['password_mahasiswa']);  // Hash password for Mahasiswa

            // Save the Mahasiswa data with the associated user_id
            Mahasiswa::create($validatedData);

            // Dispatch the event for alert and messages
            event(new AlertAndMessagesEvent(
                'Data Mahasiswa Ditambahkan',  // Title
                'Mahasiswa baru telah berhasil ditambahkan.',  // Message
                auth()->user()->id  // User ID
            ));

            // Commit the transaction
            DB::commit();

            return redirect()->route('mahasiswa.index')->with('success', 'Data mahasiswa berhasil disimpan!');
        } catch (\Exception $e) {
            // If any error occurs, rollback the transaction
            DB::rollBack();

            // Optionally, you can log the exception message for debugging
            Log::error('Error occurred while storing mahasiswa: ' . $e->getMessage());

            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Mahasiswa $mahasiswa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //find id 
        $mahasiswa = Mahasiswa::findOrFail($id);
        $getJurusan = DB::table('jurusans')->select('id', 'nama_jurusan')->get();
        // dd($mahasiswa);
        return view('mahasiswa.edit', compact('mahasiswa', 'getJurusan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMahasiswaRequest $request, $id)
    {
        DB::beginTransaction(); // Start the transaction

        try {
            // Validate the request
            $validatedData = $request->validated();

            // Find the mahasiswa by ID
            $mahasiswa = Mahasiswa::findOrFail($id);

            // Update the user associated with the mahasiswa
            $user = $mahasiswa->user; // Assuming you have a relationship between Mahasiswa and User

            // Update user details
            $user->update([
                'name' => $validatedData['nama_mahasiswa'],
                'email' => $validatedData['email_mahasiswa'],
                // If the password is changed, hash it
                'password' => $validatedData['password_mahasiswa'] ? Hash::make($validatedData['password_mahasiswa']) : $user->password,
            ]);

            // Assign the 'mahasiswa' role to the user again (optional, in case the user needs to be re-assigned)
            $user->assignRole('mahasiswa');

            // Prepare the mahasiswa data to update
            $validatedData['password_mahasiswa'] = $validatedData['password_mahasiswa'] ? Hash::make($validatedData['password_mahasiswa']) : $mahasiswa->password_mahasiswa;  // Hash password for Mahasiswa, if it's updated
            $validatedData['users_id'] = $user->id;

            // Update the mahasiswa data
            $mahasiswa->update($validatedData);

            // Dispatch event for success alert and messages
            event(new AlertAndMessagesEvent(
                'Data Mahasiswa Diperbarui',  // Title
                'Data Mahasiswa dengan nama ' . $mahasiswa->nama_mahasiswa . ' telah berhasil diperbarui.',  // Message
                auth()->user()->id  // User ID
            ));

            // Commit the transaction
            DB::commit();

            // Return success response
            return redirect()->route('mahasiswa.index')->with('success', 'Data mahasiswa berhasil diperbarui!');
        } catch (\Exception $e) {
            // If any error occurs, rollback the transaction
            DB::rollBack();

            // Optionally, log the error for debugging
            Log::error('Error occurred while updating mahasiswa: ' . $e->getMessage());

            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //mulai transaksi
        DB::beginTransaction();
        try {
            //find id
            $mahasiswa = Mahasiswa::find($id);

            //delete data
            $mahasiswa->delete();

            // Dispatch the event for alert and messages
            event(new AlertAndMessagesEvent(
                'Data Mahasiswa Dihapus',  // Title
                'Data mahasiswa dengan nama ' . $mahasiswa->nama_mahasiswa . ' telah berhasil dihapus.',  // Message
                auth()->user()->id  // User ID (who is performing the deletion)
            ));

            DB::commit();
            //return json
            return response()->json([
                'success' => true,
                'message' => 'Mahasiswa Deleted Successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Mahasiswa Deleted Failed'
            ]);
        }
    }

    public function ImportForm()
    {
        $getJurusan = DB::table('jurusans')->select('id', 'nama_jurusan')->get();
        return view('mahasiswa.import', compact('getJurusan'));
    }

    public function importMahasiswa(ImportMahasiswaRequest $request)
    {
        // Dapatkan prodis_id yang dipilih atau dikirim
        $prodis_id = $request->input('prodis_id');

        // Lakukan impor
        Excel::import(new MahasiswaImport($prodis_id), $request->file('file'));

        return redirect()->route('mahasiswa.index')->with('success', 'Data mahasiswa berhasil diimpor.');
    }
}
