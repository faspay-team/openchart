<H4>Cara Pembayaran :</H4>
       <button class="accordion">ATM/ANT/SETAR-Setor Tarik</button>
      <div class="panel">
        <ul>
            <li>Pilih menu Transfer – Ke Rek BCA Virtual Account</li>
                  <li>Masukkan Nomor BCA Virtual Account, lalu pilih Benar</li>
                  <li>Pilih menu “Ke Rek BCA Virtual Account”.</li>
                  <li>Layar ATM akan menampilkan konfirmasi transaksi: 
              <ul>
                <li>Pilih Ya bila setuju, atau </li>
              <li>Masukkan jumlah transfer, lalu pilih Benar. Layar ATM akan kembali menampilkan konfirmasi jumlah pembayaran, pilih Ya bila ingin membayar</li>
            </ul>
          </li>
                  <li>Ikuti langkah selanjutnya sampai transaksi selesai</li>
          </ul>
      </div>

      <button class="accordion">KlikBCA Individu</button>
      <div class="panel">
        <ul>
          <li>Pilih Menu Transfer Dana – Transfer ke BCA Virtual Account</li>
                    <li>Masukkan nomor BCA Virtual Account, atau pilih Dari Daftar Transfer </li>
                    <li>Akan tampil konfirmasi transaksi:
              <ul>
               <li>Masukkan jumlah nominal transfer dan berita, atau </li>
               <li>Masukkan berita</li>
            </ul>
          </li>
                    <li>Ikuti langkah selanjutnya sampai transaksi selesai </li> 
          </ul>
      </div>

      <button class="accordion">ATM BCA</button>
      <div class="panel">
        <ul>
             <p>1. Pilih Transfer Dana</p>
            
                <li>Daftar Transfer:</li>
                  <li>Pilih menu Daftar Transfer - Tambah, pilih Ke BCA Virtual Account</li>
                  <li>Masukkan nomor BCA Virtual Account</li>
                  <li>Ikuti langkah selanjutnya sampai selesai </li>         
                

                            <li>Transfer Dana: 
                     <li>Pilih menu Transfer Dana – ke BCA Virtual Account</li>
                   <li>Pilih nomor rekening yang akan didebet dan pilih nomor BCA Virtual Account, lalu lanjut </li>
                   <li>Akan tampil konfirmasi transaksi: 
                       <ul><li>Masukkan jumlah nominal transfer dan berita, atau</li>
                     <li>Masukkan berita</li></ul>
                   </li>
                   <li>Ikuti langkah selanjutnya sampai transaksi selesai</li>
                
              </li>
                            <li>Otorisasi Transaksi:              
                  <p>Pilih menu Transfer Dana - Otorisasi Transaksi Tergantung single/multi otorisasi</p>
                <p>Untuk Single Otorisasi:</p>
                  <ul>Login User Releaser 
                      <li>Tandai transaksi pada tabel Transaksi Yang Belum 
                                       Diotorisasi, pilih Setuju </li>
                     <li>Ikuti langkah selanjutnya sampai selesai</li>
                  </ul>
                <p>Untuk Multi Otorisasi:</p>
                    <ul>
                     <li>Login User Approver
                         <li>Tandai transaksi pada tabel Approver, pilih Setuju </li>
                     </li>
                     <li>Login User Releaser
                         <li>Tandai transaksi pada tabel Transaksi Yang Belum 
                                        Diotorisasi, pilih Setuju</li>
                        <li>Ikuti langkah selanjutnya sampai selesai</li>
                     </li>
                  </ul>
              </li>                         
          </ul>
      </div>

       <button class="accordion">BCA Virtual Account melalui m-BCA (BCA Mobile)</button>
      <div class="panel">
        <ul>
            <li>Pilih m-Transfer</li>
                  <li>Pilih Transfer – BCA Virtual Account</li>
          <li>Pilih nomor rekening yang akan didebet</li>
          <li>Masukkan nomor BCA Virtual Account, lalu pilih OK</li>
          <li>Tampil konfirmasi nomor BCA Virtual Account dan rekening pendebetan, lalu Kirim</li>
                  <li>Tampil konfirmasi pembayaran, lalu pilih OK 
              <ul>
               <li>Masukkan jumlah nominal transfer dan berita, atau </li>
               <li>Masukkan berita</li>
            </ul>
          </li>
                  <li>Ikuti langkah selanjutnya sampai transaksi selesai </li> 
          </ul>
      </div>

      <button class="accordion">m-BCA (STK)</button>
      <div class="panel">
        <ul>
            <li>Pilih m-BCA </li>
                  <li>Pilih m-Payment</li>
          <li>Pilih Lainnya</li>
          <li>Masukkan TVA     pada Nama PT, lalu OK</li>
          <li>Masukkan nomor BCA Virtual Account pada No. Pelanggan, lalu OK </li>
                  <li>Masukkan PIN m-BCA, lalu OK</li>
          <li>Pilih Pilih nomor rekening yang akan didebet, lalu lanjut</li>
          <li>Akan muncul konfirmasi pembayaran, lalu pilih OK 
              <ul>
               <li>Masukkan jumlah bayar dan berita, atau </li>
               <li>Masukkan berita</li>
            </ul>
          </li>
                  <li>Ikuti langkah selanjutnya sampai transaksi selesai </li> 
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