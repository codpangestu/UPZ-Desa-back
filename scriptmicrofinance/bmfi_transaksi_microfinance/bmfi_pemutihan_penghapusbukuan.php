<style>

    .foto-src {

        width: 150px;

        height: 150px;

        background-position: center;

        background-repeat: no-repeat;

        background-size: cover;

    }


</style>






<link rel="stylesheet" href="<?php echo base_url() ?>assets/template/plugins/datatables-bs-5/select2/css/select2.min.css">


<link rel="stylesheet" href="<?php echo base_url() ?>assets/template/plugins/datatables-bs-5/select2-bootstrap5-theme/select2-bootstrap-5-theme.min.css"> 



<script src="<?php echo base_url() ?>assets/template/plugins/datatables-bs-5/select2/js/select2.min.js"></script>
    
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.18/dist/css/bootstrap-select.min.css">


<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.18/dist/js/bootstrap-select.min.js"></script>



<script src="<?php echo base_url() ?>assets/template/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>









<div class="card">

    <div class="card-header">



 <center><h1 class="card-text"><b>Pemutihan Penghapusbukuan Microfinance

        </p></b></h1></center>



<center><h5 class="card-text"><b><?= $this->session->userdata('sess_all')->nama_masjid; ?>

            </h5></center>

        <center>  <p><br><?= $this->session->userdata('sess_all')->alamat_masjid; ?>

        </p></b></center>

<br>


        <a href="#" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addModal"><i class="fa fa-plus"></i> Tambah</a>




        <a href="#" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#cetakModal"><i
                class="fa fa-print"></i> Cetak</a>



    </div>

    <div class="card-body">

        <form class="mb-3">

            <div class="form-row align-items-center">

                <div class="col-10">

                    <label class="sr-only" for="txtNama">Judul</label>

                    <input type="text" class="form-control mb-2" id="txtJudul" placeholder="Judul pemutihan_penghapusbukuan">

                </div>

               

                 <div class="col-auto d-none">
                    <label class="sr-only" for="txtSlug_donasi">Slug bmfi_pemutihan_penghapusbukuan</label>
                    <input type="text" class="form-control mb-2" id="txtSlug_bmfi_pemutihan_penghapusbukuan" placeholder="Slug_bmfi_pemutihan_penghapusbukuan">
                </div>



            </div>

        </form>

        <div class="table-responsive">

            <table class="table table-bordered datatables" width="100%">

                <thead>

                    <tr>

                        <th class="align-middle" width="50">No</th>

                         <th class="align-middle">Tgl</th>


                          <th class="align-middle">Nama Peserta</th>



                        <th class="align-middle">Judul</th>


                    

                         <th class="align-middle">Keterangan</th>

                      
                        <th class="align-middle">Status</th>

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

                <h5 class="modal-title">Tambah Pemutihan Penghapusbukuan Microfinance</h5>

                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>

            <form id="bmfi_pemutihan_penghapusbukuanFormAdd">

                <div class="modal-body">

                   



                         <div class="row">


                             <div class="col-md-8">

                            <div class="form-group">

                                <label>Tanggal</label>

                                <input type="date" class="form-control" name="tgl" />

                                  <small class="text-secondary">Tanggal pada saat bmfi_pemutihan_penghapusbukuan dibuat </small><br>


                            </div>

                        </div>

                    </div>


                    
<!-- 

                     <div class="row">

                        <div class="col-md-8">


                            <div class="form-group">

                                <label>Status bmfi_pemutihan_penghapusbukuan</label>

                                <select name="status" class="form-control">

                                    <option value="">Pilih</option>

                                    <?php

                                    $status = ['Belum Selesai', 'Sudah Selesai'];

                                    foreach ($status as $st => $s) {

                                    ?>

                                        <option value="<?= $st ?>"><?= $s ?></option>

                                    <?php } ?>

                                </select>


                                  <small class="text-secondary">Status bmfi_pemutihan_penghapusbukuan, Sudah Selesai atau Belum Selesai </small><br>

                            </div>

                        </div>
                    </div>


 -->
                        <div class="row">

                        <div class="col-md-8">

                            <div class="form-group">

                                <label>Kategori pemutihan_penghapusbukuan</label>

                                <select name="bmfi_kategori_pemutihan_penghapusbukuan" class="form-control">

                                    <option value="">Pilih</option>

                                    <?php foreach ($bmfi_kategori_pemutihan_penghapusbukuan as $t) { ?>

                                        <option value="<?php echo $t['id_bmfi_kategori_pemutihan_penghapusbukuan'] ?>"><?php echo $t['nama_bmfi_kategori_pemutihan_penghapusbukuan'] ?></option>

                                    <?php } ?>

                                </select>


                                  <small class="text-secondary">Jenis Kategori pemutihan_penghapusbukuan </small><br>

                            </div>

                              </div>

                          </div>




 <div class="row">

<div class="col-md-12">

<div class="form-group">
    <label for="peserta_program_bmfi_pemutihan_penghapusbukuan">Peserta Program</label>
    <select name="peserta_program_bmfi_pemutihan_penghapusbukuan" class="form-control">
        <option value="">Pilih</option>
        <?php foreach ($peserta_program_bmfi_pemutihan_penghapusbukuan as $j) { ?>
            <option value="<?php echo $j['nama_peserta_program_bmfi_asesmen'] ?>">
                <?php echo $j['nama_peserta_program_bmfi_asesmen'] ?>
            </option>
        <?php } ?>
    </select>


     <small class="text-secondary">Untuk  Foto Peserta Program </small><br>


   

</div>

</div>

</div>








<style>
    #imagePlayer {
        width: 400px; /* Adjust the width as per your requirements */
        height: auto; /* Automatically adjust the height to maintain aspect ratio */
        max-height: 600px; /* Set a maximum height to limit the image's height */
    }
</style>



 <div class="row">

 <div class="col-md-12">

<div class="form-group">
    <label for="pendamping_bmfi_pemutihan_penghapusbukuan">Pendamping pemutihan_penghapusbukuan</label>
    <select name="pendamping_bmfi_pemutihan_penghapusbukuan" class="form-control">
        <option value="">Pilih</option>
        <?php foreach ($pendamping_bmfi_pemutihan_penghapusbukuan as $h) { ?>
            <option value="<?php echo $h['foto_profil_pendamping_bmfi_asesmen'] ?>">
                <?php echo $h['nama_pendamping_bmfi_asesmen'] ?>
            </option>
        <?php } ?>
    </select>


      <small class="text-secondary">Untuk Gambar Pendamping pemutihan_penghapusbukuan </small><br>



    <img id="imagePlayer_pendamping_bmfi_pemutihan_penghapusbukuan" class="mt-2 mb-3" src="" alt="Selected Image">
</div>

</div>

</div>


<script>
    $(document).ready(function() {
        $('select[name="pendamping_bmfi_pemutihan_penghapusbukuan"]').change(function() {
            var imageSrc = '<?php echo base_url("attachments/masjid-musholla/bmfi_asesmen/pendamping_bmfi_pemutihan_penghapusbukuan/") ?>' + $(this).val();
            $('#imagePlayer_pendamping_bmfi_pemutihan_penghapusbukuan').attr('src', imageSrc);
        });
    });
</script>


<!-- 

<script>
    $(document).ready(function() {
        $('select[name="pendamping_bmfi_pemutihan_penghapusbukuan"]').change(function() {
            var imageSrc = '<?php echo base_url("attachments/masjid-musholla/bmfi_asesmen/pendamping/") ?>' + $(this).val();
            $('#imagePlayer_pendamping_bmfi_pemutihan_penghapusbukuan').attr('src', imageSrc);
        });
    });
</script>
 -->

                        


                 

                      

                    <div class="row">

                        <div class="col-12">

                            <div class="form-group">

                                <label>Judul pemutihan_penghapusbukuan Microfinance</label>

                                <textarea name="judul" class="form-control"  rows="2"></textarea>

                                  <small class="text-secondary">Judul yang di input akan menjadi Judul pada Bmfi pemutihan_penghapusbukuan Microfinance</small><br>

                            </div>

                        </div>

                         </div>







       

                    <div class="row">

                        <div class="col-12">

                            <div class="form-group">

                                <label>Judul Nominal Pemutihan</label>

                                <textarea name="jumlah_nominal_pemutihan_penghapusbukuan" class="form-control"  rows="2"></textarea>

                               

                            </div>

                        </div>

                         </div>



               




               

                    <div class="row">

                        <div class="col-12">

                            <div class="form-group">

                                <label>Keterangan</label>

                                <textarea name="keterangan" class="form-control"></textarea>

                                  <small class="text-secondary">Keterangan Bmfi pemutihan_penghapusbukuan berfungsi untuk  memberikan informasi tambahan seperti narasi penjelasan tentang peserta program seperti keterangan usaha dan informasi lainnya</small><br>

                            </div>

                        </div>

                         </div>


                



                    <div class="row">

                        <div class="col-md-12">

                            <div class="form-group">

                                <label>Foto Bukti Pemutihan</label>

                                <div class="input-group">

                                    <div class="custom-file">

                                        <input type="file" class="custom-file-input" name="foto" id="foto">

                                        <label class="custom-file-label" for="foto">Pilih file</label>

                                         </div>

                        </div>


                        <small class="text-secondary">Gambar Foto akan digunakan sebagai informasi tambahan pada bmfi_pemutihan_penghapusbukuan dan hanya bisa 1 Gambar </small>
                        <br>

                        <small class="text-secondary"><i>Format file yang diizinkan <b>.jpg, .jpeg, png, gif</b>. Ukuran file maksimal <b>2 MB</b>.</i></small><br>

                        <small class="upload-error text-danger"></small>

                    </div>

                                

                        </div>

                    </div>

                </div>


             


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












<!-- modal cetak -->

<div class="modal fade" id="cetakModal">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header bg-secondary">

                <h5 class="modal-title">Cetak pemutihan_penghapusbukuan</h5>

                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>


          

           

                <div class="modal-body">


   <form action="<?= site_url('panel/mp/cetak_bmfi_pemutihan_penghapusbukuan') ?>" target="_blank">

                   



                    <div class="form-group">

                        <label>Tanggal Awal</label>

                        <input type="date" name="tgl1" class="form-control" required>

                    </div>

                    <div class="form-group">

                        <label>Tanggal Akhir</label>

                        <input type="date" name="tgl2" class="form-control" required>

                    </div>

                    


                    <!--  <div class="row">

                        <div class="col-md-8">


                            <div class="form-group">

                                <label>Status bmfi_pemutihan_penghapusbukuan</label>

                                <select name="txtstatus_bmfi_pemutihan_penghapusbukuan" class="form-control">

                                    <option value="">Pilih</option>

                                    <?php

                                    $status = ['Belum Selesai', 'Sudah Selesai'];

                                    foreach ($status as $st => $s) {

                                    ?>

                                        <option value="<?= $st ?>"><?= $s ?></option>

                                    <?php } ?>

                                </select>


                                  <small class="text-secondary">Status bmfi_pemutihan_penghapusbukuan, Sudah Selesai atau Belum Selesai </small><br>

                            </div>

                        </div>
                    </div>
 -->


                        <div class="row">

                        <div class="col-md-8">

                            <div class="form-group">

                                <label>Kategori pemutihan_penghapusbukuan</label>

                                  <select name="txtbmfi_kategori_pemutihan_penghapusbukuan[]" class="form-select multiple-select-field" data-placeholder="Silahkan Dipilih" multiple
 required>



                                <!-- <select name="bmfi_kategori_pemutihan_penghapusbukuan" class="form-control"> -->

                                    <option value="">Pilih</option>

                                    <?php foreach ($bmfi_kategori_pemutihan_penghapusbukuan as $t) { ?>

                                        <option value="<?php echo $t['id_bmfi_kategori_pemutihan_penghapusbukuan'] ?>"><?php echo $t['nama_bmfi_kategori_pemutihan_penghapusbukuan'] ?></option>

                                    <?php } ?>

                                </select>


                                  <small class="text-secondary">Jenis Kategori bmfi_pemutihan_penghapusbukuan </small><br>

                            </div>

                              </div>

                          </div>





                      

                  <!--   <div class="row">

                        <div class="col-12">

                            <div class="form-group">

                                <label>Judul bmfi_pemutihan_penghapusbukuan</label>

                                <textarea name="judul_bmfi_pemutihan_penghapusbukuan" class="form-control"  rows="2"></textarea>


                            </div>

                        </div>

                         </div>

 -->


             

               </div>


                <div class="modal-footer">

                    <span class="loader"><i class="fa fa-spinner fa-spin"></i> Sedang proses...</span>

                    <button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal"><i class="fa fa-times"></i> Batal</button>

                    <button type="submit" class="btn btn-primary btn-sm" ><i class="fa fa-check"></i> Cetak</button>

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

                <h5 class="modal-title">Edit pemutihan_penghapusbukuan Microfinance</h5>

                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>

            <form id="bmfi_pemutihan_penghapusbukuanFormEdit">

                <input type="hidden" name="id2">

                <div class="modal-body">



                            

                         <div class="row">


                             <div class="col-md-8">

                            <div class="form-group">

                                <label>Tanggal</label>

                                <input type="date" class="form-control" name="tgl2" />

                                  <small class="text-secondary">Tanggal pada saat pemutihan_penghapusbukuan dibuat </small><br>


                            </div>

                        </div>

                    </div>


                   


<!-- 

                          <div class="row">

                             <div class="col-md-6">

                            <div class="form-group">

                                <label>Status bmfi_pemutihan_penghapusbukuan</label>

                                <select name="status2" class="form-control">

                                    <option value="">Pilih</option>

                                    <?php

                                    $status = ['Belum Selesai', 'Sudah Selesai'];

                                    foreach ($status as $st => $s) {

                                    ?>

                                        <option value="<?= $st ?>"><?= $s ?></option>

                                    <?php } ?>

                                </select>

                                 <small class="text-secondary">Status bmfi_pemutihan_penghapusbukuan, Sudah Selesai atau Belum Selesai </small><br>

                            </div>

                        </div>

                    </div>

 -->


                    <div class="row">

                        <div class="col-md-6">

                    
                            <div class="form-group">

                                <label>Kategori pemutihan_penghapusbukuan</label>

                                <select name="bmfi_kategori_pemutihan_penghapusbukuan2" class="form-control">

                                    <option value="">Pilih</option>

                                    <?php foreach ($bmfi_kategori_pemutihan_penghapusbukuan as $t) { ?>

                                        <option value="<?php echo $t['id_bmfi_kategori_pemutihan_penghapusbukuan'] ?>"><?php echo $t['nama_bmfi_kategori_pemutihan_penghapusbukuan'] ?></option>

                                    <?php } ?>

                                </select>


                                  <small class="text-secondary">Jenis Kategori pemutihan_penghapusbukuan </small><br>

                            </div>


                            </div>

                            </div>


 <div class="row">

 <div class="col-md-12">


<div class="form-group">
    <label for="peserta_program_bmfi_pemutihan_penghapusbukuan2">Peserta Program pemutihan_penghapusbukuan</label>
    <select name="peserta_program_bmfi_pemutihan_penghapusbukuan2" class="form-control">
        <option value="">Pilih</option>
        <?php foreach ($peserta_program_bmfi_pemutihan_penghapusbukuan as $j) { ?>
            <option value="<?php echo $j['id_peserta_program_bmfi_asesmen'] ?>">
                <?php echo $j['nama_peserta_program_bmfi_asesmen'] ?>
            </option>
        <?php } ?>
    </select>


     <small class="text-secondary">Untuk Latar Lokasi Tempat pemutihan_penghapusbukuan </small><br>



</div>

</div>

</div>









<style>
    #imagePlayer {
        width: 400px; /* Adjust the width as per your requirements */
        height: auto; /* Automatically adjust the height to maintain aspect ratio */
        max-height: 600px; /* Set a maximum height to limit the image's height */
    }
</style>



 <div class="row">

 <div class="col-md-12">

<div class="form-group">
    <label for="pendamping_bmfi_pemutihan_penghapusbukuan2">Pendamping pemutihan_penghapusbukuan</label>
    <select name="pendamping_bmfi_pemutihan_penghapusbukuan2" class="form-control">
        <option value="">Pilih</option>
        <?php foreach ($pendamping_bmfi_pemutihan_penghapusbukuan as $h) { ?>
            <option value="<?php echo $h['id_pendamping_bmfi_asesmen'] ?>">
                <?php echo $h['nama_pendamping_bmfi_asesmen'] ?>
            </option>
        <?php } ?>
    </select>

<!-- 
      <small class="text-secondary">Untuk Gambar pendamping bmfi_pemutihan_penghapusbukuan </small><br>



    <img id="imagePlayer_pendamping_bmfi_pemutihan_penghapusbukuan2" class="mt-2 mb-3" src="" alt="Selected Image">
</div>

</div>

</div>


<script>
    $(document).ready(function() {
        $('select[name="pendamping_bmfi_pemutihan_penghapusbukuan2"]').change(function() {
            var imageSrc = '<?php echo base_url("attachments/masjid-musholla/bmfi_pemutihan_penghapusbukuan/pendamping_bmfi_pemutihan_penghapusbukuan/") ?>' + $(this).val();
            $('#imagePlayer_pendamping_bmfi_pemutihan_penghapusbukuan2').attr('src', imageSrc);
        });
    });
</script>
 -->


    
                      

                    
                    <div class="row">

                        <div class="col-12">

                            <div class="form-group">

                                <label>Judul pemutihan_penghapusbukuan Microfinance</label>

                                <textarea name="judul2" class="form-control"  rows="2"></textarea>


                                 <small class="text-secondary">Judul yang di input akan menjadi Judul pada Bmfi pemutihan_penghapusbukuan Microfinance</small><br>


                            </div>

                        </div>

                         </div>






              <div class="row">

                        <div class="col-12">

                            <div class="form-group">

                                <label>Judul Nominal Pemutihan</label>

                                <textarea name="jumlah_nominal_pemutihan_penghapusbukuan2" class="form-control"  rows="2"></textarea>

                               

                            </div>

                        </div>

                         </div>





                    <div class="row">

                        <div class="col-12">

                            <div class="form-group">

                                <label>Keterangan</label>

                                <textarea name="keterangan2" class="form-control"></textarea>

                                <small class="text-secondary">Keterangan Bmfi pemutihan_penghapusbukuan berfungsi untuk  memberikan informasi tambahan seperti narasi penjelasan tentang peserta program seperti keterangan usaha dan informasi lainnya. </small><br>

                            </div>

                        </div>

                    </div>






                    <div class="row">

                        <div class="col-md-8">

                            <div class="form-group">

                                <label>Foto Bukti Pemutihan</label>

                                <input type="hidden" name="foto1">

                                <div class="foto-src img-thumbnail mb-3"></div>

                                <div class="input-group">

                                    <div class="custom-file">

                                        <input type="file" class="custom-file-input" name="foto2" id="foto2">

                                        <label class="custom-file-label" for="foto2">Pilih file</label>

                                         </div>

                        </div>


                        <small class="text-secondary">Gambar Foto akan digunakan sebagai informasi tambahan pada bmfi_pemutihan_penghapusbukuan dan hanya bisa 1 Gambar </small>
                        <br>

                        <small class="text-secondary"><i>Format file yang diizinkan <b>.jpg, .jpeg, png, gif</b>. Ukuran file maksimal <b>2 MB</b>.</i></small><br>

                        <small class="upload-error text-danger"></small>

                    </div>

                                    

                        </div>

                    </div>


<!-- 
                    <div class="row">

                        <div class="col-md-12">

                            <div class="form-group">

                                <label>Video bmfi_pemutihan_penghapusbukuan (Iframe)</label>

                                <textarea name="iframe2" rows="6" class="form-control" placeholder="Contoh, https://www.youtube.com/embed/72m7REgd5ZA"></textarea>

                            </div>

                        </div>

                    </div>
 -->

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



<script>

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



        var table = $(".datatables").DataTable({

            searching: false,

            processing: true,

            serverSide: true,

             ordering: false,

            order: [],

            ajax: {

                url: base_url + 'panel/bmfi_pemutihan_penghapusbukuan/get_bmfi_pemutihan_penghapusbukuan',

                type: "POST",

                data: function(d) {

                    return $.extend({}, d, {

                        txtJudul: $("#txtJudul").val(),

                    

                        txtSlug_bmfi_pemutihan_penghapusbukuan: $("#txtSlug_bmfi_pemutihan_penghapusbukuan").val(),

                    });

                }

            },

            // columnDefs: [{

            //     targets: [0, 3, 4],

            //     orderable: false

            // }]

        });





//  $( '.multiple-select-field' ).select2( {

//     theme: "bootstrap-5",

//     width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',

//     placeholder: $( this ).data( 'placeholder' ),

//     closeOnSelect: false,

    
// });




        $("#txtJudul").bind("keyup keyup", function(e) {

            e.preventDefault();

            table.draw();

        });



           $('body').on('change', ".status-aktif-rencana-kerja", function(e) {

            e.preventDefault();

            var id = $(this).attr('data');

            var status;

            if ($(this).is(':checked')) {

                status = 'Sudah Selesai';

            } else {

                status = 'Belum Selesai';

            }

            $.ajax({

                url: base_url + 'panel/bmfi_pemutihan_penghapusbukuan/changestatus',

                type: "POST",

                data: {

                    id: id,

                    status: status

                },

                success: function(respon) {

                    toastr.success('Status berhasil diubah.');

                    $('.datatables').DataTable().ajax.reload();

                }

            })

        });







        $('body').on("click", ".btn-edit", function(e) {

            e.preventDefault();

            var id = $(this).attr('id');

            $.ajax({

                url: base_url + 'panel/bmfi_pemutihan_penghapusbukuan/detail/' + id,

                type: "GET",

                dataType: "json",

                success: function(respon) {

                    $("[name='id2']").val(respon.id_bmfi_pemutihan_penghapusbukuan);

                    $("[name='judul2']").val(respon.judul);


                    $("[name='jumlah_nominal_pemutihan_penghapusbukuan2']").val(respon.jumlah_nominal_pemutihan_penghapusbukuan);

                    $("[name='bmfi_kategori_pemutihan_penghapusbukuan2']").val(respon.id_bmfi_kategori_pemutihan_penghapusbukuan);


                      $("[name='peserta_program_bmfi_pemutihan_penghapusbukuan2']").val(respon.peserta_program_bmfi_pemutihan_penghapusbukuan);

                     $("[name='pendamping_bmfi_pemutihan_penghapusbukuan2']").val(respon.pendamping_bmfi_pemutihan_penghapusbukuan);




                    $("[name='tgl2']").val(respon.tgl_bmfi_pemutihan_penghapusbukuan);

                 



                    $("[name='iframe2']").val(respon.video_bmfi_pemutihan_penghapusbukuan);

                    CKEDITOR.instances['keterangan2'].setData(respon.keterangan);


                    

                    $("[name='foto1']").val(respon.gambar_foto);

                    $(".foto-src").css('background-image', "url('" + base_url + 'attachments/masjid-musholla/bmfi_pemutihan_penghapusbukuan/' + respon.gambar_foto + "')");

                    $("[name='status2']").val(respon.status_bmfi_pemutihan_penghapusbukuan);

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

                url: base_url + 'panel/bmfi_pemutihan_penghapusbukuan/delete/' + hiddenid.value,

                type: "GET",

                success: function(respon) {

                    $("#deleteModal").modal('hide');

                    toastr.success('Data berhasil dihapus.');

                    $('.datatables').DataTable().ajax.reload();

                }

            })

        });



        $('#bmfi_pemutihan_penghapusbukuanFormAdd').validate({

            rules: {

                bmfi_kategori_pemutihan_penghapusbukuan: {

                    required: true,

                },

                judul: {

                    required: true,

                },

              

                status: {

                    required: true,

                },

            },

            messages: {

                bmfi_kategori_pemutihan_penghapusbukuan: {

                    required: "bmfi_kategori_pemutihan_penghapusbukuan bmfi_pemutihan_penghapusbukuan tidak boleh kosong",

                },

                judul: {

                    required: "Judul bmfi_pemutihan_penghapusbukuan tidak boleh kosong",

                },

               
                status: {

                    required: "Status bmfi_pemutihan_penghapusbukuan tidak boleh kosong",

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

                    url: base_url + 'panel/bmfi_pemutihan_penghapusbukuan/add',

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

                        $("#bmfi_pemutihan_penghapusbukuanFormAdd")[0].reset();

                        CKEDITOR.instances['keterangan'].setData('');

                        $("#addModal").modal('hide');

                        toastr.success('Data berhasil disimpan.');

                        $(".datatables").DataTable().ajax.reload();

                    }

                })

            }

        });



        $('#bmfi_pemutihan_penghapusbukuanFormEdit').validate({

            rules: {

                bmfi_kategori_pemutihan_penghapusbukuan2: {

                    required: true,

                },

                judul2: {

                    required: true,

                },

             

                status2: {

                    required: true,

                },

            },

            messages: {

                bmfi_kategori_pemutihan_penghapusbukuan2: {

                    required: "bmfi_kategori_pemutihan_penghapusbukuan bmfi_pemutihan_penghapusbukuan tidak boleh kosong",

                },

                judul2: {

                    required: "Judul bmfi_pemutihan_penghapusbukuan tidak boleh kosong",

                },

              

                status2: {

                    required: "Status bmfi_pemutihan_penghapusbukuan tidak boleh kosong",

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

                    url: base_url + 'panel/bmfi_pemutihan_penghapusbukuan/edit/' + id,

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

                    }

                })

            }

        });

    })

</script>



















<script type="text/javascript">

    

    $(document).ready(function () {

   // bsCustomFileInput.init()

})



</script>





<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>
  function copyURL(event, url) {
    event.preventDefault();
    navigator.clipboard.writeText(url).then(function() {
      swal({
        title: "Tautan bmfi_pemutihan_penghapusbukuan Berhasil disalin!",
        text: "Silahkan Paste atau Tempel Tautan Link pada Media Sosial yang diinginkan seperti beberapa contoh berikut ini.",
        icon: "success",
        iconHtml: '<i class="fas fa-share-alt"></i>',
        buttons: {
          cancel: "Ok",
        },
        content: {
          element: "p",
          attributes: {
            innerHTML: `<div id="social-media-icons">
                          <i class="fab fa-whatsapp" style="color: #25D366;"></i> 
                          <i class="fab fa-twitter" style="color: #1DA1F2;"></i> 
                          <i class="fab fa-facebook" style="color: #1877F2;"></i> 
                          <i class="fab fa-instagram" style="color: #E1306C;"></i>
                          <i class="fab fa-telegram" style="color: #2CA5E0;"></i>
                          <i class="far fa-envelope" style="color: #D44638;"></i>
                          <i class="fab fa-tiktok" style="color: #69C9D0;"></i>
                          <i class="fas fa-volume-up" style="color: #F16FA5;"></i>
                          <i class="fab fa-linkedin" style="color: #0077B5;"></i>
                          <i class="fab fa-wikipedia-w" style="color: #000000;"></i>
                          <i class="fab fa-discord" style="color: #7289DA;"></i>
                          <i class="fab fa-pinterest" style="color: #BD081C;"></i>
                          <i class="fab fa-reddit" style="color: #FF4500;"></i>
                          <i class="fas fa-mosque" style="color: #008000;"></i>
                          <i class="fas fa-sms" style="color: #008080;"></i>
                          <i class="fab fa-youtube" style="color: #FF0000;"></i>
                          <i class="fab fa-tumblr" style="color: #36465D;"></i>
                          <i class="fab fa-vimeo-v" style="color: #1AB7EA;"></i>
                          <i class="fab fa-soundcloud" style="color: #FF5500;"></i>
                          <i class="fab fa-bandcamp" style="color: #629AA9;"></i>
                          <i class="fab fa-snapchat-ghost" style="color: #FFFC00;"></i>
                        </div>`
          }
        }
      });
    }).catch(function(err) {
      console.error('Gagal menyalin URL: ', err);
      swal("Gagal menyalin URL!", "", "error");
    });
  }
</script>






 <!-- <script src="<?php echo base_url() ?>assets/template/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script> -->





<!-- 

<script src="https://cdnjs.cloudflare.com/ajax/libs/bs-custom-file-input/1.3.4/bs-custom-file-input.min.js" integrity="sha512-91BoXI7UENvgjyH31ug0ga7o1Ov41tOzbMM3+RPqFVohn1UbVcjL/f5sl6YSOFfaJp+rF+/IEbOOEwtBONMz+w==" crossorigin="anonymous"></script>

 -->



