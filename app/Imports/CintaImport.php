namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class CintaImport implements ToCollection
{
    public $data = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {

            if ($index == 0) continue; // skip header

            $this->data[] = [
                'kode_barang' => $row[0],
                'nama_barang' => $row[1],
                'jenis_aset'  => $row[2],
                'kategori'    => $row[3],
                'lokasi'      => $row[4],
                'tahun'       => $row[5],
                'spesifikasi' => $row[6],
            ];
        }
    }
}