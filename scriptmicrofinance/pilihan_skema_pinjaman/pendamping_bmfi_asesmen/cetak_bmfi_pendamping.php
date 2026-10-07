<!DOCTYPE html>

<html>



<head>

    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Laporan Transaksi Microfinance</title>


     


  <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

   <link rel="stylesheet" href="<?php echo base_url() ?>assets/Bootstrap-5.3/css/bootstrap.min.css">
<script src="<?php echo base_url() ?>assets/Bootstrap-5.3/js/bootstrap.bundle.min.js"></script>



<script src="<?php echo base_url() ?>assets/Bootstrap-5.3/jquery/jquery-3.6.4.min.js">



<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css" integrity="sha512-q3eWabyZPc1XTCmF+8/LuE1ozpg5xxn7iO89yfSOd5/oKvyqLngoNGsx8jq92Y8eXJ/IRxQbEC+FGSYxtk2oiw==" crossorigin="anonymous" referrerpolicy="no-referrer" />


    <script>
    window.print();
    </script>


  
    </style>



    <script>
function downloadCSV(csv, filename) {
    var fileCSV;
    var linkDonwload;

    // File CSV
    fileCSV = new Blob([csv], {type: "text/csv"});

    // Link download
    linkDonwload = document.createElement("a");

    // Nama file
    linkDonwload.download = filename;

    // Membuat link ke file
    linkDonwload.href = window.URL.createObjectURL(fileCSV);

    // Menyembunyikan link download
    linkDonwload.style.display = "none";

    // Menambahkan link ke DOM
    document.body.appendChild(linkDonwload);

    // Klik link download
    linkDonwload.click();
}

function exportTabelKeCSV(filename) {
    var csv = [];
    var baris = document.querySelectorAll("table tr");
    
    for (var i = 0; i < baris.length; i++) {
        var row = [], cols = baris[i].querySelectorAll("td, th");
        
        for (var j = 0; j < cols.length; j++) 
            row.push(cols[j].innerText);
        
        csv.push(row.join(","));        
    }

    // Download file CSV
    downloadCSV(csv.join("\n"), filename);
}
</script>



<style type="text/css" media="print">
  @page { size: landscape; }
</style>




<style type="text/css">


    .table-print {
    width: 100%;
    margin-bottom: 1rem;
    color: #212529;
}

    .table-print thead th {
        vertical-align: bottom;
        border-bottom: 2px solid #dee2e6;
    }

    .table-print th, .table-print td {
        padding: 0.75rem;
        vertical-align: top;
        border-top: 1px solid #dee2e6;
    }
 .table-print .thead-dark th {
    color: #fff;
    background-color: #343a40;
    border-color: #454d55;
}

    

</style>




<style type="text/css" media="print">
 
 @media print { 
   .table-bordered th,
.table-bordered td {
  border: 1px solid #000 !important;
    }
}


</style>





<script type="text/javascript">
    

function printContent(el)/*el di sini sebagai perwakilan dari id-id di bawah */{
            var restorepage = document.body.innerHTML;
            var printcontent = document.getElementById(el).innerHTML;
            document.body.innerHTML = printcontent;
            window.print();/*fungsi untuk mencetak*/
            document.body.innerHTML = restorepage;
        }


</script>


</head>



<body>


 <div class="container text-center align-middle">


<hr>
       
  <button class="btn btn-primary" onclick="exportTabelKeCSV('Jurnal_bmfi_penyaluran_pinjaman_<?= $this->session->userdata('sess_all')->nama_masjid; ?>.csv')"><i class="fas fa-download"></i> Download Data CSV (excel)</button>



<button class="btn btn-warning"  onclick="printContent('konten1')"><i
                class="fa fa-print"></i> Print ( Cetak Halaman )</button>


<hr>


</div>


<div id="konten1" class="container-fluid">


         <table class="table table-sm table-borderless datatables" width="100%">
            <thead >




                   <tr>
                    <th colspan="6" class="text-centeralign-middle" ></th>
                    
                    
                </tr>


                  <tr>
                    <th colspan="6" class="text-center align-middle" ><h5><b><?= $this->session->userdata('sess_all')->nama_masjid; ?> </h5></b></th>
                    
                    
                </tr>


                 <tr>
                    <th colspan="6" class="text-center align-middle"> <h6><b><?=   preg_replace('/[,]/', '.' , $this->session->userdata('sess_all')->alamat_masjid) ?> </h6></b></th>
             

                </tr>



                 <tr>
                    <th colspan="6" class="text-center align-middle" ><h4><b>Laporan Data Pendamping <h4><b></th>
                    
                    
                </tr>



                    <tr>
                    
                     <th colspan="6" class="text-center align-middle" ><h6><b>Tanggal <?=  date_format(date_create($this->input->get('tgl1')),'d-m-Y'); ?> sampai <?=  date_format(date_create($this->input->get('tgl2')),'d-m-Y'); ?></h6></b></th>
                    
                </tr>


<!-- 
    <tr>
    <th colspan="6" class="text-center align-middle"  ><h5> <b id="total_all"><b></b></h5> </th> 
                    
                </tr> -->



         



                  <tr>
                    <th colspan="6" class="text-center align-middle" ></th>
                    
                    
                </tr>


 </thead>

<table class="table-print table-bordered datatables"  width="100%">
          
            <thead class="thead-dark">
             

                <tr>
                    <th class="text-center align-middle">No</th>
                    <th class="text-center align-middle">Tanggal</th>
                   
                    <th class="text-center align-middle">Nama Pendamping</th>
                 


                      <th class="text-center align-middle">Jenis Kelamin</th>

                     <th class="text-center align-middle">No HP</th>


                    <th class="text-center align-middle">Alamat</th>


                          <th class="text-center align-middle">Keterangan</th>





                </tr>
            </thead>
            <tbody>


                <!-- <?php $total = 0; ?> -->



                <?php if($data->num_rows() > 0){ ?>
                <?php foreach($data->result_array() as $r => $v){ ?>
                <!-- <?php $total += $v['nominal_pengembalian'] ?>




                

                <?php $nm_provinsi = $v['nama_provinsi'] ?>

                <?php $nm_kabupaten_kota = $v['nama_kabupaten_kota'] ?>
 -->
               


                <tr>
                    <td class="text-center align-middle"><?= $r+1 ?></td>
                    <td class="text-center align-middle"><?=  date_format(date_create($v['created_at']),'d-m-Y');  ?></td>


                     <td class="text-center align-middle"><?= $v['nama_pendamping_bmfi_asesmen'] ?></td>

                   
                    <td class=" text-center align-middle"><?= $v['jenis_kelamin'] ?></td>
                   

                     <td class="text-center align-middle"><?= $v['no_hp'] ?></td>



                     <td class="text-center align-middle"><?= $v['alamat'] ?></td>



                     <td class="text-center align-middle"><?=  preg_replace('/[,]/', '.' , ($v['keterangan'])) ?></td>

                     
                   


                  

                

                </tr>
                <?php }}else{ ?>
                <tr>
                    <td colspan="6">Tidak ada data</td>
                </tr>
                <?php } ?>

                 </tbody>


                <table class="table table-borderless datatables" width="100%">
           <tbody>


            
                 <tr>
                    <td colspan="6"></td>
                </tr>

             <!--    <tr>
                    <td colspan="6"  class="text-center align-middle" id="grand_total"><h5><b> Jumlah Total Pengembalian : <?= 'Rp. '.number_format($total, 0, ',', '.') ?></b></h5></td>
                </tr>
 -->

            


            </tbody>
        </table>



                <div class="container text-center align-middle"> 


                    <br>



        <!--  <h5><b >   Provinsi <?= ($nm_provinsi) ?>, <?= ($nm_kabupaten_kota) ?>  </b> </h5>  -->

        <!--  <h5><b >   Provinsi <?= ($nm_provinsi) ?>, <?= ($nm_kabupaten_kota) ?>  </b> </h5>  -->

         <h5><b id="current_date" >  </b> </h5> 
                     
                  

        <!--     <h5><b >   Ketua DKM </b> </h5>  -->

            <h5><b >   <?= $this->session->userdata('sess_all')->nama_masjid; ?> </b> </h5>   


    

             <br>


                <br>

                   <br>

                        <br>


                        <br>




             <h6><b >...............................................................</b> </h6> 


              <br>

                        <br>

                        <br>

                        <br>


</div>

</div>









</body>

</html>

<!-- <script>
// date = new Date();
// year = date.getFullYear();
// month = date.getMonth() + 1;
// day = date.getDate();



// date = new Date();
// year = date.getFullYear();
// month = date.getMonth() + 1;
// day = date.getDate();




// document.getElementById("current_date").innerHTML = day + "-" + month + "-" + year;


</script>
 -->


<script>

var date = new Date();


var tahun = date.getFullYear();
var bulan = date.getMonth();
var tanggal = date.getDate();
var hari = date.getDay();
var jam = date.getHours();
var menit = date.getMinutes();
var detik = date.getSeconds();
switch(hari) {
 case 0: hari = "Minggu"; break;
 case 1: hari = "Senin"; break;
 case 2: hari = "Selasa"; break;
 case 3: hari = "Rabu"; break;
 case 4: hari = "Kamis"; break;
 case 5: hari = "Jum'at"; break;
 case 6: hari = "Sabtu"; break;
}
switch(bulan) {
 case 0: bulan = "Januari"; break;
 case 1: bulan = "Februari"; break;
 case 2: bulan = "Maret"; break;
 case 3: bulan = "April"; break;
 case 4: bulan = "Mei"; break;
 case 5: bulan = "Juni"; break;
 case 6: bulan = "Juli"; break;
 case 7: bulan = "Agustus"; break;
 case 8: bulan = "September"; break;
 case 9: bulan = "Oktober"; break;
 case 10: bulan = "November"; break;
 case 11: bulan = "Desember"; break;
}
var tampilTanggal = "Tanggal: " + hari + ", " + tanggal + " " + bulan + " " + tahun;
var tampilWaktu = "Jam: " + jam + ":" + menit + ":" + detik;


var tampilTanggal = "Tanggal: " + hari + ", " + tanggal + " " + bulan + " " + tahun;
var tampilWaktu = "Jam: " + jam + ":" + menit + ":" + detik;





document.getElementById("current_date").innerHTML =  tanggal + " " + bulan + " " + tahun;


</script>




<script>
let html1 = document.getElementById("grand_total").innerHTML;
document.getElementById("total_all").innerHTML = html1;

let html2 = document.getElementById("grand_total_donatur").innerHTML;
document.getElementById("total_donatur_all").innerHTML = html2;
</script>
