<<!-- script type="text/javascript">
$(document).ready(function() {



$('select').selectpicker();


     // bsCustomFileInput.init()



})
</script> -->



<!-- 
<link rel="stylesheet" href="<?php echo base_url() ?>assets/template/plugins/datatables-bs-5/select2/css/select2.min.css">


<link rel="stylesheet" href="<?php echo base_url() ?>assets/template/plugins/datatables-bs-5/select2-bootstrap5-theme/select2-bootstrap-5-theme.min.css"> 



<script src="<?php echo base_url() ?>assets/template/plugins/datatables-bs-5/select2/js/select2.min.js"></script>
     -->

<!-- 
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.18/dist/css/bootstrap-select.min.css">


<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.18/dist/js/bootstrap-select.min.js"></script>
 -->



<!-- <script src="<?php echo base_url() ?>assets/template/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script> -->










<div class="card">



    <div class="card-header">



 <center><h1 class="card-text"><b>Mutasi Transaksi Microfinance

        </p></b></h1></center>



<center><h5 class="card-text"><b><?= $this->session->userdata('sess_all')->nama_masjid; ?>

            </h5></center>

        <center>  <p><br><?= $this->session->userdata('sess_all')->alamat_masjid; ?>

        </p></b></center>









<div class="container">
    <div id="totalValues">
        <table class="table table-bordered">
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

<script type="text/javascript">
    $(document).ready(function() {
        $.ajax({
            url: base_url + 'panel/bmfi_transaksi_microfinance/get_total_nominal',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                var totalSaldoMicrofinance = response.total_saldo_microfinance;
                var totalDanaDiPeserta = response.total_dana_di_peserta;
                var saldo = response.saldo;
                var jumlahPesertaOutstanding = response.jumlah_peserta_outstanding;

                // Tampilkan nilai masuk
                if (saldo.includes('Minus')) {
                    // Jika saldo memiliki label 'Minus', beri warna merah pada nilai masuk
                    $('#totalSaldoMicrofinance').text(totalSaldoMicrofinance).addClass('text-danger');
                } else {
                    $('#totalSaldoMicrofinance').text(totalSaldoMicrofinance);
                }

                $('#totalDanaDiPeserta').text(totalDanaDiPeserta);

                // Tampilkan nilai saldo
                if (saldo.includes('Minus')) {
                    // Jika nilai saldo memiliki label 'Minus', beri warna merah
                    $('#saldo').text(saldo).addClass('text-danger');
                } else {
                    $('#saldo').text(saldo);
                }

                // Tampilkan jumlah peserta dengan pinjaman yang belum lunas
                $('#jumlahPesertaOutstanding').text('Jumlah Peserta dengan Pinjaman Outstanding : ' + jumlahPesertaOutstanding + ' orang peserta microfinance');
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });
</script>







<div class="container">
    <div id="totalValues">
        <table class="table table-bordered">
            <thead>
                <tr>
                   
                    <th>Total Riwayat Penerimaan Infak dari Peserta</th>
                   
                </tr>
            </thead>
            <tbody>
                <tr>
                   
                    <td id="totalDanaInfakPeserta"></td>
                    
                </tr>
              
            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $.ajax({
            url: base_url + 'panel/bmfi_transaksi_microfinance/get_total_nominal_infak_peserta',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
            
                var totalDanaInfakPeserta = response.total_dana_infak_peserta;
                var saldo = response.saldo;
               
                

                $('#totalDanaInfakPeserta').text(totalDanaInfakPeserta);


            },


            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });
</script>




<br>




<!-- 
        <a href="#" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addModal"><i
                class="fa fa-plus"></i> Tambah</a> -->



        <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#filterModal"><i
                class="fa fa-filter"></i> Filter</a>



        <a href="#" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#cetakModal"><i
                class="fa fa-print"></i> Cetak Transaksi</a>

        


        <a href="#" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#cetakModalRiwayatInfak"><i
                class="fa fa-print"></i> Cetak Riwayat Infak</a>




    </div>



    <div class="card-body">



     <div class="row">


    <div class="col-md-12">


     <div class="form-group">



             <label>Pencarian</label>



                <input type="text" id="txtketerangan_transaksi" class="form-control" placeholder="Cari Data Berdasarkan keterangan Transaksi">



                </div>

         </div>


     </div>




        <div class="table-responsive">





            <table id="tabel_bmfi_transaksi_microfinance" class="table table-bordered" style="width:100%">
        <thead>
            <tr>
                  <th class="align-middle">No</th>

                    <th class="align-middle">Tanggal</th>

                   

                    <th class="align-middle">Nama Peserta</th>



                       <!-- <th class="align-middle">Sisa Dana Cicilan Peserta</th> -->



                     
                 

                    <th class="align-middle">Nominal Masuk</th>



                 


                    <th class="align-middle">Nominal Keluar</th>



        


                     <th class="align-middle">Keterangan Transaksi</th>


                     <th class="align-middle">Jenis Angsuran</th>




                     <!-- <th class="align-middle">Nama Pendamping</th> -->


                     

                    <th class="align-middle">Aksi</th>

            </tr>
        </thead>
    
      <tfoot>
            <tr>
                <th colspan="3" style="text-align:right">Total:</th>
                <th></th>
            </tr>
        </tfoot>


    </table>



        </div>



    </div>



</div>



<input type="hidden" id="hiddenid">







<!-- modal filter -->



<div class="modal fade" id="filterModal">



    <div class="modal-dialog modal-md">



        <div class="modal-content">



            <div class="modal-header bg-secondary">



                <h5 class="modal-title">Filter</h5>



                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">



                    <span aria-hidden="true">&times;</span>



                </button>



            </div>



            <form id="searchForm">



                <div class="modal-body">



                


                            <div class="form-group d-none">



                                <label>Nominal Pengembalian</label>



                                <input type="text" id="txtnominal_pengembalian" class="form-control rupiah">



                            </div>





                            <div class="form-group d-none">



                                <label>Nominal Peminjaman</label>



                                <input type="text" id="txtnominal_peminjaman" class="form-control rupiah">



                            </div>



<!-- 
                            <div class="form-group">



                                <label>keterangan_transaksi</label>



                                <input type="text" id="txtketerangan_transaksi" class="form-control">



                            </div>

 -->







                            <div class="form-group">



                                <label>Nama Peserta Program</label>



                                <select id="txtbmfi_peserta_program" class="form-control">



                                    <option value="">--Pilih--</option>



                                    <?php foreach ($bmfi_peserta_program as $v) { ?>



                                    <option value="<?php echo $v['id_peserta_program_bmfi_asesmen'] ?>">

                                        <?php echo $v['nama_peserta_program_bmfi_asesmen'] ?></option>



                                    <?php } ?>



                                </select>



                            </div>




<!-- 

                            <div class="form-group">



                                <label>Nama Pendamping</label>



                                <select id="txtbmfi_pendamping" class="form-control">



                                    <option value="">--Pilih--</option>



                                    <?php foreach ($bmfi_pendamping as $v) { ?>



                                    <option value="<?php echo $v['id_pendamping_bmfi_asesmen'] ?>">

                                        <?php echo $v['nama_pendamping_bmfi_asesmen'] ?></option>



                                    <?php } ?>



                                </select>



                            </div>

 -->


                          <!--   <div class="form-group">



                                <label>Jenis Angsuran</label>



                                <select id="txtbmfi_jenis_angsuran" class="form-control">



                                    <option value="">--Pilih--</option>



                                    <?php foreach ($bmfi_jenis_angsuran as $v) { ?>



                                    <option value="<?php echo $v['id_bmfi_jenis_angsuran'] ?>">

                                        <?php echo $v['nama_bmfi_jenis_angsuran'] ?></option>



                                    <?php } ?>



                                </select>



                            </div>

 -->










                                <div class="row">



                        <div class="col-md-6">




                            <div class="form-group">



                                <label for="tgl1">Tanggal Awal</label>



                                <input type="date" class="form-control mb-2" name="tgl1" placeholder="input Tanggal">



                            </div>


                            <div class="form-group">



                                <label for="tgl2">Tanggal Akhir</label>



                                <input type="date" class="form-control mb-2" name="tgl2" placeholder="input Tanggal">



                            </div>






                        </div>



                        <div class="col-md-6">



                            <div class="form-group d-none">




                                <label>Provinsi</label>



                                <select id="txtProvinsi" class="form-control select2">



                                    <option value="">--Pilih--</option>



                                    <?php foreach ($provinsi as $p) { ?>



                                    <option value="<?php echo $p['id'] ?>"><?php echo $p['nama_provinsi'] ?></option>



                                    <?php } ?>



                                </select>



                            </div>



                            <div class="form-group d-none">



                                <label>KabupatenKota</label>



                                <select id="txtKabupatenKota" class="form-control select2">



                                    <option value="">--Pilih--</option>



                                </select>



                            </div>



                            <div class="form-group d-none">



                                <label>Kecamatan</label>



                                <select id="txtKecamatan" class="form-control select2">



                                    <option value="">--Pilih--</option>



                                </select>



                            </div>



                            <div class="form-group d-none">



                                <label>kelurahan_desa</label>



                                <select id="txtkelurahan_desa" class="form-control select2">



                                    <option value="">--Pilih--</option>



                                </select>



                            </div>



                        </div>



                    </div>



                </div>






                <div class="modal-footer">



                    <span class="loader"><i class="fa fa-spinner fa-spin"></i> Sedang proses...</span>



                    <button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal"><i
                            class="fa fa-times"></i> Batal</button>



                    <button type="submit" class="btn btn-primary btn-sm" id="btn-filter"><i class="fa fa-check"></i>

                        Terapkan</button>



                </div>



            </form>



        </div>



        <!-- /.modal-content -->



    </div>



    <!-- /.modal-dialog -->



</div>



<!-- /.modal -->







<!-- modal add -->



<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">



    <div class="modal-dialog modal-lg">



        <div class="modal-content">



            <div class="modal-header bg-secondary">



                <h5 class="modal-title">Tambah Penerimaan Angsuran</h5>



                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">



                    <span aria-hidden="true">&times;</span>



                </button>



            </div>



            <form id="bmfi_transaksi_microfinanceFormAdd">



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



                                <label>Nama Peserta Program</label>



                                <select name="bmfi_peserta_program" class="form-control">



                                    <option value="">--Pilih--</option>



                                    <?php foreach ($bmfi_peserta_program as $v) { ?>



                                    <option value="<?php echo $v['id_peserta_program_bmfi_asesmen'] ?>">

                                        <?php echo $v['nama_peserta_program_bmfi_asesmen'] ?></option>



                                    <?php } ?>



                                </select>



                            </div>



                        </div>



                    </div>


<!-- 
                    <div class="form-group">



                        <label>Nama Pendamping</label>



                        <select name="bmfi_pendamping" class="form-control">



                            <option value="">--Pilih--</option>



                            <?php foreach ($bmfi_pendamping as $v) { ?>



                            <option value="<?php echo $v['id_pendamping_bmfi_asesmen'] ?>">

                                <?php echo $v['nama_pendamping_bmfi_asesmen'] ?></option>



                            <?php } ?>



                        </select>



                    </div>
 -->






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



                        <div class="col-md-6">



                            <div class="form-group">



                                <label>Nominal Pengembalian</label>



                                <input type="text" name="nominal_pengembalian" class="form-control rupiah">



                            </div>




                        </div>




                    </div>











                    <div class="row">



                        <div class="col-md-6">



                            <div class="form-group">



                                <label>Nominal Peminjaman</label>



                                <input type="text" name="nominal_peminjaman" class="form-control rupiah">



                            </div>




                        </div>




                    </div>





                    <div class="row">



                        <div class="col-md-12">



                            <div class="form-group">



                                <label>keterangan_transaksi</label>



                                <textarea name="keterangan_transaksi" rows="4" class="form-control"></textarea>



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



                    <button type="submit" class="btn btn-primary btn-sm" id="btn-add"><i class="fa fa-check"></i>

                        Submit</button>



                </div>



            </form>



        </div>



        <!-- /.modal-content -->



    </div>



    <!-- /.modal-dialog -->



</div>



<!-- /.modal -->



<!-- modal edit -->



<div class="modal fade" id="editModal">



    <div class="modal-dialog modal-lg">



        <div class="modal-content">



            <div class="modal-header bg-secondary">



                <h5 class="modal-title">Edit Penerimaan Angsuran</h5>



                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">



                    <span aria-hidden="true">&times;</span>



                </button>



            </div>



            <form id="bmfi_transaksi_microfinanceFormEdit">



                <input type="hidden" name="id2">



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




                    <div class="row">


                        <div class="col-md-6">



                            <div class="form-group">



                                <label>Tanggal</label>



                                <input type="date" name="tanggal2" class="form-control">



                            </div>



                        </div>



                    </div>



                  
                    <div class="row">



                        <div class="col-md-6">



                            <div class="form-group">



                                <label>Nama Peserta Program</label>



                                <select name="bmfi_peserta_program2" class="form-control">




                                    <?php foreach ($bmfi_peserta_program as $v) { ?>



                                    <option value="<?php echo $v['id_peserta_program_bmfi_asesmen'] ?>">

                                        <?php echo $v['nama_peserta_program_bmfi_asesmen'] ?></option>



                                    <?php } ?>



                                </select>



                            </div>


                        </div>



                    </div>




<!-- 
                    <div class="row">


                        <div class="col-md-6">


                            <div class="form-group">



                                <label>Nama Pendamping</label>



                                <select name="bmfi_pendamping2" class="form-control">




                                    <?php foreach ($bmfi_pendamping as $v) { ?>



                                    <option value="<?php echo $v['id_pendamping_bmfi_asesmen'] ?>">

                                        <?php echo $v['nama_pendamping_bmfi_asesmen'] ?></option>



                                    <?php } ?>



                                </select>



                            </div>


                        </div>


                    </div>

 -->


                    <div class="form-group">



                        <label>Jenis Angsuran</label>



                        <select name="bmfi_jenis_angsuran2" class="form-control">



                            <option value="">--Pilih--</option>



                            <?php foreach ($bmfi_jenis_angsuran as $v) { ?>



                            <option value="<?php echo $v['id_bmfi_jenis_angsuran'] ?>">

                                <?php echo $v['nama_bmfi_jenis_angsuran'] ?></option>



                            <?php } ?>



                        </select>



                    </div>





                    <div class="row">



                        <div class="col-md-6">



                            <div class="form-group">



                                <label>Nominal Pengembalian</label>



                                <input type="text" name="nominal_pengembalian2" class="form-control rupiah">



                            </div>



                        </div>


                    </div>







                     <div class="row">



                        <div class="col-md-6">



                            <div class="form-group">



                                <label>Infak Peserta</label>



                                <input type="text" name="infak_peserta2" class="form-control rupiah">



                            </div>



                        </div>


                    </div>










                    <div class="row">



                        <div class="col-md-6">



                            <div class="form-group">



                                <label>Sisa Dana Cicilan Peserta</label>



                                <input type="text" name="sisa_dana_cicilan_pengembalian2" class="form-control rupiah">



                            </div>



                        </div>


                    </div>










                      <div class="row">



                        <div class="col-md-6">



                            <div class="form-group">



                                <label>Nominal Peminjaman</label>



                                <input type="text" name="nominal_peminjaman2" class="form-control rupiah">



                            </div>



                        </div>


                    </div>





                    <div class="row">



                        <div class="col-md-12">



                            <div class="form-group">



                                <label>Keterangan Transaksi</label>



                                <textarea name="keterangan_transaksi2"  rows="4" class="form-control"></textarea>



                            </div>



                        </div>


                    </div>







                    <div class="row">



                        <div class="col-md-6">


                            <div class="form-group">



                                <label>Lampiran</label>



                                <div class="lampiran-place img-thumbnail mb-2"></div>



                                <input type="hidden" name="lampiran1">



                                <div class="input-group">



                                    <div class="custom-file">



                                        <input type="file" class="custom-file-input" name="lampiran2" id="lampiran2">



                                        <label class="custom-file-label" for="lampiran2">Pilih file</label>



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



                    <button type="submit" class="btn btn-primary btn-sm" id="btn-edit"><i class="fa fa-check"></i>

                        Submit</button>



                </div>



            </form>



        </div>



        <!-- /.modal-content -->



    </div>



    <!-- /.modal-dialog -->



</div>



<!-- /.modal -->



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

                <form action="<?= site_url('panel/mp/cetak_bmfi_transaksi_microfinance') ?>" target="_blank">



                          

<!-- 
                          <div class="form-group">



                                <label>Peserta Program</label>



                             <select name="txtbmfi_peserta_program[]" class="form-select multiple-select-field" data-placeholder="Silahkan Dipilih" multiple  required>



                                    <?php foreach ($bmfi_peserta_program as $v) { ?>



                                    <option value="<?php echo $v['id_peserta_program_bmfi_asesmen'] ?>">

                                        <?php echo $v['nama_peserta_program_bmfi_asesmen'] ?></option>



                                    <?php } ?>





                                </select>



                            </div>
 -->
  


<!-- 

                            <div class="form-group">



                                <label>Pendamping</label>



                                <select name="txtbmfi_pendamping[]" class="form-select multiple-select-field" data-placeholder="Silahkan Dipilih" multiple
 required>



                                    <?php foreach ($bmfi_pendamping as $v) { ?>



                                    <option value="<?php echo $v['id_bmfi_pendamping'] ?>">

                                        <?php echo $v['nama_bmfi_pendamping'] ?></option>



                                    <?php } ?>



                                </select>



                            </div>
 -->





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













<!-- Modal -->

<div class="modal fade" id="cetakModalRiwayatInfak" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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

                <form action="<?= site_url('panel/mp/cetak_bmfi_infak_peserta_microfinance') ?>" target="_blank">



                          

<!-- 
                          <div class="form-group">



                                <label>Peserta Program</label>



                             <select name="txtbmfi_peserta_program[]" class="form-select multiple-select-field" data-placeholder="Silahkan Dipilih" multiple  required>



                                    <?php foreach ($bmfi_peserta_program as $v) { ?>



                                    <option value="<?php echo $v['id_peserta_program_bmfi_asesmen'] ?>">

                                        <?php echo $v['nama_peserta_program_bmfi_asesmen'] ?></option>



                                    <?php } ?>





                                </select>



                            </div>
 -->
  


<!-- 

                            <div class="form-group">



                                <label>Pendamping</label>



                                <select name="txtbmfi_pendamping[]" class="form-select multiple-select-field" data-placeholder="Silahkan Dipilih" multiple
 required>



                                    <?php foreach ($bmfi_pendamping as $v) { ?>



                                    <option value="<?php echo $v['id_bmfi_pendamping'] ?>">

                                        <?php echo $v['nama_bmfi_pendamping'] ?></option>



                                    <?php } ?>



                                </select>



                            </div>
 -->





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






<input type="hidden" id="valIdKabupatenKota">



<input type="hidden" id="valIdKecamatan">



<input type="hidden" id="valIdkelurahan_desa">









<script>

var table = $("#tabel_bmfi_transaksi_microfinance").DataTable({




    // destroy: true,

    searching: false,

    processing: true,

    serverSide: true,

    
    ordering: false,


    order: [],



       "language": {
            "processing": "<div class='loading-gif'><img src='<?php echo base_url() ?>assets/img/loading.gif' style='display: block; margin: auto;'></div>"
        },


    // stateSave: true,

    ajax: {


        url: base_url + 'panel/bmfi_transaksi_microfinance/get_bmfi_transaksi_microfinance',


        type: "POST",


        data: function(d) {


            // console.log(d) 


            return $.extend({}, d, {


                tgl1: $("[name='tgl1']").val(),

                tgl2: $("[name='tgl2']").val(),
                

                txtketerangan_transaksi: $("#txtketerangan_transaksi").val(),



                txtbmfi_peserta_program: $("#txtbmfi_peserta_program").val(),



                txtbmfi_pendamping: $("#txtbmfi_pendamping").val(),




                txtnominal_pengembalian: $("#txtnominal_pengembalian").val(),


                 txtnominal_peminjaman: $("#txtnominal_peminjaman").val(),



          


                // txtMasjid: $("#txtMasjid").val(),



                // txtProvinsi: $("#txtProvinsi").val(),



                // txtKabupatenKota: $("#txtKabupatenKota").val(),



                // txtKecamatan: $("#txtKecamatan").val(),



                // txtkelurahan_desa: $("#txtkelurahan_desa").val(),



            })



        }



    },









    // columnDefs: [{



    //     targets: [0, 4, 4],



    //     orderable: false


    // }],








  // dom: 'Bfrtip',
  //       buttons: [
  //           'copy', 'csv', 'excel', 'pdf', 'print'
  //       ]
  //   } );






     "footerCallback": function (row, data, start, end, display) {
            var api = this.api(), data;
 
            // Remove the formatting to get integer data for summation
            var intVal = function (i) {
                return typeof i === 'string' ? 
                i.replace(/[\Rp. ,]/g, '') * 1 :
                 typeof i === 'number' ? 
                 i : 0;

            };
 
            // Total over all pages
            total_all = api
                .column(3)
                .data()
                .reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
 
            // Total over this page
            pageTotal = api
                .column(3, { page: 'current' })
                .data()
                .reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
 
            // Update footer

            var numFormat = $.fn.dataTable.render.number( '\.', '.', 0, 'Rp'  ).display;
            $(api.column(3).footer() ).html(numFormat( pageTotal ));

             // $(api.column(3).footer()).html('Rp' +' '+ pageTotal + ' ( Rp' +' '+ total_all + ' total)');
        },






//        dom: 'Bfrtip',
//         buttons: [
//             'copy', 'csv', 'excel', 'pdf', 'print'
//         ]
//     } );



// ({





});







$(document).ready(function() {



    $(".loader").hide();



    $('.select2').select2({



         theme: 'bootstrap-5'



    });



 $( '.multiple-select-field' ).select2( {

    theme: "bootstrap-5",

    width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',

    placeholder: $( this ).data( 'placeholder' ),

    closeOnSelect: false,

    
});





    $("#txtketerangan_transaksi").bind("keyup change", function(e) {



        e.preventDefault();



        table.draw();



    });





    $("#searchForm").submit(function(e) {



        e.preventDefault();



        $("#filterModal").modal('hide');



        table.draw();



    })






    $("#txtProvinsi").change(function() {



        var id = $(this).val();



        $.ajax({



            url: base_url + 'panel/bmfi_transaksi_microfinance/get_kabupaten_kota',



            type: "POST",



            data: {



                id: id



            },



            success: function(respon) {



                $("#txtKabupatenKota").html(respon);



            }



        })



    })







    $("#txtKabupatenKota").change(function() {



        var id = $(this).val();



        $.ajax({



            url: base_url + 'panel/bmfi_transaksi_microfinance/get_kecamatan',



            type: "POST",



            data: {



                id: id



            },



            success: function(respon) {



                $("#txtKecamatan").html(respon);



            }



        })



    })







    $("#txtKecamatan").change(function() {



        var id = $(this).val();



        $.ajax({



            url: base_url + 'panel/bmfi_transaksi_microfinance/get_kelurahan_desa',



            type: "POST",



            data: {



                id: id



            },



            success: function(respon) {



                $("#txtkelurahan_desa").html(respon);



            }



        })



    })







    $("[name='provinsi']").change(function() {



        var id = $(this).val();



        $.ajax({



            url: base_url + 'panel/bmfi_transaksi_microfinance/get_kabupaten_kota',



            type: "POST",



            data: {



                id: id



            },



            success: function(respon) {



                $("[name='kabupaten_kota']").html(respon);



            }



        })



    })







    $("[name='kabupaten_kota']").change(function() {



        var id = $(this).val();



        $.ajax({



            url: base_url + 'panel/bmfi_transaksi_microfinance/get_kecamatan',



            type: "POST",



            data: {



                id: id



            },



            success: function(respon) {



                $("[name='kecamatan']").html(respon);



            }



        })



    })







    $("[name='kecamatan']").change(function() {



        var id = $(this).val();



        $.ajax({



            url: base_url + 'panel/bmfi_transaksi_microfinance/get_kelurahan_desa',



            type: "POST",



            data: {



                id: id



            },



            success: function(respon) {



                $("[name='kelurahan_desa']").html(respon);



            }



        })



    })







    $("[name='provinsi2']").change(function() {



        var id = $(this).val();



        $.ajax({



            url: base_url + 'panel/bmfi_transaksi_microfinance/get_kabupaten_kota',



            type: "POST",



            data: {



                id: id



            },



            success: function(respon) {



                $("[name='kabupaten_kota2']").html(respon);



            }



        })



    })







    $("[name='kabupaten_kota2']").change(function() {



        var id = $(this).val();



        $.ajax({



            url: base_url + 'panel/bmfi_transaksi_microfinance/get_kecamatan',



            type: "POST",



            data: {



                id: id



            },



            success: function(respon) {



                $("[name='kecamatan2']").html(respon);



            }



        })



    })







    $("[name='kecamatan2']").change(function() {



        var id = $(this).val();



        $.ajax({



            url: base_url + 'panel/bmfi_transaksi_microfinance/get_kelurahan_desa',



            type: "POST",



            data: {



                id: id



            },



            success: function(respon) {



                $("[name='kelurahan_desa2']").html(respon);



            }



        })



    })







    $('#editModal').on('shown.bs.modal', function(e) {



        e.preventDefault();



        var idprovinsi = $("[name='provinsi2']").val();



        var idkabupaten_kota = $("#valIdKabupatenKota").val();



        var idkecamatan = $("#valIdKecamatan").val();



        var idkelurahan_desa = $("#valIdkelurahan_desa").val();







        // alert(idkecamatan);







        $.ajax({



            url: base_url + 'panel/bmfi_transaksi_microfinance/get_kabupaten_kota_selected',



            type: "POST",



            data: {



                id: idprovinsi,



                idkabupaten_kota: idkabupaten_kota



            },



            success: function(respon) {



                $("[name='kabupaten_kota2']").html(respon);



            }



        })







        $.ajax({



            url: base_url + 'panel/bmfi_transaksi_microfinance/get_kecamatan_selected',



            type: "POST",



            data: {



                id: idkabupaten_kota,



                idkecamatan: idkecamatan



            },



            success: function(respon) {



                $("[name='kecamatan2']").html(respon);



            }



        })







        $.ajax({



            url: base_url + 'panel/bmfi_transaksi_microfinance/get_kelurahan_desa_selected',



            type: "POST",



            data: {



                id: idkecamatan,



                idkelurahan_desa: idkelurahan_desa



            },



            success: function(respon) {



                $("[name='kelurahan_desa2']").html(respon);



            }



        })



    })











    $('body').on("click", ".btn-edit", function(e) {



        e.preventDefault();



        var id = $(this).attr('id');



        $.ajax({



            url: base_url + 'panel/bmfi_transaksi_microfinance/detail/' + id,



            type: "GET",



            dataType: "json",



            success: function(respon) {





                $("[name='id2']").val(respon.id_bmfi_transaksi_microfinance);



                $("[name='id_jalur2']").val(respon.id_jalur_akses).trigger('change');





                $("[name='tanggal2']").val(respon.tanggal);



                $("[name='nominal_pengembalian2']").val(respon.nominal_pengembalian);



                 $("[name='infak_peserta2']").val(respon.infak_peserta);



             $("[name='sisa_dana_cicilan_pengembalian2']").val(respon.sisa_dana_cicilan_pengembalian);





                $("[name='nominal_peminjaman2']").val(respon.nominal_peminjaman);



                $("[name='keterangan_transaksi2']").val(respon.keterangan_transaksi);



                // $("[name='provinsi2']").val(respon.id_provinsi).trigger('change');



                // $("[name='kabupaten_kota2']").val(respon.id_kabupaten_kota).trigger('change');



                // $("[name='kecamatan2']").val(respon.id_kecamatan).trigger('change');



                // $("[name='kelurahan_desa2']").val(respon.id_kelurahan_desa).trigger('change');







                $("[name='bmfi_peserta_program2']").val(respon.id_bmfi_peserta_program);





                $("[name='bmfi_pendamping2']").val(respon.id_bmfi_pendamping);






                $("[name='bmfi_jenis_angsuran2']").val(respon.id_bmfi_jenis_angsuran);



                $("[name='masjid2']").val(respon.id_masjid).trigger('change');







                $("[name='lampiran1']").val(respon.lampiran);




                $(".lampiran-place").css('background-image', 'url(' + base_url +

                    '/attachments/bmfi_transaksi_microfinance/' + respon.lampiran + ')');






                $("#valIdKabupatenKota").val(respon.id_kabupaten_kota);



                $("#valIdKecamatan").val(respon.id_kecamatan);



                $("#valIdkelurahan_desa").val(respon.id_kelurahan_desa);



                $("#editModal").modal('show');



            }



        })



    });







    $('body').on("click", ".btn-hapus", function(e) {



        e.preventDefault();



        $("#hiddenid").val($(this).attr('id'));



        $("#deleteModal").modal('show');



    });







    $('body').on("click", ".btn-konfirmasi-hapus", function(e) {



        e.preventDefault();



        $.ajax({



            url: base_url + 'panel/bmfi_transaksi_microfinance/delete/' + hiddenid.value,



            type: "GET",



            success: function(respon) {



                $("#deleteModal").modal('hide');



                toastr.success('Data berhasil dihapus.');



                $('#tabel_bmfi_transaksi_microfinance').DataTable().ajax.reload();



            }



        })



    })







    $('#bmfi_transaksi_microfinanceFormAdd').validate({



        rules:



        {



            // nominal_pengembalian: {



            //     required: true,



            // },





            keterangan_transaksi: {



                required: true,



            },











            tanggal: {



                required: true



            },









            bmfi_peserta_program: {



                required: true



            },







         


        },



        messages: {



            // nominal_pengembalian: {



            //     required: "Nominal Penerimaan tidak boleh kosong"



            // },







             keterangan_transaksi: {



                required: "keterangan_transaksi Penerimaan tidak boleh kosong"



            },









            tanggal: {



                required: "Tanggal Penerimaan harus diisi"



            },







            bmfi_peserta_program: {



                required: "Jenis Dana Penerimaan harap pilih salah satu"



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



                url: base_url + 'panel/bmfi_transaksi_microfinance/add_bmfi_transaksi_microfinance',



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



                        $("#bmfi_transaksi_microfinanceFormAdd")[0].reset();



                        $("#addModal").modal('hide');



                        $("[name='masjid']").val('').trigger('change');



                        // $("[name='provinsi']").val('').trigger('change');



                        // $("[name='kecamatan']").val('').trigger('change');



                        // $("[name='kabupaten_kota']").val('').trigger('change');



                        // $("[name='kelurahan_desa']").val('').trigger('change');



                        $(".box-masjid").hide();



                        toastr.success('Data berhasil disimpan.');


                        $('#tabel_bmfi_transaksi_microfinance').DataTable().ajax.reload();



                    }



               


            })



        }



    });







    $('#bmfi_transaksi_microfinanceFormEdit').validate({



        rules: {



            // nominal_pengembalian2: {



            //     required: true,



            // },



         


            keterangan_transaksi2: {



                required: true,



            },



            tanggal2: {



                required: true



            },






            bmfi_peserta_program2: {



                required: true



            },








        },



        messages: {



            // nominal_pengembalian2: {



            //     required: "nominal_pengembalian lengkap tidak boleh kosong"



            // },



         



             keterangan_transaksi2: {



                required: "keterangan_transaksi lengkap tidak boleh kosong"



            },







            tanggal2: {



                required: "Tanggal harap pilih salah satu"



            },





          


            bmfi_peserta_program2: {



                required: "bmfi_peserta_program harap pilih salah satu"



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



            var id = $("[name='id2']").val();



            $.ajax({



                url: base_url + 'panel/bmfi_transaksi_microfinance/edit_bmfi_transaksi_microfinance/' + id,



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



                    $("#tabel_bmfi_transaksi_microfinance").DataTable().ajax.reload();



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




     $(".jumlah").keyup(function(e) {
        // console.log('tes')

        var nilai = formatJumlah($(this).val(), ' ');

        // $(".rupiah").val(nilai);

        $(this).val(nilai);

    })


    function formatJumlah(angka, prefix) {

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

        return prefix == undefined ? rupiah : (rupiah ? '' + rupiah : '');

    }


})
</script>


