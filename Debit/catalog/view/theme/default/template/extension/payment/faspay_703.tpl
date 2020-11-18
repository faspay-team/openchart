<H4>Cara Pembayaran :</H4>
  <button class="accordion">Tata Cara Membayar Melalui ATM</button>
      <div class="panel">
        <ul>
          <li>Catat kode pembayaran yang anda dapat</li>
          <li>Gunakan ATM Mandiri untuk menyelesaikan pembayaran</li>
          <li>Masukkan PIN anda</li>
          <li>Pilih 'Bayar/Beli' lalu pilih 'Lainnya'</li>
          <li>Cari pilihan MULTI PAYMENT</li>
          <li>Masukkan Kode Perusahaan <b class="text-info"><?php echo $merchant_id ?></b></li>
          <li>Masukkan Kode Pelanggan <b class="text-info">234<?php echo $trx_id ?></b></li>
          <li>Pilih Tagihan Anda jika sudah sesuai tekan YA</li>
          <li>Konfirmasikan tagihan anda apakah sudah sesuai lalu tekan YA</li>
          <li>Harap Simpan Struk Transaksi yang anda dapatkan</li>
        </ul>
      </div>

      <button class="accordion">Tata Cara Membayar Melalui Mandiri Internet Banking</button>
      <div class="panel">
        <ul>
          <li>Pada Halaman Utama pilih submenu Lain-lain di bawah menu Pembayaran</li>
          <li>Cari Penyedia Jasa 70009 MitraPay</li>
          <li>Isi Nomor Pelanggan yang anda dapatkan</li>
          <li>Masukkan Jumlah Pembayaran sesuai dengan Jumlah Tagihan anda</li>
          <li>Pilih LANJUTKAN</li>
          <li>Transaksi selesai, jika perlu CETAK hasil transaksi anda</li>
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