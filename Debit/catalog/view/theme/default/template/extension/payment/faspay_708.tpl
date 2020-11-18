<H4>Cara Pembayaran :</H4>
  <button class="accordion">Tata Cara Membayar Melalui ATM Danamon</button>
      <div class="panel">
        <ul>
          <li>Masuk ke menu Pembayaran -> Lainnya -> Virtual Account </li>
          <li>Masukkan 16 digit nomor Virtual Account </li>
          <li>Periksa jumlah tagihan dan konfirmasi pembayaran. </li>
        </ul>
      </div>

      <button class="accordion">Tata Cara Membayar Melalui Bank Lain</button>
      <div class="panel">
        <ul>
          <li>Transfer melalui Bank Lain yang tergabung dalam jaringan ATM Bersama, ALTO dan Prima.</li>
          <li>Masukkan kode Bank Danamon (011) dan 16 digit nomor Virtual Account di rekening tujuan.</li>
          <li>Masukkan nominal transfer sesuai tagihan.</li>
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