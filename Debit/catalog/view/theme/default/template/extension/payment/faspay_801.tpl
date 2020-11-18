<H4>Cara Pembayaran :</H4>
<button class="accordion">XL Tunai</button>
  <div class="panel">
    <ul>
        <li>Nasabah melakukan pembayaran melalui ATM Bank BNI</li>
        <li>Pilih Menu Lainnya</li>
        <li>Pilih Menu Transfer</li>
        <li>Pilih Menu Rekening Tabungan</li>
        <li>Pilih Menu Ke Rekening BNI</li>
        <li>Masukan 16 digit Nomor Virtual Account <b><?php echo $trx_id ?></b></li>
        <li>Masukan Nominal Transfer</li>
        <li>Konfirmasi Pemindahbukuan</li>
        <li>Transaksi Selesai. Harap Simpan Struk Transaksi yang anda dapatkan</li>
      </ul>
  </div>

  <button class="accordion">SMS Banking BNI</button>
  <div class="panel">
    <ul>
        <li>Nasabah melakukan pembayaran melalui SMS Banking BNI</li>
        <li>Pilih Menu Transfer</li>
        <li>Masukan 16 digit Nomor Virtual Account <b><?php echo $trx_id ?></b></li>
        <li>Masukan Jumlah Pembayaran. Kemudian Proses</li>
        <li>Akan Muncul Popup dan kemudian Pilih Yes lalu Send</li>
        <li>Anda akan mendapatkan SMS konfirmasi dari BNI</li>
        <li>Reply SMS dengan ketik pin digit ke 2 & 3</li>
        <li>Transaksi Berhasil</li>
    </ul>
    <br>
        Atau bisa juga langsung mengetik sms dan kirim ke 3346 dengan format
    <br>
    <ul>
        <li><b>TRF[SPASI]NOMOR VA BNI[SPASI]NOMINAL</b></li>
        <li>Anda akan mendapatkan SMS konfirmasi dari BNI</li>
        <li>Reply SMS dengan ketik pin digit ke 2 & 3</li>
        <li>Transaksi Berhasil</li>
      </ul>
  </div>

  <button class="accordion">Internet Banking BNI</button>
  <div class="panel">
    <ul>
        <li>Nasabah melakukan pembayaran melalui Internet Banking BNI</li>
        <li>Ketik alamat <a href="https://ibank.bni.co.id">https://ibank.bni.co.id</a></li>
        <li>Masukkan User ID dan Password</li>
        <li>Klik menu <b>TRANSFER</b> kemudian pilih <b>TAMBAH REKENING FAVORIT</b>. Jika menggunakan Desktop/PC untuk menambah rekening pada menu <b>Transaksi</b> kemudian <b>Atur Rekening Tujuan</b> lalu <b>Tambah Rekening Tujuan</b></li>
        <li>Masukan Nama dan Kode Bayar dengan 16 digit Nomor Virtual Account <b><?php echo $trx_id ?></b></li>
        <li>Masukan Kode Otentikasi Token</li>
        <li>Nomor Rekening Tujuan Berhasil Ditambahkan</li>
        <li>Kembali ke menu TRANSFER. Pilih TRANSFER ANTAR REKENING BNI, kemudian pilih rekening tujuan</li>
        <li>Pilih Rekening Debit dan ketik nominal, lalu masukkan kode otentikasi token</li>
        <li>Transfer Anda Telah Berhasil</li>
      </ul>
  </div>
  <button class="accordion">Mobile Banking BNI</button>
  <div class="panel">
    <ul>
        <li>Akses BNI Mobile Banking dari handphone kemudian masukkan User ID dan Password</li>
        <li>Pilih menu Transfer</li>
        <li>Pilih Antar Rekening BNI kemudian Input Rekening Baru</li>
        <li>Masukkan nomor Rekening Debit</li>
        <li>Masukkan nomor Rekening Tujuan dengan 16 digit Nomor Virtual Account <b><?php echo $trx_id ?></b></li>
        <li>Masukkan jumlah pembayaran. Klik Benar</li>
        <li>Konfirmasi transaksi dan masukkan Password Transaksi</li>
        <li>Transaksi Anda Telah Berhasil</li>
      </ul>
  </div>
  <button class="accordion">ATM Bank Lain</button>
  <div class="panel">
    <ul>
       <li>Nasabah melakukan pembayaran melalui ATM Bank Lain</li>
        <li>Pilih menu Transaksi Lainnya</li>
        <li>Pilih menu Transfer</li>
        <li>Pilih Rekening BNI Lain</li>
        <li>Masukkan Kode Bank BNI (009) dan Pilih Benar</li>
        <li>Masukkan jumlah pembayaran</li>
        <li>Masukkan 16 digit Nomor Virtual Account <b><?php echo $trx_id ?></b></li>
        <li>Pilih Rekening yang akan di debit</li>
        <li>Konfirmasi Pembayaran</li>
        <li>Transaksi Selesai. Harap Simpan Struk Transaksi yang anda dapatkan</li>
      </ul>
  </div>

 <script>
  var acc = document.getElementsByClassName("accordion");
  var i;

  for (i = 0; i < acc.length; i++) {
    acc[i].addEventListener("click", function() {
      this.classList.toggle("active");
      var panel = this.nextElementSibling;
      if (panel.style.maxHeight){
        panel.style.maxHeight = null;
      } else {
        panel.style.maxHeight = panel.scrollHeight + "px";
      } 
    });
  }
  </script>
           