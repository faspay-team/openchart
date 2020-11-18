<H4>Cara Pembayaran :</H4>
  <button class="accordion">Tata Cara Membayar Melalui ATM Permata</button>
      <div class="panel">
        <ul>
            <li>Masukkan <span class="txt-themes">PIN</span></li>
            <li>Pilih menu <span class="txt-themes">TRANSAKSI LAINNYA</span></li>
            <li>Pilih menu <span class="txt-themes">PEMBAYARAN</span></li>
            <li>Pilih menu <span class="txt-themes">PEMBAYARAN LAINNYA</span></li>
            <li>Pilih menu <span class="txt-themes">VIRTUAL ACCOUNT</span></li>
            <li>Masukkan nomor <span class="txt-themes">VIRTUAL ACCOUNT</span> yang tertera pada halaman konfirmasi, dan tekan <span class="txt-themes">BENAR</span></li>
            <li>Pilih rekening yang menjadi sumber dana yang akan didebet, lalu tekan YA untuk konfirmasi transaksi</li>
        </ul>
      </div>

      <button class="accordion">Tata Cara Membayar Melalui ATM Prima</button>
      <div class="panel">
        <ul>
          <li>Masukkan <span class="txt-themes">PIN</span></li>
          <li>Pilih menu <span class="txt-themes">TRANSAKSI LAINNYA</span></li>
          <li>Pilih menu <span class="txt-themes">KE REK BANK LAIN</span></li>
          <li>Masukkan kode sandi Bank Permata (013) kemudian tekan <span class="txt-themes">BENAR</span></li>
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
          <li>Masukkan kode sandi <span class="txt-themes">Bank Permata (013)</span> diikuti dengan nomor <span class="txt-themes">VIRTUAL ACCOUNT</span> yang tertera pada halaman konfirmasi, dan tekan <span class="txt-themes">BENAR</span></li>
          <li>Masukkan jumlah pembayaran sesuai dengan yang ditagihkan dalam halaman konfirmasi</li>
          <li>Pilih <span class="txt-themes">BENAR</span> untuk menyetujui transaksi tersebut</li>
        </ul>
      </div>

      <button class="accordion">Tata Cara Membayar Melalui Permata Mobile</button>
      <div class="panel">
        <ul>
            <li>Buka aplikasi PermataMobile Internet (Android/iPhone)</li>
            <li>Masukkan User ID & Password</li>
            <li>Pilih Pembayaran Tagihan</li>
            <li>Pilih Virtual Account</li>
            <li>Masukkan 16 digit nomor Virtual Account yang tertera pada halaman konfirmasi</li>
            <li>Masukkan nominal pembayaran sesuai dengan yang ditagihkan</li>
            <li>Muncul Konfirmasi pembayaran</li>
            <li>Masukkan otentikasi transaksi/token</li>
            <li>Transaksi selesai</li>
        </ul>
      </div>

      <button class="accordion">Tata Cara Membayar Melalui Permata Net</button>
      <div class="panel">
        <ul>
            <li>Buka website PermataNet: <a href="https://new.permatanet.com"><span class="txt-themes">https://new.permatanet.com</span></a></li>
            <li>Masukkan user ID & Password</li>
            <li>Pilih Pembayaran Tagihan</li>
            <li>Pilih Virtual Account</li>
            <li>Masukkan 16 digit nomor Virtual Account yang tertera pada halaman konfirmasi</li>
            <li>Masukkan nominal pembayaran sesuai dengan yang ditagihkan</li>
            <li>Muncul Konfirmasi pembayaran</li>
            <li>Masukkan otentikasi transaksi/token</li>
            <li>Transaksi selesai </li>
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