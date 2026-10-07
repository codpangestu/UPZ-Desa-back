<style>

    .foto-src {

        width: 150px;

        height: 150px;

        background-position: center;

        background-repeat: no-repeat;

        background-size: cover;

    }

</style>

<div class="card">

    <div class="card-header">



 <center><h1 class="card-text"><b>Success Story

        </p></b></h1></center>




 <center><h1 class="card-text"><b>Kisah Sukses Peserta

        </p></b></h1></center>



<center><h5 class="card-text"><b><?= $this->session->userdata('sess_all')->nama_masjid; ?>

            </h5></center>

        <center>  <p><br><?= $this->session->userdata('sess_all')->alamat_masjid; ?>

        </p></b></center>

<br>


        <a href="#" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addModal"><i class="fa fa-plus"></i> Tambah</a>

    </div>

    <div class="card-body">

        <form class="mb-3">

            <div class="form-row align-items-center">

                <div class="col-auto">

                    <label class="sr-only" for="txtNama">Judul</label>

                    <input type="text" class="form-control mb-2" id="txtJudul" placeholder="Judul">

                </div>

                <div class="col-auto">

                    <label class="sr-only" for="txtNama">Penulis</label>

                    <input type="text" class="form-control mb-2" id="txtpenulis" placeholder="Penulis">

                </div>

                 <div class="col-auto d-none">
                    <label class="sr-only" for="txtSlug_donasi">Slug bmfi_success_story</label>
                    <input type="text" class="form-control mb-2" id="txtSlug_bmfi_success_story" placeholder="Slug_bmfi_success_story">
                </div>



            </div>

        </form>

        <div class="table-responsive">

            <table class="table table-bordered datatables" width="100%">

                <thead>

                    <tr>

                        <th class="align-middle" width="50">No</th>

                         <th class="align-middle">Link</th>

                        <th class="align-middle">Judul</th>

                        <th class="align-middle">Penulis</th>

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

                <h5 class="modal-title">Tambah Success Story</h5>

                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>

            <form id="bmfi_success_storyFormAdd">

                <div class="modal-body">



                      <div class="row">

                        <div class="col-md-6">

                            <div class="form-group">

                                <label>Nama Peserta</label>

                                <select name="peserta_program_bmfi_success_story" class="form-control">

                                    <option value="">Pilih</option>

                                    <?php foreach ($peserta_program_bmfi_success_story as $p) { ?>

                                        <option value="<?php echo $p['id_peserta_program_bmfi_asesmen'] ?>"><?php echo $p['nama_peserta_program_bmfi_asesmen'] ?></option>

                                    <?php } ?>

                                </select>

                            </div>

                               </div>

                                  </div>




                    <div class="row">

                        <div class="col-md-6">

                            <div class="form-group">

                                <label>Kategori BMM Success Story</label>

                                <select name="bmfi_kategori_success_story" class="form-control">

                                    <option value="">Pilih</option>

                                    <?php foreach ($bmfi_kategori_success_story as $t) { ?>

                                        <option value="<?php echo $t['id_bmfi_kategori_success_story'] ?>"><?php echo $t['nama_bmfi_kategori_success_story'] ?></option>

                                    <?php } ?>

                                </select>

                            </div>

                            <div class="form-group">

                                <label>Judul</label>

                                <input type="text" name="judul" class="form-control">

                            </div>

                            <div class="form-group">

                                <label>Tanggal</label>

                                <input type="date" class="form-control" name="tgl" />

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="form-group">

                                <label>penulis</label>

                                <input type="text" name="penulis" class="form-control">

                            </div>

                            <div class="form-group">

                                <label>Status</label>

                                <select name="status" class="form-control">

                                    <option value="">Pilih</option>

                                    <?php

                                    $status = [ 'Tidak Aktif' , 'Aktif' ];

                                    foreach ($status as $j => $s) {

                                    ?>

                                        <option value="<?= $j ?>"><?= $s ?></option>

                                    <?php } ?>

                                </select>

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

                                <label>Poster</label>

                                <div class="input-group">

                                    <div class="custom-file">

                                        <input type="file" class="custom-file-input" name="foto" id="foto">

                                        <label class="custom-file-label" for="foto">Pilih file</label>

                                         </div>

                        </div>

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



<!-- modal edit -->

<div class="modal fade" id="editModal">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header bg-secondary">

                <h5 class="modal-title">Edit Success Story</h5>

                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>

            <form id="bmfi_success_storyFormEdit">

                <input type="hidden" name="id2">

                <div class="modal-body">



               <div class="row">

                        <div class="col-md-6">

                            <div class="form-group">

                                <label>Nama Peserta</label>

                                <select name="peserta_program_bmfi_success_story2" class="form-control">

                                    <option value="">Pilih</option>

                                    <?php foreach ($peserta_program_bmfi_success_story as $p) { ?>

                                        <option value="<?php echo $p['id_peserta_program_bmfi_asesmen'] ?>"><?php echo $p['nama_peserta_program_bmfi_asesmen'] ?></option>

                                    <?php } ?>

                                </select>

                            </div>

                               </div>

                                  </div>
                 




                    <div class="row">

                        <div class="col-md-6">

                            <div class="form-group">

                                <label>Kategori bmfi_success_story</label>

                                <select name="bmfi_kategori_success_story2" class="form-control">

                                    <option value="">Pilih</option>

                                    <?php foreach ($bmfi_kategori_success_story as $t) { ?>

                                        <option value="<?php echo $t['id_bmfi_kategori_success_story'] ?>"><?php echo $t['nama_bmfi_kategori_success_story'] ?></option>

                                    <?php } ?>

                                </select>

                            </div>

                            <div class="form-group">

                                <label>Judul</label>

                                <input type="text" name="judul2" class="form-control">

                            </div>

                            <div class="form-group">

                                <label>Tanggal</label>

                                <input type="date" class="form-control" name="tgl2" />

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="form-group">

                                <label>penulis</label>

                                <input type="text" name="penulis2" class="form-control">

                            </div>

                            <div class="form-group">

                                <label>Status</label>

                                <select name="status2" class="form-control">

                                    <option value="">Pilih</option>

                                    <?php

                                    $status = [ 'Tidak Aktif' , 'Aktif' ];

                                    foreach ($status as $j => $s) {

                                    ?>

                                        <option value="<?= $j ?>"><?= $s ?></option>

                                    <?php } ?>

                                </select>

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

                    <div class="row">

                        <div class="col-md-8">

                            <div class="form-group">

                                <label>Foto</label>

                                <input type="hidden" name="foto1">

                                <div class="foto-src img-thumbnail mb-3"></div>

                                <div class="input-group">

                                    <div class="custom-file">

                                        <input type="file" class="custom-file-input" name="foto2" id="foto2">

                                        <label class="custom-file-label" for="foto2">Pilih file</label>

                                         </div>

                        </div>

                        <small class="text-secondary"><i>Format file yang diizinkan <b>.jpg, .jpeg, png, gif</b>. Ukuran file maksimal <b>2 MB</b>.</i></small><br>

                        <small class="upload-error text-danger"></small>

                    </div>

                                    

                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-12">

                            <div class="form-group">

                                <label>Video bmfi_success_story (Iframe)</label>

                                <textarea name="iframe2" rows="6" class="form-control" placeholder="Contoh, https://www.youtube.com/embed/72m7REgd5ZA"></textarea>

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

                url: base_url + 'panel/bmfi_success_story/get_bmfi_success_story',

                type: "POST",

                data: function(d) {

                    return $.extend({}, d, {

                        txtJudul: $("#txtJudul").val(),

                        txtpenulis: $("#txtpenulis").val(),

                        txtSlug_bmfi_success_story: $("#txtSlug_bmfi_success_story").val(),

                    });

                }

            },

            columnDefs: [{

                targets: [0, 3, 4],

                orderable: false

            }]

        });



        $("#txtJudul, #txtpenulis").bind("keyup keyup", function(e) {

            e.preventDefault();

            table.draw();

        });



        $('body').on('change', ".statusaktif", function(e) {

            e.preventDefault();

            var iduser = $(this).attr('data');

            var status;

            if ($(this).is(':checked')) {

                status = 1;

            } else {

                status = 0;

            }

            $.ajax({

                url: base_url + 'panel/bmfi_success_story/changestatus',

                type: "POST",

                data: {

                    id: iduser,

                    status: status

                },

                success: function(respon) {

                    toastr.success('Status aktif berhasil diubah.');

                    $('.datatables').DataTable().ajax.reload();

                }

            })

        });



        $('body').on("click", ".btn-edit", function(e) {

            e.preventDefault();

            var id = $(this).attr('id');

            $.ajax({

                url: base_url + 'panel/bmfi_success_story/detail/' + id,

                type: "GET",

                dataType: "json",

                success: function(respon) {

                    $("[name='id2']").val(respon.id_bmfi_success_story);

                    $("[name='judul2']").val(respon.judul);

                    

                    $("[name='peserta_program_bmfi_success_story2']").val(respon.id_peserta_program);



                    $("[name='bmfi_kategori_success_story2']").val(respon.id_bmfi_kategori_success_story);

                    $("[name='tgl2']").val(respon.tgl_bmfi_success_story);

                    $("[name='penulis2']").val(respon.penulis);

                    $("[name='iframe2']").val(respon.video_bmfi_success_story);

                    CKEDITOR.instances['keterangan2'].setData(respon.keterangan);


                    

                    $("[name='foto1']").val(respon.poster);

                    $(".foto-src").css('background-image', "url('" + base_url + 'attachments/masjid-musholla/bmfi_success_story/' + respon.poster + "')");

                    $("[name='status2']").val(respon.status_aktif);

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

                url: base_url + 'panel/bmfi_success_story/delete/' + hiddenid.value,

                type: "GET",

                success: function(respon) {

                    $("#deleteModal").modal('hide');

                    toastr.success('Data berhasil dihapus.');

                    $('.datatables').DataTable().ajax.reload();

                }

            })

        });



        $('#bmfi_success_storyFormAdd').validate({

            rules: {

                bmfi_kategori_success_story: {

                    required: true,

                },

                judul: {

                    required: true,

                },

                penulis: {

                    required: true,

                },

                foto: {

                    required: true,

                    extension: 'jpg|jpeg|png|gif'

                },

                status: {

                    required: true,

                },

            },

            messages: {

                bmfi_kategori_success_story: {

                    required: "bmfi_kategori_success_story bmfi_success_story tidak boleh kosong",

                },

                judul: {

                    required: "Judul bmfi_success_story tidak boleh kosong",

                },

                penulis: {

                    required: "penulis bmfi_success_story tidak boleh kosong",

                },

                foto: {

                    required: "Poster tidak boleh kosong",

                    extension: "Format tidak sesuai"

                },

                status: {

                    required: "Status bmfi_success_story tidak boleh kosong",

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

                    url: base_url + 'panel/bmfi_success_story/add',

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

                        $("#bmfi_success_storyFormAdd")[0].reset();

                        CKEDITOR.instances['keterangan'].setData('');

                        $("#addModal").modal('hide');

                        toastr.success('Data berhasil disimpan.');

                        $(".datatables").DataTable().ajax.reload();

                    }

                })

            }

        });



        $('#bmfi_success_storyFormEdit').validate({

            rules: {

                bmfi_kategori_success_story2: {

                    required: true,

                },

                judul2: {

                    required: true,

                },

                penulis2: {

                    required: true,

                },

                status2: {

                    required: true,

                },

            },

            messages: {

                bmfi_kategori_success_story2: {

                    required: "bmfi_kategori_success_story bmfi_success_story tidak boleh kosong",

                },

                judul2: {

                    required: "Judul bmfi_success_story tidak boleh kosong",

                },

                penulis2: {

                    required: "penulis bmfi_success_story tidak boleh kosong",

                },

                status2: {

                    required: "Status bmfi_success_story tidak boleh kosong",

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

                    url: base_url + 'panel/bmfi_success_story/edit/' + id,

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
        title: "Tautan bmfi_success_story Berhasil disalin!",
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



