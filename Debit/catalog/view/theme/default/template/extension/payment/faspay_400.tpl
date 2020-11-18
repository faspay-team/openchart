<H4>Cara Pembayaran :</H4>
    <button class="accordion">Via Aplikasi</button>
      <div class="panel">
         <p class="text-justify" style="color: #4e4e4e";><i>Aplikasi untuk smartphone dapat di download di customer service BRI atau Mesin ATM</i></p>
      </div>

    >Cara Pembayaran :</H4>
    <button class="accordion">Via SMS</button>
      <div class="panel">
        <p class="text-center">
          Lakukan pembayaran dengan cara mengirim <span class="txt-themes">SMS</span> ke <span class="txt-themes">9123</span>, ketik: <span class="txt-themes">BAYAR MD</span> (spasi) <span class="txt-themes"><?php echo $faspay_name ?></span><br>(spasi) <span class="txt-themes"><?php echo $total ?></span> (spasi) <span class="txt-themes">ORDER_ID</span> (spasi) <span class="txt-themes">PIN</span> atau <span class="txt-themes">BAYAR MD 1902291</span> (spasi) <span class="txt-themes">0</span> (spasi)<br><span class="txt-themes"><?php echo $trx_id ?></span> (spasi) (<span class="txt-themes">PIN ANDA</span>)
        </p>
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