<html lang="en">

     <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">




<body>
    


  <nav class="navbar navbar-expand-lg navbar-dark  fixed-top navbar-custom " id="header">

        <div class="container ">

            <button class="navbar-toggler  d-none d-md " type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">

                <span class="navbar-toggler-icon "></span>

            </button>

            



            <a class="navbar-brand "  href="<?= base_url('n/' . $d->slug_masjid); ?>" >



    <img src="<?= base_url('attachments/masjid-musholla/foto-thumbnail/' . $d->thumbnail) ?>" width="30" height="30" class="d-inline-block align-top" alt="">

  <?php echo $d->nama_masjid ?> &nbsp;<?php if ($d->status_masjid == 'Verified') { ?>

                <img src="<?php echo base_url() ?>assets/img/centang.png" alt="Verified" width="18px" class="icon-verified">

            <?php } ?>   





  </a>




   <form class="form-inline">

   




           <button class="navbar-toggler d-lg-none " type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">

                <span class="navbar-toggler-icon "></span>

            </button>





  </form>







                <div class="collapse navbar-collapse  " id="navbarSupportedContent">







                <ul class="navbar-nav ml-auto">





                    <li class="nav-item ">

                        <a class="nav-link" href="#tabs-pengumuman" data-bs-toggle="tab"  role="tab" aria-controls="tabs-pengumuman" aria-selected="false">Pengumuman</a>

                        </li>





                    <li class="nav-item ">

                        <a class="nav-link" href="<?php echo site_url('kajian') ?>">Jadwal Sholat</a>

                        </li>







                    <li class="nav-item dropdown">

                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                            Kajian Ilmu

                        </a>

                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">

                          <a class="dropdown-item" href="<?php echo site_url('kajian') ?>">Jadwal Kajian</a>

                          <a class="dropdown-item" href="<?php echo site_url('videokajian') ?>">Video Rekaman Kajian</a> 

                        </div>







                    <li class="nav-item ">

                        <a class="nav-link" href="https://quran.kemenag.go.id/"target="_blank">Alquran</a>

                    </li>

                    

            



                     <li class="nav-item dropdown">

                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                            Login

                        </a>

                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">

                            <a class="dropdown-item" href="<?php echo site_url('login/masjid') ?>">Login Masjid</a>

                          

                        </div>



                  

                        </div>

                    </li>

                </ul>

            </div>

        </div>





    </nav>





  <body>

    <span itemprop="image" itemscope itemtype="image/jpeg/jpg/png"> 

      <link itemprop="url" href="<?= base_url('attachments/masjid-musholla/foto-thumbnail/' . $d->thumbnail) ?>"> 

    </span>



    </body>





     <script>

        var base_url = "<?php echo base_url() ?>";

        



    </script>

    

    

<style>





    /* change the background color */

.navbar-custom {

    background-color: #000000;

}

/* change the brand and text color */

./*navbar-custom .navbar-brand,

.navbar-custom .navbar-text {

    color: #F827F6;

    padding: 0.8rem 1rem;

}*/

/* change the link color and add padding for height */

.navbar-custom .navbar-nav .nav-link {

    color: #fefefe;

    padding: 1rem 1rem;

}

/* change the color of active or hovered links */

.navbar-custom .nav-item.active .nav-link,

.navbar-custom .nav-item:hover .nav-link {

    color: #000000;

    background-color: #fecb00; /* add background-color to active links */

}.navbar-custom {

    background-color: <?php echo $d->background_warna_website ?>;

}



/* change the brand and text color */

.navbar-custom .navbar-brand,

.navbar-custom .navbar-text {

    color: #ffffff;

}



/* change the link color */

.navbar-custom .navbar-nav .nav-link {

    color: #fefefe;

}





.nav-tabs .nav-link{



    background-color: #ffff;

    color: #000000;

}



/* change the color of active or hovered links */

.nav-tabs .nav-link.active, .nav-tabs .show>.nav-link {



    background-color: <?php echo $d->background_warna_website ?>;





    color: <?php echo $d->warna_tulisan_website ?>;





    border-color: #000000 #000000 #fff;

}



/* change the color of active or hovered links */

.navbar-custom .nav-item.active .nav-link {

    

    background-color: #ffff;

    color: #000000;

}





.navbar-custom .nav-item:hover .nav-link {

    

    background-color: #bfbfbf;

    color: #000000;

}





.nav-link.hover {



    color: <?php echo $d->warna_tulisan_website ?>;

    background-color: <?php echo $d->background_warna_website ?>;

    

}





.nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active {

  

    color: <?php echo $d->warna_tulisan_website ?>;

    background-color: <?php echo $d->background_warna_website ?>;

    border-color: #dee2e6 #dee2e6 #000000;

}







.dropdown-item:focus, .dropdown-item:hover {

    color: #16181b;

    text-decoration: none;

    background-color: #bfbfbf;

}

a:hover {

    color: #ffffff;

    text-decoration: underline;

}







/* for dropdown only - change the color of droodown */



/* header warna*/

.navbar-custom .dropdown-menu {

    background-color: <?php echo $d->background_warna_website ?>;







}

.navbar-custom .dropdown-item {

    color: <?php echo $d->warna_tulisan_website ?>;







}

.navbar-custom .dropdown-item:hover 





.navbar-custom .dropdown-item:focus {

    color: #333333;

    background-color: rgba(255,255,255,.5);

}

    .card-img-top {

        height: 150px;

        background-size: cover;

        background-repeat: no-repeat;

        background-position: center;

    }





 .header-sticky {

        display: block;

        position: fixed;

        left: 0;

        width: 100%;

        top: 0;

        padding-top: 14px;

        padding-bottom: 14px;

        background-color: #eee;

        z-index: 2;

    }



    table tr td:nth-child(1) {

        color: #777;

        width: 30%;

        /* font-weight: bold;

        text-align: right; */

    }

    

    .with-background {

        background-repeat: no-repeat;

        background-size: 100%;

        position: relative;

        height: 100vh;

        display: flex;

        padding-top: 100px;

        background-position: center;

    }





    .navbar.second-navbar {

  margin-top: 0px;

  /*position: sticky;*/

    /* height of your primary navigation 

  background: #eee !important;

  transition: all .5s ease-in-out;

}









    .head.third-navbar {



        margin-top: 0px;



    position: sticky;





   top: 0px; /* height of your primary navigation */







      padding-top: 20px;

        padding-bottom: 20px;







 

  background: #ffff  !important;

  transition: all .5s ease-in-out;

}







    @media screen and (max-width:676px) {

        table tr td:nth-child(1) {

            width: 50%;

        }

        

        .with-background {

            height: 30vh;

        }

    }







    #header.hidden{

  top: -70px;

}









.section {

    min-height: 100px;

}



.section h5 {

    text-align: center;

    color: #000000;

    padding-bottom: 20px;

    margin-bottom: 50px;

    position: relative;

}



.section h5::after {

    content: '';

    display: block;

    position: absolute;

    height: 4px;

    width: 30px;

    background-color: <?php echo $d->background_warna_website ?>;

    left: calc(50% - 15px);

    bottom: 0;

}



.section h5::before {

    content: '';

    display: block;

    position: absolute;

    height: 1px;

    width: 100px;

    background-color: <?php echo $d->background_warna_website ?>;

    left: calc(50% - 50px);

    bottom: 1px;

}



.with-background {

    background-repeat: no-repeat;

    background-size: contain;

    position: relative;

    height: 100vh;

    display: flex;

    padding-top: 100px;

    background-position: center;

}



.with-background::after {

    content: '';

    display: block;

    position: absolute;

    top: 0;

    left: 0;

    width: 100%;

    bottom: 0;

    background-color: rgba(164, 174, 168, -6);

}





/* Thick red border */

hr.custom {

  border: 2px solid <?php echo $d->background_warna_website ?>;

}







</style>














 <div class="container pt-5 pb-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="header">
                    <!-- Judul Kwitansi -->
                    <h2 class="kwitansi-title">Data Peserta Microfinance <?php echo $d->jenis_masjid ?></h2>
                    <!-- Informasi Kwitansi Zakat -->
                    <table class="table kwitansi-info">
                        <tr>
                            <th>Nama Masjid / Musholla</th>
                            <td><?php echo $d->nama_masjid ?></td>
                        </tr>
                       
                        <tr>
                            <th>Nama Peserta</th>
                            <td><?php echo $d->nama_peserta_program_bmfi_asesmen ?></td>
                        </tr>

                         <tr>
                            <th>Jenis Kelamin</th>
                            <td><?php echo $d->jenis_kelamin ?></td>
                        </tr>


                         <tr>
                            <th>Alamat Rumah</th>
                            <td><?php echo $d->alamat_rumah ?></td>
                        </tr>


                        <tr>
                            <th>Nama Usaha</th>
                            <td><?php echo $d->nama_usaha ?></td>
                        </tr>

                         <tr>
                            <th>Lokasi Usaha</th>
                            <td><?php echo $d->lokasi_usaha ?></td>
                        </tr>


                        <tr>
                            <th>Jumlah Pinjaman</th>
                            <td>Rp <?php echo number_format($d->jumlah_pinjaman, 0, ',', '.') ?></td>
                        </tr>


                         <tr>
                            <th>Sisa Dana Belum Dikembalikan</th>
                            <td>Rp <?php echo number_format($d->sisa_dana_pengembalian, 0, ',', '.') ?></td>
                        </tr>


                     
                        <tr>
                            <th>Keterangan</th>
                            <td class="keterangan"><?php echo $d->keterangan ?></td>
                        </tr>
                    </table>





            <img src="<?php echo base_url('attachments/masjid-musholla/bmfi_asesmen/peserta_program/' . $d->foto_profil_peserta_program_bmfi_asesmen) ?>" class="img-thumbnail mt-2 mb-3" alt="<?php echo $d->foto_profil_peserta_program_bmfi_asesmen ?>" width="100%">



                </div>
            </div>
        </div>
    </div>
</body>




