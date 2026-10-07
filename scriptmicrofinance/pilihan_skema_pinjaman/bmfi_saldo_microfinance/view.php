<script type="text/javascript">

    

    $(document).ready(function () {

   // bsCustomFileInput.init()

})



</script>





 <!-- <script src="<?php echo base_url() ?>assets/template/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script> -->







<style>

    .berkas-src {

        width: 150px;

        height: 150px;

        background-position: center;

        background-repeat: no-repeat;

        background-size: cover;

    }

</style>

<div class="card">

    <div class="card-header">



 <center><h1 class="card-text"><b>Saldo Microfinance

        </p></b></h1></center>



<center><h5 class="card-text"><b><?= $this->session->userdata('sess_all')->nama_masjid; ?>

            </h5></center>

        <center>  <p><br><?= $this->session->userdata('sess_all')->alamat_masjid; ?>

        </p></b></center>

<br>


        <a href="#"  id="tombolTambah"  class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addModal"><i class="fa fa-plus"></i> Tambah</a>

    </div>

    <div class="card-body">

        <form class="mb-3">

            <div class="form-row align-items-center">

                <div class="col-10">



                    <label class="sr-only" for="txtNama">Nama</label>

                    <input type="text" class="form-control mb-2" id="txtNama" placeholder="Nama bmfi_saldo_microfinance">

                </div>

               

            </div>

        </form>

        <div class="table-responsive">

            <table class="table table-bordered datatables" width="100%">

                <thead>

                    <tr>

                        <th class="align-middle" width="50">No</th>

                        <th class="align-middle" >Nama</th>


                          <th class="align-middle" >Nominal Saldo Saat ini (Rp)</th>


                            <th class="align-middle" >Saldo Awal Penerimaan (Rp)</th>


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

                <h5 class="modal-title">Tambah Saldo Microfinance</h5>

                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>

            <form id="bmfi_saldo_microfinanceFormAdd">

                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-12">

                            <div class="form-group">

                                <label>Nama Saldo</label>

                                <input type="text" name="nama" class="form-control">

                            </div>

                        </div>

                      

                    </div>



                     <div class="row">

                        <div class="col-md-10">

                            <div class="form-group">

                                <label>Nominal Saldo</label>

                                <input type="text" name="nominal_saldo_microfinance" class="form-control rupiah">

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
            <label for="berkas">Berkas</label>
            <div class="input-group">
                <input type="file" class="form-control" name="berkas" id="berkas">
                <label class="input-group-text" for="berkas">Pilih file</label>
            </div>
            <small class="text-secondary"><i>Format file yang diizinkan <b>.Jpg dan Png </b>. Ukuran file maksimal <b>10 MB</b>.</i></small><br>
            <small class="upload-error text-danger"></small>
        </div>
    </div>
</div>
</div>
 



<!-- 
                  <div class="row">

                        <div class="col-md-8">

                            <div class="form-group">

                                <label>Berkas</label>

                                <div class="input-group">

                                    <div class="custom-file">

                                        <input type="file" class="custom-file-input" name="berkas" id="berkas">

                                        <label class="custom-file-label" for="berkas">Pilih file</label>

                                         </div>

                        </div>

                        <small class="text-secondary"><i>Format file yang diizinkan <b>.Jpg dan Png </b>. Ukuran file maksimal <b>10 MB</b>.</i></small><br>

                        <small class="upload-error text-danger"></small>

                    </div>

                                    

                        </div>

                    </div>

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



<!-- modal edit -->

<div class="modal fade" id="editModal">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header bg-secondary">

                <h5 class="modal-title">Edit Saldo Microfinance</h5>

                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>

            <form id="bmfi_saldo_microfinanceFormEdit">

                <input type="hidden" name="id2">

                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-12">

                            <div class="form-group">

                                <label>Nama Saldo</label>

                                <input type="text" name="nama2" class="form-control">

                            </div>

                        </div>

                      

                    </div>





                     <div class="row">

                        <div class="col-md-10">

                            <div class="form-group">

                                <label>Nominal Saldo Saat ini</label>

                                <input type="text" name="nominal_saldo_microfinance2" class="form-control rupiah">

                            </div>

                        </div>

                      

                    </div>






                     <div class="row">

                        <div class="col-md-10">

                            <div class="form-group">

                                <label>Saldo Awal Penerimaan</label>

                                <input type="text" name="saldo_awal_penerimaan2" class="form-control rupiah">

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
            <label for="berkas2">Berkas</label>
            <input type="hidden" name="berkas2">
            <div class="berkas-src img-thumbnail mb-3"></div>
            <div class="input-group">
                <input type="file" class="form-control" name="berkas2" id="berkas2">
                <label class="input-group-text" for="berkas2">Pilih file</label>
            </div>
            <small class="text-secondary"><i>Format file yang diizinkan <b>.Jpg dan Png</b>. Ukuran file maksimal <b>10 MB</b>.</i></small><br>
            <small class="upload-error text-danger"></small>
        </div>
    </div>
</div>  







<!-- <div class="row">

                        <div class="col-md-8">

                            <div class="form-group">

                                <label>Berkas</label>

                                <input type="hidden" name="berkas2">

                                <div class="berkas-src img-thumbnail mb-3"></div>

                                <div class="input-group">

                                    <div class="custom-file">

                                        <input type="file" class="custom-file-input" name="berkas2" id="berkas2">

                                        <label class="custom-file-label" for="berkas2">Pilih file</label>

                                         </div>

                        </div>

                        <small class="text-secondary"><i>Format file yang diizinkan <b>.Jpg dan Png</b>. Ukuran file maksimal <b>10 MB</b>.</i></small><br>

                        <small class="upload-error text-danger"></small>

                    </div>

                                    

                        </div>

                    </div>

                </div>  -->
 



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



$(document).ready(function() {

    
        var table = $(".datatables").DataTable({

            searching: false,

            processing: true,

            serverSide: true,

             ordering: false,

            order: [],

            ajax: {

                url: base_url + 'panel/bmfi_saldo_microfinance/get_bmfi_saldo_microfinance',

                type: "POST",

                data: function(d) {

                    return $.extend({}, d, {

                        txtNama: $("#txtNama").val(),

                    });

                }

            },

        });





// Setelah DataTable selesai dimuat
    table.on('draw', function() {
        var rowCount = table.rows().count();
        var tambahButton = $('#tombolTambah');

        if (rowCount > 0) {
            tambahButton.hide();
        } else {
            tambahButton.show();
        }
    });
});



        $("#txtNama").bind("keyup change", function(e) {

            e.preventDefault();

            table.draw();

        });





        $('body').on("click", ".btn-edit", function(e) {

            e.preventDefault();

            var id = $(this).attr('id');

            $.ajax({

                url: base_url + 'panel/bmfi_saldo_microfinance/detail/' + id,

                type: "GET",

                dataType: "json",

                success: function(respon) {

                    $("[name='id2']").val(respon.id_bmfi_saldo_microfinance);

                    $("[name='nama2']").val(respon.nama_bmfi_saldo_microfinance);


                     $("[name='nominal_saldo_microfinance2']").val(respon.nominal_saldo_microfinance);


                       $("[name='saldo_awal_penerimaan2']").val(respon.saldo_awal_penerimaan);

                

                    CKEDITOR.instances['keterangan2'].setData(respon.keterangan);

                    $("[name='berkas1']").val(respon.berkas_bmfi_saldo_microfinance);

                    $(".berkas-src").css('background-image', "url('" + base_url + 'attachments/masjid-musholla/bmfi_asesmen/bmfi_saldo_microfinance/' + respon.berkas_bmfi_saldo_microfinance + "')");

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

                url: base_url + 'panel/bmfi_saldo_microfinance/delete/' + hiddenid.value,

                type: "GET",

                success: function(respon) {

                    $("#deleteModal").modal('hide');

                    toastr.success('Data berhasil dihapus.');

                    $('.datatables').DataTable().ajax.reload();

                }

            })

        });



        $('#bmfi_saldo_microfinanceFormAdd').validate({

            rules: {

                nama: {

                    required: true,

                },

              

                // berkas: {

                //     required: true,

                //     extension: 'jpg|jpeg|gif|png'

                // },

            },

            messages: {

                nama: {

                    required: "Nama bmfi_saldo_microfinance tidak boleh kosong",

                },

              

                // berkas: {

                //     required: "berkas tidak boleh kosong",

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

                    url: base_url + 'panel/bmfi_saldo_microfinance/add',

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

                        $("#bmfi_saldo_microfinanceFormAdd")[0].reset();

                        CKEDITOR.instances['keterangan'].setData('');

                        $("#addModal").modal('hide');

                        toastr.success('Data berhasil disimpan.');

                        $(".datatables").DataTable().ajax.reload();

                    }

                })

            }

        });



        $('#bmfi_saldo_microfinanceFormEdit').validate({

            rules: {

                nama2: {

                    required: true,

                },


                //  berkas2: {

                //     required: true,

                //          extension: 'jpg|jpeg|gif|png'


                // },




              

            },

            messages: {

                nama2: {

                    required: "Nama bmfi_saldo_microfinance tidak boleh kosong",

                },

                
                // berkas: {

                //     required: "berkas tidak boleh kosong",

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

                    url: base_url + 'panel/bmfi_saldo_microfinance/edit/' + id,

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