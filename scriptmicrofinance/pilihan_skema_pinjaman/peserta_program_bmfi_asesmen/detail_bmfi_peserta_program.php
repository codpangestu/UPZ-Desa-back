<div class="detailbmfi_peserta_program">

</div>



<script>

    showbmfi_peserta_program();



    function showbmfi_peserta_program() {

        var slug = "<?php echo $this->uri->segment(2) ?>";

        $.ajax({

            url: base_url + 'masjid/masjid_panel/src_detail_bmfi_peserta_program/' + slug,

            type: "GET",

            success: function(respon) {

                console.log(respon);

                $(".detailbmfi_peserta_program").html(respon);

            }

        })

    }

</script>