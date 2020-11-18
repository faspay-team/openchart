<H4>Cara Pembayaran :</H4>
  <button class="accordion">Tata Cara Membayar melalui ATM BRI:</button>
      <div class="panel">
        <ul>
           <li>Nasabah melakukan pembayaran melalui ATM Bank BRI</li>
            <li>Pilih Menu Transaksi Lain</li>
            <li>Pilih Menu Pembayaran</li>
            <li>Pilih Menu Lainnya</li>
            <li>Pilih Menu BRIVA</li>
            <li>Masukan 16 digit Nomor Virtual Account <b class="text-info"><?php echo $trx_id ?></b></li>
            <li>Proses Pembayaran (Ya/Tidak)</li>
            <li>Harap Simpan Struk Transaksi yang anda dapatkan</li>
        </ul>
      </div>

      <button class="accordion">Tata Cara Membayar Melalui Mobile/SMS Banking BRI</button>
      <div class="panel">
        <ul>
          <li>Nasabah melakukan pembayaran melalui Mobile/SMS Banking BRI</li>
          <li>Nasabah memilih Menu Pembayaran melalui Menu Mobile/SMS Banking BRI</li>
          <li>Nasabah memilih Menu BRIVA</li>
          <li>Masukan 16 digit Nomor Virtual Account <b class="text-info"><?php echo $trx_id ?></b></li>
          <li>Masukan Jumlah Pembayaran sesuai Tagihan</li>
          <li>Masukan PIN Mobile/SMS Banking BRI</li>
          <li>Nasabah mendapat Notifikasi Pembayaran</li>
        </ul>
      </div>

       <button class="accordion">Tata Cara Membayar Melalui Internet Banking BRI</button>
      <div class="panel">
        <ul>
          <li>Nasabah melakukan pembayaran melalui Internet Banking BRI</li>
          <li>Nasabah memilih Menu Pembayaran</li>
          <li>Nasabah memilih Menu BRIVA</li>
          <li>Masukan Kode Bayar dengan 16 digit Nomor Virtual Account <b class="text-info"><?php echo $trx_id ?></b></li>
          <li>Masukan Password Internet Banking BRI</li>
          <li>Masukan mToken Internet Banking BRI</li>
          <li>Nasabah mendapat Notifikasi Pembayaran</li>
        </ul>
      </div>

       <button class="accordion">Tata Cara Membayar Melalui ATM Bank Lain</button>
      <div class="panel">
        <ul>
          <li>Setelah memasukkan kartu ATM dan nomor PIN, pilih menu <b>Transaksi Lainnya</b></li>
          <li>Pilih menu <b>Transfer</b></li>
          <li>Pilih menu <b>Ke Rek Bank Lain</b></li>
          <li>Masukan Kode Bank Tujuan : BRI (Kode Bank : 002) lalu klik <b>Benar</b></li>
          <li>Masukkan jumlah pembayaran sesuai tagihan. Klik <b>Benar</b></li>
          <li>Masukan Nomor Virtual Account <b class="text-info"><?php echo $trx_id ?></b></li>
          <li>Pilih dari rekening apa pembayaran akan di-debet</li>
          <li>Sistem akan memverifikasi data yang dimasukkan. Pilih <b>Benar</b> untuk memproses pembayaran</li>
          <li>Harap Simpan Struk Transaksi yang anda dapatkan</li>
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