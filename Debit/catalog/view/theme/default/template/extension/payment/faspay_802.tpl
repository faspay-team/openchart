<H4>Cara Pembayaran :</H4>
  <button class="accordion">Tata Cara Membayar Melalui ATM</button>
      <div class="panel">
        <ul>
          <li>Catat kode pembayaran yang anda dapat</li>
          <li>Gunakan ATM Mandiri untuk menyelesaikan pembayaran</li>
          <li>Masukkan PIN anda</li>
          <li>Pilih 'Bayar/Beli'</li>
          <li>Cari pilihan MULTI PAYMENT</li>
          <li>Masukkan Kode Perusahaan <b class="text-info"><?php echo $merchant_id ?></b></li>
          <li>Masukkan Kode Pelanggan <b class="text-info"><?php echo $merchant_id ?><?php echo $trx_id ?></b></li>
          <li>Masukkan Jumlah Pembayaran sesuai dengan Jumlah Tagihan anda kemudian tekan 'Benar'</li>
          <li>Pilih Tagihan Anda jika sudah sesuai tekan YA</li>
          <li>Konfirmasikan tagihan anda apakah sudah sesuai lalu tekan YA</li>
          <li>Harap Simpan Struk Transaksi yang anda dapatkan</li>
        </ul>
      </div>

      <button class="accordion">Tata Cara Membayar Melalui Internet Banking Mandiri</button>
      <div class="panel">
        <ul>
          <li>Pada Halaman Utama pilih menu BAYAR</li>
          <li>Pilih submenu MULTI PAYMENT</li>
          <li>Cari Penyedia Jasa '<b>FASPAY</b>'</li>
          <li>Masukkan Kode Pelanggan <b class="text-info"><?php echo $merchant_id ?><?php echo $trx_id ?></b></li>
          <li>Masukkan Jumlah Pembayaran sesuai dengan Jumlah Tagihan anda</li>
          <li>Pilih LANJUTKAN</li>
          <li>Pilih Tagihan Anda jika sudah sesuai tekan LANJUTKAN</li>
          <li>Transaksi selesai, jika perlu CETAK hasil transaksi anda</li>
        </ul>
      </div>

      <button class="accordion">Tata Cara Membayar Melalui ATM Prima</button>
      <div class="panel">
        <ul>
          <li>Masukkan <span class="txt-themes">PIN</span></li>
          <li>Pilih menu <span class="txt-themes">TRANSAKSI LAINNYA</span></li>
          <li>Pilih menu <span class="txt-themes">KE REK BANK LAIN</span></li>
          <li>Masukkan kode sandi <span class="txt-themes">Bank Mandiri (008)</span> kemudian tekan <span class="txt-themes">BENAR</span></li>
          <li>Masukkan nomor <span class="txt-themes">VIRTUAL ACCOUNT</span> yang tertera pada halaman konfirmasi, dan tekan <span class="txt-themes">BENAR</span></li>
          <li>Masukkan jumlah pembayaran sesuai dengan yang ditagihkan dalam halaman konfirmasi</li>
          <li>Pilih <span class="txt-themes">BENAR</span> untuk menyetujui transaksi tersebut</li>
        </ul>
      </div>

      <button class="accordion">Tata Cara Membayar Melalui ATM Bersama</button>
      <div class="panel">
        <ul>
          <li>Masukkan <span class="txt-themes">PIN</span></li>
          <li>Pilih menu <span class="txt-themes">TRANSAKSI</span></li>
          <li>Pilih menu <span class="txt-themes">KE REK BANK LAIN</span></li>
          <li>Masukkan kode sandi <span class="txt-themes">Bank Mandiri (008)</span> diikuti dengan nomor <span class="txt-themes">VIRTUAL ACCOUNT</span> yang tertera pada halaman konfirmasi, dan tekan <span class="txt-themes">BENAR</span></li>
          <li>Masukkan jumlah pembayaran sesuai dengan yang ditagihkan dalam halaman konfirmasi</li>
          <li>Pilih <span class="txt-themes">BENAR</span> untuk menyetujui transaksi tersebut</li>
        </ul>
      </div>

      <button class="accordion">Tata Cara Membayar Melalui Mandiri online</button>
      <div class="panel">
        <ul>
          <li>Login Mandiri Online dengan memasukkan username dan password</li>
          <li>Pilih menu PEMBAYARAN</li>
          <li>Pilih menu MULTI PAYMENT</li>
          <li>Cari Penyedia Jasa '<b>FASPAY</b>'</li>
          <li>Masukkan Nomor Virtual Account <b class="text-info"><?php echo $merchant_id ?><?php echo $trx_id ?></b> dan nominal yang akan dibayarkan, lalu pilih Lanjut</li>
          <li>Setelah muncul tagihan, pilih Konfirmasi</li>
          <li>Masukkan PIN/ challange code token</li>
          <li>Transaksi selesai, simpan bukti bayar anda</li>
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