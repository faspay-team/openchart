<H4>Cara Pembayaran :</H4>
  <button class="accordion">Tata Cara Membayar Melalui XL Tunai</button>
      <div class="panel">
        <ul>
          <li>Masukkan *123*120# di Handphone Anda</li>
          <li>Pilih menu 'Belanja Online'</li>
          <li>Masukkan ID Merchant sebagai berikut:<b class="text-info"><?php echo $merchant_id ?></b></li>
          <li>Masukkan Order ID sebagai berikut: <b class="text-info"><?php echo $trx_id ?></b></li>
          <li>Setujui Transaksi dengan memilih 'Lanjut'</li>
          <li>Anda akan menerima SMS notifikasi bahwa pembayaran telah berhasil.</li>
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