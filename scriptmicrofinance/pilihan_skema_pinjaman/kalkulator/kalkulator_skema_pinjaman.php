<!DOCTYPE html>
<html>
<head>
  <title>Kalkulator Skema Pinjaman</title>
  <style>
    .container {
      width: 300px;
      margin: 0 auto;
      padding: 20px;
      border: 1px solid #ccc;
      text-align: center;
    }

    input[type="text"] {
      width: 100%;
      padding: 10px;
      margin-bottom: 10px;
    }

    button {
      background-color: #4CAF50;
      color: white;
      padding: 10px 20px;
      border: none;
      cursor: pointer;
    }

    button:hover {
      opacity: 0.8;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Kalkulator Skema Pinjaman</h2>

    <br>

    <label for="jumlahTarget">Jumlah Pinjaman:</label>
    <br>
      <label for="jumlahTarget">Hitungan Rupiah</label>
    <input type="text" id="jumlahTarget" required class="form-control rupiah"><br>

    <label for="jumlahOrang">Jumlah Orang Ikut Meminjam:</label>
    <input type="text" id="jumlahOrang" required class="form-control jumlah"><br>

    <label for="satuan-target">Skema Waktu:</label>
    <br>
     <label for="satuan-target">Si Peminjam</label>

    <select id="satuan-target" class="form-control" onchange="setjenis_satuan()">
      <option value="" selected disabled>Pilih</option>
      <option value="hari">Hari</option>
      <option value="minggu">Minggu</option>
      <option value="bulan">Bulan</option>
      <option value="tahun">Tahun</option>
    </select>

    <br>

    <label for="jenis_satuan">Skema Pinjaman:</label>
    <input type="text" id="jenis_satuan" required class="form-control jumlah" disabled>
    <input type="text" id="jumlahPerkiraan" required class="form-control jumlah"><br>

    <br>

    <button onclick="hitungTargetPengembalian()">Hitung Target Angsuran Pengembalian</button>
    <p id="hasilTargetPengembalian"></p>
  </div>

<br>



<center><img src="<?php echo base_url('attachments/kalkulator_skema_pinjaman/microfinance_kalkulator.jpg') ?>" alt="Microfinance Kalkulator"> </center>



  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    function formatRupiah(angka, prefix) {
      var number_string = angka.toString().replace(/[^0-9]/g, '');
      var split = number_string.split(',');
      var sisa = split[0].length % 3;
      var rupiah = split[0].substr(0, sisa);
      var ribuan = split[0].substr(sisa).match(/\d{3}/gi);

      if (ribuan) {
        separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
      }

      rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;

      return prefix === undefined ? rupiah : (rupiah ? prefix + rupiah : '');
    }

    function formatJumlah(angka, prefix) {
      var number_string = angka.toString().replace(/[^0-9]/g, '');
      var split = number_string.split(',');
      var sisa = split[0].length % 3;
      var rupiah = split[0].substr(0, sisa);
      var ribuan = split[0].substr(sisa).match(/\d{3}/gi);

      if (ribuan) {
        separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
      }

      rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;

      return prefix === undefined ? rupiah : (rupiah ? prefix + rupiah : '');
    }

    function setjenis_satuan() {
      var satuan = document.getElementById("satuan-target").value;
      var jenis_satuanInput = document.getElementById("jenis_satuan");

      if (satuan === "hari") {
        jenis_satuanInput.value = "Jumlah Batas Hari";
      } else if (satuan === "minggu") {
        jenis_satuanInput.value = "Jumlah Minggu";
      } else if (satuan === "bulan") {
        jenis_satuanInput.value = "Jumlah Batas Bulan";
      } else if (satuan === "tahun") {
        jenis_satuanInput.value = "Jumlah Batas Tahun";
      }
    }

    function hitungTargetPengembalian() {
      var jumlahOrang = parseFloat(document.getElementById("jumlahOrang").value.replace(/[^0-9]/g, ''));
      var jumlahTarget = parseFloat(document.getElementById("jumlahTarget").value.replace(/[^0-9]/g, ''));
      var satuan = document.getElementById("satuan-target").value;

      var jumlahPerkiraan = parseFloat(document.getElementById("jumlahPerkiraan").value.replace(/[^0-9]/g, ''));

      // Pemeriksaan kesalahan untuk jumlahPerkiraan
      if (isNaN(jumlahPerkiraan) || jumlahPerkiraan <= 0) {
        alert("Masukkan nilai yang valid untuk Jumlah Perkiraan.");
        return;
      }

      var TargetPengembalian = jumlahTarget / jumlahOrang / jumlahPerkiraan;

      var hasilTargetPengembalian = document.getElementById("hasilTargetPengembalian");
      hasilTargetPengembalian.innerHTML = "Jumlah Perkiraan: " + formatRupiah(TargetPengembalian.toFixed(0), 'Rp. ') + "<br> per " + " 1 Orang " +  " per " + satuan  + "<br> untuk Target  " + jumlahPerkiraan + "  "   + satuan + "<br> Kontribusi Pengembalian " + jumlahOrang +   " Orang  "  ;
    }

    $(document).ready(function() {
      $(".rupiah").keyup(function(e) {
        var nilai = formatRupiah($(this).val(), 'Rp. ');
        $(this).val(nilai);
      });

      $(".jumlah").keyup(function(e) {
        var nilai = formatJumlah($(this).val(), '');
        $(this).val(nilai);
      });
    });
  </script>
</body>
</html>
