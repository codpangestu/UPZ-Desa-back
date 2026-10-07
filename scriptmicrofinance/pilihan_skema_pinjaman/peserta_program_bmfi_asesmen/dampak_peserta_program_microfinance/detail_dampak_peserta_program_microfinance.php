<div class="detaildampak_peserta_program_microfinance">

</div>



<script>

    showdampak_peserta_program_microfinance();



    function showdampak_peserta_program_microfinance() {

        var slug = "<?php echo $this->uri->segment(2) ?>";

        $.ajax({

            url: base_url + 'panel/bmfi_dampak_peserta_program_microfinance/src_detail_dampak_peserta_program_microfinance/' + slug,

            type: "GET",

            success: function(respon) {

                console.log(respon);

                $(".detaildampak_peserta_program_microfinance").html(respon);

            }

        })

    }

</script>