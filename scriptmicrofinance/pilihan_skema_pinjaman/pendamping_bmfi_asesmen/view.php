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



    .berkas_riwayat_hidup_pendamping-src {

        width: 150px;

        height: 150px;

        background-position: center;

        background-repeat: no-repeat;

        background-size: cover;

    }

</style>

<div class="card">

    <div class="card-header">



 <center><h1 class="card-text"><b>Pendamping Microfinance

        </p></b></h1></center>



<center><h5 class="card-text"><b><?= $this->session->userdata('sess_all')->nama_masjid; ?>

            </h5></center>

     <center>  <p><br><?= $this->session->userdata('sess_all')->alamat_masjid; ?>

        </p></b></center> 

<br>


        <a href="#" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addModal"><i class="fa fa-plus"></i> Tambah Pendamping</a>



        <a href="#" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#cetakModal"><i
                class="fa fa-print"></i> Cetak</a>




    </div>

    <div class="card-body">

        <form class="mb-3">

            <div class="form-row align-items-center">

                <div class="col-10">



                    <label class="sr-only" for="txtNama">Nama</label>

                    <input type="text" class="form-control mb-2" id="txtNama" placeholder="Nama Pendamping Microfinance">

                </div>

               

            </div>

        </form>

        <div class="table-responsive">

            <table class="table table-bordered datatables" width="100%">

                <thead>

                    <tr>

                        <th class="align-middle" width="50">No</th>

                        <th class="align-middle" >Nama</th>


                        <th class="align-middle" >Kontak</th>



                         <th class="align-middle" >Alamat</th>




                         <th class="align-middle" >Keterangan</th>


                       

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

                <h5 class="modal-title">Tambah Pendamping Microfinance</h5>

                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>

            <form id="pendamping_bmfi_asesmenFormAdd">

                <div class="modal-body">

                    
                    <div class="row">

                        <div class="col-md-12">

                            <div class="form-group">

                                <label>Nama Pendamping <span style="color: red;">*</span></label>

                                <input type="text" name="nama" class="form-control">

                            </div>

                        </div>


                        </div>




                     <div class="row">

                        <div class="col-md-12">

                         <div class="form-group">

                                <label>Jenis Kelamin <span style="color: red;">*</span></label>

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



                                <label>No KTP <span style="color: red;">*</span></label>



                                <input type="text" name="no_ktp" class="form-control nomor">



                            </div>




                        </div>




                    </div>








                <div class="row">
                    

                        <div class="col-md-12">



                            <div class="form-group">



                                <label>No HP <span style="color: red;">*</span></label>



                                <input type="text" name="no_hp" class="form-control nomor">



                            </div>




                        </div>




                    </div>








                        <div class="row">

                        <div class="col-md-12">

                            <div class="form-group">

                                <label>Tempat Tanggal Lahir <span style="color: red;">*</span></label>

                                <input type="text" name="tempat_tanggal_lahir" class="form-control">

                            </div>

                        </div>



                        </div>


                          <div class="row">

                        <div class="col-md-12">

                            <div class="form-group">

                                <label>Alamat <span style="color: red;">*</span></label>

                                <textarea name="alamat" class="form-control" rows="4"></textarea>
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



<!-- 
                    <div class="row">

                        <div class="col-md-8">

                            <div class="form-group">

                                <label>Foto Profil</label>

                                <div class="input-group">

                                    <div class="custom-file">

                                        <input type="file" class="custom-file-input" name="foto_profil" id="foto_profil">

                                        <label class="custom-file-label" for="foto_profil">Pilih file</label>

                                         </div>

                        </div>

                        <small class="text-secondary"><i>Format file yang diizinkan <b>.jpg, .jpeg, png, gif</b>. Ukuran file maksimal <b>10 MB</b>.</i></small><br>

                        <small class="upload-error text-danger"></small>

                    </div>

                                    

                        </div>

                    </div>
 -->




<!-- 
            <div class="row">
    <div class="col-md-8">
        <div class="form-group">
            <label for="foto_profil" class="form-label">Foto Profil</label>
            <div class="input-group">
                <input type="file" class="form-control" name="foto_profil" id="foto_profil">
                <label class="input-group-text" for="foto_profil">Pilih file</label>
            </div>
            <small class="text-secondary"><i>Format file yang diizinkan <b>.jpg, .jpeg, png, gif</b>. Ukuran file maksimal <b>10 MB</b>.</i></small><br>
            <small class="upload-error text-danger"></small>
        </div>
    </div>
</div>
 -->






<div class="row">
    <div class="col-md-12">
        <div class="form-group">
<!-- <label for="foto_profil" class="form-label">Foto Profil <span style="color: red;">*</span></label> -->


<label for="foto_profil" class="form-label">Foto Profil </label>


            <div class="input-group">
                <input type="file" class="form-control" name="foto_profil" id="foto_profil" onchange="previewImage()">
            </div>

            
            <div class="mt-2" id="imagePreviewFotoProfilPendamping"></div>


            <small class="text-secondary"><i>Format file yang diizinkan <b>.jpg, .jpeg, .png</b>. Ukuran file maksimal <b>2 MB</b>.</i></small><br>
            <small class="upload-error text-danger"></small>
        </div>
    </div>
</div>

<script>
function previewImage() {
    var input = document.getElementById('foto_profil');
    var previewContainer = document.getElementById('imagePreviewFotoProfilPendamping');
    var previewImage = document.createElement('img');

    // Bersihkan pratinjau sebelumnya
    previewContainer.innerHTML = '';

    // Cek apakah file yang dipilih adalah gambar
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            previewImage.src = e.target.result;
            previewImage.classList.add('img-fluid'); // Tambahkan kelas Bootstrap untuk gambar responsif
            previewContainer.appendChild(previewImage);
        }

        reader.readAsDataURL(input.files[0]);
    }
}
</script>





                <div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label for="berkas_riwayat_hidup_pendamping" class="form-label">Berkas Riwayat Hidup Pendamping</label>
            <div class="input-group">
                <input type="file" class="form-control" name="berkas_riwayat_hidup_pendamping" id="berkas_riwayat_hidup_pendamping" >
                <!-- <label class="input-group-text" for="berkas_riwayat_hidup_pendamping">Pilih file</label> -->
            </div>
            <small class="text-secondary"><i>Format file yang diizinkan <b>.jpg, .jpeg, .png, .pdf, .pdf</b>. Ukuran file maksimal <b>10 MB</b>.</i></small><br>
            <small class="upload-error text-danger"></small>
        </div>
    </div>
</div>









                </div>







                <div class="modal-footer">

                    <!-- <span class="loader"><i class="fa fa-spinner fa-spin"></i> Sedang proses...</span> -->




<div class="loader">Proses Menunggu <span id="progressPercentage">0%</span></div>



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



<!-- modal edit -->

<div class="modal fade" id="editModal">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header bg-secondary">

                <h5 class="modal-title">Edit Pendamping Microfinance</h5>

                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>

            <form id="pendamping_bmfi_asesmenFormEdit">

                <input type="hidden" name="id2">

                <div class="modal-body">

                   
                    <div class="row">

                        <div class="col-md-12">

                            <div class="form-group">

                                <label>Nama Pendamping</label>

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

                                <label>Alamat</label>

                                <textarea name="alamat2" class="form-control" rows="4"></textarea>
                            </div>

                        </div>

                    </div>



                    <div class="row">

                        <div class="col-12">

                            <div class="form-group">

                                <label>Keterangan</label>

                                <textarea name="keterangan2" class="form-control"></textarea>

                            </div>

                        </div>

                    </div>





                   <!--  <div class="row">

                        <div class="col-md-8">

                            <div class="form-group">

                                <label>Foto Profil</label>

                                <input type="hidden" name="foto_profil1">

                                <div class="foto_profil-src img-thumbnail mb-3"></div>

                                <div class="input-group">

                                    <div class="custom-file">

                                        <input type="file" class="custom-file-input" name="foto_profil2" id="foto_profil2">

                                        <label class="custom-file-label" for="foto_profil2">Pilih file</label>

                                         </div>

                        </div>

                        <small class="text-secondary"><i>Format file yang diizinkan <b>.jpg, .jpeg, png, gif</b>. Ukuran file maksimal <b>10 MB</b>.</i></small><br>

                        <small class="upload-error text-danger"></small>

                    </div>

                                    

                        </div>

                    </div> -->






<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label for="foto_profil2" class="form-label">Foto Profil</label>
            <input type="hidden" name="foto_profil1">
            <div class="foto_profil-src img-thumbnail mb-3"></div>
            <div class="input-group">
                <input type="file" class="form-control" name="foto_profil2" id="foto_profil2">
                <!-- <label class="input-group-text" for="foto_profil2">Pilih file</label> -->
            </div>
            <small class="text-secondary"><i>Format file yang diizinkan <b>.jpg, .jpeg, .png</b>. Ukuran file maksimal <b>10 MB</b>.</i></small><br>
            <small class="upload-error text-danger"></small>
        </div>
    </div>
</div>









<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label for="berkas_riwayat_hidup_pendamping2" class="form-label">Berkas Riwayat Hidup Pendamping</label>


            <input type="hidden" name="berkas_riwayat_hidup_pendamping1">

            <!-- <input  name="berkas_riwayat_hidup_pendamping1"> -->


            <div class="berkas_riwayat_hidup_pendamping-src img-thumbnail mb-3"></div>


            <div class="input-group">
                <input type="file" class="form-control" name="berkas_riwayat_hidup_pendamping2" id="berkas_riwayat_hidup_pendamping2">
                <!-- <label class="input-group-text" for="berkas_riwayat_hidup_pendamping2">Pilih file</label> -->
            </div>
            <small class="text-secondary"><i>Format file yang diizinkan <b>.jpg, .jpeg, .png, .pdf, .doc, .docx, .xls, .xlsx</b>. Ukuran file maksimal <b>10 MB</b>.</i></small><br>
            <small class="upload-error text-danger"></small>
        </div>
    </div>
</div>






                </div>






                <div class="modal-footer">

                    <!-- <span class="loader"><i class="fa fa-spinner fa-spin"></i> Sedang proses...</span> -->



<div class="loader">Proses Menunggu <span id="progressPercentage2edit">0%</span></div>



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

                <form action="<?= site_url('panel/mp/cetak_bmfi_pendamping') ?>" target="_blank">



                          



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



<script>

    $(document).ready(function() {

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



        var table = $(".datatables").DataTable({

            searching: false,

            processing: true,

            serverSide: true,

             ordering: false,

            order: [],

            ajax: {

                url: base_url + 'panel/bmfi_pendamping/get_bmfi_pendamping',

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

            var id = $(this).attr('id');

            $.ajax({

                url: base_url + 'panel/bmfi_pendamping/detail/' + id,

                type: "GET",

                dataType: "json",

                success: function(respon) {

                    $("[name='id2']").val(respon.id_pendamping_bmfi_asesmen);

                    $("[name='nama2']").val(respon.nama_pendamping_bmfi_asesmen);


                    
                    $("[name='jenis_kelamin2']").val(respon.jenis_kelamin);



                    $("[name='no_ktp2']").val(respon.no_ktp);


                    $("[name='no_hp2']").val(respon.no_hp);


                    $("[name='tempat_tanggal_lahir2']").val(respon.tempat_tanggal_lahir);


                    $("[name='alamat2']").val(respon.alamat);




                     $("[name='berkas_riwayat_hidup_pendamping1']").val(respon.berkas_riwayat_hidup_pendamping);

                      $(".berkas_riwayat_hidup_pendamping-src").css('background-image', "url('" + base_url + 'attachments/masjid-musholla/bmfi_asesmen/pendamping/berkas_riwayat_hidup_pendamping/' + respon.berkas_riwayat_hidup_pendamping + "')");

                

                

                    CKEDITOR.instances['keterangan2'].setData(respon.keterangan);



                    $("[name='foto_profil1']").val(respon.foto_profil_pendamping_bmfi_asesmen);

                    $(".foto_profil-src").css('background-image', "url('" + base_url + 'attachments/masjid-musholla/bmfi_asesmen/pendamping/' + respon.foto_profil_pendamping_bmfi_asesmen + "')");




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

                url: base_url + 'panel/bmfi_pendamping/delete/' + hiddenid.value,

                type: "GET",

                success: function(respon) {

                    $("#deleteModal").modal('hide');

                    toastr.success('Data berhasil dihapus.');

                    $('.datatables').DataTable().ajax.reload();

                }

            })

        });



        $('#pendamping_bmfi_asesmenFormAdd').validate({

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



                  alamat: {

                    required: true,

                },



                 keterangan: {

                    required: true,

                },


              

                // foto_profil: {

                //     required: true,

                //     extension: 'jpg|jpeg|png|gif'

                // },

            },

            messages: {

                nama: {

                    required: "Nama pendamping_bmfi_asesmen tidak boleh kosong",

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


                 alamat: {

                    required: "Alamat Rumah tidak boleh kosong",

                },



                 keterangan: {

                    required: "Keterangan tidak boleh kosong",

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

                    url: base_url + 'panel/bmfi_pendamping/add',

                    type: "POST",

                    dataType: "json",

                    data: dataForm,

                    processData: false,

                    contentType: false,






 xhr: function() {
            var xhr = new window.XMLHttpRequest();
            // Mengatur fungsi onprogress untuk menangani perubahan progress
            xhr.upload.onprogress = function(e) {
    if (e.lengthComputable) {
        var percentComplete = (e.loaded / e.total) * 100;
        $("#progressPercentage").text(percentComplete.toFixed(2) + '%');
    }
};
            return xhr;
        },





                    beforeSend: function() {

                        $(".loader").show();

                        $("#btn-add").addClass('disabled');

                    },

                    success: function(respon) {

                        $(".loader").hide();

                        $("#btn-add").removeClass('disabled');

                        $("#pendamping_bmfi_asesmenFormAdd")[0].reset();

                        CKEDITOR.instances['keterangan'].setData('');

                        $("#addModal").modal('hide');

                        toastr.success('Data berhasil disimpan.');

                        $(".datatables").DataTable().ajax.reload();

                    }

                })

            }

        });



        $('#pendamping_bmfi_asesmenFormEdit').validate({

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



                  alamat2: {

                    required: true,

                },



                 keterangan2: {

                    required: true,

                },



              

            },

            messages: {

                nama2: {

                    required: "Nama pendamping_bmfi_asesmen tidak boleh kosong",

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


                 alamat2: {

                    required: "Alamat Rumah tidak boleh kosong",

                },



                 keterangan2: {

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

                var id = $("[name='id2']").val();

                $.ajax({

                    url: base_url + 'panel/bmfi_pendamping/edit/' + id,

                    type: "POST",

                    dataType: "json",

                    data: dataForm,

                    processData: false,

                    contentType: false,





 xhr: function() {
            var xhr = new window.XMLHttpRequest();
            // Mengatur fungsi onprogress untuk menangani perubahan progress
            xhr.upload.onprogress = function(e) {
    if (e.lengthComputable) {
        var percentComplete = (e.loaded / e.total) * 100;
        $("#progressPercentage2edit").text(percentComplete.toFixed(2) + '%');
    }
};
            return xhr;
        },





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