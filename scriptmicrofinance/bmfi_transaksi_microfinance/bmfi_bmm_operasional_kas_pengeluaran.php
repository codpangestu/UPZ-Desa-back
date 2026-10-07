<script type="text/javascript">
$(document).ready(function() {



$('select').selectpicker();


     // bsCustomFileInput.init()



})
</script>



 
<link rel="stylesheet" href="<?php echo base_url() ?>assets/template/plugins/datatables-bs-5/select2/css/select2.min.css">



 <link rel="stylesheet" href="<?php echo base_url() ?>assets/template/plugins/datatables-bs-5/select2-bootstrap5-theme/select2-bootstrap-5-theme.min.css"> 


 

<script src="<?php echo base_url() ?>assets/template/plugins/datatables-bs-5/select2/js/select2.min.js"></script>  


<!-- <script src="<?php echo base_url() ?>assets/template/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script> -->






<style>
    /* Menetapkan gaya teks tebal untuk judul dan konten */
    .swal-title {
        font-weight: bold !important;
    }

    .swal-text {
        font-weight: bold !important;
    }

     .swal-text {
        text-align: center !important;
    }

    
</style>







<div class="card">



    <div class="card-header">



 <center><h1 class="card-text"><b>BMM Operasional Kas (Pengeluaran)

        </p></b></h1></center>



<center><h5 class="card-text"><b><?= $this->session->userdata('sess_all')->nama_masjid; ?>

            </h5></center>

        <center>  <p><br><?= $this->session->userdata('sess_all')->alamat_masjid; ?>

        </p></b></center>



 


  <!-- <a href="" id="gambar_logo_dan_stempelDropdown" data-bs-toggle="dropdown" aria-expanded="false" class="link-fitur p-3 mb-3 font-weight-bold dropdown-toggle">Gambar Logo dan Cap Stempel    
    <br>
    <small><em>Harap diunggah jika belum tersedia, untuk keperluan pencetakan kwitansi digital dalam transaksi buku kas jika diperlukan.</em></small> 

</a> -->



  <ul class="dropdown-menu " aria-labelledby="gambar_logo_dan_stempelDropdown">
   
    <li><a href="<?php echo site_url('panel/mp/gambar_logo') ?>" class="dropdown-item  p-3 mb-3 font-weight-bold ">Gambar Logo</a></li>
    <li><a href="<?php echo site_url('panel/mp/cap_stempel') ?>" class="dropdown-item p-3 mb-3 font-weight-bold ">Cap Stempel</a></li>
  </ul>




        <div class="container">
    <div id="totalValues">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Total Nominal Masuk</th>
                    <th>Total Nominal Keluar</th>
                    <th>Saldo</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td id="totalNominalMasuk"></td>
                    <td id="totalNominalKeluar"></td>
                    <td id="saldo"></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>




<br>




        <a href="#" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addModal"><i
                class="fa fa-plus"></i> Tambah</a>



        <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#filterModal"><i
                class="fa fa-filter"></i> Filter</a>



        <a href="#" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#cetakModal"><i
                class="fa fa-print"></i> Cetak</a>

    </div>



    <div class="card-body">




         <div class="row">


    <div class="col-md-12">


     <div class="form-group">



             <label>Pencarian</label>



                <input type="text" id="txtketerangan" class="form-control" placeholder="Cari Data Berdasarkan Keterangan">



                </div>

         </div>


     </div>




        <div class="table-responsive">



            <table id="tabel_bmfi_bmm_operasional_kas_pengeluaran" class="table table-bordered" width="100%">



                <thead>



                    <tr>



                        <th class="align-middle">No</th>


                        <th class="align-middle">Tanggal</th>


                      
                            <th class="align-middle">Opsi</th>


                
                    <!-- <th class="align-middle">Opsi</th> -->





                        <th class="align-middle">Label Info Dana</th>


                        <th class="align-middle">Nominal (Rp)</th>


                         <th class="align-middle">Jumlah Penerima Manfaat (Jiwa)</th>



                          <th class="align-middle">Keterangan</th>




                        <th class="align-middle">Aksi</th>



                    </tr>



                </thead>



                   <tfoot>
            <tr>
                <th colspan="4" style="text-align:right">Total:</th>
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



                                <label>Nominal</label>



                                <input type="text" id="txtnominal_keluar" class="form-control">



                            </div>





                            <div class="form-group d-none">



                                <label>Jumlah Penerima Manfaat</label>



                                <input type="text" id="txtjumlah_penerima_manfaat" class="form-control">



                            </div>






<!-- 
                            <div class="form-group">



                                <label>Keterangan</label>



                                <input type="text" id="txtketerangan" class="form-control">



                            </div>
 -->






                                 <div class="form-group">



                                <label>Label Info Dana</label>



                                <select id="txtbmm_label_penggunaan_kas_operasional" class="form-control">



                                    <option value="">--Pilih--</option>



                                    <?php foreach ($bmm_label_penggunaan_kas_operasional as $v) { ?>



                                    <option value="<?php echo $v['id_bmm_label_penggunaan_kas_operasional'] ?>">

                                        <?php echo $v['nama_bmm_label_penggunaan_kas_operasional'] ?></option>



                                    <?php } ?>



                                </select>



                            </div>










                             <div class="row">



                        <div class="col-md-6">




                            <div class="form-group">



                                <label for="tanggal">Tanggal Awal</label>



                                <input type="date" class="form-control mb-2" name="tgl1" placeholder="input Tanggal">



                            </div>

                            <div class="form-group">

                                <label for="tanggal">Tanggal Akhir</label>



                                <input type="date" class="form-control mb-2" name="tgl2" placeholder="input Tanggal">



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



<div class="modal fade" id="addModal">



    <div class="modal-dialog modal-lg">



        <div class="modal-content">



            <div class="modal-header bg-secondary">



                <h5 class="modal-title">Tambah bmfi_bmm_operasional_kas_pengeluaran</h5>



                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">



                    <span aria-hidden="true">&times;</span>



                </button>



            </div>



            <form id="bmfi_bmm_operasional_kas_pengeluaranFormAdd">



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









<script>
// Fungsi untuk memeriksa tanggal yang dipilih
function periksaTanggal_input_pengeluaran() {
    // Mendapatkan elemen input tanggal
    var inputTanggal = document.querySelector('input[name="tanggal"]');
    // Mendapatkan tanggal yang dipilih
    var tanggalDipilih = new Date(inputTanggal.value);
    // Mendapatkan tanggal sekarang
    var tanggalSekarang = new Date();

    // Membandingkan tanggal yang dipilih dengan tanggal sekarang
    if (tanggalDipilih > tanggalSekarang) {
        // Jika tanggal yang dipilih lebih dari tanggal sekarang, tampilkan SweetAlert dengan gaya tulisan yang ditebalkan
        swal({
            title: "Peringatan!",
            text: "Tidak boleh memilih Tanggal Bukukas Pengeluaran lebih dari tanggal saat ini. ",
            icon: "warning",
            button: "OK",
            customClass: {
                title: 'text-bold', // Menambahkan gaya teks tebal untuk judul
                content: 'text-bold' // Menambahkan gaya teks tebal untuk teks konten
            },
          
            content: {
                element: "p",
                attributes: {
                    innerHTML: "Mohon pilih tanggal dan tahun yang sesuai."
                }
            }
        }).then((value) => {
            // Setelah pengguna menekan tombol OK, kosongkan nilai input tanggal
            inputTanggal.value = '';
        });
    }
}

// Menambahkan event listener untuk memanggil fungsi periksaTanggal saat nilai input tanggal berubah
document.querySelector('input[name="tanggal"]').addEventListener('change', periksaTanggal_input_pengeluaran);


</script>




             





                    <div class="row">


                        <div class="col-md-6">


                            <div class="form-group">



                                <label>Label Info Dana</label>



                                <select name="bmm_label_penggunaan_kas_operasional" class="form-control">



                                    <option value="">--Pilih--</option>



                                    <?php foreach ($bmm_label_penggunaan_kas_operasional as $v) { ?>



                                    <option value="<?php echo $v['id_bmm_label_penggunaan_kas_operasional'] ?>">

                                        <?php echo $v['nama_bmm_label_penggunaan_kas_operasional'] ?></option>



                                    <?php } ?>



                                </select>



                            </div>


                        </div>


                    </div>






                    <div class="row">



                        <div class="col-md-6">



                            <div class="form-group">



                                <label>Nominal</label>



                                <input type="text" name="nominal_keluar" class="form-control rupiah">



                            </div>



                        </div>


                    </div>




                      <div class="row">



                        <div class="col-md-12">



                            <div class="form-group">



                                <label>Jumlah Penerima Manfaat</label>



                                <input type="text" name="jumlah_penerima_manfaat" class="form-control jumlah">



                              <small>Jumlah Penerima Manfaat adalah hitungan Jumlah Orang Yang Merasakan Manfaat Karena Pengeluaran Biaya Tersebut </small>

                              <p>  <small>Tulis Angka 0 jika Tidak Ada </small></p>

                            




                            </div>



                        </div>


                    </div>
















                    <div class="row">



                        <div class="col-md-12">



                            <div class="form-group">



                                <label>Keterangan</label>



                                <textarea name="keterangan" class="form-control"></textarea>



                            </div>



                        </div>


                    </div>








                    <div class="row">


                        <div class="col-md-6">


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





<!-- 
                <div class="form-group">

                 <div class="form-check">

                <input class="form-check-input" type="checkbox"  id="disclaimer" name="disclaimer" >

                <label class="form-check-label" for="disclaimer"> Saya telah menyatakan bahwa data yang di input telah sesuai .
               
                </label>
            
            </div>
        
        </div>
 -->



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



                <h5 class="modal-title">Edit Bukukas Pengeluaran</h5>



                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">



                    <span aria-hidden="true">&times;</span>



                </button>



            </div>



            <form id="bmfi_bmm_operasional_kas_pengeluaranFormEdit">



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







<script>
// Fungsi untuk memeriksa tanggal yang dipilih
function periksaTanggal_edit_pengeluaran() {
    // Mendapatkan elemen input tanggal
    var inputTanggal = document.querySelector('input[name="tanggal2"]');
    // Mendapatkan tanggal yang dipilih
    var tanggalDipilih = new Date(inputTanggal.value);
    // Mendapatkan tanggal sekarang
    var tanggalSekarang = new Date();

    // Membandingkan tanggal yang dipilih dengan tanggal sekarang
    if (tanggalDipilih > tanggalSekarang) {
        // Jika tanggal yang dipilih lebih dari tanggal sekarang, tampilkan SweetAlert dengan gaya tulisan yang ditebalkan
        swal({
            title: "Peringatan!",
            text: "Tidak boleh memilih Tanggal Bukukas Pengeluaran lebih dari tanggal saat ini. ",
            icon: "warning",
            button: "OK",
            customClass: {
                title: 'text-bold', // Menambahkan gaya teks tebal untuk judul
                content: 'text-bold' // Menambahkan gaya teks tebal untuk teks konten
            },
          
            content: {
                element: "p",
                attributes: {
                    innerHTML: "Mohon pilih tanggal dan tahun yang sesuai."
                }
            }
        }).then((value) => {
            // Setelah pengguna menekan tombol OK, kosongkan nilai input tanggal
            inputTanggal.value = '';
        });
    }
}

// Menambahkan event listener untuk memanggil fungsi periksaTanggal saat nilai input tanggal berubah
document.querySelector('input[name="tanggal2"]').addEventListener('change', periksaTanggal_edit_pengeluaran);


</script>



                    <div class="row">



                        <div class="col-md-6">



                            <div class="form-group">



                                <label>Nominal</label>



                                <input type="text" name="nominal_keluar2" class="form-control rupiah">



                            </div>



                        </div>


                    </div>



                        <div class="row">



                        <div class="col-md-12">



                            <div class="form-group">



                                <label>Jumlah Penerima Manfaat</label>



                                <input type="text" name="jumlah_penerima_manfaat2" class="form-control jumlah">


                                 <small>Jumlah Penerima Manfaat adalah hitungan Jumlah Orang Yang Merasakan Manfaat Karena Pengeluaran Biaya Tersebut </small>


                                 <p>  <small>Tulis Angka 0 jika Tidak Ada </small></p>



                            </div>



                        </div>


                    </div>













                    <div class="row">



                        <div class="col-md-12">



                            <div class="form-group">



                                <label>Keterangan</label>



                                <textarea name="keterangan2" class="form-control"></textarea>



                            </div>



                        </div>


                    </div>



                  



            





                      <div class="row">


                        <div class="col-md-6">



                            <div class="form-group">



                                <label>Label Info Dana</label>



                                <select name="bmm_label_penggunaan_kas_operasional2" class="form-control">




                                    <?php foreach ($bmm_label_penggunaan_kas_operasional as $v) { ?>



                                    <option value="<?php echo $v['id_bmm_label_penggunaan_kas_operasional'] ?>">

                                        <?php echo $v['nama_bmm_label_penggunaan_kas_operasional'] ?></option>



                                    <?php } ?>



                                </select>



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



<!-- 
                <div class="form-group">

                 <div class="form-check">

                <input class="form-check-input" type="checkbox"  id="disclaimer2" name="disclaimer2" >

                <label class="form-check-label" for="disclaimer2"> Saya telah menyatakan bahwa data yang di input telah sesuai .
               
                </label>
            
            </div>
        
        </div>
 -->



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



<!-- Modal Cetak -->

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

                <form action="<?= site_url('panel/mp/cetak_bmfi_bmm_operasional_kas_pengeluaran') ?>" target="_blank">


                           




                      <div class="form-group">



                                <label>Label Info Dana</label>



                                <select name="txtbmm_label_penggunaan_kas_operasional[]" class="form-select multiple-select-field" data-placeholder="Silahkan Dipilih" multiple
 required>



                                    <?php foreach ($bmm_label_penggunaan_kas_operasional as $v) { ?>



                                    <option value="<?php echo $v['id_bmm_label_penggunaan_kas_operasional'] ?>">

                                        <?php echo $v['nama_bmm_label_penggunaan_kas_operasional'] ?></option>



                                    <?php } ?>



                                </select>



                            </div>








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





<script type="text/javascript">
    $(document).ready(function() {
        $.ajax({
            url: base_url + 'panel/bmfi_bmm_operasional_kas_pengeluaran/get_total_nominal',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                var totalNominalMasuk = response.total_nominal_masuk;
                var totalNominalKeluar = response.total_nominal_keluar;
                var saldo = response.saldo;

                // Tampilkan nilai masuk
                if (saldo.includes('Minus')) {
                    // Jika saldo memiliki label 'Minus', beri warna merah pada nilai masuk
                    $('#totalNominalMasuk').text(totalNominalMasuk).addClass('text-danger');
                } else {
                    $('#totalNominalMasuk').text(totalNominalMasuk);
                }

                $('#totalNominalKeluar').text(totalNominalKeluar);

                // Tampilkan nilai saldo
                if (saldo.includes('Minus')) {
                    // Jika nilai saldo memiliki label 'Minus', beri warna merah
                    $('#saldo').text(saldo).addClass('text-danger');
                } else {
                    $('#saldo').text(saldo);
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });
</script>





<script>
var table = $("#tabel_bmfi_bmm_operasional_kas_pengeluaran").DataTable({



    searching: false,



    processing: true,



    serverSide: true,


     ordering: false,

     

    order: [],



       "language": {
            "processing": "<div class='loading-gif'><img src='<?php echo base_url() ?>assets/img/loading.gif' style='display: block; margin: auto;'></div>"
        },




    ajax: {



        url: base_url + 'panel/bmfi_bmm_operasional_kas_pengeluaran/get_bmfi_bmm_operasional_kas_pengeluaran',



        type: "POST",



        data: function(d) {



            return $.extend({}, d, {







                tgl1: $("[name='tgl1']").val(),

                tgl2: $("[name='tgl2']").val(),



                txtketerangan: $("#txtketerangan").val(),





           



                 txtbmm_label_penggunaan_kas_operasional: $("#txtbmm_label_penggunaan_kas_operasional").val(),




                txtnominal_keluar: $("#txtnominal_keluar").val(),


                    txtjumlah_penerima_manfaat: $("#txtjumlah_penerima_manfaat").val(),




                //txtMasjid: $("#txtMasjid").val(),




              


            })







        }



    },



    // columnDefs: [{



    //     targets: [0, 9, 9],



    //     orderable: false



    // }],





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
                .column(4 )
                .data()
                .reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
 
            // Total over this page
            pageTotal = api
                .column(4, { page: 'current' })
                .data()
                .reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
 
            // Update footer

            var numFormat = $.fn.dataTable.render.number( '\.', '.', 0, 'Rp'  ).display;
            $(api.column(4).footer() ).html(numFormat( pageTotal ));

             // $(api.column(5).footer()).html('Rp' +' '+ pageTotal + ' ( Rp' +' '+ total_all + ' total)');
        },





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










    // $("#txtNama, #txtLevel, #txtlabel, #txtMasjid").bind("keyup change change change", function(e) {



    //     e.preventDefault();



    //     table.draw();



    // });





    $("#txtketerangan").bind("keyup change", function(e) {



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



            url: base_url + 'panel/bmfi_bmm_operasional_kas_pengeluaran/get_kabupaten_kota',



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



            url: base_url + 'panel/bmfi_bmm_operasional_kas_pengeluaran/get_kecamatan',



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



            url: base_url + 'panel/bmfi_bmm_operasional_kas_pengeluaran/get_kelurahan_desa',



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



            url: base_url + 'panel/bmfi_bmm_operasional_kas_pengeluaran/get_kabupaten_kota',



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



            url: base_url + 'panel/bmfi_bmm_operasional_kas_pengeluaran/get_kecamatan',



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



            url: base_url + 'panel/bmfi_bmm_operasional_kas_pengeluaran/get_kelurahan_desa',



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



            url: base_url + 'panel/bmfi_bmm_operasional_kas_pengeluaran/get_kabupaten_kota',



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



            url: base_url + 'panel/bmfi_bmm_operasional_kas_pengeluaran/get_kecamatan',



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



            url: base_url + 'panel/bmfi_bmm_operasional_kas_pengeluaran/get_kelurahan_desa',



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



            url: base_url + 'panel/bmfi_bmm_operasional_kas_pengeluaran/get_kabupaten_kota_selected',



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



            url: base_url + 'panel/bmfi_bmm_operasional_kas_pengeluaran/get_kecamatan_selected',



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



            url: base_url + 'panel/bmfi_bmm_operasional_kas_pengeluaran/get_kelurahan_desa_selected',



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







    $('body').on('change', ".id_status_validasi_bukukas", function(e) {



        e.preventDefault();



        var id_bmfi_bmm_operasional_kas_pengeluaran = $(this).attr('id_bmfi_bmm_operasional_kas_pengeluaran');



        var id_status_validasi_bukukas;



            if ($(this).is(':checked')) {

                id_status_validasi_bukukas = '1';

            } else {

                id_status_validasi_bukukas = '0';

            }





        $.ajax({



            url: base_url + 'panel/bmfi_bmm_operasional_kas_pengeluaran/changeid_status_validasi_bukukas',



            type: "POST",



            data: {



                id_bmfi_bmm_operasional_kas_pengeluaran: id_bmfi_bmm_operasional_kas_pengeluaran,



                id_status_validasi_bukukas: id_status_validasi_bukukas



            },



            success: function(respon) {



                toastr.success('id_status_validasi_bukukas berhasil diubah.');



                $('#tabel_bmfi_bmm_operasional_kas_pengeluaran').DataTable().ajax.reload();



            }



        })



    });









    $('body').on("click", ".btn-edit", function(e) {



        e.preventDefault();



        var id = $(this).attr('id');



        $.ajax({



            url: base_url + 'panel/bmfi_bmm_operasional_kas_pengeluaran/detail/' + id,



            type: "GET",



            dataType: "json",



            success: function(respon) {











                $("[name='id2']").val(respon.id_bmfi_bmm_operasional_kas_pengeluaran);



                $("[name='id_jalur2']").val(respon.id_jalur_akses).trigger('change');





                $("[name='tanggal2']").val(respon.tanggal);



                $("[name='nominal_keluar2']").val(respon.nominal_keluar);



                $("[name='jumlah_penerima_manfaat2']").val(respon.jumlah_penerima_manfaat);









                $("[name='keterangan2']").val(respon.keterangan);



                // $("[name='provinsi2']").val(respon.id_provinsi).trigger('change');



                // $("[name='kabupaten_kota2']").val(respon.id_kabupaten_kota).trigger('change');



                // $("[name='kecamatan2']").val(respon.id_kecamatan).trigger('change');



                // $("[name='kelurahan_desa2']").val(respon.id_kelurahan_desa).trigger('change');












                $("[name='bmm_label_penggunaan_kas_operasional2']").val(respon.id_bmm_label_penggunaan_kas_operasional);







                $("[name='masjid2']").val(respon.id_masjid).trigger('change');







                $("[name='lampiran1']").val(respon.lampiran);



            


                $(".lampiran-place").css('background-image', 'url(' + base_url +

                    '/attachments/bmfi_bmm_operasional_kas_pengeluaran/' + respon.lampiran + ')');





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



            url: base_url + 'panel/bmfi_bmm_operasional_kas_pengeluaran/delete/' + hiddenid.value,



            type: "GET",



            success: function(respon) {



                $("#deleteModal").modal('hide');



                toastr.success('Data berhasil dihapus.');



                $('#tabel_bmfi_bmm_operasional_kas_pengeluaran').DataTable().ajax.reload();



            }



        })



    })







    $('#bmfi_bmm_operasional_kas_pengeluaranFormAdd').validate({



        rules:



        {



            nominal_keluar: {



                required: true,



            },



                jumlah_penerima_manfaat: {



                required: true,



            },












            keterangan: {



                required: true,



            },











            tanggal: {



                required: true



            },

















              bmm_label_penggunaan_kas_operasional: {



                required: true



            },





            // disclaimer: {



            //     required: true



            // },




         



        },



        messages: {



            nominal_keluar: {



                required: "Nominal lengkap tidak boleh kosong"



            },





            jumlah_penerima_manfaat: {



                required: "Jumlah Penerima Manfaat tidak boleh kosong"



            },







              keterangan: {



                required: "keterangan lengkap tidak boleh kosong"



            },









            tanggal: {



                required: "tanggal harus diisi"



            },

















            bmm_label_penggunaan_kas_operasional: {



                required: "Label Info Dana harap pilih salah satu"



            },




            // disclaimer: {



            //     required: "Persetujuan Harap di cheklist"



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



                url: base_url + 'panel/bmfi_bmm_operasional_kas_pengeluaran/add_bmfi_bmm_operasional_kas_pengeluaran',



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



                        $("#bmfi_bmm_operasional_kas_pengeluaranFormAdd")[0].reset();



                        $("#addModal").modal('hide');



                        $("[name='masjid']").val('').trigger('change');



                        $("[name='provinsi']").val('').trigger('change');



                        $("[name='kecamatan']").val('').trigger('change');



                        $("[name='kabupaten_kota']").val('').trigger('change');



                        $("[name='kelurahan_desa']").val('').trigger('change');



                        $(".box-masjid").hide();



                        toastr.success('Data berhasil disimpan.');



                        $("#tabel_bmfi_bmm_operasional_kas_pengeluaran").DataTable().ajax.reload();



                    }



            


            })



        }



    });







    $('#bmfi_bmm_operasional_kas_pengeluaranFormEdit').validate({



        rules: {



            nominal_keluar2: {



                required: true,



            },



            jumlah_penerima_manfaat2: {



                required: true,



            },




          



             keterangan2: {



                required: true,



            },



            tanggal2: {



                required: true



            },












            bmm_label_penggunaan_kas_operasional2: {



                required: true



            },



            //  disclaimer2: {



            //     required: true



            // },




        },



        messages: {



            nominal_keluar2: {



                required: "nominal_keluar lengkap tidak boleh kosong"



            },



                jumlah_penerima_manfaat2: {



                required: "Jumlah Penerima Manfaat tidak boleh kosong"



            },



            





              keterangan2: {



                required: "keterangan lengkap tidak boleh kosong"



            },






            tanggal2: {



                required: "Tanggal harap pilih salah satu"



            },














            bmm_label_penggunaan_kas_operasional2: {



                required: "Label Info Dana Pengeluaran harap pilih salah satu"



            },




            // disclaimer2: {



            //     required: "Persetujuan Harap di cheklist"



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



                url: base_url + 'panel/bmfi_bmm_operasional_kas_pengeluaran/edit_bmfi_bmm_operasional_kas_pengeluaran/' + id,



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



                    $("#tabel_bmfi_bmm_operasional_kas_pengeluaran").DataTable().ajax.reload();



                }



            })



        }



    });

    $(".rupiah").keyup(function(e) {
        console.log('tes')

        var nilai = formatRupiah($(this).val(), 'Rp. ');

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
        console.log('tes')

        var nilai = formatJumlah($(this).val(), '');

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