namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CintaImport;
use App\Models\MasterAset;

class ImportCintaController extends Controller
{
    public function index()
    {
        return view('admin.import-cinta');
    }

    public function preview(Request $request)
    {
        $import = new CintaImport();
        Excel::import($import, $request->file('file'));

        return view('admin.preview-cinta', [
            'data' => $import->data
        ]);
    }

    public function store(Request $request)
    {
        foreach ($request->data as $item) {

            if (MasterAset::where('kode_barang', $item['kode_barang'])->exists()) {
                continue;
            }

            MasterAset::create([
                'kode_barang' => $item['kode_barang'],
                'nama_barang' => $item['nama_barang'],
                'jenis_aset'  => $item['jenis_aset'],
                'kategori'    => $item['kategori'],
                'lokasi'      => $item['lokasi'],
                'tahun_perolehan' => $item['tahun'],
                'spesifikasi' => $item['spesifikasi'],
            ]);
        }

        return back()->with('success','Import berhasil');
    }
}