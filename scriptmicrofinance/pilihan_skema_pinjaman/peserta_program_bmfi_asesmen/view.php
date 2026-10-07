<script type="text/javascript">

    

    $(document).ready(function () {

   // bsCustomFileInput.init()

})



</script>





 <!-- <script src="<?php echo base_url() ?>assets/template/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script> -->







<style>

    .foto_profil-src {

        width: 150px;

        height: 150px;

        background-position: center;

        background-repeat: no-repeat;

        background-size: cover;

    }

</style>




<style>

    .foto_dampak_program-src {

        width: 150px;

        height: 150px;

        background-position: center;

        background-repeat: no-repeat;

        background-size: cover;

    }

</style>





<style>

    .lampiran_pelengkap-src {

        width: 150px;

        height: 150px;

        background-position: center;

        background-repeat: no-repeat;

        background-size: cover;

    }

</style>






<div class="card">

    <div class="card-header">



 <center><h1 class="card-text"><b>Peserta Program Microfinance

        </p></b></h1></center>



<center><h5 class="card-text"><b><?= $this->session->userdata('sess_all')->nama_masjid; ?>

            </h5></center>

      <center>  <p><br><?= $this->session->userdata('sess_all')->alamat_masjid; ?>

        </p></b></center>

<br>




<div class="container">
    <div id="totalValues">
        <table class="table table-bordered" id="tabel_saldo_microfinance">
            <thead>
                <tr>
                    <th>Saldo Tersedia Saat ini</th>
                    <th>Dana Saldo Masih di Peserta (Outstanding)</th>
                    <th>Total Saldo Keseluruhan</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td id="totalSaldoMicrofinance"></td>
                    <td id="totalDanaDiPeserta"></td>
                    <td id="saldo"></td>
                </tr>
                <!-- Baris baru untuk menampilkan jumlah peserta dengan pinjaman yang belum lunas -->
                <tr>
                    <td colspan="3" id="jumlahPesertaOutstanding"></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="confirm_update_saldo_microfinanceModal" tabindex="-1" aria-labelledby="confirm_update_saldo_microfinanceModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirm_update_saldo_microfinanceModalLabel">Konfirmasi Perbarui Nilai Tabel Saldo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Apakah Anda yakin ingin memperbarui nilai pada tabel saldo microfinance untuk dibantu balance secara otomatis oleh sistem?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="confirm_update_saldo_microfinanceBtn">OK</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        // Inisialisasi DataTables
        var table = $("#tabel_saldo_microfinance").DataTable({
            searching: false,
            processing: false,
            serverSide: true,
            ordering: false,
            order: [],
            ajax: {
                url: base_url + 'panel/bmfi_transaksi_microfinance/get_total_nominal',
                type: 'GET',
                dataType: 'json',
                dataSrc: '', // Jika struktur respons tidak perlu diubah
                success: function(response) {
                    var totalSaldoMicrofinance = response.total_saldo_microfinance;
                    var totalDanaDiPeserta = response.total_dana_di_peserta;
                    var saldo = response.saldo;
                    var saldoAwalPenerimaan = response.saldo_awal_penerimaan; // Ambil nilai saldo awal penerimaan
                    var jumlahPesertaOutstanding = response.jumlah_peserta_outstanding;

                    // Tampilkan nilai masuk
                    if (saldo.includes('Minus')) {
                        // Jika saldo memiliki label 'Minus', beri warna merah pada nilai masuk
                        $('#totalSaldoMicrofinance').text(totalSaldoMicrofinance).addClass('text-danger');
                    } else {
                        $('#totalSaldoMicrofinance').text(totalSaldoMicrofinance);
                    }

                    $('#totalDanaDiPeserta').text(totalDanaDiPeserta);

                    // Tampilkan nilai saldo, termasuk saldo awal penerimaan
                    if (saldo == saldoAwalPenerimaan) {
                        // Jika saldo sama dengan saldo awal penerimaan, hanya tampilkan saldo
                        $('#saldo').text(saldo);
                    } else {


                         // Jika saldo berbeda dengan saldo awal penerimaan, tampilkan teks tambahan
    $('#saldo').html(saldo + '<br>(Total saldo seharusnya adalah ' + saldoAwalPenerimaan + ')');


                        // Tambahkan tombol untuk perbaiki nilai jika belum ada
                        if ($('#perbaikiSaldoBtn').length === 0) {
                            var buttonHtml = '<button id="perbaikiSaldoBtn" class="btn btn-primary">Perbaiki Nilai</button>';
                            $('#saldo').append(buttonHtml);
                        }

                        // Tambahkan event listener untuk tombol perbaiki nilai
                        $('#perbaikiSaldoBtn').off('click').on('click', function() {
                            // Tampilkan modal konfirmasi
                            $('#confirm_update_saldo_microfinanceModal').modal('show');
                        });

                        // Event listener untuk tombol konfirmasi di dalam modal
                        $('#confirm_update_saldo_microfinanceBtn').off('click').on('click', function() {
                            // Kirim request ke server untuk memperbarui saldo
                            $.ajax({
                                url: base_url + 'panel/bmfi_transaksi_microfinance/update_saldo_microfinance',
                                type: 'POST',
                                dataType: 'json',
                                success: function(response) {
                                    // Handle response jika sukses
                                    if (response.status === 'success') {
                                        // console.log('Nilai saldo microfinance berhasil diperbarui.');

                                        // Tambahkan logika lainnya jika diperlukan, seperti notifikasi atau refresh data
                                        $("#tabel_saldo_microfinance").DataTable().ajax.reload();

                                        // // Tampilkan notifikasi sukses
                                        // alert('Nilai saldo microfinance berhasil diperbarui.');

                                    } else {
                                        // console.error('Gagal memperbarui nilai saldo microfinance:', response.message);

                                        // Tampilkan pesan error atau handle sesuai kebutuhan aplikasi
                                        alert('Gagal memperbarui nilai saldo microfinance: ' + response.message);
                                    }
                                },
                                error: function(xhr, status, error) {
                                    console.error('Terjadi kesalahan:', error);
                                    // Handle error, seperti menampilkan pesan kepada pengguna atau melakukan tindakan perbaikan
                                    alert('Terjadi kesalahan: ' + error);
                                }
                            });

                            // Sembunyikan modal setelah konfirmasi
                            $('#confirm_update_saldo_microfinanceModal').modal('hide');
                        });
                    }

                    // Tampilkan jumlah peserta dengan pinjaman yang belum lunas
                    $('#jumlahPesertaOutstanding').text('Jumlah Peserta dengan Pinjaman Outstanding: ' + jumlahPesertaOutstanding + ' orang peserta microfinance');
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            }
        });
    });
</script>






<br>





        <a href="#" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addModal"><i class="fa fa-plus"></i> Tambah Peserta Program</a> 



        <a href="#" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#cetakModal"><i
                class="fa fa-print"></i> Cetak Data Peserta</a>


          <a href="<?php echo site_url('panel/mp/bmfi_asesmen/bmfi_asesmen') ?>" class="btn btn-danger btn-sm" ><i class="fa fa-eye"></i> Lihat Riwayat Asesmen</a>


           <a href="<?php echo site_url('panel/mp/bmfi_transaksi_microfinance') ?>" class="btn btn-warning btn-sm" ><i class="fa fa-eye"></i> Mutasi Transaksi Microfinance</a>



    </div>

    <div class="card-body">

        <form class="mb-3">

            <div class="form-row align-items-center">

                <div class="col-10">



                    <label class="sr-only" for="txtNama">Nama</label>

                    <input type="text" class="form-control mb-8" id="txtNama" placeholder="Nama Peserta Program Microfinance">

                </div>

               

            </div>

        </form>

        <div class="table-responsive">

            <table class="table table-bordered datatables" width="100%">

                <thead>

                    <tr>

                        <th class="align-middle" width="50">No</th>



                          <!-- <th class="align-middle" width="50">Foto</th> -->


                          <th class="align-middle" width="50">Nilai</th>


                          <th class="align-middle" width="50">Dampak</th>


                          <th class="align-middle" width="50">Kartu</th>


                         <th class="align-middle" width="50">Kwitansi</th>

                        <th class="align-middle" width="250">Nama Peserta</th>


                        <th class="align-middle" width="300" >Keuangan</th>


                         <th class="align-middle" width="350" >Keterangan Usaha</th>

                       

                        <th class="align-middle">Transaksi</th>

                          <th class="align-middle">Aksi</th>

                    </tr>

                </thead>

            </table>

        </div>

    </div>

</div>

<input type="hidden" id="hiddenid">



<!-- modal add -->

<div class="modal fade" id="addModal">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header bg-secondary">

                <h5 class="modal-title">Tambah Peserta Program Microfinance</h5>

                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>

            <form id="peserta_program_bmfi_asesmenFormAdd">

                <div class="modal-body">



             <div class="row">

                <div class="col-md-6">


                    <div class="form-group">

                                <label>Tanggal Registrasi</label>

                                <input type="date" class="form-control" name="tgl_registrasi_peserta" />

                            </div>


                            </div>


                        </div>




                    <div class="row">

                        <div class="col-md-12">

                            <div class="form-group">

                                <label>Nama Peserta</label>

                                <input type="text" name="nama" class="form-control">

                            </div>

                        </div>


                        </div>




                        <div class="row">

                        <div class="col-md-12">

                         <div class="form-group">

                                <label>Jenis Kelamin</label>

                                <select name="jenis_kelamin" class="form-control">

                                    <option value="">--Pilih--</option>

                                    <option value="Laki-laki">Laki-laki</option>

                                    <option value="Perempuan">Perempuan</option>

                                </select>

                            </div>


                        </div>


                        </div>



                        


                <div class="row">
                    

                        <div class="col-md-12">



                            <div class="form-group">



                                <label>No KTP</label>



                                <input type="text" name="no_ktp" class="form-control nomor">



                            </div>




                        </div>




                    </div>





                    
                

                <div class="row">


                        <div class="col-md-12">



                            <div class="form-group">



                                <label>No HP</label>



                                <input type="text" name="no_hp" class="form-control nomor">



                            </div>




                        </div>




                    </div>




                        <div class="row">

                        <div class="col-md-12">

                            <div class="form-group">

                                <label>Tempat Tanggal Lahir</label>

                                <input type="text" name="tempat_tanggal_lahir" class="form-control">

                            </div>

                        </div>



                        </div>



                    <div class="row">

                        <div class="col-md-12">

                            <div class="form-group">

                                <label>Alamat Rumah</label>

                                <textarea name="alamat_rumah" class="form-control" rows="4"></textarea>
                            </div>

                        </div>

                    </div>





                    <div class="row">

                        <div class="col-md-12">

                            <div class="form-group">

                                <label>Nama Usaha</label>

                                <textarea name="nama_usaha" class="form-control" rows="4"></textarea>
                            </div>

                        </div>

                    </div>





                       <div class="form-group">



                        <label>Sektor Usaha Peserta</label>



                        <select name="bmfi_sektor_usaha_peserta" class="form-control" >



                            <option value="">--Pilih--</option>



                            <?php foreach ($bmfi_sektor_usaha_peserta as $v) { ?>



                            <option value="<?php echo $v['id_bmfi_sektor_usaha_peserta'] ?>">

                                <?php echo $v['nama_bmfi_sektor_usaha_peserta'] ?></option>



                            <?php } ?>



                        </select>



                    </div>




                    <div class="row">

                        <div class="col-md-12">

                            <div class="form-group">

                                <label>No Rekening</label>

                                <textarea name="no_rekening" class="form-control" rows="2"></textarea>
                            </div>

                        </div>

                    </div>



                      <div class="row">

                        <div class="col-md-12">

                            <div class="form-group">

                                <label>Nama Bank</label>

                                <textarea name="nama_bank" class="form-control" rows="2"></textarea>
                            </div>

                        </div>

                    </div>



                         <div class="row">

                        <div class="col-md-12">

                            <div class="form-group">

                                <label>Atas Nama Rekening</label>

                                <textarea name="atas_nama_rekening" class="form-control" rows="2"></textarea>
                            </div>

                        </div>

                    </div>






              <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Jumlah Anggota Keluarga</label>
                                <input type="number" name="jumlah_anggota_keluarga" class="form-control">
                            </div>
                        </div>
                    </div>



                    <div class="row">

                        <div class="col-md-12">

                            <div class="form-group">

                                <label>Nama Penjamin</label>

                                <textarea name="nama_penjamin" class="form-control" rows="2"></textarea>
                            </div>

                        </div>

                    </div>





                      <div class="form-group">



                        <label>Status Hubungan Penjamin</label>



                        <select name="bmfi_status_hubungan_penjamin" class="form-control" >



                            <option value="">--Pilih--</option>



                            <?php foreach ($bmfi_status_hubungan_penjamin as $v) { ?>



                            <option value="<?php echo $v['id_bmfi_status_hubungan_penjamin'] ?>">

                                <?php echo $v['nama_bmfi_status_hubungan_penjamin'] ?></option>



                            <?php } ?>



                        </select>



                    </div>







                     <div class="row">

                        <div class="col-md-12">

                            <div class="form-group">

                                <label>Lokasi Usaha</label>

                                <textarea name="lokasi_usaha" class="form-control" rows="4"></textarea>
                            </div>

                        </div>

                    </div>












                    <div class="row">

                        <div class="col-12">

                            <div class="form-group">

                                <label>Keterangan</label>

                                <textarea name="keterangan" class="form-control"></textarea>

                            </div>

                        </div>

                    </div>





<div class="row">
    <div class="col-md-8">
        <div class="form-group">
            <label for="foto_profil" class="form-label">Foto Profil Peserta</label>
            <div class="input-group">
                <input type="file" class="form-control" name="foto_profil" id="foto_profil">
                <label class="input-group-text" for="foto_profil">Pilih file</label>
            </div>
            <small class="form-text text-muted"><i>File Foto Bersifat Opsional (Tidak Wajib)</i></small>
            <small class="text-secondary"><i>Format file yang diizinkan <b>Jpg dan Png</b>. Ukuran file maksimal <b>10 MB</b>.</i></small><br>
            <small class="upload-error text-danger"></small>
        </div>
    </div>
</div>






                </div>









                     <!--  <div class="form-group">



                        <label>Kategori Kualitas Angsuran Peserta</label>



                        <select name="bmfi_kategori_kualitas_angsuran_peserta" class="form-control" >



                            <option value="">--Pilih--</option>



                            <?php foreach ($bmfi_kategori_kualitas_angsuran_peserta as $v) { ?>



                            <option value="<?php echo $v['id_bmfi_kategori_kualitas_angsuran_peserta'] ?>">

                                <?php echo $v['nama_bmfi_kategori_kualitas_angsuran_peserta'] ?></option>



                            <?php } ?>



                        </select>



                    </div>

 -->


<!-- 
                      <div class="form-group">



                        <label>Rating Dampak Kemajuan Peserta</label>



                        <select name="bmfi_rating_dampak_kemajuan_peserta" class="form-control" >



                            <option value="">--Pilih--</option>



                            <?php foreach ($bmfi_rating_dampak_kemajuan_peserta as $v) { ?>



                            <option value="<?php echo $v['id_bmfi_rating_dampak_kemajuan_peserta'] ?>">

                                <?php echo $v['nama_bmfi_rating_dampak_kemajuan_peserta'] ?></option>



                            <?php } ?>



                        </select>



                    </div>

 -->


                                    

                <div class="modal-footer">

                    <span class="loader"><i class="fa fa-spinner fa-spin"></i> Sedang proses...</span>

                    <button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal"><i class="fa fa-times"></i> Batal</button>

                    <button type="submit" class="btn btn-primary btn-sm" id="btn-add"><i class="fa fa-check"></i> Submit</button>

                </div>

            </form>

        </div>

        <!-- /.modal-content -->

    </div>

    <!-- /.modal-dialog -->

</div>

<!-- /.modal -->















<!-- Modal Alert -->
<div class="modal fade" id="peringatan-untuk-asesmen-alertModal" tabindex="-1" aria-labelledby="modalperingatan-telah-lunasLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAlertAsesmenLabel">Peringatan untuk Asesmen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                 <p>Anda harus melakukan asesmen nilai nominal peminjaman peserta terlebih dahulu.</p>
                 <p>Edit data peserta bisa digunakan, setelah proses asesmen telah dilaksanakan</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
               
            </div>
        </div>
    </div>
</div>



<script>
// Menangani klik tombol "Transaksi" (Asesmen dan Peminjaman)
$(document).on('click', '.btn-peringatan-untuk-asesmen-alert', function() {
    // var idPeserta2 = $(this).attr('id_peserta2'); // Ambil nilai id_peserta dari tombol
    // var namaPeserta2 = $(this).attr('nama_peserta2'); // Ambil nilai nama_peserta dari tombol
    // var idAnggaran2 = $(this).attr('id_anggaran2'); // Ambil nilai id_anggaran dari tombol
    // var idSaldo2 = $(this).attr('id_saldo2'); // Ambil nilai id_saldo dari tombol

    // // Mengisi nilai ke dalam input sesuai dengan data yang diambil dari tombol
    // $("#bmfi_peserta_program2").val(idPeserta2);
    // $("#nama_peserta_program2").val(namaPeserta2);
    // $("#anggaran_microfinance2").val(idAnggaran2);
    // $("#saldo_microfinance2").val(idSaldo2);

    // Buka modal "Peminjaman Alert"
    $("#peringatan-untuk-asesmen-alertModal").modal('show');
});


</script>






<!-- modal edit -->

<div class="modal fade" id="editModal">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header bg-secondary">

                <h5 class="modal-title">Lihat dan Edit Peserta Program Microfinance</h5>

                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>

            <form id="peserta_program_bmfi_asesmenFormEdit">

                <input type="hidden" name="id2">

                <div class="modal-body">




             <div class="row">

                <div class="col-md-6">


                    <div class="form-group">

                                <label>Tanggal Registrasi</label>

                                <input type="date" class="form-control" name="tgl_registrasi_peserta2" />

                            </div>


                            </div>


                        </div>




                    <div class="row">

                           <div class="col-md-12">

                            <div class="form-group">

                                <label>Nama Peserta</label>

                                <input type="text" name="nama2" class="form-control">

                            </div>

                        </div>


                        </div>




                        
                        <div class="row">

                        <div class="col-md-12">

                         <div class="form-group">

                                <label>Jenis Kelamin</label>

                                <select name="jenis_kelamin2" class="form-control">

                                    <option value="">--Pilih--</option>

                                    <option value="Laki-laki">Laki-laki</option>

                                    <option value="Perempuan">Perempuan</option>

                                </select>

                            </div>


                        </div>


                        </div>






                <div class="row">
                    

                        <div class="col-md-12">



                            <div class="form-group">



                                <label>No KTP</label>



                                <input type="text" name="no_ktp2" class="form-control nomor">



                            </div>




                        </div>




                    </div>








                <div class="row">
                    

                        <div class="col-md-12">



                            <div class="form-group">



                                <label>No HP</label>



                                <input type="text" name="no_hp2" class="form-control nomor">



                            </div>




                        </div>




                    </div>







                        <div class="row">

                        <div class="col-md-12">

                            <div class="form-group">

                                <label>Tempat Tanggal Lahir</label>

                                <input type="text" name="tempat_tanggal_lahir2" class="form-control">

                            </div>

                        </div>



                        </div>



                     <div class="row">


                        <div class="col-md-12">

                            <div class="form-group">

                                <label>Alamat Rumah</label>

                                <textarea name="alamat_rumah2" class="form-control" rows="4"></textarea>
                            </div>

                        </div>

                    </div>




                     <div class="row">


                        <div class="col-md-12">

                            <div class="form-group">

                                <label>Nama Usaha</label>

                                <textarea name="nama_usaha2" class="form-control" rows="4"></textarea>
                            </div>

                        </div>

                    </div>






                      <div class="form-group">



                        <label>Sektor Usaha Peserta</label>



                        <select name="bmfi_sektor_usaha_peserta2" class="form-control" >



                            <option value="">--Pilih--</option>



                            <?php foreach ($bmfi_sektor_usaha_peserta as $v) { ?>



                            <option value="<?php echo $v['id_bmfi_sektor_usaha_peserta'] ?>">

                                <?php echo $v['nama_bmfi_sektor_usaha_peserta'] ?></option>



                            <?php } ?>



                        </select>



                    </div>







                      <div class="row">


                        <div class="col-md-12">

                            <div class="form-group">

                                <label>No Rekening</label>

                                <textarea name="no_rekening2" class="form-control" rows="2"></textarea>
                            </div>

                        </div>

                    </div>





                      <div class="row">


                        <div class="col-md-12">

                            <div class="form-group">

                                <label>Nama Bank</label>

                                <textarea name="nama_bank2" class="form-control" rows="2"></textarea>
                            </div>

                        </div>

                    </div>





                      <div class="row">


                        <div class="col-md-12">

                            <div class="form-group">

                                <label>Atas Nama Rekening</label>

                                <textarea name="atas_nama_rekening2" class="form-control" rows="2"></textarea>
                            </div>

                        </div>

                    </div>





                      <div class="row">


                        <div class="col-md-12">

                            <div class="form-group">

                                <label>Jumlah Anggota Keluarga</label>

                                <textarea name="jumlah_anggota_keluarga2" class="form-control" rows="2"></textarea>
                            </div>

                        </div>

                    </div>



                     <div class="row">


                        <div class="col-md-12">

                            <div class="form-group">

                                <label>Nama Penjamin</label>

                                <textarea name="nama_penjamin2" class="form-control" rows="2"></textarea>
                            </div>

                        </div>

                    </div>





                       <div class="form-group">



                        <label>Status Hubungan Penjamin</label>



                        <select name="bmfi_status_hubungan_penjamin2" class="form-control" >



                            <option value="">--Pilih--</option>



                            <?php foreach ($bmfi_status_hubungan_penjamin as $v) { ?>



                            <option value="<?php echo $v['id_bmfi_status_hubungan_penjamin'] ?>">

                                <?php echo $v['nama_bmfi_status_hubungan_penjamin'] ?></option>



                            <?php } ?>



                        </select>



                    </div>





                     <div class="row">


                        <div class="col-md-12">

                            <div class="form-group">

                                <label>Lokasi Usaha</label>

                                <textarea name="lokasi_usaha2" class="form-control" rows="4"></textarea>
                            </div>

                        </div>

                    </div>






     <div class="row">


                        <div class="col-md-6">

                            <div class="form-group">



             <input type="text"  name="data_nilai_saldo_microfinance_lama"  id="data-nilai_saldo_microfinance_lama2"
                                    class="form-control" readonly hidden >



                            </div>



                        </div>


                    </div>







     <div class="row">


                        <div class="col-md-6">

                            <div class="form-group">



             <input type="text"  name="data_nilai_pinjaman_lama"  id="data-nilai_pinjaman_lama2"
                                    class="form-control" readonly hidden >



                            </div>



                        </div>


                    </div>





                <div class="row">


                        <div class="col-md-6">

                            <div class="form-group">



             <input type="text"  name="nominal_sisa_dana_lama"  id="data-nilai_sisa_dana_lama2"
                                    class="form-control" readonly hidden>



                            </div>



                        </div>


                    </div>








                   

         <div class="row">

            
            <div class="col-md-12">

                
                 <div class="form-group">


    <label for="pilihan_skema_pinjaman">Pilihan Jumlah Pinjaman yang diambil</label>

    <select name="jumlah_pinjaman2" id="edit_jumlah_pinjaman2" class="form-control"  >

        <option value="">Pilih</option>

        <?php foreach ($pilihan_skema_pinjaman as $h) { ?>

            <option value="<?php echo $h['jumlah_nominal'] ?>">
            <?php echo $h['nama_pilihan_skema_pinjaman'] ?> - <?php echo "Rp " . number_format($h['jumlah_nominal'], 0, ',', '.'); ?>
        </option>


        <?php } ?>


    </select>


       </div>

        
         </div>


             </div>






                    <div class="row">



                        <div class="col-md-6">



                            <div class="form-group">



                                <label>Sisa Dana Pengembalian</label>



                                <input type="text" name="sisa_dana_pengembalian2"  class="form-control jumlah-nominal-sisa-dana-pengembalian-rupiah">



                            </div>




                        </div>




                    </div>









<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fungsi untuk memformat angka rupiah
        function formatjumlahnominalSisaDanaPengembalianRupiah(angka) {
            var number_string = angka.toString().replace(/[^,\d]/g, ''),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;

            return 'Rp. ' + rupiah; // Mengembalikan nilai dengan menambahkan 'Rp. ' di depan
        }

        // Mendapatkan elemen input untuk sisa dana pengembalian
        var sisaDanaInput = document.getElementById('edit_jumlah_pinjaman2');

        // Mendapatkan semua elemen input dengan class 'form-control jumlah-nominal-pengembalian-rupiah'
        var inputElements = document.querySelectorAll('.form-control.jumlah-nominal-sisa-dana-pengembalian-rupiah');

        // Menambahkan event listener untuk menghandle perubahan nilai pada setiap elemen input
        inputElements.forEach(function(inputElement) {
            inputElement.addEventListener('input', function() {
                // Menghilangkan karakter selain angka
                var nominalValue = inputElement.value.replace(/[^\d]/g, '');

                // Ambil nilai sisa dana pengembalian
                var maksimalNominal = parseFloat(sisaDanaInput.value.replace(/[^\d]/g, ''));

                // Validasi jika melebihi maksimal
                if (parseFloat(nominalValue) > maksimalNominal) {
                    alert('Masukkan hanya angka dan tidak boleh melebihi total nilai pinjaman (' + formatjumlahnominalSisaDanaPengembalianRupiah(maksimalNominal) + ').');
                    // Atau Anda bisa mereset nilainya
                    inputElement.value = '';
                } else {
                    // Memformat angka rupiah dengan separator
                    inputElement.value = formatjumlahnominalSisaDanaPengembalianRupiah(nominalValue);
                }
            });
        });
    });
</script>












                    <div class="form-group">



                        <label>Jenis Angsuran</label>



                        <select name="bmfi_jenis_angsuran2" class="form-control" >



                            <option value="">--Pilih--</option>



                            <?php foreach ($bmfi_jenis_angsuran as $v) { ?>



                            <option value="<?php echo $v['id_bmfi_jenis_angsuran'] ?>">

                                <?php echo $v['nama_bmfi_jenis_angsuran'] ?></option>



                            <?php } ?>



                        </select>



                    </div>




                    

                    <div class="row">

                        <div class="col-12">

                            <div class="form-group">

                                <label>Keterangan</label>

                                <textarea name="keterangan2" class="form-control"></textarea>

                            </div>

                        </div>

                    </div>






                  <div class="row">
    <div class="col-md-10">
        <div class="form-group">
            <label for="foto_profil2" class="form-label">Foto Profil</label>
            <input type="hidden" name="foto_profil1">
            <div class="foto_profil-src img-thumbnail mb-3"></div>
            <div class="input-group">
                <input type="file" class="form-control" name="foto_profil2" id="foto_profil2">
                <label class="input-group-text" for="foto_profil2">Pilih file</label>
            </div>
            <small class="text-secondary"><i>Format file yang diizinkan <b>Jpg dan Png</b>. Ukuran file maksimal <b>10 MB</b>.</i></small><br>
            <small class="upload-error text-danger"></small>
        </div>
    </div>
</div>










                  <!--   <div class="row">

                        <div class="col-md-10">

                            <div class="form-group">

                                <label>Lampiran Pelengkap Peserta</label>

                                <input type="hidden" name="foto_profil1">

                                <div class="lampiran_pelengkap-src img-thumbnail mb-3"></div>

                                <div class="input-group">

                                    <div class="custom-file">

                                        <input type="file" class="custom-file-input" name="lampiran_pelengkap2" id="foto_profil2">

                                        <label class="custom-file-label" for="lampiran_pelengkap2">Pilih file</label>

                                         </div>

                        </div>

                        <small class="text-secondary"><i>Format file yang diizinkan <b>Jpg, Png, Pdf, Zip, Rar</b>. Ukuran file maksimal <b>10 MB</b>.</i></small><br>

                        <small class="upload-error text-danger"></small>

                    </div>

                                

                        </div>

                    </div>

 -->





<div class="row">
    <div class="col-md-10">
        <div class="form-group">
            <label for="lampiran_pelengkap2" class="form-label">Lampiran Pelengkap Peserta</label>
            <input type="hidden" name="foto_profil1">
            <div class="lampiran_pelengkap-src img-thumbnail mb-3"></div>
            <div class="input-group">
                <input type="file" class="form-control" name="lampiran_pelengkap2" id="lampiran_pelengkap2">
                <label class="input-group-text" for="lampiran_pelengkap2">Pilih file</label>
            </div>
            <small class="text-secondary"><i>Format file yang diizinkan <b>Jpg, Png, Pdf, Zip, Rar</b>. Ukuran file maksimal <b>10 MB</b>.</i></small><br>
            <small class="upload-error text-danger"></small>
        </div>
    </div>
</div>











                <div class="row">

                        <div class="col-12">

                            <div class="form-group">




                        <label>Kategori Kualitas Angsuran Peserta</label>



                        <select name="bmfi_kategori_kualitas_angsuran_peserta2" class="form-control" >



                            <option value="">--Pilih--</option>



                            <?php foreach ($bmfi_kategori_kualitas_angsuran_peserta as $v) { ?>



                            <option value="<?php echo $v['id_bmfi_kategori_kualitas_angsuran_peserta'] ?>">

                                <?php echo $v['nama_bmfi_kategori_kualitas_angsuran_peserta'] ?></option>



                            <?php } ?>



                        </select>



                    </div>


                    </div>


                    </div>





                    <br>



                    <hr>


                    <label>Hasil Evaluasi Dampak Bantuan </label>


                    <hr>


                    <div class="row">

                        <div class="col-12">

                            <div class="form-group">



                        <label>Rating Dampak Kemajuan Peserta</label>



                        <select name="bmfi_rating_dampak_kemajuan_peserta2" class="form-control" >



                            <option value="">--Pilih--</option>



                            <?php foreach ($bmfi_rating_dampak_kemajuan_peserta as $v) { ?>



                            <option value="<?php echo $v['id_bmfi_rating_dampak_kemajuan_peserta'] ?>">

                                <?php echo $v['nama_bmfi_rating_dampak_kemajuan_peserta'] ?>  <?php echo $v['keterangan_bmfi_rating_dampak_kemajuan_peserta'] ?>  

                                <?php echo $v['deskripsi_penjelasan'] ?>
                                    

                                </option>



                            <?php } ?>



                        </select>



                    </div>


                       </div>


                          </div>





                       
                <div class="row">

                        <div class="col-12">

                            <div class="form-group">




                        <label>Nama Jenis Program Pendampingan Peserta</label>



                        <select name="bmfi_jenis_program_pendampingan_peserta2" class="form-control" >



                            <option value="">--Pilih--</option>



                            <?php foreach ($bmfi_jenis_program_pendampingan_peserta as $v) { ?>



                            <option value="<?php echo $v['id_bmfi_jenis_program_pendampingan_peserta'] ?>">

                                <?php echo $v['nama_bmfi_jenis_program_pendampingan_peserta'] ?></option>



                            <?php } ?>



                        </select>



                    </div>


                    </div>


                    </div>






                       <div class="row">


                        <div class="col-md-12">

                            <div class="form-group">

                                <label>Hasil Dampak Kemajuan</label>

                                <textarea name="hasil_dampak_kemajuan2" class="form-control" ></textarea>
                            </div>

                        </div>

                    </div>



                    <!--  <div class="row">


                        <div class="col-md-12">

                            <div class="form-group">

                                <label>Hasil Dampak Kemajuan</label>

                                <textarea name="hasil_dampak_kemajuan2" class="form-control" rows="6"></textarea>
                            </div>

                        </div>

                    </div> -->









                        <div class="row">


                        <div class="col-md-12">

                            <div class="form-group">

                                <label>Pendapatan Sebelum</label>

                                <textarea name="pendapatan_sebelum2" class="form-control" rows="2"></textarea>
                            </div>

                        </div>

                    </div>






                        <div class="row">


                        <div class="col-md-12">

                            <div class="form-group">

                                <label>Pendapatan Sesudah</label>

                                <textarea name="pendapatan_sesudah2" class="form-control" rows="2"></textarea>
                            </div>

                        </div>

                    </div>






                    
                        <div class="row">


                        <div class="col-md-12">

                            <div class="form-group">

                                <label>Nilai Jumlah Intervensi Bantuan</label>

                                <textarea name="nilai_jumlah_intervensi_bantuan2" class="form-control" rows="4"></textarea>
                            </div>

                        </div>

                    </div>






                        <div class="row">


                        <div class="col-md-12">

                            <div class="form-group">

                                <label>Durasi Pendampingan</label>

                                <textarea name="durasi_pendampingan_peserta2" class="form-control" rows="3"></textarea>
                            </div>

                        </div>

                    </div>






<!-- 

                    <div class="row">

                        <div class="col-md-10">

                            <div class="form-group">

                                <label>Foto Dampak Program</label>

                                <input type="hidden" name="foto_profil1">

                                <div class="foto_dampak_program-src img-thumbnail mb-3"></div>

                                <div class="input-group">

                                    <div class="custom-file">

                                        <input type="file" class="custom-file-input" name="foto_dampak_program2" id="foto_profil2">

                                        <label class="custom-file-label" for="foto_dampak_program2">Pilih file</label>

                                         </div>

                        </div>

                        <small class="text-secondary"><i>Format file yang diizinkan <b>Jpg dan Png</b>. Ukuran file maksimal <b>10 MB</b>.</i></small><br>

                        <small class="upload-error text-danger"></small>

                    </div>

                                

                        </div>

                    </div>


 -->





<div class="row">
    <div class="col-md-10">
        <div class="form-group">
            <label for="foto_dampak_program2" class="form-label">Foto Dampak Program</label>
            <input type="hidden" name="foto_profil1">
            <div class="foto_dampak_program-src img-thumbnail mb-3"></div>
            <div class="input-group">
                <input type="file" class="form-control" name="foto_dampak_program2" id="foto_dampak_program2">
                <label class="input-group-text" for="foto_dampak_program2">Pilih file</label>
            </div>
            <small class="text-secondary"><i>Format file yang diizinkan <b>Jpg dan Png</b>. Ukuran file maksimal <b>10 MB</b>.</i></small><br>
            <small class="upload-error text-danger"></small>
        </div>
    </div>
</div>








                </div>

                <div class="modal-footer">

                    <span class="loader"><i class="fa fa-spinner fa-spin"></i> Sedang proses...</span>

                    <button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal"><i class="fa fa-times"></i> Batal</button>

                    <button type="submit" class="btn btn-primary btn-sm" id="btn-edit"><i class="fa fa-check"></i> Submit</button>

                </div>

            </form>

        </div>

        <!-- /.modal-content -->

    </div>

    <!-- /.modal-dialog -->

</div>

<!-- /.modal -->














<!-- modal kualitas_angsuran_peserta edit -->

<div class="modal fade" id="kualitas_angsuran_peserta_editModal">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header bg-secondary">

                <h5 class="modal-title">Kualitas Angsuran Peserta</h5>

                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>

            <form id="kualitas_angsuran_peserta_bmfi_FormEdit">

                <input type="hidden" name="id2">

                <div class="modal-body">




<div class="row">
    <div class="col-12">
        <div class="form-group">
            <input type="text" name="nama_peserta2"  class="form-control" readonly>
        </div>
    </div>
</div>






                <div class="row">

                        <div class="col-12">

                            <div class="form-group">




                        <label>Kategori Kualitas Angsuran Peserta</label>



                        <select name="bmfi_kategori_kualitas_angsuran_peserta2" class="form-control" >



                            <option value="">--Pilih--</option>



                            <?php foreach ($bmfi_kategori_kualitas_angsuran_peserta as $v) { ?>



                            <option value="<?php echo $v['id_bmfi_kategori_kualitas_angsuran_peserta'] ?>">

                                <?php echo $v['nama_bmfi_kategori_kualitas_angsuran_peserta'] ?> - ( <?php echo $v['keterangan_bmfi_kategori_kualitas_angsuran_peserta'] ?> ) </option>



                            <?php } ?>



                        </select>



                    </div>


                    </div>


                    </div>






                </div>

                <div class="modal-footer">

                    <span class="loader"><i class="fa fa-spinner fa-spin"></i> Sedang proses...</span>

                    <button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal"><i class="fa fa-times"></i> Batal</button>

                    <button type="submit" class="btn btn-primary btn-sm" id="btn-kualitas_angsuran_peserta_edit"><i class="fa fa-check"></i> Submit</button>

                </div>

            </form>

        </div>

        <!-- /.modal-content -->

    </div>

    <!-- /.modal-dialog -->

</div>

<!-- /.modal -->







<!-- modal jenis_angsuran_peserta edit -->

<div class="modal fade" id="jenis_angsuran_peserta_editModal">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header bg-secondary">

                <h5 class="modal-title">Jenis Angsuran Peserta</h5>

                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>

            <form id="jenis_angsuran_peserta_bmfi_FormEdit">

                <input type="hidden" name="id2">

                <div class="modal-body">




<div class="row">
    <div class="col-12">
        <div class="form-group">
            <input type="text" name="nama_peserta2"  class="form-control" readonly>
        </div>
    </div>
</div>






                <div class="row">

                        <div class="col-12">

                          
                    <div class="form-group">



                        <label>Jenis Angsuran</label>



                        <select name="bmfi_jenis_angsuran_peserta2" class="form-control">



                            <option value="">--Pilih--</option>



                            <?php foreach ($bmfi_jenis_angsuran as $v) { ?>



                            <option value="<?php echo $v['id_bmfi_jenis_angsuran'] ?>">

                                <?php echo $v['nama_bmfi_jenis_angsuran'] ?></option>



                            <?php } ?>



                        </select>



                    </div>


                    </div>


                    </div>






                </div>

                <div class="modal-footer">

                    <span class="loader"><i class="fa fa-spinner fa-spin"></i> Sedang proses...</span>

                    <button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal"><i class="fa fa-times"></i> Batal</button>

                    <button type="submit" class="btn btn-primary btn-sm" id="btn-jenis_angsuran_peserta_edit"><i class="fa fa-check"></i> Submit</button>

                </div>

            </form>

        </div>

        <!-- /.modal-content -->

    </div>

    <!-- /.modal-dialog -->

</div>

<!-- /.modal -->










<!-- modal komitmen_tanggal_pengembalian_peserta edit -->

<div class="modal fade" id="komitmen_tanggal_pengembalian_peserta_editModal">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header bg-secondary">

                <h5 class="modal-title">Komitmen Pengembalian Peserta</h5>

                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>

            <form id="komitmen_tanggal_pengembalian_peserta_bmfi_FormEdit">

                <input type="hidden" name="id2">

                <div class="modal-body">




<div class="row">
    <div class="col-12">
        <div class="form-group">
            <input type="text" name="nama_peserta2"  class="form-control" readonly>
        </div>
    </div>
</div>








<div class="row">
    <div class="col-md-5">
        <div class="form-group">
            <label>Perjanjian Tanggal Pengembalian</label>
            <input type="number" id="komitmentanggalInput_perjanjian_pengembalian" name="perjanjian_tanggal_pengembalian_peserta2" class="form-control" min="1" max="31">
            <small>Tanggal yang diinput akan digunakan sebagai pengingat untuk penagihan angsuran peserta</small>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var input = document.getElementById('komitmentanggalInput_perjanjian_pengembalian');

        input.addEventListener('input', function() {
            var value = parseInt(input.value, 10);

            if (value > 31) {
                alert('Tanggal tidak valid. Masukkan angka antara 1 hingga 31.');
                input.value = '';
            } else if (value < 1) {
                alert('Tanggal tidak valid. Masukkan angka antara 1 hingga 31.');
                input.value = '';
            }
        });
    });
</script>







                </div>

                <div class="modal-footer">

                    <span class="loader"><i class="fa fa-spinner fa-spin"></i> Sedang proses...</span>

                    <button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal"><i class="fa fa-times"></i> Batal</button>

                    <button type="submit" class="btn btn-primary btn-sm" id="btn-komitmen_tanggal_pengembalian_peserta_edit"><i class="fa fa-check"></i> Submit</button>

                </div>

            </form>

        </div>

        <!-- /.modal-content -->

    </div>

    <!-- /.modal-dialog -->

</div>

<!-- /.modal -->















<!-- modal kordinat_lokasi_usaha_peserta edit -->
<div class="modal fade" id="kordinat_lokasi_usaha_peserta_editModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <h5 class="modal-title">Kordinat Lokasi Usaha Peserta</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="kordinat_lokasi_usaha_peserta_bmfi_FormEdit">
                <input type="hidden" name="id2">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <input type="text" name="nama_peserta2" class="form-control" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>Kordinat Latitude</label>
                                <input type="text" name="kordinat_latitude_lokasi_usaha2" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>Kordinat Longitude</label>
                                <input type="text" name="kordinat_longitude_lokasi_usaha2" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>Alamat</label>
                                <input type="text" name="lokasi_usaha2" class="form-control">

                                 <small  class="form-text">Alamat harus ditulis secara lengkap secara manual untuk keakuratan informasi.</small>



                            </div>
                        </div>
                    </div>



<!-- 

<div class="row">
    <div class="col-md-10">
        <div class="form-group">
            <label for="foto_dampak_program2" class="form-label">Foto Dampak Program</label>
            <input type="hidden" name="foto_profil1">
            <div class="foto_dampak_program-src img-thumbnail mb-3"></div>
            <div class="input-group">
                <input type="file" class="form-control" name="foto_dampak_program2" id="foto_dampak_program2">
                <label class="input-group-text" for="foto_dampak_program2">Pilih file</label>
            </div>
            <small class="text-secondary"><i>Format file yang diizinkan <b>Jpg dan Png</b>. Ukuran file maksimal <b>10 MB</b>.</i></small><br>
            <small class="upload-error text-danger"></small>
        </div>
    </div>
</div>
 -->

                    <button type="button" class="btn btn-primary" id="get-location"><i class="fa fa-map-marker"></i>  Dapatkan Lokasi</button>




                     <button type="button" class="btn btn-danger" id="view-map">   <i class="fas fa-map-marker-alt"></i> Lihat Peta di Google Maps</button>
                  



                </div>
                <div class="modal-footer">
                    <span class="loader"><i class="fa fa-spinner fa-spin"></i> Sedang proses...</span>
                    <button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm" id="btn-kordinat_lokasi_usaha_peserta_edit"><i class="fa fa-check"></i> Submit</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-- JavaScript to get location -->
<script>
 document.getElementById('get-location').addEventListener('click', function() {
    // Konfirmasi sebelum mendapatkan lokasi
    var confirmLocation = confirm('Apakah Anda ingin mendapatkan Kordinat Lokasi secara otomatis?');

    if (confirmLocation) {
        // Lanjutkan mendapatkan lokasi jika dikonfirmasi
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(async function(position) {
                const latitude = position.coords.latitude;
                const longitude = position.coords.longitude;

                // Update latitude and longitude inputs
                document.querySelector('input[name="kordinat_latitude_lokasi_usaha2"]').value = latitude;
                document.querySelector('input[name="kordinat_longitude_lokasi_usaha2"]').value = longitude;

                // Fetch the address using Nominatim reverse geocoding with GET method
                const url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${latitude}&lon=${longitude}&zoom=18&addressdetails=1`;

                const response = await fetch(url);

                if (response.ok) {
                    const data = await response.json();
                    const address = data.display_name;

                //     // Update address input
                //     document.querySelector('input[name="lokasi_usaha2"]').value = address;


                // } else {
                //     console.error('Error fetching address:', response.statusText);
                //     alert('Gagal mendapatkan alamat. Silakan coba lagi.');


                }


            }, function(error) {
                console.error('Error Code = ' + error.code + ' - ' + error.message);
                alert('Gagal mendapatkan lokasi. Pastikan izin lokasi sudah diberikan.');
            });
        } else {
            alert('Geolocation tidak didukung oleh browser ini.');
        }
    }
});




// Event listener to open Google Maps based on coordinates
    document.getElementById('view-map').addEventListener('click', function() {
        const latitude = document.querySelector('input[name="kordinat_latitude_lokasi_usaha2"]').value;
        const longitude = document.querySelector('input[name="kordinat_longitude_lokasi_usaha2"]').value;

        // Call the function to open Google Maps with the coordinates
        openIconGmap(latitude, longitude);
    });

    function openIconGmap(latitude, longitude) {
        // Create Google Maps URL with the latitude and longitude
        var url = `https://www.google.com/maps?q=${latitude},${longitude}`;

        // Open the URL in a new tab or window
        window.open(url, '_blank');
    }


</script>










<div class="modal fade" id="bmm_web_usaha_peserta_editModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <h5 class="modal-title">BMM Web Usaha Peserta Microfinance</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="bmm_web_usaha_peserta_edit_FormEdit">



                   <!-- <input type="text" name="id2"> -->



                <input type="text" name="id_peserta_program_bmfi_bmm2">


                  <input type="text" name="nama_peserta_program_bmfi_bmm"  id="bmfi_nama_peserta_program_survei2">







                <div class="modal-body">
                
<div class="col-md-6">
    <div class="form-group">
        <label>Website</label>
        <input type="text" name="slug_bmm_web_usaha2" class="form-control" placeholder="Enter website ID">
      
        <button type="button" class="btn btn-danger" id="view-website-bmm-web-usaha">   <i class="fas fa-map-marker-alt"></i> Lihat Website</button>
                  
    </div>
</div>






<script type="text/javascript">



// Event listener to open Google Maps based on coordinates
    document.getElementById('view-website-bmm-web-usaha').addEventListener('click', function() {
        const slug_web = document.querySelector('input[name="slug_bmm_web_usaha2"]').value;
       

        
        openIconWeb(slug_web);
    });

    function openIconWeb(slug_web) {
        // Create Google Maps URL with the latitude and longitude
        var url = `https://menara.baznas.go.id/bmm_web_usaha/${slug_web}`;

        // Open the URL in a new tab or window
        window.open(url, '_blank');
    }


</script>












  <div class="col-md-6">

                            <div class="form-group">


                                <label>Masjid</label>



                                <input type="text" value="<?php echo $this->session->userdata('nmjalur') ?>"
                                    class="form-control" readonly>



                            </div>



                        </div>











<div class="row">
  <div class="col-md-12">
    <div class="form-group">
      <label for="warna_tulisan_website">Warna Tulisan Website</label>
      <select name="warna_tulisan_website2" class="form-control">
        <option value="">Pilih</option>
        <?php foreach ($kategori_warna_tulisan as $t) { ?>
          <option value="<?php echo $t['kode_warna_tulisan'] ?>" style="background-color: <?php echo $t['kode_warna_tulisan'] ?>; background-color: <?php echo $t['kode_warna_tulisan'] ?>;">
            <?php echo $t['nama_kategori_warna_tulisan'] ?> <?php echo $t['kode_warna_tulisan'] ?>  <span class="fas fa-icon-name"></span>
          </option>
        <?php } ?>
      </select>


          <small class="text-secondary">Warna yang dipilih akan menjadi warna keseluruhan pada warna tulisan website </small><br>



    </div>
  </div>
</div>




<div class="row">
  <div class="col-md-12">
    <div class="form-group">
      <label for="background_warna_website">Background Warna Website</label>
      <select name="background_warna_website2" class="form-control">
        <option value="">Pilih</option>
        <?php foreach ($kategori_warna_latar_belakang as $l) { ?>
          <option value="<?php echo $l['kode_warna_latar_belakang'] ?>" style="background-color: <?php echo $l['kode_warna_latar_belakang'] ?>; background-color: <?php echo $l['kode_warna_latar_belakang'] ?>;">
            <?php echo $l['nama_kategori_warna_latar_belakang'] ?> <?php echo $l['kode_warna_latar_belakang'] ?>  <span class="fas fa-icon-name"></span>
          </option>
        <?php } ?>
      </select>

 <small class="text-secondary">Warna yang dipilih akan menjadi warna keseluruhan pada latar warna background website </small><br>
 
    </div>
  </div>
</div>





                             <div class="col-md-8">

                            <div class="form-group">

                                <label>Jenis BMM Web Usaha Peserta</label>

                                <select name="jenis" class="form-control">

                                    <option value="">--Pilih--</option>

                                    <option value="Bmm_web_usaha2">BMM Web Usaha</option>

                                    <!-- <option value="Musholla">Musholla</option> -->

                                </select>

                            </div>

                             </div>





                              <div class="col-md-12">

                            <div class="form-group">

                                <label>Nama BMM Web Usaha</label>

                                <input type="text" name="nama2" class="form-control">

                            </div>

                           
                        </div>

     



                    <div class="row">

                        <div class="col-12">

                            <div class="form-group">

                                <label>Keterangan lainnya</label>

                                <textarea name="deskripsi2" class="form-control"></textarea>

                            </div>

                        </div>

                    </div>

  

                <div class="modal-footer">
                    <span class="loader"><i class="fa fa-spinner fa-spin"></i> Sedang proses...</span>
                    <button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm" id="btn-bmm_web_usaha_peserta_edit"><i class="fa fa-check"></i> Submit</button>
                </div>
            </form>

            
        </div>
        
    </div>
  
</div>


</div>











<div class="modal fade" id="akun_login_peserta_editModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <h5 class="modal-title">Akun Login Peserta Microfinance</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="akun_login_peserta_edit_FormEdit">



                <input type="text" name="id_peserta_program_bmfi_bmm2">


                  <input type="text" name="nama_peserta_program_bmfi_bmm"  id="bmfi_nama_peserta_program_survei2">





                <div class="modal-body">






  <div class="col-md-6">

                            <div class="form-group">


                                <label>Masjid</label>



                                <input type="text" value="<?php echo $this->session->userdata('nmjalur') ?>"
                                    class="form-control" readonly>



                            </div>



                        </div>














  <div class="row">


                        <div class="col-md-12">



                            <div class="form-group">



                                <label>Nama Lengkap</label>



                                <input type="text" name="nama_peserta_program_bmfi_bmm2" class="form-control">



                            </div>

                        </div>


                     </div>





                    <div class="row">

                        <div class="col-md-12">


                            <div class="form-group">



                                <label>Jenis Kelamin</label>



                                <select name="jenis_kelamin2" class="form-control">



                                    <option value="">--Pilih--</option>



                                    <option value="Laki-laki">Laki-laki</option>



                                    <option value="Perempuan">Perempuan</option>



                                </select>



                            </div>



                            </div>


                                </div>




            

                    <div class="row">



                        <div class="col-md-12">



                            <div class="form-group">



                                <label>Alamat</label>



                                <textarea name="alamat2" class="form-control"></textarea>



                            </div>



                        </div>



                    </div>






                    <div class="row">



                        <div class="col-md-10">



                            <div class="form-group">



                                <label>Email</label>



                                <input type="email" name="email2" class="form-control">



                            </div>

                               </div>

                                  </div>



                      <div class="row">
    <div class="col-md-8">
        <div class="form-group">
            <label>No. Hp</label>
            <input type="number" name="no_hp2" class="form-control">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 mt-3">
        <!-- Tombol untuk halaman login -->
        <a href="https://menara.baznas.go.id/login/bmfi_bmm_peserta_microfinance" 
           class="btn btn-primary" target="_blank">
            Halaman Login
        </a>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-12">
        <span class="text-secondary">Catatan :</span>
        <ul style="font-size:14px;padding:0;list-style:none;" class="text-secondary">
            <li>- Jika pengguna tidak diizinkan untuk mengakses <strong>Login</strong>, atur statusnya menjadi <strong>tidak aktif</strong>.</li>
            <li>- Password secara <i>default</i> sudah dibuatkan oleh Super Admin yaitu <strong>menara23</strong></li>
        </ul>
    </div>
</div>


    
  

                <div class="modal-footer">
                    <span class="loader"><i class="fa fa-spinner fa-spin"></i> Sedang proses...</span>
                    <button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm" id="btn-akun_login_peserta_edit"><i class="fa fa-check"></i> Submit</button>
                </div>
            </form>

            
        </div>
        
    </div>
  
</div>


</div>











<div class="modal fade" id="kuesioner_peserta_editModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <h5 class="modal-title">Kuesioner Peserta Microfinance</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="kuesioner_peserta_edit_FormEdit">



                <input type="hidden" name="id_peserta_program_bmfi_bmm2">


                  <input type="text" name="nama_peserta_program_bmfi_bmm"  id="bmfi_nama_peserta_program_survei2">





                <div class="modal-body">







 <div class="row">
    <div class="col-12">
     <div class="form-group">

                 
            <h3>Business Quality Assessment</h3>
            
                <label for="business_duration" class="form-label">1. Berapa lama usaha Anda sudah berjalan?</label>
                <select class="form-select" name="1_business_duration2" required>
                    <option value="" disabled selected>Pilih durasi</option>
                    <option value="1"> < 6 bulan </option>
                    <option value="2"> 6-12 bulan </option>
                    <option value="3"> 1-2 tahun </option>
                    <option value="4"> 2-5 tahun </option>
                    <option value="5"> > 5 tahun </option>
                </select>
            </div>
        </div>
     </div>


    

    <div class="row">
        <div class="col-12">
            <div class="form-group">

                <label for="growth_and_sales" class="form-label">2. Bagaimana Anda menilai pertumbuhan usaha Anda dan peningkatan penjualan dalam 3 bulan terakhir?</label>
                <select class="form-select" name="2_growth_and_sales2" required>
                    <option value="" disabled selected>Pilih opsi</option>
                    <option value="5"> Sangat baik, peningkatan > 20% </option>
                    <option value="4"> Baik, peningkatan 10-20% </option>
                    <option value="3"> Cukup, peningkatan 5-10% </option>
                    <option value="2"> Kurang, peningkatan < 5% </option>
                    <option value="1"> Sangat kurang, tidak ada peningkatan </option>
                </select>
             </div>
        </div>
     </div>


            
    <div class="row">
        <div class="col-12">
          <div class="form-group">


                <label for="customer_demand" class="form-label">3. Seberapa sering Anda mengalami kesulitan dalam memenuhi permintaan pelanggan usaha Anda?</label>
                <select class="form-select" name="3_customer_demand2" required>
                    <option value="" disabled selected>Pilih opsi</option>
                    <option value="1"> Tidak pernah </option>
                    <option value="2"> Jarang </option>
                    <option value="3"> Kadang-kadang </option>
                    <option value="4"> Sering </option>
                    <option value="5"> Selalu </option>
                </select>
             </div>
        </div>
     </div>



           
<div class="row">
    <div class="col-12">
        <div class="form-group">


                <label for="business_plan" class="form-label">4. Apakah Anda memiliki rencana bisnis yang tertulis dan jelas?</label>
                <select class="form-select" name="4_business_plan2" required>
                    <option value="" disabled selected>Pilih opsi</option>
                    <option value="5"> Ya, sangat detail </option>
                    <option value="4"> Ya, cukup detail </option>
                    <option value="3"> Ya, tapi tidak detail </option>
                    <option value="2"> Tidak, tapi ada gambaran umum </option>
                    <option value="1"> Tidak sama sekali </option>
                </select>
              </div>
        </div>
     </div>




            
 <div class="row">
    <div class="col-12">
     <div class="form-group">


                <label for="financial_condition" class="form-label">5. Bagaimana kondisi keuangan usaha Anda saat ini?</label>
                <select class="form-select" name="5_financial_condition2" required>
                    <option value="" disabled selected>Pilih opsi</option>
                    <option value="5"> Sangat stabil </option>
                    <option value="4"> Stabil </option>
                    <option value="3"> Cukup stabil </option>
                    <option value="2"> Tidak stabil </option>
                    <option value="1"> Sangat tidak stabil </option>
                </select>
             </div>
        </div>
     </div>





<div class="row">
    <div class="col-12">
     <div class="form-group">


        
            <h3 class="mt-4">Health Impact Assessment (Reduced)</h3>
           
                <label for="health_issues" class="form-label">6. Seberapa sering Anda mengalami masalah kesehatan yang mengganggu aktivitas bisnis Anda?</label>
                <select class="form-select" name="6_health_issues2" required>
                    <option value="" disabled selected>Pilih opsi</option>
                    <option value="1"> Tidak pernah </option>
                    <option value="2"> Jarang </option>
                    <option value="3"> Kadang-kadang </option>
                    <option value="4"> Sering </option>
                    <option value="5"> Selalu </option>
                </select>
              </div>
        </div>
     </div>




           
    <div class="row">
        <div class="col-12">
         <div class="form-group">


                <label for="health_impact" class="form-label">7. Seberapa besar pengaruh kesehatan fisik Anda terhadap produktivitas kerja dalam menjalankan usaha Anda?</label>
                <select class="form-select" name="7_health_impact2" required>
                    <option value="" disabled selected>Pilih opsi</option>
                    <option value="5"> Sangat besar </option>
                    <option value="4"> Besar </option>
                    <option value="3"> Sedang </option>
                    <option value="2"> Kecil </option>
                    <option value="1"> Tidak berpengaruh </option>
                </select>
              </div>
        </div>
     </div>





<div class="row">
    <div class="col-12">
        <div class="form-group">

          
            <h3 class="mt-4">Combined Assessment</h3>
        
                <label for="stress_impact" class="form-label">8. Seberapa sering Anda merasa stres atau cemas yang mempengaruhi kinerja bisnis Anda?</label>
                <select class="form-select" name="8_stress_impact2" required>
                    <option value="" disabled selected>Pilih opsi</option>
                    <option value="1"> Tidak pernah </option>
                    <option value="2"> Jarang </option>
                    <option value="3"> Kadang-kadang </option>
                    <option value="4"> Sering </option>
                    <option value="5"> Selalu </option>
                </select>
             </div>
        </div>
     </div>




           
<div class="row">
    <div class="col-12">
     <div class="form-group">


                <label for="support_system" class="form-label">9. Seberapa besar dukungan keluarga, mitra, atau teman dalam menjalankan usaha Anda?</label>
                <select class="form-select" name="9_support_system2" required>
                    <option value="" disabled selected>Pilih opsi</option>
                    <option value="5"> Sangat besar </option>
                    <option value="4"> Besar </option>
                    <option value="3"> Sedang </option>
                    <option value="2"> Kecil </option>
                    <option value="1"> Tidak ada </option>
                </select>
              </div>
        </div>
     </div>





  

          
    <div class="row">
        <div class="col-12">
            <div class="form-group">


                <label for="health_impact_on_repayment" class="form-label">10. Apakah Anda pernah mengalami gangguan kesehatan yang mempengaruhi kemampuan Anda untuk membayar angsuran?</label>
                <select class="form-select" name="10_health_impact_on_repayment2" required>
                    <option value="" disabled selected>Pilih opsi</option>
                    <option value="1"> Tidak pernah </option>
                    <option value="2"> Sekali </option>
                    <option value="3"> Beberapa kali </option>
                    <option value="4"> Sering </option>
                    <option value="5"> Selalu </option>
                </select>
              </div>
        </div>
     </div>



<style>
    .results {
        display: none;
        margin-top: 40px;
    }
    #radarChart {
        width: 100% !important;
        height: 500px; /* Atur sesuai kebutuhan */
        max-width: 800px;
        margin: 0 auto;
    }
</style>


<div class="results mt-4">
            <h3>Hasil Analisis</h3>

               <canvas id="radarChart" width="400" height="400"></canvas>


            <p id="resultText_kuesioner"></p>
         
        </div>


 <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
  

                <div class="modal-footer">
                    <span class="loader"><i class="fa fa-spinner fa-spin"></i> Sedang proses...</span>
                    <button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm" id="btn-kuesioner_peserta_edit"><i class="fa fa-check"></i> Submit</button>
                </div>
            </form>

            
        </div>
        
    </div>
  
</div>


</div>











<div class="modal fade" id="psikotes_peserta_editModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <h5 class="modal-title">psikotes Peserta Microfinance</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="psikotes_peserta_edit_FormEdit">



                <input type="hidden" name="id_peserta_program_bmfi_bmm2">


                  <input type="text" name="nama_peserta_program_bmfi_bmm"  id="bmfi_nama_peserta_program_psikotes2">





                <div class="modal-body">






 <div class="mb-3">
                <h3>Ekstraversi (E) vs. Introversi (I)</h3>
                <label class="form-label">
                    1. Saya merasa lebih berenergi ketika berinteraksi dengan banyak orang.
                    <div>
                        <input type="radio" name="1_question2" value="E"> Setuju
                        <input type="radio" name="1_question2" value="I"> Tidak Setuju
                    </div>
                </label>
                <label class="form-label">
                    2. Saya lebih suka menghabiskan waktu sendiri daripada bersama banyak orang.
                    <div>
                        <input type="radio" name="2_question2" value="I"> Setuju
                        <input type="radio" name="2_question2" value="E"> Tidak Setuju
                    </div>
                </label>
            </div>

            <div class="mb-3">
                <h3>Sensing (S) vs. Intuition (N)</h3>
                <label class="form-label">
                    3. Saya lebih suka informasi yang konkret dan nyata.
                    <div>
                        <input type="radio" name="3_question2" value="S"> Setuju
                        <input type="radio" name="3_question2" value="N"> Tidak Setuju
                    </div>
                </label>
                <label class="form-label">
                    4. Saya lebih suka memikirkan kemungkinan dan ide-ide baru.
                    <div>
                        <input type="radio" name="4_question2" value="N"> Setuju
                        <input type="radio" name="4_question2" value="S"> Tidak Setuju
                    </div>
                </label>
            </div>

            <div class="mb-3">
                <h3>Thinking (T) vs. Feeling (F)</h3>
                <label class="form-label">
                    5. Saya lebih suka membuat keputusan berdasarkan logika dan fakta.
                    <div>
                        <input type="radio" name="5_question2" value="T"> Setuju
                        <input type="radio" name="5_question2" value="F"> Tidak Setuju
                    </div>
                </label>
                <label class="form-label">
                    6. Saya lebih suka mempertimbangkan perasaan orang lain dalam membuat keputusan.
                    <div>
                        <input type="radio" name="6_question2" value="F"> Setuju
                        <input type="radio" name="6_question2" value="T"> Tidak Setuju
                    </div>
                </label>
            </div>

            <div class="mb-3">
                <h3>Judging (J) vs. Perceiving (P)</h3>
                <label class="form-label">
                    7. Saya lebih suka merencanakan segala sesuatu dengan baik sebelumnya.
                    <div>
                        <input type="radio" name="7_question2" value="J"> Setuju
                        <input type="radio" name="7_question2" value="P"> Tidak Setuju
                    </div>
                </label>
                <label class="form-label">
                    8. Saya lebih suka spontan dan fleksibel dalam menjalani hidup.
                    <div>
                        <input type="radio" name="8_question2" value="P"> Setuju
                        <input type="radio" name="8_question2" value="J"> Tidak Setuju
                    </div>
                </label>
            </div>

            <div class="mb-3">
                <h3>Ekstraversi (E) vs. Introversi (I)</h3>
                <label class="form-label">
                    9. Saya merasa lebih bersemangat setelah bertemu dengan banyak orang.
                    <div>
                        <input type="radio" name="9_question2" value="E"> Setuju
                        <input type="radio" name="9_question2" value="I"> Tidak Setuju
                    </div>
                </label>
                <label class="form-label">
                    10. Saya merasa lebih nyaman dengan lingkungan yang tenang dan tidak ramai.
                    <div>
                        <input type="radio" name="10_question2" value="I"> Setuju
                        <input type="radio" name="10_question2" value="E"> Tidak Setuju
                    </div>
                </label>
            </div>

            <div class="mb-3">
                <h3>Sensing (S) vs. Intuition (N)</h3>
                <label class="form-label">
                    11. Saya lebih suka bekerja dengan data dan detail yang jelas.
                    <div>
                        <input type="radio" name="11_question2" value="S"> Setuju
                        <input type="radio" name="11_question2" value="N"> Tidak Setuju
                    </div>
                </label>
                <label class="form-label">
                    12. Saya lebih suka merenung tentang masa depan dan kemungkinan yang ada.
                    <div>
                        <input type="radio" name="12_question2" value="N"> Setuju
                        <input type="radio" name="12_question2" value="S"> Tidak Setuju
                    </div>
                </label>
            </div>



<style>
    .results {
        display: none;
        margin-top: 40px;
    }
    #radarChart {
        width: 100% !important;
        height: 500px; /* Atur sesuai kebutuhan */
        max-width: 800px;
        margin: 0 auto;
    }
</style>


<div class="results mt-4">
            <h3>Hasil Analisis</h3>

               <canvas id="radarChartPsikotes" width="400" height="400"></canvas>


            <p id="resultText_psikotes"></p>
         
        </div>


 <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
  

                <div class="modal-footer">
                    <span class="loader"><i class="fa fa-spinner fa-spin"></i> Sedang proses...</span>
                    <button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm" id="btn-psikotes_peserta_edit"><i class="fa fa-check"></i> Submit</button>
                </div>
            </form>

            
        </div>
        
    </div>
  
</div>


</div>













<!-- modal kordinat_lokasi_rumah_peserta edit -->
<div class="modal fade" id="kordinat_lokasi_rumah_peserta_editModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <h5 class="modal-title">Kordinat Lokasi rumah Peserta</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="kordinat_lokasi_rumah_peserta_bmfi_FormEdit">
                <input type="hidden" name="id2">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <input type="text" name="nama_peserta2" class="form-control" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>Kordinat Latitude</label>
                                <input type="text" name="kordinat_latitude_rumah2" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>Kordinat Longitude</label>
                                <input type="text" name="kordinat_longitude_rumah2" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>Alamat</label>
                                <input type="text" name="alamat_rumah2" class="form-control">

                                 <small  class="form-text">Alamat harus ditulis secara lengkap secara manual untuk keakuratan informasi.</small>



                            </div>
                        </div>
                    </div>



<!-- 

<div class="row">
    <div class="col-md-10">
        <div class="form-group">
            <label for="foto_dampak_program2" class="form-label">Foto Dampak Program</label>
            <input type="hidden" name="foto_profil1">
            <div class="foto_dampak_program-src img-thumbnail mb-3"></div>
            <div class="input-group">
                <input type="file" class="form-control" name="foto_dampak_program2" id="foto_dampak_program2">
                <label class="input-group-text" for="foto_dampak_program2">Pilih file</label>
            </div>
            <small class="text-secondary"><i>Format file yang diizinkan <b>Jpg dan Png</b>. Ukuran file maksimal <b>10 MB</b>.</i></small><br>
            <small class="upload-error text-danger"></small>
        </div>
    </div>
</div>
 -->

                    <button type="button" class="btn btn-primary" id="get-location-rumah"><i class="fa fa-map-marker"></i>  Dapatkan Lokasi</button>




                     <button type="button" class="btn btn-danger" id="view-map-rumah">   <i class="fas fa-map-marker-alt"></i> Lihat Peta di Google Maps</button>
                  



                </div>
                <div class="modal-footer">
                    <span class="loader"><i class="fa fa-spinner fa-spin"></i> Sedang proses...</span>
                    <button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm" id="btn-kordinat_lokasi_rumah_peserta_edit"><i class="fa fa-check"></i> Submit</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-- JavaScript to get location -->
<script>
 document.getElementById('get-location-rumah').addEventListener('click', function() {
    // Konfirmasi sebelum mendapatkan lokasi
    var confirmLocation = confirm('Apakah Anda ingin mendapatkan Kordinat Lokasi secara otomatis?');

    if (confirmLocation) {
        // Lanjutkan mendapatkan lokasi jika dikonfirmasi
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(async function(position) {
                const latitude = position.coords.latitude;
                const longitude = position.coords.longitude;

                // Update latitude and longitude inputs
                document.querySelector('input[name="kordinat_latitude_rumah2"]').value = latitude;
                document.querySelector('input[name="kordinat_longitude_rumah2"]').value = longitude;

                // Fetch the address using Nominatim reverse geocoding with GET method
                const url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${latitude}&lon=${longitude}&zoom=18&addressdetails=1`;

                const response = await fetch(url);

                if (response.ok) {
                    const data = await response.json();
                    const address = data.display_name;

                //     // Update address input
                //     document.querySelector('input[name="lokasi_rumah2"]').value = address;


                // } else {
                //     console.error('Error fetching address:', response.statusText);
                //     alert('Gagal mendapatkan alamat. Silakan coba lagi.');


                }


            }, function(error) {
                console.error('Error Code = ' + error.code + ' - ' + error.message);
                alert('Gagal mendapatkan lokasi. Pastikan izin lokasi sudah diberikan.');
            });
        } else {
            alert('Geolocation tidak didukung oleh browser ini.');
        }
    }
});




// Event listener to open Google Maps based on coordinates
    document.getElementById('view-map-rumah').addEventListener('click', function() {
        const latitude = document.querySelector('input[name="kordinat_latitude_rumah2"]').value;
        const longitude = document.querySelector('input[name="kordinat_longitude_rumah2"]').value;

        // Call the function to open Google Maps with the coordinates
        openIconGmap(latitude, longitude);
    });

    function openIconGmap(latitude, longitude) {
        // Create Google Maps URL with the latitude and longitude
        var url = `https://www.google.com/maps?q=${latitude},${longitude}`;

        // Open the URL in a new tab or window
        window.open(url, '_blank');
    }


</script>











<!-- Modal -->

<div class="modal fade" id="cetakModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">

    <div class="modal-dialog modal-sm" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title" id="exampleModalLabel">Pilih Cetak</h5>

                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>

            <div class="modal-body">

                <form action="<?= site_url('panel/mp/cetak_bmfi_peserta_program') ?>" target="_blank">



                          



                    <div class="form-group">

                        <label>Tanggal Awal</label>

                        <input type="date" name="tgl1" class="form-control" required>

                    </div>

                    <div class="form-group">

                        <label>Tanggal Akhir</label>

                        <input type="date" name="tgl2" class="form-control" required>

                    </div>


                <div class="modal-footer">


                    <span class="loader"><i class="fa fa-spinner fa-spin"></i> Sedang proses...</span>



                    <button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal"><i
                            class="fa fa-times"></i> Batal</button>



                    <button type="submit" class="btn btn-primary btn-sm" ><i class="fa fa-check"></i>

                        Cetak</button>



                </div>



                </form>

            </div>

        </div>

    </div>

</div>








<!-- Modal Alert -->
<div class="modal fade" id="peringatan-telah-lunasModal" tabindex="-1" aria-labelledby="modalperingatan-telah-lunasLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAlertAsesmenLabel">Peringatan telah Lunas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                 Selamat ! Saat ini Anda telah Lunas dan tunggakan anda sudah bersih
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
               
            </div>
        </div>
    </div>
</div>



<script>
// Menangani klik tombol "Transaksi" (Asesmen dan Peminjaman)
$(document).on('click', '.btn-peringatan-telah-lunas', function() {
    var idPeserta2 = $(this).attr('id_peserta2'); // Ambil nilai id_peserta dari tombol
    var namaPeserta2 = $(this).attr('nama_peserta2'); // Ambil nilai nama_peserta dari tombol
    var idAnggaran2 = $(this).attr('id_anggaran2'); // Ambil nilai id_anggaran dari tombol
    var idSaldo2 = $(this).attr('id_saldo2'); // Ambil nilai id_saldo dari tombol

    // Mengisi nilai ke dalam input sesuai dengan data yang diambil dari tombol
    $("#bmfi_peserta_program2").val(idPeserta2);
    $("#nama_peserta_program2").val(namaPeserta2);
    $("#anggaran_microfinance2").val(idAnggaran2);
    $("#saldo_microfinance2").val(idSaldo2);

    // Buka modal "Peminjaman Alert"
    $("#peringatan-telah-lunasModal").modal('show');
});


</script>




<!-- Modal Alert -->
<div class="modal fade" id="peringatan-untuk-asesmen-alertModal" tabindex="-1" aria-labelledby="modalperingatan-untuk-asesmen-alertLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalperingatan-untuk-asesmen-alertLabel">Peringatan Untuk Asesmen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                maaf anda tidak bisa melakukan pengembalian, karena anda belum memiliki tunggakan, silahkan melakukan asesmen dan peminjaman terlebih dahulu
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
               
            </div>
        </div>
    </div>
</div>



<script>
// Menangani klik tombol "Transaksi" (Asesmen dan Peminjaman)
$(document).on('click', '.btn-peringatan-untuk-asesmen-alert', function() {
    var idPeserta2 = $(this).attr('id_peserta2'); // Ambil nilai id_peserta dari tombol
    var namaPeserta2 = $(this).attr('nama_peserta2'); // Ambil nilai nama_peserta dari tombol
    var idAnggaran2 = $(this).attr('id_anggaran2'); // Ambil nilai id_anggaran dari tombol
    var idSaldo2 = $(this).attr('id_saldo2'); // Ambil nilai id_saldo dari tombol

    // Mengisi nilai ke dalam input sesuai dengan data yang diambil dari tombol
    $("#bmfi_peserta_program2").val(idPeserta2);
    $("#nama_peserta_program2").val(namaPeserta2);
    $("#anggaran_microfinance2").val(idAnggaran2);
    $("#saldo_microfinance2").val(idSaldo2);

    // Buka modal "Peminjaman Alert"
    $("#peringatan-untuk-asesmen-alertModal").modal('show');
});


</script>









<div class="modal fade" id="add_transaksiModal">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header bg-secondary">

                <h5 class="modal-title">Tambah Transaksi Pengembalian</h5>

                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>



            <form id="bmfi_transaksi_pengembalianFormAdd">



                <input type="hidden" name="jalur" value="Masjid">



                <input type="hidden" name="id_jalur" value="<?php echo $this->session->userdata('parent') ?>">

 




                <div class="modal-body">



                   <div class="row">


                        <div class="col-md-6">

                            <div class="form-group">


                                <label>Masjid</label>



                                <input type="text" value="<?php echo $this->session->userdata('nmjalur') ?>"
                                    class="form-control" readonly>



                            </div>



                        </div>


                    </div>






                <input type="hidden" name="bmfi_peserta_program" id="bmfi_peserta_program">


               

                   <div class="row">


                        <div class="col-md-6">

                            <div class="form-group">



                                 <label>Nama Peserta</label>



                                <input type="text" name="nama_peserta_infak_kas" id="nama_peserta_program"
                                    class="form-control" readonly>



                            </div>



                        </div>


                    </div>





                   <div class="row">


                        <div class="col-md-6">

                            <div class="form-group">



                                <input type="text" name="id_peserta_program_bmfi_asesmen" id="nama_id_peserta_program"
                                    class="form-control" readonly hidden>



                            </div>



                        </div>


                    </div>




                   <div class="row">


                        <div class="col-md-6">

                            <div class="form-group">



                                <label>Jumlah Total Pinjaman</label>


                                <input type="text"  id="nama_jumlah_pinjaman"
                                    class="form-control" readonly>



                            </div>



                        </div>


                    </div>




                   <div class="row">


                        <div class="col-md-6">

                            <div class="form-group">



                                <label>Sisa Dana Pengembalian</label>


                                <input type="text" name="sisa_dana_pengembalian" id="nama_sisa_dana"
                                    class="form-control" readonly>



                            </div>



                        </div>


                    </div>





                    <div class="row">



                        <div class="col-md-8">



                            <div class="form-group">



                                <label>Tanggal</label>



                                <input type="date" name="tanggal" class="form-control">



                            </div>



                        </div>


                    </div>



                




                      <div class="row">


                        <div class="col-md-6">

                            <div class="form-group">



             <input type="text"  name="bmfi_saldo_microfinance"  id="anggaran_microfinance"
                                    class="form-control" readonly hidden>



                            </div>



                        </div>


                    </div>





                <div class="row">


                        <div class="col-md-6">

                            <div class="form-group">



             <input type="text"  name="nominal_saldo_microfinance"  id="saldo_microfinance"
                                    class="form-control" readonly hidden>



                            </div>



                        </div>


                    </div>












                <div class="row">


                        <div class="col-md-6">

                            <div class="form-group">



             <input type="text"  name="id_bmfi_jenis_angsuran"  id="angsuran_microfinance"

             
                                    class="form-control" readonly hidden >



                            </div>



                        </div>


                    </div>







                <div class="row">


                        <div class="col-md-6">

                            <div class="form-group">


                  <label>Jenis Angsuran</label>


             <input type="text"    id="nama_angsuran_microfinance"
                                    class="form-control" readonly >



                            </div>



                        </div>


                    </div>












                    <div class="row">



                        <div class="col-md-6">



                            <div class="form-group">



                                <label>Nominal Pengembalian</label>



                                <input type="text" name="nominal_pengembalian" class="form-control jumlah-nominal-pengembalian-rupiah">



                            </div>




                        </div>




                    </div>










<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fungsi untuk memformat angka rupiah
        function formatjumlahnominalPengembalianRupiah(angka) {
            var number_string = angka.toString().replace(/[^,\d]/g, ''),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;

            return 'Rp. ' + rupiah; // Mengembalikan nilai dengan menambahkan 'Rp. ' di depan
        }

        // Mendapatkan elemen input untuk sisa dana pengembalian
        var sisaDanaInput = document.getElementById('nama_sisa_dana');

        // Mendapatkan semua elemen input dengan class 'form-control jumlah-nominal-pengembalian-rupiah'
        var inputElements = document.querySelectorAll('.form-control.jumlah-nominal-pengembalian-rupiah');

        // Menambahkan event listener untuk menghandle perubahan nilai pada setiap elemen input
        inputElements.forEach(function(inputElement) {
            inputElement.addEventListener('input', function() {
                // Menghilangkan karakter selain angka
                var nominalValue = inputElement.value.replace(/[^\d]/g, '');

                // Ambil nilai sisa dana pengembalian
                var maksimalNominal = parseFloat(sisaDanaInput.value.replace(/[^\d]/g, ''));

                // Validasi jika melebihi maksimal
                if (parseFloat(nominalValue) > maksimalNominal) {
                    alert('Masukkan hanya angka dan tidak boleh melebihi sisa dana pengembalian (' + formatjumlahnominalPengembalianRupiah(maksimalNominal) + ').');
                    // Atau Anda bisa mereset nilainya
                    inputElement.value = '';
                } else {
                    // Memformat angka rupiah dengan separator
                    inputElement.value = formatjumlahnominalPengembalianRupiah(nominalValue);
                }
            });
        });
    });
</script>


                    <div class="row">



                        <div class="col-md-6">



                            <div class="form-group">



                                <label>Infak / Sedekah</label>



                                <input type="text" name="infak_peserta" class="form-control rupiah">



                            </div>




                        </div>




                    </div>




                    <div class="row">



                        <div class="col-md-12">



                            <div class="form-group">



                                <label>Keterangan Transaksi</label>



                                <textarea name="keterangan_transaksi" class="form-control"></textarea>



                            </div>



                        </div>



                    </div>







                    <div class="row">

                        <div class="col-md-12">

                            <div class="form-group">


                                <label>Lampiran</label>



                                <div class="input-group">



                                    <div class="custom-file">



                                        <input type="file" class="custom-file-input" name="lampiran" id="lampiran">



                                        <label class="custom-file-label" for="lampiran">Pilih file</label>



                                    </div>



                                </div>

                            </div>


                        </div>



                    </div>






                </div>







                <div class="modal-footer">



                    <span class="loader"><i class="fa fa-spinner fa-spin"></i> Sedang proses...</span>



                    <button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal"><i
                            class="fa fa-times"></i> Batal</button>



                    <button type="submit" class="btn btn-primary btn-sm" id="btn-add_transaksi_pengembalian"><i class="fa fa-check"></i>

                        Submit</button>



                </div>



            </form>



        </div>



        <!-- /.modal-content -->



    </div>



    <!-- /.modal-dialog -->



</div>











<!-- 
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var form = document.getElementById('bmfi_transaksi_pengembalianFormAdd');
        var sisaDanaInput = document.getElementById('nama_sisa_dana');
        var nominalPengembalianInput = document.getElementsByName('nominal_pengembalian')[0]; // Ambil elemen pertama dengan name 'nominal_pengembalian'

        // Fungsi untuk menampilkan notifikasi
        function showNotification(message) {
            var notification = document.createElement('div');
            notification.className = 'alert alert-danger mt-3';
            notification.textContent = message;

            var modalBody = document.querySelector('.modal-body');
            modalBody.appendChild(notification);

            // Hapus notifikasi setelah beberapa detik
            setTimeout(function() {
                notification.remove();
            }, 3000);
        }

        form.addEventListener('submit', function(event) {
            var sisaDana = parseFloat(sisaDanaInput.value.replace(/\D/g, '')); // Mengambil nilai sisa dana pengembalian, menghapus karakter non-digit
            var nominalPengembalian = parseFloat(nominalPengembalianInput.value.replace(/\D/g, '')); // Mengambil nilai nominal pengembalian, menghapus karakter non-digit

            if (nominalPengembalian > sisaDana) {
                showNotification('Nominal pengembalian tidak boleh lebih besar dari sisa dana pengembalian!');
                event.preventDefault(); // Mencegah pengiriman formulir
            }
        });
    });
</script> -->






<!-- Modal Alert -->
<div class="modal fade" id="melakukan_cicilan-alertModal" tabindex="-1" aria-labelledby="modalmelakukan_cicilan-alertLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalmelakukan_cicilan-alertLabel">Peringatan Melakukan Cicilan Pertama</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Anda belum pernah melakukan cicilan, silakan melakukan pengembalian terlebih dahulu.</p>
                 <p><strong>Nama Peserta:</strong> <span id="modal-nama_peserta1"></span></p>
              <p><strong>Nilai Pinjaman:</strong> <span id="modal-nilai_pinjaman1"></span></p>
              <p><strong>Sisa Dana Belum dikembalikan:</strong> <span id="modal-nilai_sisa_dana1"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>


<script>
// Menangani klik tombol "Transaksi" (Asesmen dan Peminjaman)
$(document).on('click', '.btn-melakukan_cicilan-alert', function() {
    var idPeserta2 = $(this).attr('id_peserta2'); // Ambil nilai id_peserta dari tombol
    var namaPeserta2 = $(this).attr('nama_peserta2'); // Ambil nilai nama_peserta dari tombol
     var nilaiPinjaman2 = $(this).attr('nilai_pinjaman2'); // Ambil nilai id_anggaran dari tombol
    var nilaiSisaDana2 = $(this).attr('nilai_sisa_dana2'); // Ambil nilai id_saldo dari tombol

     // Mengisi nilai ke dalam input sesuai dengan data yang diambil dari tombol
    $("#bmfi_peserta_program2").text(idPeserta2);
   



    // Menampilkan nilai dalam modal dengan format Rupiah
    $("#modal-nama_peserta1").text(namaPeserta2);
    $("#modal-nilai_pinjaman1").text('Rp ' + formatRupiah(nilaiPinjaman2));
    $("#modal-nilai_sisa_dana1").text('Rp ' + formatRupiah(nilaiSisaDana2));



    // Buka modal "Peminjaman Alert"
    $("#melakukan_cicilan-alertModal").modal('show');
});
</script>





<!-- Modal Alert -->
<div class="modal fade" id="peringatan-untuk-pelunasan-alertModal" tabindex="-1" aria-labelledby="modalperingatan-untuk-pelunasan-alertLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalperingatan-untuk-pelunasan-alertLabel">Peringatan untuk Pelunasan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
               <p>Saat ini Anda masih memiliki sisa cicilan, mohon dilunaskan terlebih dahulu.</p>
              <p><strong>Nama Peserta:</strong> <span id="modal-nama_peserta2"></span></p>
              <p><strong>Nilai Pinjaman:</strong> <span id="modal-nilai_pinjaman2"></span></p>
              <p><strong>Sisa Dana Belum dikembalikan:</strong> <span id="modal-nilai_sisa_dana2"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>




<script>
// Menangani klik tombol "Transaksi" (Asesmen dan Peminjaman)
$(document).on('click', '.btn-peringatan-untuk-pelunasan-alert', function() {
    var idPeserta2 = $(this).attr('id_peserta2'); // Ambil nilai id_peserta dari tombol
    var namaPeserta2 = $(this).attr('nama_peserta2'); // Ambil nilai nama_peserta dari tombol
    var nilaiPinjaman2 = $(this).attr('nilai_pinjaman2'); // Ambil nilai id_anggaran dari tombol
    var nilaiSisaDana2 = $(this).attr('nilai_sisa_dana2'); // Ambil nilai id_saldo dari tombol

    // Mengisi nilai ke dalam input sesuai dengan data yang diambil dari tombol
    $("#bmfi_peserta_program2").text(idPeserta2);
   



    // Menampilkan nilai dalam modal dengan format Rupiah
    $("#modal-nama_peserta2").text(namaPeserta2);
    $("#modal-nilai_pinjaman2").text('Rp ' + formatRupiah(nilaiPinjaman2));
    $("#modal-nilai_sisa_dana2").text('Rp ' + formatRupiah(nilaiSisaDana2));


    // Buka modal "Peminjaman Alert"
    $("#peringatan-untuk-pelunasan-alertModal").modal('show');
});


</script>








<!-- Modal Alert -->
<div class="modal fade" id="peminjaman-alertModal" tabindex="-1" aria-labelledby="modalAlertAsesmenLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAlertAsesmenLabel">Peringatan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Peserta telah diasesmen sebelumnya. Apakah yakin diasesmen ulang?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="btnYaAsesmenUlang">Iya</button>
            </div>
        </div>
    </div>
</div>



<script>
// Menangani klik tombol "Transaksi" (Asesmen dan Peminjaman)
$(document).on('click', '.btn-peminjaman-alert', function() {
    var idPeserta2 = $(this).attr('id_peserta2'); // Ambil nilai id_peserta dari tombol
    var namaPeserta2 = $(this).attr('nama_peserta2'); // Ambil nilai nama_peserta dari tombol
    var idAnggaran2 = $(this).attr('id_anggaran2'); // Ambil nilai id_anggaran dari tombol
    var idSaldo2 = $(this).attr('id_saldo2'); // Ambil nilai id_saldo dari tombol

    // Mengisi nilai ke dalam input sesuai dengan data yang diambil dari tombol
    $("#bmfi_peserta_program2").val(idPeserta2);
    $("#nama_peserta_program2").val(namaPeserta2);
    $("#anggaran_microfinance2").val(idAnggaran2);
    $("#saldo_microfinance2").val(idSaldo2);

    // Buka modal "Peminjaman Alert"
    $("#peminjaman-alertModal").modal('show');
});

// Menangani klik tombol "Iya" pada modal alert
$(document).on('click', '#btnYaAsesmenUlang', function() {
    var idPeserta2 = $("#bmfi_peserta_program2").val(); // Ambil nilai id_peserta dari input tersembunyi

    
    
    // Lakukan aksi setelah pengguna memilih "Iya"
    alert('Anda memilih untuk melakukan asesmen ulang. Mengarahkan ke halaman peminjaman...');

    // Tutup modal "Peminjaman Alert"
    $("#peminjaman-alertModal").modal('hide');



        // Buka modal "Transaksi Peminjaman"
        $("#add_transaksipeminjamanModal").modal('show');


    // // Lanjutkan ke halaman peminjaman atau lakukan aksi lainnya
    // // Ganti URL dengan halaman peminjaman yang sesuai
    // window.location.href = 'halaman-peminjaman.php?id=' + idPeserta2;
});
</script>









<!-- Modal Alert -->
<div class="modal fade" id="peringatan-untuk-pelunasan-hapus-alertModal" tabindex="-1" aria-labelledby="modalperingatan-untuk-pelunasan-hapus-alertLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalperingatan-untuk-pelunasan-hapus-alertLabel">Tidak bisa Hapus - Peringatan untuk Pelunasan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Karena Anda masih memiliki sisa cicilan, mohon dilunaskan terlebih dahulu. Maka Hapus Profil akan bisa dilakukan</p>
                <p><strong>Nama Peserta:</strong> <span id="modal-alert-hapus-nama_peserta3"></span></p>
                <p><strong>Nilai Pinjaman:</strong> <span id="modal-alert-hapus-nilai_pinjaman3"></span></p>
                <p><strong>Sisa Dana Belum dikembalikan:</strong> <span id="modal-alert-hapus-nilai_sisa_dana3"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>




<script>
    // Menangani klik tombol "Hapus" (dengan peringatan)
    $(document).on('click', '.btn-hapus-alert', function() {
        var idPeserta = $(this).attr('id_peserta_program_bmfi_asesmen'); // Ambil nilai id_peserta_program_bmfi_asesmen dari tombol
        var namaPeserta = $(this).attr('nama_peserta3'); // Ambil nilai nama_peserta dari tombol
        var nilaiPinjaman = $(this).attr('nilai_pinjaman3'); // Ambil nilai nilai_pinjaman dari tombol
        var nilaiSisaDana = $(this).attr('nilai_sisa_dana3'); // Ambil nilai nilai_sisa_dana dari tombol

        // Mengisi nilai ke dalam input sesuai dengan data yang diambil dari tombol
        $("#modal-alert-hapus-nama_peserta3").text(namaPeserta);
        $("#modal-alert-hapus-nilai_pinjaman3").text('Rp ' + formatRupiah(nilaiPinjaman));
        $("#modal-alert-hapus-nilai_sisa_dana3").text('Rp ' + formatRupiah(nilaiSisaDana));

        // Buka modal "Peringatan untuk Pelunasan"
        $("#peringatan-untuk-pelunasan-hapus-alertModal").modal('show');
    });

   
</script>







<div class="modal fade" id="add_transaksipeminjamanModal">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header bg-secondary">

                <h5 class="modal-title">Tambah Transaksi Peminjaman</h5>

                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>



            <form id="bmfi_transaksi_peminjamanFormAdd">



                <input type="hidden" name="jalur" value="Masjid">



                <input type="hidden" name="id_jalur" value="<?php echo $this->session->userdata('parent') ?>">

 




                <div class="modal-body">



                   <div class="row">


                        <div class="col-md-6">

                            <div class="form-group">


                                <label>Masjid</label>



                                <input type="text" value="<?php echo $this->session->userdata('nmjalur') ?>"
                                    class="form-control" readonly>



                            </div>



                        </div>


                    </div>






                <input type="hidden" name="peserta_program_bmfi_asesmen"  id="bmfi_peserta_program2">



                   <div class="row">


                        <div class="col-md-6">

                            <div class="form-group">



                                <input type="text"  id="nama_peserta_program2"
                                    class="form-control" readonly>



                            </div>



                        </div>


                    </div>








                         <div class="row">


                             <div class="col-md-8">

                            <div class="form-group">

                                <label>Tanggal</label>

                                <input type="date" class="form-control" name="tgl" />

                                  <small class="text-secondary">Tanggal pada saat bmfi_asesmen dibuat </small><br>


                            </div>

                        </div>

                    </div>




             <div class="row">

                        <div class="col-md-8">

                            <div class="form-group">

                                <label>Kategori Asesmen</label>

                                <select name="bmfi_kategori_asesmen" class="form-control">

                                    <option value="">Pilih</option>

                                    <?php foreach ($bmfi_kategori_asesmen as $t) { ?>

                                        <option value="<?php echo $t['id_bmfi_kategori_asesmen'] ?>"><?php echo $t['nama_bmfi_kategori_asesmen'] ?></option>

                                    <?php } ?>

                                </select>


                                  <small class="text-secondary">Jenis Kategori Asesmen </small><br>

                            </div>

                              </div>

                          </div>



                    <div class="row">

                        <div class="col-12">

                            <div class="form-group">

                                <label>Judul Asesmen Microfinance</label>

                                <textarea name="judul" class="form-control"  rows="2"></textarea>

                                  <small class="text-secondary">Judul yang di input akan menjadi Judul pada Bmfi Asesmen Microfinance</small><br>

                            </div>

                        </div>

                         </div>





         <div class="row">

         <div class="col-md-12">

        <div class="form-group">
            <label for="pendamping_bmfi_asesmen">Pendamping Asesmen</label>
            <select name="pendamping_bmfi_asesmen" class="form-control">
                <option value="">Pilih</option>
                <?php foreach ($pendamping_bmfi_asesmen as $h) { ?>
                    <option value="<?php echo $h['id_pendamping_bmfi_asesmen'] ?>">
                        <?php echo $h['nama_pendamping_bmfi_asesmen'] ?>
                    </option>
                <?php } ?>
            </select>


              

           
        </div>

    </div>

</div>






                  <div class="row">

            
            <div class="col-md-12">

                
                 <div class="form-group">


    <label for="pilihan_skema_pinjaman">Pilihan Jumlah Pinjaman</label>

    <select name="nominal_pilihan_skema_pinjaman" class="form-control">

        <option value="">Pilih</option>

        <?php foreach ($pilihan_skema_pinjaman as $h) { ?>

            <option value="<?php echo $h['jumlah_nominal'] ?>">

            <?php echo $h['nama_pilihan_skema_pinjaman'] ?> - <?php echo "Rp " . number_format($h['jumlah_nominal'], 0, ',', '.'); ?>

        </option>

        <?php } ?>


    </select>


       </div>

        
         </div>


             </div>



                    <div class="form-group">



                        <label>Jenis Angsuran</label>



                        <select name="bmfi_jenis_angsuran" class="form-control">



                            <option value="">--Pilih--</option>



                            <?php foreach ($bmfi_jenis_angsuran as $v) { ?>



                            <option value="<?php echo $v['id_bmfi_jenis_angsuran'] ?>">

                                <?php echo $v['nama_bmfi_jenis_angsuran'] ?></option>



                            <?php } ?>



                        </select>



                    </div>







<div class="row">
    <div class="col-md-5">
        <div class="form-group">
            <label>Perjanjian Tanggal Pengembalian</label>
            <input type="number" id="tanggalInput_perjanjian_pengembalian" name="perjanjian_tanggal_pengembalian" class="form-control" min="1" max="31">
            <small>Tanggal yang diinput akan digunakan sebagai pengingat untuk penagihan angsuran peserta</small>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var input = document.getElementById('tanggalInput_perjanjian_pengembalian');

        input.addEventListener('input', function() {
            var value = parseInt(input.value, 10);

            if (value > 31) {
                alert('Tanggal tidak valid. Masukkan angka antara 1 hingga 31.');
                input.value = '';
            } else if (value < 1) {
                alert('Tanggal tidak valid. Masukkan angka antara 1 hingga 31.');
                input.value = '';
            }
        });
    });
</script>
    




                <div class="row">


                        <div class="col-md-6">

                            <div class="form-group">



             <input type="text"  name="bmfi_saldo_microfinance"  id="anggaran_microfinance3"
                                    class="form-control" readonly hidden>



                            </div>



                        </div>


                    </div>





                <div class="row">


                        <div class="col-md-6">

                            <div class="form-group">



             <input type="text"  name="nominal_saldo_microfinance"  id="saldo_microfinance3"
                                    class="form-control" readonly hidden>



                            </div>



                        </div>


                    </div>



<div class="form-group">
    <label>Anggaran yang dipakai</label>
    <select name="bmfi_anggaran_microfinance" id="bmfi_anggaran_microfinance" class="form-select">
        <option value="">--Pilih Saldo Anggaran--</option>
        <?php foreach ($bmfi_anggaran_microfinance as $v) { ?>
            <option value="<?php echo $v['id_bmfi_saldo_microfinance'] ?>" data-nominal="<?php echo $v['nominal_saldo_microfinance'] ?>">
                Rp <?php echo number_format($v['nominal_saldo_microfinance'], 0, ',', '.') ?> : <?php echo $v['nama_bmfi_saldo_microfinance'] ?>
            </option>
        <?php } ?>
    </select>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        // Menangani perubahan pada dropdown bmfi_anggaran_microfinance
        $('#bmfi_anggaran_microfinance').change(function() {
            var selectedId = $(this).val(); // Mendapatkan nilai id_bmfi_saldo_microfinance yang dipilih
            var selectedNominal = $(this).find('option:selected').data('nominal'); // Mendapatkan nilai nominal_saldo_microfinance yang sesuai

            // Mengisi nilai ke dalam input dengan id anggaran_microfinance2
            $('#anggaran_microfinance3').val(selectedId);

            // Mengisi nilai ke dalam input dengan id saldo_microfinance3
            $('#saldo_microfinance3').val(formatRupiah(selectedNominal)); // Pastikan formatRupiah sesuai dengan kebutuhan Anda
        });
    });
</script>



                    <div class="row">

                        <div class="col-12">

                            <div class="form-group">

                                <label>Keterangan</label>

                                <textarea name="keterangan" class="form-control"></textarea>

                                  <small class="text-secondary">Keterangan Bmfi Asesmen berfungsi untuk  memberikan informasi tambahan seperti narasi penjelasan tentang peserta program seperti keterangan usaha dan informasi lainnya</small><br>

                            </div>

                        </div>

                         </div>


                


<!-- 
                    <div class="row">

                        <div class="col-md-12">

                            <div class="form-group">

                                <label>Foto Usaha</label>

                                <div class="input-group">

                                    <div class="custom-file">

                                        <input type="file" class="custom-file-input" name="foto" id="foto" required>

                                        <label class="custom-file-label" for="foto">Pilih file</label>

                                         </div>

                        </div>


                        <small class="text-secondary">Gambar Foto akan digunakan sebagai informasi tambahan pada bmfi_asesmen dan hanya bisa 1 Gambar </small>
                        <br>

                        <small class="text-secondary"><i>Format file yang diizinkan <b>.jpg, .jpeg, png, gif</b>. Ukuran file maksimal <b>2 MB</b>.</i></small><br>

                        <small class="upload-error text-danger"></small>

                    </div>

                                

                        </div>

                    </div>

               

 -->




<!-- 


<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label for="foto" class="form-label">Foto Usaha</label>
            <div class="input-group">
                <input type="file" class="form-control" id="foto" name="foto">
               
            </div>
            <small class="form-text text-muted">Gambar Foto akan digunakan sebagai informasi tambahan pada bmfi_asesmen dan hanya bisa 1 Gambar</small>
            <small class="form-text text-muted"><i>Format file yang diizinkan <b>.jpg, .jpeg, .png, .gif</b>. Ukuran file maksimal <b>2 MB</b>.</i></small>
            <small class="form-text text-danger upload-error"></small>
        </div>
    </div>
</div>

 -->





<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label for="foto" class="form-label">Foto Usaha</label>
            <input type="file" class="form-control" id="foto" name="foto" onchange="previewImageFoto_usaha()">
            <small class="form-text text-muted"><i>File Gambar Bersifat Opsional (Tidak Wajib)</i></small>
            <small class="form-text text-muted">Gambar Foto akan digunakan sebagai informasi tambahan pada bmfi_asesmen dan hanya bisa 1 Gambar</small>
            <small class="form-text text-muted"><i>Format file yang diizinkan <b>.jpg, .jpeg, .png, .gif</b>. Ukuran file maksimal <b>2 MB</b>.</i></small>
            <small class="form-text text-danger upload-error"></small>
            <img id="preview_foto_usaha" src="#" alt="preview_foto_usaha" style="display:none; max-width: 100%; max-height: 200px;">
        </div>
    </div>
</div>

<script>
    function previewImageFoto_usaha() {
        var fileInput = document.getElementById('foto');
        var preview_foto_usaha = document.getElementById('preview_foto_usaha');

        if (fileInput.files && fileInput.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                preview_foto_usaha.src = e.target.result;
                preview_foto_usaha.style.display = 'block';
            }

            reader.readAsDataURL(fileInput.files[0]);
        }
    }
</script>






   </div>



                <div class="modal-footer">



                    <span class="loader"><i class="fa fa-spinner fa-spin"></i> Sedang proses...</span>



                    <button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal"><i
                            class="fa fa-times"></i> Batal</button>



                    <button type="submit" class="btn btn-primary btn-sm" id="btn-add_transaksi_peminjaman"><i class="fa fa-check"></i>

                        Submit</button>



                </div>



            </form>



        </div>



        <!-- /.modal-content -->



    </div>




    <!-- /.modal-dialog -->



</div>

















<div class="modal fade" id="add_bmm_web_usaha_peserta_addModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <h5 class="modal-title">Tambah Bmm Web Usaha Peserta</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="bmfi_bmm_web_usaha_peserta_addFormAdd">
                <div class="modal-body">




  <div class="col-md-6">

                            <div class="form-group">


                                <label>Masjid</label>



                                <input type="text" value="<?php echo $this->session->userdata('nmjalur') ?>"
                                    class="form-control" readonly>



                            </div>



                        </div>





<!-- 
                <input type="hidden" name="id_peserta_program_bmfi_bmm"  id="bmfi_peserta_program_survei2">




                <input type="hidden" name="nama_peserta_program_bmfi_bmm"  id="bmfi_nama_peserta_program_survei2">
 -->


    <input type="text" name="id_peserta_program_bmfi_bmm"  id="id_peserta_bmm_web_usaha2">




     <input type="text" name="nama_peserta_program_bmfi_bmm"  id="nama_peserta_bmm_web_usaha2">



     <!-- <input type="text" name="no_hp_peserta_program_bmfi_bmm"  id="no_hp_peserta_akun_login2"> -->









<div class="row">
  <div class="col-md-12">
    <div class="form-group">
      <label for="warna_tulisan_website">Warna Tulisan Website</label>
      <select name="warna_tulisan_website" class="form-control">
        <option value="">Pilih</option>
        <?php foreach ($kategori_warna_tulisan as $t) { ?>
          <option value="<?php echo $t['kode_warna_tulisan'] ?>" style="background-color: <?php echo $t['kode_warna_tulisan'] ?>; background-color: <?php echo $t['kode_warna_tulisan'] ?>;">
            <?php echo $t['nama_kategori_warna_tulisan'] ?> <?php echo $t['kode_warna_tulisan'] ?>  <span class="fas fa-icon-name"></span>
          </option>
        <?php } ?>
      </select>


          <small class="text-secondary">Warna yang dipilih akan menjadi warna keseluruhan pada warna tulisan website </small><br>



    </div>
  </div>
</div>




<div class="row">
  <div class="col-md-12">
    <div class="form-group">
      <label for="background_warna_website">Background Warna Website</label>
      <select name="background_warna_website" class="form-control">
        <option value="">Pilih</option>
        <?php foreach ($kategori_warna_latar_belakang as $l) { ?>
          <option value="<?php echo $l['kode_warna_latar_belakang'] ?>" style="background-color: <?php echo $l['kode_warna_latar_belakang'] ?>; background-color: <?php echo $l['kode_warna_latar_belakang'] ?>;">
            <?php echo $l['nama_kategori_warna_latar_belakang'] ?> <?php echo $l['kode_warna_latar_belakang'] ?>  <span class="fas fa-icon-name"></span>
          </option>
        <?php } ?>
      </select>

 <small class="text-secondary">Warna yang dipilih akan menjadi warna keseluruhan pada latar warna background website </small><br>
 
    </div>
  </div>
</div>





                             <div class="col-md-8">

                            <div class="form-group">

                                <label>Jenis BMM Web Usaha Peserta</label>

                                <select name="jenis" class="form-control">

                                    <option value="">--Pilih--</option>

                                    <option value="Bmm_web_usaha">BMM Web Usaha</option>

                                    <!-- <option value="Musholla">Musholla</option> -->

                                </select>

                            </div>

                             </div>





                              <div class="col-md-12">

                            <div class="form-group">

                                <label>Nama BMM Web Usaha</label>

                                <input type="text" name="nama" class="form-control">

                            </div>

                           
                        </div>

     


                    <div class="row">

                        <div class="col-12">

                            <div class="form-group">

                                <label>Keterangan lainnya</label>

                                <textarea name="deskripsi" class="form-control"></textarea>

                            </div>

                        </div>

                    </div>

           



                </div>

                <div class="modal-footer">
                    <span class="loader"><i class="fa fa-spinner fa-spin"></i> Sedang proses...</span>
                    <button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm" id="btn-add_bmm_web_usaha_peserta_add"><i class="fa fa-check"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>










<div class="modal fade" id="add_akun_login_peserta_addModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <h5 class="modal-title">Tambah Akun Login Peserta</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="bmfi_akun_login_peserta_addFormAdd">
                <div class="modal-body">




  <div class="col-md-6">

                            <div class="form-group">


                                <label>Masjid</label>



                                <input type="text" value="<?php echo $this->session->userdata('nmjalur') ?>"
                                    class="form-control" readonly>



                            </div>



                        </div>






    <input type="text" name="id_peserta_program_bmfi_bmm_akun_login"  id="id_peserta_akun_login2">




     <input type="text" name="nama_peserta_program_bmfi_bmm"  id="nama_peserta_akun_login2">



     <input type="text" name="no_hp_peserta_program_bmfi_bmm"  id="no_hp_peserta_akun_login2">






  <div class="row">


                        <div class="col-md-12">



                            <div class="form-group">



                                <label>Nama Lengkap</label>



                                <input type="text" name="nama_peserta_program_bmfi_bmm" class="form-control">



                            </div>

                        </div>


                     </div>


                    <div class="row">

                        <div class="col-md-12">


                            <div class="form-group">



                                <label>Jenis Kelamin</label>



                                <select name="jenis_kelamin" class="form-control">



                                    <option value="">--Pilih--</option>



                                    <option value="Laki-laki">Laki-laki</option>



                                    <option value="Perempuan">Perempuan</option>



                                </select>



                            </div>



                            </div>


                                </div>




            

                    <div class="row">



                        <div class="col-md-12">



                            <div class="form-group">



                                <label>Alamat</label>



                                <textarea name="alamat" class="form-control"></textarea>



                            </div>



                        </div>



                    </div>






                    <div class="row">



                        <div class="col-md-10">



                            <div class="form-group">



                                <label>Email</label>



                                <input type="email" name="email" class="form-control">



                            </div>

                               </div>

                                  </div>



<div class="row">
    <div class="col-md-8">
        <div class="form-group">
            <label>No. Hp</label>
            <input type="number" name="no_hp" class="form-control">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 mt-3">
        <!-- Tombol untuk halaman login -->
        <a href="https://menara.baznas.go.id/login/bmfi_bmm_peserta_microfinance" 
           class="btn btn-primary" target="_blank">
            Halaman Login
        </a>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-12">
        <span class="text-secondary">Catatan :</span>
        <ul style="font-size:14px;padding:0;list-style:none;" class="text-secondary">
            <li>- Jika pengguna tidak diizinkan untuk mengakses <strong>Login</strong>, atur statusnya menjadi <strong>tidak aktif</strong>.</li>
            <li>- Password secara <i>default</i> sudah dibuatkan oleh Super Admin yaitu <strong>menara23</strong></li>
        </ul>
    </div>
</div>


                </div>

           



                <div class="modal-footer">
                    <span class="loader"><i class="fa fa-spinner fa-spin"></i> Sedang proses...</span>
                    <button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm" id="btn-add_akun_login_peserta_add"><i class="fa fa-check"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>














<div class="modal fade" id="add_transaksisurveipesertaModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <h5 class="modal-title">Tambah Survei</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="bmfi_transaksi_survei_pesertaFormAdd">
                <div class="modal-body">




  <div class="col-md-6">

                            <div class="form-group">


                                <label>Masjid</label>



                                <input type="text" value="<?php echo $this->session->userdata('nmjalur') ?>"
                                    class="form-control" readonly>



                            </div>



                        </div>






                <input type="hidden" name="id_peserta_program_bmfi_bmm"  id="bmfi_peserta_program_survei2">




                <input type="hidden" name="nama_peserta_program_bmfi_bmm"  id="bmfi_nama_peserta_program_survei2">







                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <h3>Business Quality Assessment</h3>
                                <label for="business_duration" class="form-label">1. Berapa lama usaha Anda sudah berjalan?</label>
                                <select class="form-select" name="1_business_duration" required>
                                    <option value="" disabled selected>Pilih durasi</option>
                                    <option value="1"> < 6 bulan </option>
                                    <option value="2"> 6-12 bulan </option>
                                    <option value="3"> 1-2 tahun </option>
                                    <option value="4"> 2-5 tahun </option>
                                    <option value="5"> > 5 tahun </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="growth_and_sales" class="form-label">2. Bagaimana Anda menilai pertumbuhan usaha Anda dan peningkatan penjualan dalam 3 bulan terakhir?</label>
                                <select class="form-select" name="2_growth_and_sales" required>
                                    <option value="" disabled selected>Pilih opsi</option>
                                    <option value="5"> Sangat baik, peningkatan > 20% </option>
                                    <option value="4"> Baik, peningkatan 10-20% </option>
                                    <option value="3"> Cukup, peningkatan 5-10% </option>
                                    <option value="2"> Kurang, peningkatan < 5% </option>
                                    <option value="1"> Sangat kurang, tidak ada peningkatan </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="customer_demand" class="form-label">3. Seberapa sering Anda mengalami kesulitan dalam memenuhi permintaan pelanggan usaha Anda?</label>
                                <select class="form-select" name="3_customer_demand" required>
                                    <option value="" disabled selected>Pilih opsi</option>
                                    <option value="1"> Tidak pernah </option>
                                    <option value="2"> Jarang </option>
                                    <option value="3"> Kadang-kadang </option>
                                    <option value="4"> Sering </option>
                                    <option value="5"> Selalu </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="business_plan" class="form-label">4. Apakah Anda memiliki rencana bisnis yang tertulis dan jelas?</label>
                                <select class="form-select" name="4_business_plan" required>
                                    <option value="" disabled selected>Pilih opsi</option>
                                    <option value="5"> Ya, sangat detail </option>
                                    <option value="4"> Ya, cukup detail </option>
                                    <option value="3"> Ya, tapi tidak detail </option>
                                    <option value="2"> Tidak, tapi ada gambaran umum </option>
                                    <option value="1"> Tidak sama sekali </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="financial_condition" class="form-label">5. Bagaimana kondisi keuangan usaha Anda saat ini?</label>
                                <select class="form-select" name="5_financial_condition" required>
                                    <option value="" disabled selected>Pilih opsi</option>
                                    <option value="5"> Sangat stabil </option>
                                    <option value="4"> Stabil </option>
                                    <option value="3"> Cukup stabil </option>
                                    <option value="2"> Tidak stabil </option>
                                    <option value="1"> Sangat tidak stabil </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <h3 class="mt-4">Health Impact Assessment (Reduced)</h3>
                                <label for="health_issues" class="form-label">6. Seberapa sering Anda mengalami masalah kesehatan yang mengganggu aktivitas bisnis Anda?</label>
                                <select class="form-select" name="6_health_issues" required>
                                    <option value="" disabled selected>Pilih opsi</option>
                                    <option value="1"> Tidak pernah </option>
                                    <option value="2"> Jarang </option>
                                    <option value="3"> Kadang-kadang </option>
                                    <option value="4"> Sering </option>
                                    <option value="5"> Selalu </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="health_impact" class="form-label">7. Seberapa besar pengaruh kesehatan fisik Anda terhadap produktivitas kerja dalam menjalankan usaha Anda?</label>
                                <select class="form-select" name="7_health_impact" required>
                                    <option value="" disabled selected>Pilih opsi</option>
                                    <option value="5"> Sangat besar </option>
                                    <option value="4"> Besar </option>
                                    <option value="3"> Sedang </option>
                                    <option value="2"> Kecil </option>
                                    <option value="1"> Tidak berpengaruh </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <h3 class="mt-4">Combined Assessment</h3>
                                <label for="stress_impact" class="form-label">8. Seberapa sering Anda merasa stres atau cemas yang mempengaruhi kinerja bisnis Anda?</label>
                                <select class="form-select" name="8_stress_impact" required>
                                    <option value="" disabled selected>Pilih opsi</option>
                                    <option value="1"> Tidak pernah </option>
                                    <option value="2"> Jarang </option>
                                    <option value="3"> Kadang-kadang </option>
                                    <option value="4"> Sering </option>
                                    <option value="5"> Selalu </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="support_system" class="form-label">9. Seberapa besar dukungan keluarga, mitra, atau teman dalam menjalankan usaha Anda?</label>
                                <select class="form-select" name="9_support_system" required>
                                    <option value="" disabled selected>Pilih opsi</option>
                                    <option value="5"> Sangat besar </option>
                                    <option value="4"> Besar </option>
                                    <option value="3"> Sedang </option>
                                    <option value="2"> Kecil </option>
                                    <option value="1"> Tidak ada </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="health_impact_on_repayment" class="form-label">10. Apakah Anda pernah mengalami gangguan kesehatan yang mempengaruhi kemampuan Anda untuk membayar angsuran?</label>
                                <select class="form-select" name="10_health_impact_on_repayment" required>
                                    <option value="" disabled selected>Pilih opsi</option>
                                    <option value="1"> Tidak pernah </option>
                                    <option value="2"> Sekali </option>
                                    <option value="3"> Beberapa kali </option>
                                    <option value="4"> Sering </option>
                                    <option value="5"> Selalu </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <span class="loader"><i class="fa fa-spinner fa-spin"></i> Sedang proses...</span>
                    <button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm" id="btn-add_transaksi_survei_peserta"><i class="fa fa-check"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>












<div class="modal fade" id="add_transaksipsikotespesertaModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <h5 class="modal-title">Tambah psikotes</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="bmfi_transaksi_psikotes_pesertaFormAdd">
                <div class="modal-body">




  <div class="col-md-6">

                            <div class="form-group">


                                <label>Masjid</label>



                                <input type="text" value="<?php echo $this->session->userdata('nmjalur') ?>"
                                    class="form-control" readonly>



                            </div>



                        </div>






                <input type="hidden" name="id_peserta_program_bmfi_bmm"  id="bmfi_peserta_program_psikotes2">




                <input type="hidden" name="nama_peserta_program_bmfi_bmm"  id="bmfi_nama_peserta_program_psikotes2">








 <div class="mb-3">
                <h3>Ekstraversi (E) vs. Introversi (I)</h3>
                <label class="form-label">
                    1. Saya merasa lebih berenergi ketika berinteraksi dengan banyak orang.
                    <div>
                        <input type="radio" name="1_question" value="E"> Setuju
                        <input type="radio" name="1_question" value="I"> Tidak Setuju
                    </div>
                </label>
                <label class="form-label">
                    2. Saya lebih suka menghabiskan waktu sendiri daripada bersama banyak orang.
                    <div>
                        <input type="radio" name="2_question" value="I"> Setuju
                        <input type="radio" name="2_question" value="E"> Tidak Setuju
                    </div>
                </label>
            </div>

            <div class="mb-3">
                <h3>Sensing (S) vs. Intuition (N)</h3>
                <label class="form-label">
                    3. Saya lebih suka informasi yang konkret dan nyata.
                    <div>
                        <input type="radio" name="3_question" value="S"> Setuju
                        <input type="radio" name="3_question" value="N"> Tidak Setuju
                    </div>
                </label>
                <label class="form-label">
                    4. Saya lebih suka memikirkan kemungkinan dan ide-ide baru.
                    <div>
                        <input type="radio" name="4_question" value="N"> Setuju
                        <input type="radio" name="4_question" value="S"> Tidak Setuju
                    </div>
                </label>
            </div>

            <div class="mb-3">
                <h3>Thinking (T) vs. Feeling (F)</h3>
                <label class="form-label">
                    5. Saya lebih suka membuat keputusan berdasarkan logika dan fakta.
                    <div>
                        <input type="radio" name="5_question" value="T"> Setuju
                        <input type="radio" name="5_question" value="F"> Tidak Setuju
                    </div>
                </label>
                <label class="form-label">
                    6. Saya lebih suka mempertimbangkan perasaan orang lain dalam membuat keputusan.
                    <div>
                        <input type="radio" name="6_question" value="F"> Setuju
                        <input type="radio" name="6_question" value="T"> Tidak Setuju
                    </div>
                </label>
            </div>

            <div class="mb-3">
                <h3>Judging (J) vs. Perceiving (P)</h3>
                <label class="form-label">
                    7. Saya lebih suka merencanakan segala sesuatu dengan baik sebelumnya.
                    <div>
                        <input type="radio" name="7_question" value="J"> Setuju
                        <input type="radio" name="7_question" value="P"> Tidak Setuju
                    </div>
                </label>
                <label class="form-label">
                    8. Saya lebih suka spontan dan fleksibel dalam menjalani hidup.
                    <div>
                        <input type="radio" name="8_question" value="P"> Setuju
                        <input type="radio" name="8_question" value="J"> Tidak Setuju
                    </div>
                </label>
            </div>

            <div class="mb-3">
                <h3>Ekstraversi (E) vs. Introversi (I)</h3>
                <label class="form-label">
                    9. Saya merasa lebih bersemangat setelah bertemu dengan banyak orang.
                    <div>
                        <input type="radio" name="9_question" value="E"> Setuju
                        <input type="radio" name="9_question" value="I"> Tidak Setuju
                    </div>
                </label>
                <label class="form-label">
                    10. Saya merasa lebih nyaman dengan lingkungan yang tenang dan tidak ramai.
                    <div>
                        <input type="radio" name="10_question" value="I"> Setuju
                        <input type="radio" name="10_question" value="E"> Tidak Setuju
                    </div>
                </label>
            </div>

            <div class="mb-3">
                <h3>Sensing (S) vs. Intuition (N)</h3>
                <label class="form-label">
                    11. Saya lebih suka bekerja dengan data dan detail yang jelas.
                    <div>
                        <input type="radio" name="11_question" value="S"> Setuju
                        <input type="radio" name="11_question" value="N"> Tidak Setuju
                    </div>
                </label>
                <label class="form-label">
                    12. Saya lebih suka merenung tentang masa depan dan kemungkinan yang ada.
                    <div>
                        <input type="radio" name="12_question" value="N"> Setuju
                        <input type="radio" name="12_question" value="S"> Tidak Setuju
                    </div>
                </label>
            </div>







                </div>

                <div class="modal-footer">
                    <span class="loader"><i class="fa fa-spinner fa-spin"></i> Sedang proses...</span>
                    <button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm" id="btn-add_transaksi_psikotes_peserta"><i class="fa fa-check"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>


















<script>






    $(document).ready(function() {






  // Menangani klik tombol "Transaksi"
    $(document).on('click', '.btn-bmm_web_usaha_peserta_add' , function() {
        


 var idPeserta2 = $(this).attr('id_peserta2'); // Ambil nilai id_peserta dari tombol
        // Mengisi nilai ID peserta program ke dalam input 'bmfi_peserta_program'


           var idNamaPeserta2 = $(this).attr('nama_peserta2'); // Ambil nilai id_peserta dari tombol
        // Mengisi nilai ID peserta program ke dalam input 'bmfi_peserta_program'





        $("#bmfi_peserta_program_survei2").val(idPeserta2);


         $("#nama_peserta_program_survei2").val(idNamaPeserta2);


         




        // Buka modal "Transaksi"
        $("#add_bmm_web_usaha_peserta_addModal").modal('show');
    });










 //  // Menangani klik tombol "Transaksi"
 //    $(document).on('click', '.btn-akun_login_peserta_add' , function() {
        


 // var idPeserta_akun_login2 = $(this).attr('id_peserta_akun_login2'); // Ambil nilai id_peserta dari tombol
 //        // Mengisi nilai ID peserta program ke dalam input 'bmfi_peserta_program'


 //           var idNamaPeserta_akun_login2 = $(this).attr('nama_peserta_akun_login2'); // Ambil nilai id_peserta dari tombol
 //        // Mengisi nilai ID peserta program ke dalam input 'bmfi_peserta_program'





 //        $("#id_peserta_program_akun_login2").val(idPeserta_akun_login2);


 //         $("#nama_peserta_program_akun_login2").val(idNamaPeserta_akun_login2);


         




 //        // Buka modal "Transaksi"
 //        $("#add_akun_login_peserta_addModal").modal('show');
 //    });













  // Menangani klik tombol "Transaksi"
    $(document).on('click', '.btn-transaksisurveipeserta' , function() {
        


 var idPeserta2 = $(this).attr('id_peserta2'); // Ambil nilai id_peserta dari tombol
        // Mengisi nilai ID peserta program ke dalam input 'bmfi_peserta_program'


           var idNamaPeserta2 = $(this).attr('nama_peserta2'); // Ambil nilai id_peserta dari tombol
        // Mengisi nilai ID peserta program ke dalam input 'bmfi_peserta_program'





        $("#bmfi_peserta_program_survei2").val(idPeserta2);


         $("#nama_peserta_program_survei2").val(idNamaPeserta2);


         




        // Buka modal "Transaksi"
        $("#add_transaksisurveipesertaModal").modal('show');
    });









  // Menangani klik tombol "Transaksi"
    $(document).on('click', '.btn-transaksipsikotespeserta' , function() {
        


 var idPeserta2 = $(this).attr('id_peserta2'); // Ambil nilai id_peserta dari tombol
        // Mengisi nilai ID peserta program ke dalam input 'bmfi_peserta_program'


           var idNamaPeserta2 = $(this).attr('nama_peserta2'); // Ambil nilai id_peserta dari tombol
        // Mengisi nilai ID peserta program ke dalam input 'bmfi_peserta_program'





        $("#bmfi_peserta_program_psikotes2").val(idPeserta2);


         $("#nama_peserta_program_psikotes2").val(idNamaPeserta2);


         




        // Buka modal "Transaksi"
        $("#add_transaksipsikotespesertaModal").modal('show');
    });











 // Menangani klik tombol "Transaksi"
    $(document).on('click', '.btn-transaksi', function() {
        var idPeserta = $(this).attr('id_peserta'); // Ambil nilai id_peserta dari tombol
        // Mengisi nilai ID peserta program ke dalam input 'bmfi_peserta_program'



      
        var idPesertaProgram = $(this).attr('id_peserta_program'); // Ambil nilai id_peserta dari tombol
        // Mengisi nilai ID peserta program ke dalam input 'bmfi_peserta_program'




       
               var idAngsuranMicrofinance = $(this).attr('id_angsuran'); // Ambil nilai id_peserta dari tombol
        // Mengisi nilai ID peserta program ke dalam input 'bmfi_peserta_program'





           var idSisa_dana = $(this).attr('sisa_dana'); // Ambil nilai id_peserta dari tombol
        // Mengisi nilai ID peserta program ke dalam input 'bmfi_peserta_program'




           var idJumlah_pinjaman = $(this).attr('jumlah_total_pinjaman'); // Ambil nilai id_peserta dari tombol
        // Mengisi nilai ID peserta program ke dalam input 'bmfi_peserta_program'




           var idNamaPeserta = $(this).attr('nama_peserta'); // Ambil nilai id_peserta dari tombol
        // Mengisi nilai ID peserta program ke dalam input 'bmfi_peserta_program'



               var idAnggaranMicrofinance = $(this).attr('id_anggaran'); // Ambil nilai id_peserta dari tombol
        // Mengisi nilai ID peserta program ke dalam input 'bmfi_peserta_program'




           var idSaldoMicrofinance = $(this).attr('id_saldo'); // Ambil nilai id_peserta dari tombol
        // Mengisi nilai ID peserta program ke dalam input 'bmfi_peserta_program'




           var idNamaAngsuranMicrofinance = $(this).attr('id_nama_angsuran'); // Ambil nilai id_peserta dari tombol
        // Mengisi nilai ID peserta program ke dalam input 'bmfi_peserta_program'


        $("#bmfi_peserta_program").val(idPeserta);


           $("#nama_id_peserta_program").val(idPesertaProgram);




        $("#angsuran_microfinance").val(idAngsuranMicrofinance);

       
         // $("#nama_sisa_dana").val(idSisa_dana);


         // $("#nama_jumlah_pinjaman").val(idJumlah_pinjaman);


// Memformat nilai dengan 'Rp' di depan

            $("#nama_sisa_dana").val('Rp ' + formatRupiah(idSisa_dana));

            $("#nama_jumlah_pinjaman").val('Rp ' + formatRupiah(idJumlah_pinjaman));




         $("#nama_peserta_program").val(idNamaPeserta);




            $("#anggaran_microfinance").val(idAnggaranMicrofinance);


             $("#saldo_microfinance").val(idSaldoMicrofinance);




 $("#nama_angsuran_microfinance").val(idNamaAngsuranMicrofinance);


        // Buka modal "Transaksi"
        $("#add_transaksiModal").modal('show');
    });




  // Menangani klik tombol "Transaksi"
    $(document).on('click', '.btn-transaksipeminjaman' , function() {
        var idPeserta2 = $(this).attr('id_peserta2'); // Ambil nilai id_peserta dari tombol
        // Mengisi nilai ID peserta program ke dalam input 'bmfi_peserta_program'


           var idNamaPeserta2 = $(this).attr('nama_peserta2'); // Ambil nilai id_peserta dari tombol
        // Mengisi nilai ID peserta program ke dalam input 'bmfi_peserta_program'




           var idAnggaranMicrofinance2 = $(this).attr('id_anggaran2'); // Ambil nilai id_peserta dari tombol
        // Mengisi nilai ID peserta program ke dalam input 'bmfi_peserta_program'




           var idSaldoMicrofinance2 = $(this).attr('id_saldo2'); // Ambil nilai id_peserta dari tombol
        // Mengisi nilai ID peserta program ke dalam input 'bmfi_peserta_program'


        $("#bmfi_peserta_program2").val(idPeserta2);


         $("#nama_peserta_program2").val(idNamaPeserta2);


            $("#anggaran_microfinance2").val(idAnggaranMicrofinance2);


             $("#saldo_microfinance2").val(idSaldoMicrofinance2);




        // Buka modal "Transaksi"
        $("#add_transaksipeminjamanModal").modal('show');
    });



















        $(".loader").hide();

        CKEDITOR.replace('keterangan', {

            on: {

                change: function() {

                    this.updateElement();

                }

            },

            toolbar: [

                // {

                //     name: 'document',

                //     groups: ['mode', 'document', 'doctools'],

                //     items: ['Source', '-', 'Save', 'NewPage', 'Preview', 'Print', '-', 'Templates']

                // },

                // {

                //     name: 'clipboard',

                //     groups: ['clipboard', 'undo'],

                //     items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo']

                // },

                // {

                //     name: 'editing',

                //     groups: ['find', 'selection', 'spellchecker'],

                //     items: ['Find', 'Replace', '-', 'SelectAll', '-', 'Scayt']

                // },

                // {

                //     name: 'forms',

                //     items: ['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField']

                // },

                // '/',

                {

                    name: 'basicstyles',

                    groups: ['basicstyles', 'cleanup'],

                    items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat']

                },

                {

                    name: 'paragraph',

                    groups: ['list', 'indent', 'blocks', 'align', 'bidi'],

                    items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language']

                },

                {

                    name: 'links',

                    items: ['Link', 'Unlink']

                    // items: ['Link', 'Unlink', 'Anchor']

                },

                // {

                //     name: 'insert',

                //     items: ['Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe']

                // },

                // '/',

                // {

                //     name: 'styles',

                //     items: ['Styles', 'Format', 'Font', 'FontSize']

                // },

                // {

                //     name: 'colors',

                //     items: ['TextColor', 'BGColor']

                // },

                // {

                //     name: 'tools',

                //     items: ['Maximize', 'ShowBlocks']

                // },

                // {

                //     name: 'others',

                //     items: ['-']

                // },

                // {

                //     name: 'about',

                //     items: ['About']

                // }

            ]

        });

        CKEDITOR.replace('keterangan2', {

            on: {

                change: function() {

                    this.updateElement();

                }

            },

            toolbar: [

                // {

                //     name: 'document',

                //     groups: ['mode', 'document', 'doctools'],

                //     items: ['Source', '-', 'Save', 'NewPage', 'Preview', 'Print', '-', 'Templates']

                // },

                // {

                //     name: 'clipboard',

                //     groups: ['clipboard', 'undo'],

                //     items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo']

                // },

                // {

                //     name: 'editing',

                //     groups: ['find', 'selection', 'spellchecker'],

                //     items: ['Find', 'Replace', '-', 'SelectAll', '-', 'Scayt']

                // },

                // {

                //     name: 'forms',

                //     items: ['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField']

                // },

                // '/',

                {

                    name: 'basicstyles',

                    groups: ['basicstyles', 'cleanup'],

                    items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat']

                },

                {

                    name: 'paragraph',

                    groups: ['list', 'indent', 'blocks', 'align', 'bidi'],

                    items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language']

                },

                {

                    name: 'links',

                    items: ['Link', 'Unlink']

                    // items: ['Link', 'Unlink', 'Anchor']

                },

                // {

                //     name: 'insert',

                //     items: ['Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe']

                // },

                // '/',

                // {

                //     name: 'styles',

                //     items: ['Styles', 'Format', 'Font', 'FontSize']

                // },

                // {

                //     name: 'colors',

                //     items: ['TextColor', 'BGColor']

                // },

                // {

                //     name: 'tools',

                //     items: ['Maximize', 'ShowBlocks']

                // },

                // {

                //     name: 'others',

                //     items: ['-']

                // },

                // {

                //     name: 'about',

                //     items: ['About']

                // }

            ]

        });




          CKEDITOR.replace('hasil_dampak_kemajuan2', {

            on: {

                change: function() {

                    this.updateElement();

                }

            },

            toolbar: [

                // {

                //     name: 'document',

                //     groups: ['mode', 'document', 'doctools'],

                //     items: ['Source', '-', 'Save', 'NewPage', 'Preview', 'Print', '-', 'Templates']

                // },

                // {

                //     name: 'clipboard',

                //     groups: ['clipboard', 'undo'],

                //     items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo']

                // },

                // {

                //     name: 'editing',

                //     groups: ['find', 'selection', 'spellchecker'],

                //     items: ['Find', 'Replace', '-', 'SelectAll', '-', 'Scayt']

                // },

                // {

                //     name: 'forms',

                //     items: ['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField']

                // },

                // '/',

                {

                    name: 'basicstyles',

                    groups: ['basicstyles', 'cleanup'],

                    items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat']

                },

                {

                    name: 'paragraph',

                    groups: ['list', 'indent', 'blocks', 'align', 'bidi'],

                    items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language']

                },

                {

                    name: 'links',

                    items: ['Link', 'Unlink']

                    // items: ['Link', 'Unlink', 'Anchor']

                },

                // {

                //     name: 'insert',

                //     items: ['Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe']

                // },

                // '/',

                // {

                //     name: 'styles',

                //     items: ['Styles', 'Format', 'Font', 'FontSize']

                // },

                // {

                //     name: 'colors',

                //     items: ['TextColor', 'BGColor']

                // },

                // {

                //     name: 'tools',

                //     items: ['Maximize', 'ShowBlocks']

                // },

                // {

                //     name: 'others',

                //     items: ['-']

                // },

                // {

                //     name: 'about',

                //     items: ['About']

                // }

            ]

        });





        var table = $(".datatables").DataTable({



            searching: false,

            processing: true,

            serverSide: true,

             ordering: false,

            order: [],

            ajax: {

                url: base_url + 'panel/bmfi_peserta_program/get_bmfi_peserta_program',

                type: "POST",

                data: function(d) {

                    return $.extend({}, d, {

                        txtNama: $("#txtNama").val(),

                       

                    });

                }

            },

            // columnDefs: [{

            //     targets: [0, 3],

            //     orderable: false

            // }]

        });



        $("#txtNama").bind("keyup change", function(e) {

            e.preventDefault();

            table.draw();

        });





        $('body').on("click", ".btn-edit", function(e) {

            e.preventDefault();



  var nilaiSaldo_dana_microfinance2 = $(this).attr('nilai_saldo_dana_microfinance2'); // Ambil nilai saldo microfinance dari tombol

    var nilaiPinjaman2 = $(this).attr('nilai_pinjaman2'); // Ambil nilai id_anggaran dari tombol

    var nilaiSisaDana2 = $(this).attr('nilai_sisa_dana2'); // Ambil nilai id_saldo dari tombol



     $("#data-nilai_saldo_microfinance_lama2").val('Rp ' + formatRupiah(nilaiSaldo_dana_microfinance2));


    $("#data-nilai_pinjaman_lama2").val('Rp ' + formatRupiah(nilaiPinjaman2));
    $("#data-nilai_sisa_dana_lama2").val('Rp ' + formatRupiah(nilaiSisaDana2));



            var id = $(this).attr('id');






            $.ajax({

                url: base_url + 'panel/bmfi_peserta_program/detail/' + id,

                type: "GET",

                dataType: "json",

                success: function(respon) {

                    $("[name='id2']").val(respon.id_peserta_program_bmfi_asesmen);


                      $("[name='tgl_registrasi_peserta2']").val(respon.tgl_registrasi_peserta);



                    $("[name='nama2']").val(respon.nama_peserta_program_bmfi_asesmen);


                    $("[name='jenis_kelamin2']").val(respon.jenis_kelamin);



                    $("[name='no_ktp2']").val(respon.no_ktp);


                    $("[name='no_hp2']").val(respon.no_hp);


                    $("[name='tempat_tanggal_lahir2']").val(respon.tempat_tanggal_lahir);


                    $("[name='alamat_rumah2']").val(respon.alamat_rumah);


                    $("[name='lokasi_usaha2']").val(respon.lokasi_usaha);


                     $("[name='nama_usaha2']").val(respon.nama_usaha);



                        $("[name='bmfi_sektor_usaha_peserta2']").val(respon.id_sektor_usaha_peserta);



                $("[name='bmfi_kategori_kualitas_angsuran_peserta2']").val(respon.id_kategori_kualitas_angsuran_peserta);


                $("[name='bmfi_rating_dampak_kemajuan_peserta2']").val(respon.id_rating_dampak_kemajuan_peserta);

                        


                       $("[name='no_rekening2']").val(respon.no_rekening);


                       $("[name='nama_bank2']").val(respon.nama_bank);


                          $("[name='atas_nama_rekening2']").val(respon.atas_nama_rekening);



                    $("[name='jumlah_anggota_keluarga2']").val(respon.jumlah_anggota_keluarga);



                      $("[name='nama_penjamin2']").val(respon.nama_penjamin);



                     $("[name='bmfi_status_hubungan_penjamin2']").val(respon.id_status_hubungan_penjamin);



                    $("[name='jumlah_pinjaman2']").val(respon.jumlah_pinjaman);


                     // $("[name='sisa_dana_pengembalian2']").val(respon.sisa_dana_pengembalian);


                     $("[name='sisa_dana_pengembalian2']").val("Rp " + formatRupiah(respon.sisa_dana_pengembalian));
                


                     $("[name='bmfi_jenis_angsuran2']").val(respon.id_jenis_angsuran);




                    CKEDITOR.instances['keterangan2'].setData(respon.keterangan);

                    $("[name='foto_profil1']").val(respon.foto_profil_peserta_program_bmfi_asesmen);

                    $(".foto_profil-src").css('background-image', "url('" + base_url + 'attachments/masjid-musholla/bmfi_asesmen/peserta_program/' + respon.foto_profil_peserta_program_bmfi_asesmen + "')");





                     $("[name='foto_dampak_program1']").val(respon.foto_dampak_program);

                    $(".foto_dampak_program-src").css('background-image', "url('" + base_url + 'attachments/masjid-musholla/bmfi_asesmen/foto_dampak_program/' + respon.foto_dampak_program + "')");







                     $("[name='lampiran_pelengkap1']").val(respon.lampiran_pelengkap);

                    $(".lampiran_pelengkap-src").css('background-image', "url('" + base_url + 'attachments/masjid-musholla/bmfi_asesmen/lampiran_pelengkap/' + respon.lampiran_pelengkap + "')");






                      $("[name='bmfi_jenis_program_pendampingan_peserta2']").val(respon.id_jenis_program_pendampingan_peserta);



                    CKEDITOR.instances['hasil_dampak_kemajuan2'].setData(respon.hasil_dampak_kemajuan);



                       $("[name='pendapatan_sebelum2']").val(respon.pendapatan_sebelum);

                        $("[name='pendapatan_sesudah2']").val(respon.pendapatan_sesudah);

                         $("[name='nilai_jumlah_intervensi_bantuan2']").val(respon.nilai_jumlah_intervensi_bantuan);

                          $("[name='durasi_pendampingan_peserta2']").val(respon.durasi_pendampingan_peserta);






                    $("#editModal").modal('show');

                }

            })

        });















    
        $('body').on("click", ".btn-kualitas_angsuran_peserta_edit", function(e) {

            e.preventDefault();




            var id = $(this).attr('id');





            $.ajax({

                url: base_url + 'panel/bmfi_peserta_program/detail/' + id,

                type: "GET",

                dataType: "json",

                success: function(respon) {

                    $("[name='id2']").val(respon.id_peserta_program_bmfi_asesmen);


             $("[name='nama_peserta2']").val(respon.nama_peserta_program_bmfi_asesmen);


                
                $("[name='bmfi_kategori_kualitas_angsuran_peserta2']").val(respon.id_kategori_kualitas_angsuran_peserta);




                    $("#kualitas_angsuran_peserta_editModal").modal('show');

                }

            })

        });






        $('body').on("click", ".btn-jenis_angsuran_peserta_edit", function(e) {

            e.preventDefault();




            var id = $(this).attr('id');





            $.ajax({

                url: base_url + 'panel/bmfi_peserta_program/detail/' + id,

                type: "GET",

                dataType: "json",

                success: function(respon) {

                    $("[name='id2']").val(respon.id_peserta_program_bmfi_asesmen);


             $("[name='nama_peserta2']").val(respon.nama_peserta_program_bmfi_asesmen);


                
                $("[name='bmfi_jenis_angsuran_peserta2']").val(respon.id_jenis_angsuran);




                    $("#jenis_angsuran_peserta_editModal").modal('show');

                }

            })

        });








    
        $('body').on("click", ".btn-komitmen_tanggal_pengembalian_peserta_edit", function(e) {

            e.preventDefault();




            var id = $(this).attr('id');





            $.ajax({

                url: base_url + 'panel/bmfi_peserta_program/detail/' + id,

                type: "GET",

                dataType: "json",

                success: function(respon) {

                    $("[name='id2']").val(respon.id_peserta_program_bmfi_asesmen);


             $("[name='nama_peserta2']").val(respon.nama_peserta_program_bmfi_asesmen);



               $("[name='perjanjian_tanggal_pengembalian_peserta2']").val(respon.perjanjian_tanggal_pengembalian_peserta);


                
                // $("[name='bmfi_kategori_komitmen_tanggal_pengembalian_peserta2']").val(respon.id_kategori_komitmen_tanggal_pengembalian_peserta);




                    $("#komitmen_tanggal_pengembalian_peserta_editModal").modal('show');

                }

            })

        });










    
        $('body').on("click", ".btn-kordinat_lokasi_usaha_peserta_edit", function(e) {

            e.preventDefault();




            var id = $(this).attr('id');





            $.ajax({

                url: base_url + 'panel/bmfi_peserta_program/detail/' + id,

                type: "GET",

                dataType: "json",

                success: function(respon) {

                    $("[name='id2']").val(respon.id_peserta_program_bmfi_asesmen);


             $("[name='nama_peserta2']").val(respon.nama_peserta_program_bmfi_asesmen);


                


                   $("[name='kordinat_latitude_lokasi_usaha2']").val(respon.kordinat_latitude_lokasi_usaha);


                      $("[name='kordinat_longitude_lokasi_usaha2']").val(respon.kordinat_longitude_lokasi_usaha);

              


                          $("[name='lokasi_usaha2']").val(respon.lokasi_usaha);

              

                    //         $("[name='foto_dampak_program1']").val(respon.foto_dampak_program);

                    // $(".foto_dampak_program-src").css('background-image', "url('" + base_url + 'attachments/masjid-musholla/bmfi_asesmen/foto_dampak_program/' + respon.foto_dampak_program + "')");



                    $("#kordinat_lokasi_usaha_peserta_editModal").modal('show');

                }

            })

        });












    
        // $('body').on("click", ".btn-kuesioner_peserta_edit", function(e) {

        //     e.preventDefault();




        //     var id_peserta_program_bmfi_bmm = $(this).attr('id_peserta_program_bmfi_bmm2');





        //     $.ajax({

        //         url: base_url + 'panel/bmfi_survei_peserta_bmm/detail/' + id_peserta_program_bmfi_bmm,

        //         type: "GET",

        //         dataType: "json",

        //         success: function(respon) {

        //             $("[name='id_peserta_program_bmfi_bmm2']").val(respon.id_peserta_program_bmfi_bmm);


        //      // $("[name='nama_peserta2']").val(respon.nama_peserta_program_bmfi_asesmen);


                


        //                $("[name='1_business_duration2']").val(respon['1_business_duration']);


                        
        //                $("[name='2_growth_and_sales2']").val(respon['2_growth_and_sales']);



        //                $("[name='3_customer_demand2']").val(respon['3_customer_demand']);



        //                $("[name='4_business_plan2']").val(respon['4_business_plan']);



        //                $("[name='5_financial_condition2']").val(respon['5_financial_condition']);



        //                $("[name='6_health_issues2']").val(respon['6_health_issues']);



        //                $("[name='7_health_impact2']").val(respon['7_health_impact']);



        //                $("[name='8_stress_impact2']").val(respon['8_stress_impact']);



        //                $("[name='9_support_system2']").val(respon['9_support_system']);



        //  $("[name='10_health_impact_on_repayment2']").val(respon['10_health_impact_on_repayment']);





                


        //             $("#kuesioner_peserta_editModal").modal('show');

        //         }

        //     })

        // });



// $('body').on("click", ".btn-kuesioner_peserta_edit", function(e) {
//     e.preventDefault();

//     var id_peserta_program_bmfi_bmm = $(this).attr('id_peserta_program_bmfi_bmm2');


//      var nama_peserta_program_bmfi_bmm = $(this).attr('nama_peserta_program_bmfi_bmm2');

//     $.ajax({
//         url: base_url + 'panel/bmfi_survei_peserta_bmm/detail/' + id_peserta_program_bmfi_bmm,
//         type: "GET",
//         dataType: "json",
//         success: function(respon) {
//             if (respon) {


//         $("#bmfi_nama_peserta_program_survei2").val(nama_peserta_program_bmfi_bmm);


//                 // If the response contains questionnaire data, populate the form and show the kuesioner modal
//                 $("[name='id_peserta_program_bmfi_bmm2']").val(respon.id_peserta_program_bmfi_bmm);
//                 $("[name='1_business_duration2']").val(respon['1_business_duration']);
//                 $("[name='2_growth_and_sales2']").val(respon['2_growth_and_sales']);
//                 $("[name='3_customer_demand2']").val(respon['3_customer_demand']);
//                 $("[name='4_business_plan2']").val(respon['4_business_plan']);
//                 $("[name='5_financial_condition2']").val(respon['5_financial_condition']);
//                 $("[name='6_health_issues2']").val(respon['6_health_issues']);
//                 $("[name='7_health_impact2']").val(respon['7_health_impact']);
//                 $("[name='8_stress_impact2']").val(respon['8_stress_impact']);
//                 $("[name='9_support_system2']").val(respon['9_support_system']);
//                 $("[name='10_health_impact_on_repayment2']").val(respon['10_health_impact_on_repayment']);
                
//                 $("#kuesioner_peserta_editModal").modal('show');
//             } else {




//         $("#bmfi_peserta_program_survei2").val(id_peserta_program_bmfi_bmm);



//         $("#bmfi_nama_peserta_program_survei2").val(nama_peserta_program_bmfi_bmm);


   

//                 // If no questionnaire data is available, show the transaksi survei modal
//                 $("#add_transaksisurveipesertaModal").modal('show');
//             }
//         }
//     });
// });



$('body').on("click", ".btn-bmm_web_usaha_peserta_edit", function(e) {
    e.preventDefault();

    var idPesertaProgramBmfiBmm = $(this).data('id-peserta-akun-login2');
    var namaPesertaProgramBmfiBmm = $(this).data('nama-peserta-akun-login2');

    $.ajax({
        url: base_url + 'panel/bmfi_bmm_peserta_microfinance/bmm_web_usaha/detail_data_website_bmm_web_usaha/' + idPesertaProgramBmfiBmm,
        type: "GET",
        dataType: "json",
        success: function(respon) {
            if (respon) {
                $("#bmfi_nama_peserta_program_survei2").val(namaPesertaProgramBmfiBmm);

                // Populate the form with response data
                $("[name='id_peserta_program_bmfi_bmm2']").val(respon.id_peserta_program_bmfi_bmm);



                  $("[name='slug_bmm_web_usaha2']").val(respon.slug_bmm_web_usaha);


                 $("[name='nama2']").val(respon.nama_bmm_web_usaha);

                    $("[name='jenis2']").val(respon.jenis_bmm_web_usaha);



                   $("[name='background_warna_website2']").val(respon.background_warna_website);

                    $("[name='warna_tulisan_website2']").val(respon.warna_tulisan_website);

               

                // Show edit modal
                $("#bmm_web_usaha_peserta_editModal").modal('show');

               
            } else {
                $("#id_peserta_bmm_web_usaha2").val(idPesertaProgramBmfiBmm);
                $("#nama_peserta_bmm_web_usaha2").val(namaPesertaProgramBmfiBmm);

                // If no questionnaire data is available, show the survey transaction modal
                $("#add_bmm_web_usaha_peserta_addModal").modal('show');
            }
        }
    });
});



$('body').on("click", ".btn-akun_login_peserta_edit", function(e) {
    e.preventDefault();

    // Mengambil data dari atribut data-*
    var idPesertaProgramBmfiBmm = $(this).data('id-peserta-akun-login2');
    var namaPesertaProgramBmfiBmm = $(this).data('nama-peserta-akun-login2');

     var nohpPesertaProgramBmfiBmm = $(this).data('no_hp-peserta-akun-login2');

    $.ajax({
        // Menggunakan variabel yang benar di URL
        url: base_url + 'panel/bmfi_bmm_peserta_microfinance/bmm_user_login_peserta/detail_data_akun_login_peserta/' + idPesertaProgramBmfiBmm,
        type: "GET",
        dataType: "json",
        success: function(respon) {
            if (respon) {
                $("#bmfi_nama_peserta_program_survei2").val(namaPesertaProgramBmfiBmm);

                // Mengisi form dengan data dari respon
                $("[name='id_peserta_program_bmfi_bmm2']").val(respon.id_peserta_program_bmfi_bmm);
                $("[name='nama_peserta_program_bmfi_bmm2']").val(respon.nama_peserta_program_bmfi_bmm);
                $("[name='jenis_kelamin2']").val(respon.jenis_kelamin);
                $("[name='status2']").val(respon.status_aktif);
                $("[name='alamat2']").val(respon.alamat);
                $("[name='no_hp2']").val(respon.no_hp);
                $("[name='email2']").val(respon.email);
                $("[name='lainnya2']").val(respon.catatan);

                // Menampilkan modal edit
                $("#akun_login_peserta_editModal").modal('show');
            } else {
                // Jika respon kosong, tampilkan modal alternatif
                $("#id_peserta_akun_login2").val(idPesertaProgramBmfiBmm);
                $("#nama_peserta_akun_login2").val(namaPesertaProgramBmfiBmm);

                $("#no_hp_peserta_akun_login2").val(nohpPesertaProgramBmfiBmm);

                // Menampilkan modal alternatif
                $("#add_akun_login_peserta_addModal").modal('show');
            }
        },
        error: function() {
            alert("Terjadi kesalahan saat mengambil data.");
        }
    });
});













$('body').on("click", ".btn-kuesioner_peserta_edit", function(e) {
    e.preventDefault();

    var id_peserta_program_bmfi_bmm = $(this).attr('id_peserta_program_bmfi_bmm2');
    var nama_peserta_program_bmfi_bmm = $(this).attr('nama_peserta_program_bmfi_bmm2');

    $.ajax({
        url: base_url + 'panel/bmfi_survei_peserta_bmm/detail/' + id_peserta_program_bmfi_bmm,
        type: "GET",
        dataType: "json",
        success: function(respon) {
            if (respon) {
                $("#bmfi_nama_peserta_program_survei2").val(nama_peserta_program_bmfi_bmm);

                // Populate the form with response data
                $("[name='id_peserta_program_bmfi_bmm2']").val(respon.id_peserta_program_bmfi_bmm);
                $("[name='1_business_duration2']").val(respon['1_business_duration']);
                $("[name='2_growth_and_sales2']").val(respon['2_growth_and_sales']);
                $("[name='3_customer_demand2']").val(respon['3_customer_demand']);
                $("[name='4_business_plan2']").val(respon['4_business_plan']);
                $("[name='5_financial_condition2']").val(respon['5_financial_condition']);
                $("[name='6_health_issues2']").val(respon['6_health_issues']);
                $("[name='7_health_impact2']").val(respon['7_health_impact']);
                $("[name='8_stress_impact2']").val(respon['8_stress_impact']);
                $("[name='9_support_system2']").val(respon['9_support_system']);
                $("[name='10_health_impact_on_repayment2']").val(respon['10_health_impact_on_repayment']);

                $("#kuesioner_peserta_editModal").modal('show');

                // Analyze results and display
                analyzeResults(respon);
            } else {
                $("#bmfi_peserta_program_survei2").val(id_peserta_program_bmfi_bmm);
                $("#bmfi_nama_peserta_program_survei2").val(nama_peserta_program_bmfi_bmm);

                // If no questionnaire data is available, show the transaksi survei modal
                $("#add_transaksisurveipesertaModal").modal('show');
            }
        }
    });

    function analyzeResults(respon) {
        const scores = {
            businessDuration: parseInt(respon['1_business_duration']),
            growthAndSales: parseInt(respon['2_growth_and_sales']),
            customerDemand: parseInt(respon['3_customer_demand']),
            businessPlan: parseInt(respon['4_business_plan']),
            financialCondition: parseInt(respon['5_financial_condition']),
            healthIssues: parseInt(respon['6_health_issues']),
            healthImpact: parseInt(respon['7_health_impact']),
            stressImpact: parseInt(respon['8_stress_impact']),
            supportSystem: parseInt(respon['9_support_system']),
            healthImpactOnRepayment: parseInt(respon['10_health_impact_on_repayment']),
        };

        const averageScore = (
            (scores.businessDuration + 
            scores.growthAndSales + 
            scores.customerDemand + 
            scores.businessPlan + 
            scores.financialCondition + 
            scores.healthIssues + 
            scores.healthImpact + 
            scores.stressImpact + 
            scores.supportSystem + 
            scores.healthImpactOnRepayment) / 10
        ).toFixed(1);

        let resultText = '';

        if (averageScore >= 4) {
            resultText = 'Usaha Anda menunjukkan kualitas yang sangat baik dengan dampak kesehatan yang minimal.';
        } else if (averageScore >= 3) {
            resultText = `Usaha Anda menunjukkan kualitas yang baik dengan beberapa area yang mungkin memerlukan perhatian. Berikut adalah beberapa area yang perlu diperhatikan:



            1. **Pertumbuhan & Penjualan**: Pertumbuhan dan peningkatan penjualan Anda tergolong baik, namun ada ruang untuk meningkatkan persentase pertumbuhan. Pertimbangkan untuk mengevaluasi strategi pemasaran dan penjualan.

2. **Kondisi Keuangan**: Kondisi keuangan Anda stabil, tetapi perlu diwaspadai potensi risiko keuangan. Menjaga pengelolaan keuangan dan menyiapkan cadangan darurat bisa membantu.

3. **Permintaan Pelanggan**: Anda mungkin mengalami beberapa kesulitan dalam memenuhi permintaan pelanggan. Pertimbangkan untuk meningkatkan kapasitas produksi atau pelayanan.

4. **Rencana Bisnis**: Rencana bisnis Anda sudah ada, tetapi bisa lebih detail. Menyusun rencana yang lebih komprehensif dapat membantu dalam perencanaan jangka panjang.

5. **Dukungan Sistem**: Dukungan dari keluarga, mitra, atau teman Anda baik, tetapi pertimbangkan untuk memperluas jaringan dukungan atau mendapatkan bimbingan profesional.

6. **Dampak Kesehatan**: Kesehatan fisik Anda mempengaruhi produktivitas. Meningkatkan perhatian terhadap kesehatan dapat meningkatkan performa bisnis.

7. **Stres dan Kecemasan**: Anda mungkin merasa stres atau cemas. Pertimbangkan teknik relaksasi atau mendapatkan bantuan profesional jika diperlukan.

8. **Gangguan Kesehatan pada Pembayaran Angsuran**: Jika gangguan kesehatan mempengaruhi kemampuan membayar angsuran, cari solusi atau dukungan keuangan tambahan.`; // Add more details as needed



        } else if (averageScore >= 2) {
            resultText = 'Usaha Anda menunjukkan kualitas yang cukup, tetapi ada beberapa area yang perlu ditingkatkan untuk mencapai hasil yang lebih baik. Pertimbangkan untuk memperbaiki aspek-aspek seperti pertumbuhan penjualan, kondisi keuangan, dan dukungan sistem.';


        } else {
            resultText = 'Usaha Anda mungkin mengalami beberapa masalah dalam kualitas dan dampak kesehatan, dan perlu perhatian lebih lanjut. Evaluasi lebih mendalam pada semua aspek, termasuk strategi bisnis, kondisi keuangan, dan kesejahteraan pribadi, sangat disarankan.';
        }

        document.getElementById('resultText_kuesioner').innerText = resultText;
        document.querySelector('.results').style.display = 'block';

        // Generate Radar Chart
        const ctx = document.getElementById('radarChart').getContext('2d');
        const radarChart = new Chart(ctx, {
            type: 'radar',
            data: {
                labels: ['Durasi Usaha', 'Pertumbuhan & Penjualan', 'Permintaan Pelanggan', 'Rencana Bisnis', 'Kondisi Keuangan', 'Masalah Kesehatan', 'Dampak Kesehatan', 'Dampak Stres', 'Dukungan Sistem', 'Dampak Kesehatan pada Pembayaran'],
                datasets: [{
                    label: 'Skor',
                    data: [
                        scores.businessDuration,
                        scores.growthAndSales,
                        scores.customerDemand,
                        scores.businessPlan,
                        scores.financialCondition,
                        scores.healthIssues,
                        scores.healthImpact,
                        scores.stressImpact,
                        scores.supportSystem,
                        scores.healthImpactOnRepayment
                    ],
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    r: {
                        angleLines: { display: false },
                        suggestedMin: 1,
                        suggestedMax: 5
                    }
                }
            }
        });
    }
});








$('body').on("click", ".btn-psikotes_peserta_edit", function(e) {
    e.preventDefault();

    var id_peserta_program_bmfi_bmm = $(this).attr('id_peserta_program_bmfi_bmm2');
    var nama_peserta_program_bmfi_bmm = $(this).attr('nama_peserta_program_bmfi_bmm2');

    $.ajax({
        url: base_url + 'panel/bmfi_psikotes_peserta_bmm/detail/' + id_peserta_program_bmfi_bmm,
        type: "GET",
        dataType: "json",
        success: function(respon) {
            if (respon) {
                $("#bmfi_nama_peserta_program_psikotes2").val(nama_peserta_program_bmfi_bmm);

                // Populate the form with response data
                $("[name='id_peserta_program_bmfi_bmm2']").val(respon.id_peserta_program_bmfi_bmm);
                $("[name='1_question2']").val(respon['1_question']);
                $("[name='2_question2']").val(respon['2_question']);
                $("[name='3_question2']").val(respon['3_question']);
                $("[name='4_question2']").val(respon['4_question']);
                $("[name='5_question2']").val(respon['5_question']);
                $("[name='6_question2']").val(respon['6_question']);
                $("[name='7_question2']").val(respon['7_question']);
                $("[name='8_question2']").val(respon['8_question']);
                $("[name='9_question2']").val(respon['9_question']);
                $("[name='10_question2']").val(respon['10_question']);
                 $("[name='11_question2']").val(respon['11_question']);
                $("[name='12_question2']").val(respon['12_question']);

                $("#psikotes_peserta_editModal").modal('show');

                // Analyze results and display
                analyzeResults(respon);
            } else {
                $("#bmfi_peserta_program_psikotes2").val(id_peserta_program_bmfi_bmm);
                $("#bmfi_nama_peserta_program_psikotes2").val(nama_peserta_program_bmfi_bmm);

                // If no questionnaire data is available, show the transaksi psikotes modal
                $("#add_transaksipsikotespesertaModal").modal('show');
            }
        }
    });



function analyzeResultsMBTI(respon) {
    // Contoh analisis data MBTI
    const mbtiTypes = {
        E: parseInt(respon['E_score']),
        I: parseInt(respon['I_score']),
        S: parseInt(respon['S_score']),
        N: parseInt(respon['N_score']),
        T: parseInt(respon['T_score']),
        F: parseInt(respon['F_score']),
        J: parseInt(respon['J_score']),
        P: parseInt(respon['P_score']),
    };

    // Menentukan tipe MBTI berdasarkan skor
    const typeEI = mbtiTypes.E > mbtiTypes.I ? 'E' : 'I';
    const typeSN = mbtiTypes.S > mbtiTypes.N ? 'S' : 'N';
    const typeTF = mbtiTypes.T > mbtiTypes.F ? 'T' : 'F';
    const typeJP = mbtiTypes.J > mbtiTypes.P ? 'J' : 'P';

    const mbtiResult = typeEI + typeSN + typeTF + typeJP;

    const explanations = {
        'ISTJ': {
            type: 'ISTJ',
            description: 'ISTJ adalah tipe yang logis, fokus pada detail, dan memiliki rasa tanggung jawab yang tinggi.',
            EI: 'Introverted - ISTJ cenderung lebih fokus pada pemikiran internal dan energi dari waktu sendiri.',
            SN: 'Sensing - ISTJ lebih suka informasi konkret dan nyata.',
            TF: 'Thinking - ISTJ membuat keputusan berdasarkan logika dan fakta.',
            JP: 'Judging - ISTJ lebih suka merencanakan segala sesuatu dengan baik sebelumnya.',
            careers: 'Beberapa karir yang cocok untuk ISTJ meliputi akuntan, analis keuangan, atau administrator bisnis.',
            entrepreneurship: 'Sebagai seorang ISTJ yang cermat dan terorganisir, Anda mungkin sukses dalam memulai bisnis konsultan manajemen atau agensi perjalanan yang membutuhkan perencanaan detail dan pengelolaan yang ketat.'
        },
        // Tambahkan tipe MBTI lainnya di sini...


        'ISFJ': {
        type: 'ISFJ',
        description: 'ISFJ adalah tipe yang peduli pada orang lain, teliti, dan sangat loyal.',
        EI: 'Introverted - ISFJ cenderung lebih fokus pada pemikiran internal dan energi dari waktu sendiri.',
        SN: 'Sensing - ISFJ lebih suka informasi konkret dan nyata.',
        TF: 'Feeling - ISFJ membuat keputusan berdasarkan nilai-nilai pribadi dan emosi mereka sendiri.',
        JP: 'Judging - ISFJ lebih suka merencanakan segala sesuatu dengan baik sebelumnya.',
        careers: 'Beberapa karir yang cocok untuk ISFJ meliputi guru, perawat, atau administrator kesehatan.',
        entrepreneurship: 'Sebagai seorang ISFJ yang peduli dan teliti, Anda mungkin sukses dalam memulai bisnis daycare atau layanan kebersihan yang menawarkan perhatian khusus pada kualitas pelayanan dan kepercayaan.'
    },
    'INFJ': {
        type: 'INFJ',
        description: 'INFJ adalah tipe yang berwawasan luas, idealis, dan sangat berempati.',
        EI: 'Introverted - INFJ cenderung lebih fokus pada pemikiran internal dan energi dari waktu sendiri.',
        SN: 'Intuitive - INFJ lebih tertarik pada konsep abstrak, ide-ide besar, dan makna yang tersembunyi.',
        TF: 'Feeling - INFJ membuat keputusan berdasarkan nilai-nilai pribadi dan emosi mereka sendiri.',
        JP: 'Judging - INFJ lebih suka merencanakan segala sesuatu dengan baik sebelumnya.',
        careers: 'Beberapa karir yang cocok untuk INFJ meliputi psikolog, penasihat karir, atau pengembang program sosial.',
        entrepreneurship: 'Sebagai seorang INFJ yang berwawasan luas dan idealis, Anda mungkin sukses dalam memulai bisnis kreatif seperti desain grafis atau pendidikan alternatif yang mempromosikan nilai-nilai yang Anda yakini.'
    },
    'INTJ': {
        type: 'INTJ',
        description: 'INTJ adalah tipe yang analitis, berpikiran strategis, dan memiliki visi yang kuat.',
        EI: 'Introverted - INTJ cenderung lebih fokus pada pemikiran internal dan energi dari waktu sendiri.',
        SN: 'Intuitive - INTJ lebih tertarik pada konsep abstrak, ide-ide besar, dan makna yang tersembunyi.',
        TF: 'Thinking - INTJ membuat keputusan berdasarkan logika dan fakta.',
        JP: 'Judging - INTJ lebih suka merencanakan segala sesuatu dengan baik sebelumnya.',
        careers: 'Beberapa karir yang cocok untuk INTJ meliputi ilmuwan, arsitek, atau pengembang perangkat lunak.',
        entrepreneurship: 'Sebagai seorang INTJ yang analitis dan berpikiran strategis, Anda mungkin sukses dalam memulai bisnis teknologi atau konsultan strategi yang mengandalkan visi jangka panjang dan solusi inovatif.'
    },
    'ISTP': {
        type: 'ISTP',
        description: 'ISTP adalah tipe yang praktis, suka memecahkan masalah, dan mandiri.',
        EI: 'Introverted - ISTP cenderung lebih fokus pada pemikiran internal dan energi dari waktu sendiri.',
        SN: 'Sensing - ISTP lebih suka informasi konkret dan nyata.',
        TF: 'Thinking - ISTP membuat keputusan berdasarkan logika dan fakta.',
        JP: 'Perceiving - ISTP lebih fleksibel dalam menghadapi situasi dan terbuka terhadap perubahan.',
        careers: 'Beberapa karir yang cocok untuk ISTP meliputi insinyur, teknisi, atau pilot.',
        entrepreneurship: 'Sebagai seorang ISTP yang praktis dan mandiri, Anda mungkin sukses dalam memulai bisnis teknologi atau layanan teknis yang menawarkan solusi cepat dan efisien untuk masalah teknis.'
    },
    'ISFP': {
        type: 'ISFP',
        description: 'ISFP adalah tipe yang kreatif, peduli pada keharmonisan, dan fleksibel.',
        EI: 'Introverted - ISFP cenderung lebih fokus pada pemikiran internal dan energi dari waktu sendiri.',
        SN: 'Sensing - ISFP lebih suka informasi konkret dan nyata.',
        TF: 'Feeling - ISFP membuat keputusan berdasarkan nilai-nilai pribadi dan emosi mereka sendiri.',
        JP: 'Perceiving - ISFP lebih fleksibel dalam menghadapi situasi dan terbuka terhadap perubahan.',
        careers: 'Beberapa karir yang cocok untuk ISFP meliputi seniman, desainer grafis, atau terapis.',
        entrepreneurship: 'Sebagai seorang ISFP yang kreatif dan fleksibel, Anda mungkin sukses dalam memulai bisnis kreatif seperti seni rupa atau desain yang menekankan estetika dan ekspresi pribadi.'
    },
     'INFP': {
        type: 'INFP',
        description: 'INFP adalah tipe yang idealis, penuh inspirasi, dan sangat menghargai nilai-nilai pribadi.',
        EI: 'Introverted - INFP cenderung lebih fokus pada pemikiran internal dan energi dari waktu sendiri.',
        SN: 'Intuitive - INFP lebih tertarik pada konsep abstrak, ide-ide besar, dan makna yang tersembunyi.',
        TF: 'Feeling - INFP membuat keputusan berdasarkan nilai-nilai pribadi dan emosi mereka sendiri.',
        JP: 'Perceiving - INFP lebih fleksibel dalam menghadapi situasi dan terbuka terhadap perubahan.',
        careers: 'Beberapa karir yang cocok untuk INFP meliputi penulis, konselor, atau advokat hak asasi manusia.',
        entrepreneurship: 'Sebagai seorang INFP yang idealis dan kreatif, Anda mungkin sukses dalam memulai bisnis yang berkaitan dengan seni kreatif, seperti studio seni atau agensi kreatif yang menyediakan layanan konseling atau advokasi untuk komunitas tertentu.'
    },
    'INTP': {
        type: 'INTP',
        description: 'INTP adalah tipe yang teoretis, inovatif, dan memiliki rasa ingin tahu yang tinggi.',
        EI: 'Introverted - INTP cenderung lebih fokus pada pemikiran internal dan energi dari waktu sendiri.',
        SN: 'Intuitive - INTP lebih tertarik pada konsep abstrak, ide-ide besar, dan makna yang tersembunyi.',
        TF: 'Thinking - INTP membuat keputusan berdasarkan logika dan fakta.',
        JP: 'Perceiving - INTP lebih fleksibel dalam menghadapi situasi dan terbuka terhadap perubahan.',
        careers: 'Beberapa karir yang cocok untuk INTP meliputi ilmuwan komputer, filosof, atau analis data.',
        entrepreneurship: 'Sebagai seorang INTP yang analitis dan inovatif, Anda mungkin sukses dalam memulai bisnis teknologi atau konsultasi yang menawarkan solusi kreatif dan teknologi untuk permasalahan yang rumit.'
    },
    'ESFP': {
        type: 'ESFP',
        description: 'ESFP adalah tipe yang ramah, spontan, dan suka berinteraksi dengan orang lain.',
        EI: 'Extraverted - ESFP cenderung lebih fokus pada interaksi sosial dan mendapatkan energi dari aktivitas di luar.',
        SN: 'Sensing - ESFP lebih suka informasi konkret dan nyata.',
        TF: 'Feeling - ESFP membuat keputusan berdasarkan nilai-nilai pribadi dan emosi mereka sendiri.',
        JP: 'Perceiving - ESFP lebih fleksibel dalam menghadapi situasi dan terbuka terhadap perubahan.',
        careers: 'Beberapa karir yang cocok untuk ESFP meliputi entertainer, perancang interior, atau perawat.',
        entrepreneurship: 'Sebagai seorang ESFP yang ramah dan bersemangat, Anda mungkin sukses dalam memulai bisnis yang berorientasi pada layanan, seperti restoran atau agensi perjalanan yang menawarkan pengalaman unik kepada pelanggan.'
    },
    'ENTP': {
        type: 'ENTP',
        description: 'ENTP adalah tipe yang kreatif, suka debat, dan memiliki pemikiran yang cepat.',
        EI: 'Extraverted - ENTP cenderung lebih fokus pada interaksi sosial dan mendapatkan energi dari aktivitas di luar.',
        SN: 'Intuitive - ENTP lebih tertarik pada konsep abstrak, ide-ide besar, dan makna yang tersembunyi.',
        TF: 'Thinking - ENTP membuat keputusan berdasarkan logika dan fakta.',
        JP: 'Perceiving - ENTP lebih fleksibel dalam menghadapi situasi dan terbuka terhadap perubahan.',
        careers: 'Beberapa karir yang cocok untuk ENTP meliputi pengacara, arsitek, atau wirausahawan.',
        entrepreneurship: 'Sebagai seorang ENTP yang kreatif dan analitis, Anda mungkin sukses dalam memulai bisnis yang berfokus pada inovasi dan solusi baru, seperti start-up teknologi atau agensi kreatif yang menawarkan ide-ide baru untuk pasar yang berkembang.'
    },
    'ESTJ': {
        type: 'ESTJ',
        description: 'ESTJ adalah tipe yang terorganisir, tegas, dan memiliki kemampuan memimpin yang baik.',
        EI: 'Extraverted - ESTJ cenderung lebih fokus pada interaksi sosial dan mendapatkan energi dari aktivitas di luar.',
        SN: 'Sensing - ESTJ lebih suka informasi konkret dan nyata.',
        TF: 'Thinking - ESTJ membuat keputusan berdasarkan logika dan fakta.',
        JP: 'Judging - ESTJ lebih suka merencanakan segala sesuatu dengan baik sebelumnya.',
        careers: 'Beberapa karir yang cocok untuk ESTJ meliputi eksekutif perusahaan, administrator, atau inspektur.',
        entrepreneurship: 'Sebagai seorang ESTJ yang terorganisir dan tegas, Anda mungkin sukses dalam memulai bisnis yang membutuhkan kepemimpinan yang kuat, seperti konsultan manajemen atau agensi pemasaran yang berfokus pada hasil dan efisiensi.'
    },
    'ESFJ': {
        type: 'ESFJ',
        description: 'ESFJ adalah tipe yang ramah, bertanggung jawab, dan sangat peduli pada orang lain.',
        EI: 'Extraverted - ESFJ cenderung lebih fokus pada interaksi sosial dan mendapatkan energi dari aktivitas di luar.',
        SN: 'Sensing - ESFJ lebih suka informasi konkret dan nyata.',
        TF: 'Feeling - ESFJ membuat keputusan berdasarkan nilai-nilai pribadi dan emosi mereka sendiri.',
        JP: 'Judging - ESFJ lebih suka merencanakan segala sesuatu dengan baik sebelumnya.',
        careers: 'Beberapa karir yang cocok untuk ESFJ meliputi konsultan pendidikan, administrator HR, atau pelatih.',
        entrepreneurship: 'Sebagai seorang ESFJ yang bertanggung jawab dan peduli pada orang lain, Anda mungkin sukses dalam memulai bisnis yang berfokus pada layanan sosial atau pendidikan, seperti agensi rekrutmen atau organisasi nirlaba yang mendukung kesejahteraan masyarakat.'
    },
    'ENFJ': {
        type: 'ENFJ',
        description: 'ENFJ adalah tipe yang karismatik, memiliki kemampuan komunikasi yang baik, dan sangat peduli pada hubungan antar manusia.',
        EI: 'Extraverted - ENFJ cenderung lebih fokus pada interaksi sosial dan mendapatkan energi dari aktivitas di luar.',
        SN: 'Intuitive - ENFJ lebih tertarik pada konsep abstrak, ide-ide besar, dan makna yang tersembunyi.',
        TF: 'Feeling - ENFJ membuat keputusan berdasarkan nilai-nilai pribadi dan emosi mereka sendiri.',
        JP: 'Judging - ENFJ lebih suka merencanakan segala sesuatu dengan baik sebelumnya.',
        careers: 'Beberapa karir yang cocok untuk ENFJ meliputi guru, terapis, atau manajer acara.',
        entrepreneurship: 'Sebagai seorang ENFJ yang karismatik dan peduli pada hubungan, Anda mungkin sukses dalam memulai bisnis yang berorientasi pada layanan manusia, seperti klinik kesehatan mental atau agensi perencanaan acara yang menawarkan pengalaman berharga kepada klien.'
    },
    'ENTJ': {
        type: 'ENTJ',
        description: 'ENTJ adalah tipe yang tegas, berpikiran strategis, dan memiliki kemampuan memimpin yang kuat.',
        EI: 'Extraverted - ENTJ cenderung lebih fokus pada interaksi sosial dan mendapatkan energi dari aktivitas di luar.',
        SN: 'Intuitive - ENTJ lebih tertarik pada konsep abstrak, ide-ide besar, dan makna yang tersembunyi.',
        TF: 'Thinking - ENTJ membuat keputusan berdasarkan logika dan fakta.',
        JP: 'Judging - ENTJ lebih suka merencanakan segala sesuatu dengan baik sebelumnya.',
        careers: 'Beberapa karir yang cocok untuk ENTJ meliputi CEO, pengacara, atau konsultan strategi.',
        entrepreneurship: 'Sebagai seorang ENTJ yang tegas dan berorientasi pada hasil, Anda mungkin sukses dalam memulai bisnis besar atau konsultan manajemen yang menawarkan solusi strategis dan efektif untuk klien korporat.'
    }


    };

    let resultText_psikotes = `Hasil MBTI Anda adalah ${mbtiResult}.`;

    if (explanations[mbtiResult]) {
        const explanation = explanations[mbtiResult];
        resultText_psikotes += `\n\n${explanation.description}\n\n${explanation.EI}\n${explanation.SN}\n${explanation.TF}\n${explanation.JP}`;
        resultText_psikotes += `\n\nKarier yang cocok: ${explanation.careers}`;
        resultText_psikotes += `\n\nEntrepreneurship: ${explanation.entrepreneurship}`;
    } else {
        resultText_psikotes += ' Anda memiliki kepribadian yang unik dengan potensi besar untuk berbagai peran dan situasi.';
    }

    document.getElementById('resultText_psikotes').innerText = resultText_psikotes;
    document.querySelector('.results').style.display = 'block';

    // Membuat Radar Chart untuk MBTI
    const ctxpsikotes = document.getElementById('radarChartPsikotes').getContext('2d');
    const radarpsikotes = new Chart(ctxpsikotes, {
        type: 'radar',
        data: {
            labels: ['Extraversion', 'Introversion', 'Sensing', 'Intuition', 'Thinking', 'Feeling', 'Judging', 'Perceiving'],
            datasets: [{
                label: 'Skor',
                data: [
                    mbtiTypes.E,
                    mbtiTypes.I,
                    mbtiTypes.S,
                    mbtiTypes.N,
                    mbtiTypes.T,
                    mbtiTypes.F,
                    mbtiTypes.J,
                    mbtiTypes.P
                ],
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                r: {
                    angleLines: { display: false },
                    suggestedMin: 0,
                    suggestedMax: 100
                }
            }
        }
    });
}


});












        $('body').on("click", ".btn-kordinat_lokasi_rumah_peserta_edit", function(e) {

            e.preventDefault();




            var id = $(this).attr('id');





            $.ajax({

                url: base_url + 'panel/bmfi_peserta_program/detail/' + id,

                type: "GET",

                dataType: "json",

                success: function(respon) {

                    $("[name='id2']").val(respon.id_peserta_program_bmfi_asesmen);


             $("[name='nama_peserta2']").val(respon.nama_peserta_program_bmfi_asesmen);


                


                   $("[name='kordinat_latitude_rumah2']").val(respon.kordinat_latitude_rumah);


                      $("[name='kordinat_longitude_rumah2']").val(respon.kordinat_longitude_rumah);

              


                          $("[name='alamat_rumah2']").val(respon.alamat_rumah);

              

                    //         $("[name='foto_dampak_program1']").val(respon.foto_dampak_program);

                    // $(".foto_dampak_program-src").css('background-image', "url('" + base_url + 'attachments/masjid-musholla/bmfi_asesmen/foto_dampak_program/' + respon.foto_dampak_program + "')");



                    $("#kordinat_lokasi_rumah_peserta_editModal").modal('show');

                }

            })

        });








        $('body').on("click", ".btn-hapus", function(e) {

            e.preventDefault();

            $("#hiddenid").val($(this).attr('id_peserta_program_bmfi_asesmen'));

            $("#deleteModal").modal('show');

        });



        $('body').on("click", ".btn-konfirmasi-hapus", function(e) {

            e.preventDefault();

            $.ajax({

                url: base_url + 'panel/bmfi_peserta_program/delete/' + hiddenid.value,

                type: "GET",

                success: function(respon) {

                    $("#deleteModal").modal('hide');

                    toastr.success('Data berhasil dihapus.');

                    $(".datatables").DataTable().ajax.reload();

                    $("#tabel_saldo_microfinance").DataTable().ajax.reload();

                }

            })

        });



        $('#peserta_program_bmfi_asesmenFormAdd').validate({

            rules: {

                nama: {

                    required: true,

                },


                 no_ktp: {

                    required: true,

                },



                 no_hp: {

                    required: true,

                },



                  jenis_kelamin: {

                    required: true,

                },



                  tempat_tanggal_lahir: {

                    required: true,

                },



                  alamat_rumah: {

                    required: true,

                },


                  nama_usaha: {

                    required: true,

                },



                  lokasi_usaha: {

                    required: true,

                },




                   tgl_registrasi_peserta: {

                    required: true,

                },



              

                // foto_profil: {

                //     required: true,

                //     extension: 'jpg|jpeg|gif|png'

                // },




            },

            messages: {

                nama: {

                    required: "Nama Peserta Program Microfinance tidak boleh kosong",

                },


                  no_ktp: {

                    required: "No KTP tidak boleh kosong",

                },



               no_hp: {

                    required: "No Handphone tidak boleh kosong",

                },


                 jenis_kelamin: {

                    required: "Jenis Kelamin tidak boleh kosong",

                },


                 tempat_tanggal_lahir: {

                    required: "Tempat Tanggal Lahir tidak boleh kosong",

                },


                 alamat_rumah: {

                    required: "Alamat Rumah tidak boleh kosong",

                },


                 nama_usaha: {

                    required: "Nama Usaha tidak boleh kosong",

                },



                 lokasi_usaha: {

                    required: "Lokasi Usaha tidak boleh kosong",

                },



                 tgl_registrasi_peserta: {

                    required: "Tgl Registrasi Peserta tidak boleh kosong",

                },


                // foto_profil: {

                //     required: "foto_profil tidak boleh kosong",

                //     extension: "Format tidak sesuai"

                // },

            },

            errorElement: 'span',

            errorPlacement: function(error, element) {

                error.addClass('invalid-feedback');

                element.closest('.form-group').append(error);

            },

            highlight: function(element, errorClass, validClass) {

                $(element).addClass('is-invalid');

            },

            unhighlight: function(element, errorClass, validClass) {

                $(element).removeClass('is-invalid');

            },

            submitHandler: function(form) {

                var dataForm = new FormData(form);

                $.ajax({

                    url: base_url + 'panel/bmfi_peserta_program/add',

                    type: "POST",

                    dataType: "json",

                    data: dataForm,

                    processData: false,

                    contentType: false,

                    beforeSend: function() {

                        $(".loader").show();

                        $("#btn-add").addClass('disabled');

                    },

                    success: function(respon) {

                        $(".loader").hide();

                        $("#btn-add").removeClass('disabled');

                        $("#peserta_program_bmfi_asesmenFormAdd")[0].reset();

                        CKEDITOR.instances['keterangan'].setData('');

                        $("#addModal").modal('hide');

                        toastr.success('Data berhasil disimpan.');

                        $(".datatables").DataTable().ajax.reload();

                        $("#tabel_saldo_microfinance").DataTable().ajax.reload();

                    }

                })

            }

        });






        $('#bmfi_transaksi_pengembalianFormAdd').validate({

            rules: {


                 tanggal: {

                    required: true,

                },



                 nominal_pengembalian: {

                    required: true,

                },



                 keterangan: {

                    required: true,

                },

                

            },

            messages: {



                 tanggal: {

                    required: "Tanggal Pengembalian tidak boleh kosong",

                },


                  nominal_pengembalian: {

                    required: "Nominal Pengembalian tidak boleh kosong",

                },




                 keterangan: {

                    required: "Keterangan tidak boleh kosong",

                },




            },

            errorElement: 'span',

            errorPlacement: function(error, element) {

                error.addClass('invalid-feedback');

                element.closest('.form-group').append(error);

            },

            highlight: function(element, errorClass, validClass) {

                $(element).addClass('is-invalid');

            },

            unhighlight: function(element, errorClass, validClass) {

                $(element).removeClass('is-invalid');

            },


            
 submitHandler: function(form) {

                var dataForm = new FormData(form);



                $.ajax({

                    url: base_url + 'panel/bmfi_peserta_program/add_transaksi_pengembalian',

                    type: "POST",

                    dataType: "json",

                    data: dataForm,

                    processData: false,

                    contentType: false,

                    beforeSend: function() {

                        $(".loader").show();

                        $("#btn-add_transaksi_pengembalian").addClass('disabled');

                    },

                    success: function(respon) {

                        $(".loader").hide();

                        $("#btn-add_transaksi_pengembalian").removeClass('disabled');

                        $("#bmfi_transaksi_pengembalianFormAdd")[0].reset();

                        CKEDITOR.instances['keterangan'].setData('');

                        $("#add_transaksiModal").modal('hide');

                        toastr.success('Data berhasil disimpan.');

                        $(".datatables").DataTable().ajax.reload();

                        $("#tabel_saldo_microfinance").DataTable().ajax.reload();

                    }

                })

            }

        });








        $('#bmfi_transaksi_peminjamanFormAdd').validate({

            rules: {


                  tgl: {

                    required: true,

                },



                  bmfi_kategori_asesmen: {

                    required: true,

                },


                 judul: {

                    required: true,

                },
                

                 pendamping_bmfi_asesmen: {

                    required: true,

                },



                  nominal_pilihan_skema_pinjaman: {

                    required: true,

                },




                 bmfi_jenis_angsuran: {

                    required: true,

                },


                 bmfi_anggaran_microfinance: {

                    required: true,

                },


                keterangan: {

                    required: true,

                },


                

                // foto: {

                //     required: true,

                //     extension: 'jpg|jpeg|gif|png'

                // },


            },

            messages: {


                 tgl: {

                    required: "Tanggal Asesmen tidak boleh kosong",

                },




                bmfi_kategori_asesmen: {

                    required: "Kategori Asesmen Harus di Pilih",

                },



                 judul: {

                    required: "Judul Asesmen tidak boleh kosong",

                },



                pendamping_bmfi_asesmen: {

                    required: "Pendamping Asesmen Harus di Pilih",

                },


                 nominal_pilihan_skema_pinjaman: {

                    required: "Pilihan Jumlah Pinjaman Harus di Pilih",

                },



                bmfi_jenis_angsuran: {

                    required: "Jenis Angsuran Harus di Pilih",

                },



                  bmfi_anggaran_microfinance: {

                    required: "Saldo Anggaran Harus di Pilih",

                },



                 keterangan: {

                    required: "Keterangan tidak boleh kosong",

                },




                //   foto: {

                //     required: "Foto Usaha tidak boleh kosong",

                //     extension: "Format tidak sesuai"

                // },



            },

            errorElement: 'span',

            errorPlacement: function(error, element) {

                error.addClass('invalid-feedback');

                element.closest('.form-group').append(error);

            },

            highlight: function(element, errorClass, validClass) {

                $(element).addClass('is-invalid');

            },

            unhighlight: function(element, errorClass, validClass) {

                $(element).removeClass('is-invalid');

            },


            
 submitHandler: function(form) {

                var dataForm = new FormData(form);



                $.ajax({

                    url: base_url + 'panel/bmfi_peserta_program/add_transaksi_peminjaman',

                    type: "POST",

                    dataType: "json",

                    data: dataForm,

                    processData: false,

                    contentType: false,

                    beforeSend: function() {

                        $(".loader").show();

                        $("#btn-add_transaksi_peminjaman").addClass('disabled');

                    },

                    success: function(respon) {

                        $(".loader").hide();

                        $("#btn-add_transaksi_peminjaman").removeClass('disabled');

                        $("#bmfi_transaksi_peminjamanFormAdd")[0].reset();

                        CKEDITOR.instances['keterangan'].setData('');

                        $("#add_transaksipeminjamanModal").modal('hide');

                        toastr.success('Data berhasil disimpan.');

                        $(".datatables").DataTable().ajax.reload();

                        $("#tabel_saldo_microfinance").DataTable().ajax.reload();

                    }

                })

            }

        });








        $('#bmfi_bmm_web_usaha_peserta_addFormAdd').validate({

            rules: {




                //  '1_business_duration': {

                //     required: true,

                // },
                

               

              


            },

            messages: {


            // '1_business_duration': {

            //         required: "Judul Asesmen tidak boleh kosong",

            //     },





            },

            errorElement: 'span',

            errorPlacement: function(error, element) {

                error.addClass('invalid-feedback');

                element.closest('.form-group').append(error);

            },

            highlight: function(element, errorClass, validClass) {

                $(element).addClass('is-invalid');

            },

            unhighlight: function(element, errorClass, validClass) {

                $(element).removeClass('is-invalid');

            },


            
 submitHandler: function(form) {

                var dataForm = new FormData(form);



                $.ajax({

                    url: base_url + 'panel/bmfi_bmm_peserta_microfinance/bmm_web_usaha/add_bmm_web_usaha_peserta',

                    type: "POST",

                    dataType: "json",

                    data: dataForm,

                    processData: false,

                    contentType: false,

                    beforeSend: function() {

                        $(".loader").show();

                        $("#btn-add_bmm_web_usaha_peserta_add").addClass('disabled');

                    },

                    success: function(respon) {

                        $(".loader").hide();

                        $("#btn-add_bmm_web_usaha_peserta_add").removeClass('disabled');

                        $("#bmfi_bmm_web_usaha_peserta_addFormAdd")[0].reset();

                        CKEDITOR.instances['keterangan'].setData('');

                        $("#add_bmm_web_usaha_peserta_addModal").modal('hide');

                        toastr.success('Data berhasil disimpan.');

                        $(".datatables").DataTable().ajax.reload();

                        

                    }

                })

            }

        });












        $('#bmfi_akun_login_peserta_addFormAdd').validate({

            rules: {




                //  '1_business_duration': {

                //     required: true,

                // },
                

               

              


            },

            messages: {


            // '1_business_duration': {

            //         required: "Judul Asesmen tidak boleh kosong",

            //     },





            },

            errorElement: 'span',

            errorPlacement: function(error, element) {

                error.addClass('invalid-feedback');

                element.closest('.form-group').append(error);

            },

            highlight: function(element, errorClass, validClass) {

                $(element).addClass('is-invalid');

            },

            unhighlight: function(element, errorClass, validClass) {

                $(element).removeClass('is-invalid');

            },


            
 submitHandler: function(form) {

                var dataForm = new FormData(form);



                $.ajax({

                    url: base_url + 'panel/bmfi_bmm_peserta_microfinance/bmm_user_login_peserta/add_akun_login_peserta',

                    type: "POST",

                    dataType: "json",

                    data: dataForm,

                    processData: false,

                    contentType: false,

                    beforeSend: function() {

                        $(".loader").show();

                        $("#btn-add_akun_login_peserta_add").addClass('disabled');

                    },

                    success: function(respon) {

                        $(".loader").hide();

                        $("#btn-add_akun_login_peserta_add").removeClass('disabled');

                        $("#bmfi_akun_login_peserta_addFormAdd")[0].reset();

                        CKEDITOR.instances['keterangan'].setData('');

                        $("#add_akun_login_peserta_addModal").modal('hide');

                        toastr.success('Data berhasil disimpan.');

                        $(".datatables").DataTable().ajax.reload();

                        

                    }

                })

            }

        });













        $('#bmfi_transaksi_survei_pesertaFormAdd').validate({

            rules: {




                //  '1_business_duration': {

                //     required: true,

                // },
                

               

              


            },

            messages: {


            // '1_business_duration': {

            //         required: "Judul Asesmen tidak boleh kosong",

            //     },





            },

            errorElement: 'span',

            errorPlacement: function(error, element) {

                error.addClass('invalid-feedback');

                element.closest('.form-group').append(error);

            },

            highlight: function(element, errorClass, validClass) {

                $(element).addClass('is-invalid');

            },

            unhighlight: function(element, errorClass, validClass) {

                $(element).removeClass('is-invalid');

            },


            
 submitHandler: function(form) {

                var dataForm = new FormData(form);



                $.ajax({

                    url: base_url + 'panel/bmfi_survei_peserta_bmm/add_survei_peserta',

                    type: "POST",

                    dataType: "json",

                    data: dataForm,

                    processData: false,

                    contentType: false,

                    beforeSend: function() {

                        $(".loader").show();

                        $("#btn-add_transaksi_survei_peserta").addClass('disabled');

                    },

                    success: function(respon) {

                        $(".loader").hide();

                        $("#btn-add_transaksi_survei_peserta").removeClass('disabled');

                        $("#bmfi_transaksi_survei_pesertaFormAdd")[0].reset();

                        CKEDITOR.instances['keterangan'].setData('');

                        $("#add_transaksisurveipesertaModal").modal('hide');

                        toastr.success('Data berhasil disimpan.');

                        $(".datatables").DataTable().ajax.reload();

                        $("#tabel_saldo_microfinance").DataTable().ajax.reload();

                    }

                })

            }

        });












        $('#bmfi_transaksi_psikotes_pesertaFormAdd').validate({

            rules: {




                //  '1_business_duration': {

                //     required: true,

                // },
                

               

              


            },

            messages: {


            // '1_business_duration': {

            //         required: "Judul Asesmen tidak boleh kosong",

            //     },





            },

            errorElement: 'span',

            errorPlacement: function(error, element) {

                error.addClass('invalid-feedback');

                element.closest('.form-group').append(error);

            },

            highlight: function(element, errorClass, validClass) {

                $(element).addClass('is-invalid');

            },

            unhighlight: function(element, errorClass, validClass) {

                $(element).removeClass('is-invalid');

            },


            
 submitHandler: function(form) {

                var dataForm = new FormData(form);



                $.ajax({

                    url: base_url + 'panel/bmfi_psikotes_peserta_bmm/add_psikotes_peserta',

                    type: "POST",

                    dataType: "json",

                    data: dataForm,

                    processData: false,

                    contentType: false,

                    beforeSend: function() {

                        $(".loader").show();

                        $("#btn-add_transaksi_psikotes_peserta").addClass('disabled');

                    },

                    success: function(respon) {

                        $(".loader").hide();

                        $("#btn-add_transaksi_psikotes_peserta").removeClass('disabled');

                        $("#bmfi_transaksi_psikotes_pesertaFormAdd")[0].reset();

                        CKEDITOR.instances['keterangan'].setData('');

                        $("#add_transaksipsikotespesertaModal").modal('hide');

                        toastr.success('Data berhasil disimpan.');

                        $(".datatables").DataTable().ajax.reload();

                        $("#tabel_saldo_microfinance").DataTable().ajax.reload();

                    }

                })

            }

        });














        $('#peserta_program_bmfi_asesmenFormEdit').validate({

            rules: {

                nama2: {

                    required: true,

                },



                 no_ktp2: {

                    required: true,

                },



                 no_hp2: {

                    required: true,

                },



                  jenis_kelamin2: {

                    required: true,

                },



                  tempat_tanggal_lahir2: {

                    required: true,

                },



                  alamat_rumah2: {

                    required: true,

                },


                  nama_usaha2: {

                    required: true,

                },



                  lokasi_usaha2: {

                    required: true,

                },



                    tgl_registrasi_peserta2: {

                    required: true,

                },




                



                //  foto_profil2: {

                //     required: true,

                //          extension: 'jpg|jpeg|gif|png'


                // },




              

            },

            messages: {

                nama2: {

                    required: "Nama peserta_program_bmfi_asesmen tidak boleh kosong",

                },


                 no_ktp2: {

                    required: "No KTP tidak boleh kosong",

                },



               no_hp2: {

                    required: "No Handphone tidak boleh kosong",

                },


                 jenis_kelamin2: {

                    required: "Jenis Kelamin tidak boleh kosong",

                },


                 tempat_tanggal_lahir2: {

                    required: "Tempat Tanggal Lahir tidak boleh kosong",

                },


                 alamat_rumah2: {

                    required: "Alamat Rumah tidak boleh kosong",

                },


                 nama_usaha2: {

                    required: "Nama Usaha tidak boleh kosong",

                },



                 lokasi_usaha2: {

                    required: "Lokasi Usaha tidak boleh kosong",

                },




                 tgl_registrasi_peserta2: {

                    required: "Tgl Registrasi Peserta tidak boleh kosong",

                },

               


                
                // foto_profil2: {

                //     required: "foto_profil tidak boleh kosong",

                //     extension: "Format tidak sesuai"

                // },

             

            },

            errorElement: 'span',

            errorPlacement: function(error, element) {

                error.addClass('invalid-feedback');

                element.closest('.form-group').append(error);

            },

            highlight: function(element, errorClass, validClass) {

                $(element).addClass('is-invalid');

            },

            unhighlight: function(element, errorClass, validClass) {

                $(element).removeClass('is-invalid');

            },

            submitHandler: function(form) {

                var dataForm = new FormData(form);

                var id = $("[name='id2']").val();

                $.ajax({

                    url: base_url + 'panel/bmfi_peserta_program/edit/' + id,

                    type: "POST",

                    dataType: "json",

                    data: dataForm,

                    processData: false,

                    contentType: false,

                    beforeSend: function() {

                        $(".loader").show();

                        $("#btn-edit").addClass('disabled');

                    },

                    success: function(respon) {

                        $(".loader").hide();

                        $("#btn-edit").removeClass('disabled');

                        $("#editModal").modal('hide');

                        toastr.success('Data berhasil disimpan.');


                        $(".datatables").DataTable().ajax.reload();


  $("#tabel_saldo_microfinance").DataTable().ajax.reload();


    


                    }

                })

            }

        });















        $('#kualitas_angsuran_peserta_bmfi_FormEdit').validate({

            rules: {



                //     tgl_registrasi_peserta2: {

                //     required: true,

                // },



              

            },

            messages: {

               


                //  tgl_registrasi_peserta2: {

                //     required: "Tgl Registrasi Peserta tidak boleh kosong",

                // },

               



             

            },

            errorElement: 'span',

            errorPlacement: function(error, element) {

                error.addClass('invalid-feedback');

                element.closest('.form-group').append(error);

            },

            highlight: function(element, errorClass, validClass) {

                $(element).addClass('is-invalid');

            },

            unhighlight: function(element, errorClass, validClass) {

                $(element).removeClass('is-invalid');

            },

            submitHandler: function(form) {

                var dataForm = new FormData(form);

                var id = $("[name='id2']").val();

                $.ajax({

                    url: base_url + 'panel/bmfi_peserta_program/kualitas_angsuran_peserta_edit/' + id,

                    type: "POST",

                    dataType: "json",

                    data: dataForm,

                    processData: false,

                    contentType: false,

                    beforeSend: function() {

                        $(".loader").show();

                        $("#btn-kualitas_angsuran_peserta_edit").addClass('disabled');

                    },

                    success: function(respon) {

                        $(".loader").hide();

                        $("#btn-kualitas_angsuran_peserta_edit").removeClass('disabled');

                        $("#kualitas_angsuran_peserta_editModal").modal('hide');

                        toastr.success('Data berhasil disimpan.');


                        $(".datatables").DataTable().ajax.reload();


  $("#tabel_saldo_microfinance").DataTable().ajax.reload();


    


                    }

                })

            }

        });









        $('#jenis_angsuran_peserta_bmfi_FormEdit').validate({

            rules: {



                //     tgl_registrasi_peserta2: {

                //     required: true,

                // },



              

            },

            messages: {

               


                //  tgl_registrasi_peserta2: {

                //     required: "Tgl Registrasi Peserta tidak boleh kosong",

                // },

               



             

            },

            errorElement: 'span',

            errorPlacement: function(error, element) {

                error.addClass('invalid-feedback');

                element.closest('.form-group').append(error);

            },

            highlight: function(element, errorClass, validClass) {

                $(element).addClass('is-invalid');

            },

            unhighlight: function(element, errorClass, validClass) {

                $(element).removeClass('is-invalid');

            },

            submitHandler: function(form) {

                var dataForm = new FormData(form);

                var id = $("[name='id2']").val();

                $.ajax({

                    url: base_url + 'panel/bmfi_peserta_program/jenis_angsuran_peserta_edit/' + id,

                    type: "POST",

                    dataType: "json",

                    data: dataForm,

                    processData: false,

                    contentType: false,

                    beforeSend: function() {

                        $(".loader").show();

                        $("#btn-jenis_angsuran_peserta_edit").addClass('disabled');

                    },

                    success: function(respon) {

                        $(".loader").hide();

                        $("#btn-jenis_angsuran_peserta_edit").removeClass('disabled');

                        $("#jenis_angsuran_peserta_editModal").modal('hide');

                        toastr.success('Data berhasil disimpan.');


                        $(".datatables").DataTable().ajax.reload();


  $("#tabel_saldo_microfinance").DataTable().ajax.reload();


    


                    }

                })

            }

        });









        $('#komitmen_tanggal_pengembalian_peserta_bmfi_FormEdit').validate({

            rules: {



                //     tgl_registrasi_peserta2: {

                //     required: true,

                // },



              

            },

            messages: {

               


                //  tgl_registrasi_peserta2: {

                //     required: "Tgl Registrasi Peserta tidak boleh kosong",

                // },

               



             

            },

            errorElement: 'span',

            errorPlacement: function(error, element) {

                error.addClass('invalid-feedback');

                element.closest('.form-group').append(error);

            },

            highlight: function(element, errorClass, validClass) {

                $(element).addClass('is-invalid');

            },

            unhighlight: function(element, errorClass, validClass) {

                $(element).removeClass('is-invalid');

            },

            submitHandler: function(form) {

                var dataForm = new FormData(form);

                var id = $("[name='id2']").val();

                $.ajax({

                    url: base_url + 'panel/bmfi_peserta_program/komitmen_tanggal_pengembalian_peserta_edit/' + id,

                    type: "POST",

                    dataType: "json",

                    data: dataForm,

                    processData: false,

                    contentType: false,

                    beforeSend: function() {

                        $(".loader").show();

                        $("#btn-komitmen_tanggal_pengembalian_peserta_edit").addClass('disabled');

                    },

                    success: function(respon) {

                        $(".loader").hide();

                        $("#btn-komitmen_tanggal_pengembalian_peserta_edit").removeClass('disabled');

                        $("#komitmen_tanggal_pengembalian_peserta_editModal").modal('hide');

                        toastr.success('Data berhasil disimpan.');


                        $(".datatables").DataTable().ajax.reload();


  $("#tabel_saldo_microfinance").DataTable().ajax.reload();


    


                    }

                })

            }

        });

















        $('#kordinat_lokasi_usaha_peserta_bmfi_FormEdit').validate({

            rules: {



                //     tgl_registrasi_peserta2: {

                //     required: true,

                // },



              

            },

            messages: {

               


                //  tgl_registrasi_peserta2: {

                //     required: "Tgl Registrasi Peserta tidak boleh kosong",

                // },

               



             

            },

            errorElement: 'span',

            errorPlacement: function(error, element) {

                error.addClass('invalid-feedback');

                element.closest('.form-group').append(error);

            },

            highlight: function(element, errorClass, validClass) {

                $(element).addClass('is-invalid');

            },

            unhighlight: function(element, errorClass, validClass) {

                $(element).removeClass('is-invalid');

            },

            submitHandler: function(form) {

                var dataForm = new FormData(form);

                var id = $("[name='id2']").val();

                $.ajax({

                    url: base_url + 'panel/bmfi_peserta_program/kordinat_lokasi_usaha_peserta_edit/' + id,

                    type: "POST",

                    dataType: "json",

                    data: dataForm,

                    processData: false,

                    contentType: false,

                    beforeSend: function() {

                        $(".loader").show();

                        $("#btn-kordinat_lokasi_usaha_peserta_edit").addClass('disabled');

                    },

                    success: function(respon) {

                        $(".loader").hide();

                        $("#btn-kordinat_lokasi_usaha_peserta_edit").removeClass('disabled');

                        $("#kordinat_lokasi_usaha_peserta_editModal").modal('hide');

                        toastr.success('Data berhasil disimpan.');


                        $(".datatables").DataTable().ajax.reload();


  $("#tabel_saldo_microfinance").DataTable().ajax.reload();


    


                    }

                })

            }

        });











        $('#bmm_web_usaha_peserta_edit_FormEdit').validate({

            rules: {



                //     tgl_registrasi_peserta2: {

                //     required: true,

                // },



              

            },

            messages: {

               


                //  tgl_registrasi_peserta2: {

                //     required: "Tgl Registrasi Peserta tidak boleh kosong",

                // },

               



             

            },

            errorElement: 'span',

            errorPlacement: function(error, element) {

                error.addClass('invalid-feedback');

                element.closest('.form-group').append(error);

            },

            highlight: function(element, errorClass, validClass) {

                $(element).addClass('is-invalid');

            },

            unhighlight: function(element, errorClass, validClass) {

                $(element).removeClass('is-invalid');

            },

            submitHandler: function(form) {

                var dataForm = new FormData(form);

                var id_peserta_program_bmfi_bmm = $("[name='id_peserta_program_bmfi_bmm2']").val();

                $.ajax({

                    url: base_url + 'panel/bmfi_bmm_peserta_microfinance/bmm_web_usaha/edit_bmm_web_usaha_peserta/' + id_peserta_program_bmfi_bmm,

                    type: "POST",

                    dataType: "json",

                    data: dataForm,

                    processData: false,

                    contentType: false,

                    beforeSend: function() {

                        $(".loader").show();

                        $("#btn-bmm_web_usaha_peserta_edit").addClass('disabled');

                    },

                    success: function(respon) {

                        $(".loader").hide();

                        $("#btn-bmm_web_usaha_peserta_edit").removeClass('disabled');

                        $("#bmm_web_usaha_peserta_editModal").modal('hide');

                        toastr.success('Data berhasil disimpan.');


                        $(".datatables").DataTable().ajax.reload();


  // $("#tabel_saldo_microfinance").DataTable().ajax.reload();


    


                    }

                })

            }

        });










        $('#akun_login_peserta_edit_FormEdit').validate({

            rules: {



                //     tgl_registrasi_peserta2: {

                //     required: true,

                // },



              

            },

            messages: {

               


                //  tgl_registrasi_peserta2: {

                //     required: "Tgl Registrasi Peserta tidak boleh kosong",

                // },

               



             

            },

            errorElement: 'span',

            errorPlacement: function(error, element) {

                error.addClass('invalid-feedback');

                element.closest('.form-group').append(error);

            },

            highlight: function(element, errorClass, validClass) {

                $(element).addClass('is-invalid');

            },

            unhighlight: function(element, errorClass, validClass) {

                $(element).removeClass('is-invalid');

            },

            submitHandler: function(form) {

                var dataForm = new FormData(form);

                var id_peserta_program_bmfi_bmm = $("[name='id_peserta_program_bmfi_bmm2']").val();

                $.ajax({

                    url: base_url + 'panel/bmfi_bmm_peserta_microfinance/bmm_user_login_peserta/edit_akun_login_peserta/' + id_peserta_program_bmfi_bmm,

                    type: "POST",

                    dataType: "json",

                    data: dataForm,

                    processData: false,

                    contentType: false,

                    beforeSend: function() {

                        $(".loader").show();

                        $("#btn-akun_login_peserta_edit").addClass('disabled');

                    },

                    success: function(respon) {

                        $(".loader").hide();

                        $("#btn-akun_login_peserta_edit").removeClass('disabled');

                        $("#akun_login_peserta_editModal").modal('hide');

                        toastr.success('Data berhasil disimpan.');


                        $(".datatables").DataTable().ajax.reload();


  // $("#tabel_saldo_microfinance").DataTable().ajax.reload();


    


                    }

                })

            }

        });










        $('#kuesioner_peserta_edit_FormEdit').validate({

            rules: {



                //     tgl_registrasi_peserta2: {

                //     required: true,

                // },



              

            },

            messages: {

               


                //  tgl_registrasi_peserta2: {

                //     required: "Tgl Registrasi Peserta tidak boleh kosong",

                // },

               



             

            },

            errorElement: 'span',

            errorPlacement: function(error, element) {

                error.addClass('invalid-feedback');

                element.closest('.form-group').append(error);

            },

            highlight: function(element, errorClass, validClass) {

                $(element).addClass('is-invalid');

            },

            unhighlight: function(element, errorClass, validClass) {

                $(element).removeClass('is-invalid');

            },

            submitHandler: function(form) {

                var dataForm = new FormData(form);

                var id_peserta_program_bmfi_bmm = $("[name='id_peserta_program_bmfi_bmm2']").val();

                $.ajax({

                    url: base_url + 'panel/bmfi_survei_peserta_bmm/kuesioner_peserta_edit/' + id_peserta_program_bmfi_bmm,

                    type: "POST",

                    dataType: "json",

                    data: dataForm,

                    processData: false,

                    contentType: false,

                    beforeSend: function() {

                        $(".loader").show();

                        $("#btn-kuesioner_peserta_edit").addClass('disabled');

                    },

                    success: function(respon) {

                        $(".loader").hide();

                        $("#btn-kuesioner_peserta_edit").removeClass('disabled');

                        $("#kuesioner_peserta_editModal").modal('hide');

                        toastr.success('Data berhasil disimpan.');


                        $(".datatables").DataTable().ajax.reload();


  $("#tabel_saldo_microfinance").DataTable().ajax.reload();


    


                    }

                })

            }

        });










        $('#psikotes_peserta_edit_FormEdit').validate({

            rules: {



                //     tgl_registrasi_peserta2: {

                //     required: true,

                // },



              

            },

            messages: {

               


                //  tgl_registrasi_peserta2: {

                //     required: "Tgl Registrasi Peserta tidak boleh kosong",

                // },

               



             

            },

            errorElement: 'span',

            errorPlacement: function(error, element) {

                error.addClass('invalid-feedback');

                element.closest('.form-group').append(error);

            },

            highlight: function(element, errorClass, validClass) {

                $(element).addClass('is-invalid');

            },

            unhighlight: function(element, errorClass, validClass) {

                $(element).removeClass('is-invalid');

            },

            submitHandler: function(form) {

                var dataForm = new FormData(form);

                var id_peserta_program_bmfi_bmm = $("[name='id_peserta_program_bmfi_bmm2']").val();

                $.ajax({

                    url: base_url + 'panel/bmfi_psikotes_peserta_bmm/psikotes_peserta_edit/' + id_peserta_program_bmfi_bmm,

                    type: "POST",

                    dataType: "json",

                    data: dataForm,

                    processData: false,

                    contentType: false,

                    beforeSend: function() {

                        $(".loader").show();

                        $("#btn-psikotes_peserta_edit").addClass('disabled');

                    },

                    success: function(respon) {

                        $(".loader").hide();

                        $("#btn-psikotes_peserta_edit").removeClass('disabled');

                        $("#psikotes_peserta_editModal").modal('hide');

                        toastr.success('Data berhasil disimpan.');


                        $(".datatables").DataTable().ajax.reload();


  $("#tabel_saldo_microfinance").DataTable().ajax.reload();


    


                    }

                })

            }

        });








        $('#kordinat_lokasi_rumah_peserta_bmfi_FormEdit').validate({

            rules: {



                //     tgl_registrasi_peserta2: {

                //     required: true,

                // },



              

            },

            messages: {

               


                //  tgl_registrasi_peserta2: {

                //     required: "Tgl Registrasi Peserta tidak boleh kosong",

                // },

               



             

            },

            errorElement: 'span',

            errorPlacement: function(error, element) {

                error.addClass('invalid-feedback');

                element.closest('.form-group').append(error);

            },

            highlight: function(element, errorClass, validClass) {

                $(element).addClass('is-invalid');

            },

            unhighlight: function(element, errorClass, validClass) {

                $(element).removeClass('is-invalid');

            },

            submitHandler: function(form) {

                var dataForm = new FormData(form);

                var id = $("[name='id2']").val();

                $.ajax({

                    url: base_url + 'panel/bmfi_peserta_program/kordinat_lokasi_rumah_peserta_edit/' + id,

                    type: "POST",

                    dataType: "json",

                    data: dataForm,

                    processData: false,

                    contentType: false,

                    beforeSend: function() {

                        $(".loader").show();

                        $("#btn-kordinat_lokasi_rumah_peserta_edit").addClass('disabled');

                    },

                    success: function(respon) {

                        $(".loader").hide();

                        $("#btn-kordinat_lokasi_rumah_peserta_edit").removeClass('disabled');

                        $("#kordinat_lokasi_rumah_peserta_editModal").modal('hide');

                        toastr.success('Data berhasil disimpan.');


                        $(".datatables").DataTable().ajax.reload();


  $("#tabel_saldo_microfinance").DataTable().ajax.reload();


    


                    }

                })

            }

        });









    $(".rupiah").keyup(function(e) {
        // console.log('tes')

        var nilai = formatRupiah($(this).val(), 'Rp. ');

        // $(".rupiah").val(nilai);

        $(this).val(nilai);

    })


    function formatRupiah(angka, prefix) {

        var number_string = angka.toString().replace(/[^,\d]/g, ''),

            split = number_string.split(','),

            sisa = split[0].length % 3,

            rupiah = split[0].substr(0, sisa),

            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {

            separator = sisa ? '.' : '';

            rupiah += separator + ribuan.join('.');

        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;

        return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');

    }




     $(".nomor").keyup(function(e) {
        // console.log('tes')

        var nilai = formatNomor($(this).val(), ' ');

        // $(".rupiah").val(nilai);

        $(this).val(nilai);

    })


    function formatNomor(angka, prefix) {

        var number_string = angka.toString().replace(/[^,\d]/g, ''),

            split = number_string.split(','),

            sisa = split[0].length % 3,

            rupiah = split[0].substr(0, sisa),

            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {

            separator = sisa ? ' ' : ' ';

            rupiah += separator + ribuan.join(' ');

        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;

        return prefix == undefined ? rupiah : (rupiah ? '' + rupiah : '');

    }





    })





</script>