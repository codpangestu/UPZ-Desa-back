<div class="detailkartu_anggota_peserta_microfinance">

</div>



<script>

    showkartu_anggota_peserta_microfinance();



    function showkartu_anggota_peserta_microfinance() {

        var slug = "<?php echo $this->uri->segment(2) ?>";

        $.ajax({

            url: base_url + 'panel/bmfi_kartu_anggota_peserta_microfinance/src_detail_kartu_anggota_peserta_microfinance/' + slug,

            type: "GET",

            success: function(respon) {

                console.log(respon);

                $(".detailkartu_anggota_peserta_microfinance").html(respon);

            }

        })

    }

</script>