# Format Panduan Import Peserta Excel

## Struktur File Excel
File Excel untuk import peserta sangat sederhana - hanya membutuhkan kolom NIP:

### Format Import (Disarankan)
Hanya dengan kolom NIP, sistem akan otomatis mengambil semua data dari database pegawai:

| NIP |
|-----|
| 1234567890123456 |
| 6543210987654321 |

### Format Tambahan (Opsional)
Jika ingin menambahkan kolom lain, data dari Excel akan diabaikan dan tetap mengambil dari database:

| NIP | Nama | Jabatan | SKPD |
|-----|------|---------|------|
| 1234567890123456 | (Data diabaikan) | (Data diabaikan) | (Data diabaikan) |
| 6543210987654321 | (Data diabaikan) | (Data diabaikan) | (Data diabaikan) |

## Nama Kolom yang Didukung
Sistem mendukung berbagai format penulisan nama kolom:

- **NIP**: `nip`, `NIP`, `Nip`
- **Nama**: `nama`, `Nama`, `NAMA`, `name`, `Name`
- **Jabatan**: `jabatan`, `Jabatan`, `JABATAN`, `position`, `Position`
- **SKPD**: `skpd`, `Skpd`, `SKPD`, `instansi`, `Instansi`, `INSTANSI`, `unit`, `Unit`

## Aturan Validasi:
1. **NIP** wajib diisi dan tidak boleh kosong
2. **Pegawai dengan NIP tersebut harus ada di database** - semua data (nama, jabatan, SKPD) diambil dari database
3. NIP harus unik untuk setiap event (tidak boleh ada duplikasi)
4. Maksimal panjang NIP: 50 karakter

## Cara Kerja Sistem:
1. Sistem membaca NIP dari file Excel
2. Mencari data pegawai di database berdasarkan NIP
3. Jika pegawai ditemukan, semua data (nama, jabatan, SKPD) diambil dari database pegawai
4. Jika pegawai tidak ditemukan, baris akan dilewati dengan error
5. Data dari Excel selain NIP akan diabaikan untuk menjaga konsistensi

## Pesan Error yang Mungkin Muncul:
- "Kolom NIP wajib diisi" - Kolom NIP tidak ditemukan atau kosong
- "Pegawai dengan NIP XXX tidak ditemukan di database" - NIP tidak ada di database pegawai
- "Peserta dengan NIP XXX sudah terdaftar di event ini" - Duplikasi NIP dalam event yang sama
- "Error memproses baris: [pesan error]" - Error lain saat memproses data

## Tips:
1. **Pastikan semua NIP yang akan diimport sudah ada di database pegawai**
2. Cukup sediakan kolom NIP saja, format lain akan diabaikan
3. Sistem otomatis menyesuaikan format nama kolom NIP (case-insensitive)
4. Simpan file dalam format .xlsx atau .xls
5. Periksa kembali data sebelum import untuk menghindari duplikasi
6. Jika ada error, sistem akan menampilkan detail error untuk setiap baris bermasalah
