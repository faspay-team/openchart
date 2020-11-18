<H4>Cara Pembayaran :</H4>
<button class="accordion">Tata Cara Membayar Melalui Alfamart</button>
  <div class="panel">
    <ul>
        <li>Catat dan simpan kode pembayaran Indomaret Anda, yaitu :<b class="text-info"><?php echo $trx_id ?></b>.</li>
        <li>Tunjukkan kode pembayaran ke kasir Indomaret terdekat, dan lakukan pembayaran senilai <b class="text-info"></b>.</li>
        <li>Simpan bukti pembayaran yang sewaktu-waktu diperlukan jika terjadi kendala transaksi.</li>     
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