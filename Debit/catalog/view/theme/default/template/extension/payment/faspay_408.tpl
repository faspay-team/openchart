<H4>Cara Pembayaran :</H4>
  <button class="accordion">Tata Cara Membayar Melalui ATM Maybank - Menu Pembayaran</button>
      <div class="panel">
        <ul>
           <li>Pilih menu <span class="txt-themes">PEMBAYARAN/TOP UP PULSA</span></li>
            <li>Pilih menu <span class="txt-themes">VIRTUAL ACCOUNT</span></li>
            <li>Masukkan nomor <span class="txt-themes">VIRTUAL ACCOUNT</span> yang tertera pada halaman konfirmasi</li>
            <li>Pilih <span class="txt-themes">YA</span> untuk menyetujui pembayaran tersebut</li>
        </ul>
      </div>

      <button class="accordion">Tata Cara Membayar Melalui Mesin ATM Maybank - Menu Transfer</button>
      <div class="panel">
        <ul>
          <li>Pilih menu <span class="txt-themes">TRANSFER</span></li>
          <li>Pilih menu <span class="txt-themes">VIRTUAL ACCOUNT</span></li>
          <li>Masukkan nomor <span class="txt-themes">VIRTUAL ACCOUNT</span> yang tertera pada halaman konfirmasi</li>
          <li>Masukkan jumlah pembayaran sesuai dengan yang ditagihkan dalam halaman konfirmasi</li>
          <li>Silahkan masukkan nomor referensi apabila diperlukan, lalu tekan <span class="txt-themes">BENAR</span></li>
          <li>Pilih <span class="txt-themes">YA</span> untuk menyetujui pembayaran tersebut</li>
        </ul>
      </div>

      <button class="accordion">Tata Cara Membayar Melalui Maybank Internet Banking</button>
      <div class="panel">
        <ul>
          <li>Silahkan login Internet Banking dari Maybank</li>
          <li>Pilih menu <span class="txt-themes">Rekening dan Transaksi</span></li>
          <li>Kemudian pilih <span class="txt-themes">Maybank Virtual Account</span></li>
          <li>Masukkan nomor rekening dengan nomor Virtual Account Anda (contoh: <span class="txt-themes">78218<?php echo $trx_id ?></span>)</li>
          <li>Masukkan jumlah pembayaran sesuai dengan yang ditagihkan dalam halaman konfirmasi</li>
          <li>Masukkan SMS Token (TAC) dan klik <span class="txt-themes">Setuju</span></li>
        </ul>
      </div>

       <button class="accordion">Tata Cara Membayar Melalui ATM Bank lain</button>
      <div class="panel">
        <ul>
          <li>Pilih menu <span class="txt-themes"> TRANSFER ANTAR BANK </span></li>
          <li>Pilih <span class="txt-themes">Maybank</span> sebagai bank tujuan atau dengan memasukkan <span class="txt-themes"> kode bank Maybank “016” </span>diikuti dengan 16 digit nomor VIRTUAL ACCOUNT yang tertera pada halaman konfirmasi</li>
          <li>Masukkan jumlah pembayaran sesuai dengan yang ditagihkan dalam halaman konfirmasi</li>
          <li>Konfirmasikan transaksi anda pada halaman berikutnya. Apabila benar tekan <span class="txt-themes">BENAR</span> untuk mengeksekusi transaksi</li>
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