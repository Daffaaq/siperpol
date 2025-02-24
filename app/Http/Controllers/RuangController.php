<?php

namespace App\Http\Controllers;

use App\Events\AlertAndMessagesEvent;
use App\Http\Requests\StoreRuangRequest;
use App\Http\Requests\UpdateRuangRequest;
use App\Models\Ruang;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RuangController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:ruang.index')->only('index', 'list');
        $this->middleware('permission:ruang.create')->only('create', 'store');
        $this->middleware('permission:ruang.edit')->only('edit', 'update');
        $this->middleware('permission:ruang.destroy')->only('destroy');
    }

    public function list(Request $request)
    {
        $ruang = DB::table('ruangs')
            ->leftJoin('jurusans', 'ruangs.jurusans_id', '=', 'jurusans.id')
            ->select('ruangs.id', 'ruangs.nama_ruang', 'ruangs.kode_ruang', 'ruangs.is_active', 'jurusans.nama_jurusan')
            ->get();

        return DataTables::of($ruang)
            ->addIndexColumn()
            ->make(true);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('ruang.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jurusans = DB::table('jurusans')->select('nama_jurusan', 'id')->get();
        $fasilitas = DB::table('fasilitas')->select('nama_fasilitas', 'id')->get();
        return view('ruang.create', compact('jurusans', 'fasilitas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRuangRequest $request)
    {
        // Start transaction
        DB::beginTransaction();

        try {
            // Get validated data from the request
            $validated = $request->validated();

            // Create Ruang instance
            $ruang = Ruang::create($validated);

            // Handle Image upload (if any)
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('images/ruangs', 'public');
                $ruang->image = $imagePath;
                $ruang->save(); // Don't forget to save the image path!
            }
            // dd($ruang);
            // Attach the selected Fasilitas
            if ($request->has('fasilitas_id')) {
                $ruang->fasilitas()->attach($request->fasilitas_id);
            }

            event(new AlertAndMessagesEvent(
                'Data Ruang',  // Title
                'Ruang baru telah berhasil ditambahkan.',  // Message
                auth()->user()->id  // User ID
            ));

            // Commit transaction if everything is fine
            DB::commit();

            // Redirect with success message
            return redirect()->route('ruang.index')->with('success', 'Ruang berhasil ditambahkan.');
        } catch (\Exception $e) {
            // Rollback transaction if any error occurs
            DB::rollBack();
            Log::error($e);

            // Return back with error message
            return back()->withInput()->with('error', 'Terjadi kesalahan saat membuat ruang baru.');
        }
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Find the Ruang by ID, or fail if not found
        $ruang = Ruang::with('fasilitas')->findOrFail($id);

        // Pass the Ruang and its related Fasilitas to the view
        return view('ruang.show', compact('ruang'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Fetch the ruang by its ID
        $ruang = Ruang::findOrFail($id);

        // Get jurusans and fasilitas for the select options
        $jurusans = DB::table('jurusans')->select('nama_jurusan', 'id')->get();
        $fasilitas = DB::table('fasilitas')->select('nama_fasilitas', 'id')->get();

        // Pass the ruang, jurusans, and fasilitas to the edit view
        return view('ruang.edit', compact('ruang', 'jurusans', 'fasilitas'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRuangRequest $request, $id)
    {
        // Start transaction
        DB::beginTransaction();

        try {
            // Get validated data from the request
            $validated = $request->validated();

            // Find the existing Ruang by its ID
            $ruang = Ruang::findOrFail($id);

            // Update the Ruang data
            $ruang->update($validated);

            // Handle image upload (if a new image is uploaded)
            if ($request->hasFile('image')) {
                // Delete the old image if it exists (optional)
                if ($ruang->image) {
                    Storage::disk('public')->delete($ruang->image);
                }

                // Upload the new image
                $imagePath = $request->file('image')->store('images/ruangs', 'public');
                $ruang->image = $imagePath;
            }

            // Attach the selected Fasilitas
            if ($request->has('fasilitas_id') && count($request->fasilitas_id) > 0) {
                // Sinkronkan fasilitas yang dipilih
                $ruang->fasilitas()->sync($request->fasilitas_id);
            } else {
                // Jika tidak ada fasilitas yang dipilih, tampilkan error
                return back()->withInput()->with('error', 'Setidaknya satu fasilitas harus dipilih.');
            }

            // Dispatch event to handle the alert and message notifications
            event(new AlertAndMessagesEvent(
                'Data Ruang Diperbarui',  // Title
                'Ruang dengan nama ' . $ruang->nama_ruang . ' telah berhasil diperbarui.',  // Message
                auth()->user()->id  // User ID
            ));
            // Commit the transaction
            DB::commit();

            // Redirect with success message
            return redirect()->route('ruang.index')->with('success', 'Ruang berhasil diperbarui.');
        } catch (\Exception $e) {
            // Rollback the transaction if an error occurs
            DB::rollBack();
            Log::error($e);

            // Return back with an error message
            return back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui ruang.');
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // mulai transaksi
        DB::beginTransaction();
        try {
            $ruang = Ruang::find($id);
            $ruang->delete();

            // Dispatch event to handle the alert and message notifications
            event(new AlertAndMessagesEvent(
                'Data Ruang Dihapus',  // Title
                'Ruang dengan nama ' . $ruang->nama_ruang . ' telah berhasil dihapus.',  // Message
                auth()->user()->id  // User ID
            ));

            // Commit the transaction
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Ruang Deleted Successfully'
            ]);
        } catch (\Exception $e) {
            // Rollback the transaction if an error occurs
            DB::rollBack();
            Log::error($e);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete the ruang. Please try again later.'
            ]);
        }
    }
}
