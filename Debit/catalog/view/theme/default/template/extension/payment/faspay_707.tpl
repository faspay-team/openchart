<H4>Cara Pembayaran :</H4>
       <button class="accordion">Tata Cara Membayar Melalui Alfamart</button>
      <div class="panel">
        <ul>
             <li>Catat dan simpan kode pembayaran Alfamart Anda, yaitu : <b class="text-info"><?php echo $trx_id ?></b>.</li>
              <li>Datangi kasir Alfamart terdekat dan beritahukan pada kasir bahwa Anda ingin melakukan pembayaran"<b class="text-info"><?php echo $faspay_name ?></b>".</li> <!-- , Alfamidi, Alfa Express, Lawson atau Dan+Dan -->
              <li>Beritahukan kode pembayaran Alfamart Anda pada kasir dan silahkan lakukan pembayaran Anda senilai <b class="text-info"></b>.</li>
              <li>Simpan struk pembayaran Anda sebagai tanda bukti pembayaran yang sah.</li>  
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

