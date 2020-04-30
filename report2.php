<?php 
  require_once ("koneksi.php"); 
  session_start();  
  if(isset($_SESSION["username"]))  
  {  
       $sqlpustkawan="SELECT * from pustakawan where idPustakawan = '".$_SESSION['idacc']."'";
       $syncronize=$dbConn->prepare($sqlpustkawan);
       $syncronize->execute();
       $acc=$syncronize->fetch(PDO::FETCH_ASSOC);
  } else  
  {  
       header("location:index.html");  
  } 
   

if(isset($_POST['cari'])){
$tk=$_POST['tingkat'];
$jurusan=$_POST['jurusan'];
$today=date('Y-m-d');

if($jurusan != "All" && $tk != "All"){
  $tran=$dbConn->prepare("SELECT TIMESTAMPDIFF(DAY,t.tglPinjam,'".$today."') as lamaPinjam,  t.idTransaksi,
   d.idBuku, d.status, t.nis, s.nis, s.nama, p.nama as namap,b.judul,t.tglPinjam,s.kelas,s.jurusan,s.tingkat from transaksi t,detailtransaksi d,siswa s,
   pustakawan p,buku b WHERE t.idTransaksi=d.idTransaksi AND t.nis=s.nis AND t.idPustakawan=p.idPustakawan
    AND d.idBuku=b.idBuku AND (SELECT TIMESTAMPDIFF(DAY,t.tglPinjam,'".$today."') AS lamaMinjam) > 3 AND d.status=0  AND s.jurusan='".$jurusan."' AND s.tingkat='".$tk."' GROUP BY t.idTransaksi");
   $tran->execute();
}
else if($jurusan =="All" && $tk !="All"){
  $tran=$dbConn->prepare("SELECT TIMESTAMPDIFF(DAY,t.tglPinjam,'".$today."') as lamaPinjam,  t.idTransaksi,
  d.idBuku, d.status, t.nis, s.nis, s.nama, p.nama as namap,b.judul,t.tglPinjam,s.kelas,s.jurusan,s.tingkat from transaksi t,detailtransaksi d,siswa s,
  pustakawan p,buku b WHERE t.idTransaksi=d.idTransaksi AND t.nis=s.nis AND t.idPustakawan=p.idPustakawan
   AND d.idBuku=b.idBuku AND (SELECT TIMESTAMPDIFF(DAY,t.tglPinjam,'".$today."') AS lamaMinjam) > 3 AND d.status=0  AND s.tingkat='".$tk."' GROUP BY t.idTransaksi");
  $tran->execute();
}
else if($jurusan!="All" && $tk =="All"){
  $tran=$dbConn->prepare("SELECT TIMESTAMPDIFF(DAY,t.tglPinjam,'".$today."') as lamaPinjam,  t.idTransaksi,
  d.idBuku, d.status, t.nis, s.nis, s.nama, p.nama as namap,b.judul,t.tglPinjam,s.kelas,s.jurusan,s.tingkat from transaksi t,detailtransaksi d,siswa s,
  pustakawan p,buku b WHERE t.idTransaksi=d.idTransaksi AND t.nis=s.nis AND t.idPustakawan=p.idPustakawan
   AND d.idBuku=b.idBuku AND (SELECT TIMESTAMPDIFF(DAY,t.tglPinjam,'".$today."') AS lamaMinjam) > 3 AND d.status=0  AND s.jurusan='".$jurusan."' GROUP BY t.idTransaksi");
  $tran->execute();
}
}else{
  $today=date('Y-m-d');
  $tran=$dbConn->prepare("SELECT TIMESTAMPDIFF(DAY,t.tglPinjam,'".$today."') as lamaPinjam,  t.idTransaksi,
  d.idBuku, d.status, t.nis, s.nis, s.nama, p.nama as namap,b.judul,t.tglPinjam,s.kelas,s.jurusan,s.tingkat from transaksi t,detailtransaksi d,siswa s,
  pustakawan p,buku b WHERE t.idTransaksi=d.idTransaksi AND t.nis=s.nis AND t.idPustakawan=p.idPustakawan
   AND d.idBuku=b.idBuku AND (SELECT TIMESTAMPDIFF(DAY,t.tglPinjam,'".$today."') AS lamaMinjam) > 3 AND d.status=0  GROUP BY t.idTransaksi");
  $tran->execute();
}

 

?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="css/bootstrap.css" type="text/css">  
<link rel="stylesheet" href="css/style.css" type="text/css">
<script src="js/jquery-3.4.1.min.js"></script>
        <script src="js/bootstrap.min.js"></script> 
        <title>Manage Book-Library</title>
</head>
<body>
<section style="background-color: whitesmoke;">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <a class="navbar-brand" href="dashboard.php">
            <img src="img/logo stm.png" width="30" height="30" class="d-inline-block align-top" alt="">
            STM LIBRARY
          </a>
            <ul class="navbar-nav mr-auto"> 
                </ul>
                <ul class="navbar-nav">
                <li class="nav-item dropdown ">
                    <a class="nav-link dropdown-toggle text-light" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <img style="object-fit: cover" src="upload/pustakawan/<?php echo $acc['image']?>"  class="rounded-circle" width="40" height="40">
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                      <a class="dropdown-item" href="editprofile.php">Edit Account</a>
                      <div class="dropdown-divider"></div>
                      <a class="dropdown-item" href="index.html">Log Out</a>
                    </div>
                  </li>
                </ul>
        </nav>
            <div class="container-fluid p-4 pt-0">
                <p class="ps h2 text-center">Laporan Transaksi</p>
                <div class="form-group">
                    <form action="" method="POST">
                      <div class="row">
                          <div class="col">
                          

                          
                          <select class="form-control float-right w-25" name="tingkat">
                          <option value="All">All</option>   
                          <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                             
                          </select>
                       
                          </div>
          
                          <div class="col">
                          <select class="form-control float-left w-25" id="jurusan"  name="jurusan">
                          <option value="All">All</option>      
                          <option value="TPTU">TPTU</option>
                                <option value="TOI">TOI</option>
                                <option value="KP">KP</option>
                                <option value="KM">KM</option>
                                <option value="TEK">TEDK</option>
                                <option value="TEI">EIND</option>
                                <option value="SIJA">SIJA</option>
                                <option value="RPL">RPL</option>
                                <option value="TP4">PFPT</option>    
                          </select>
                            </div>
                      </div>
                      <div class="row">
                          <div class="col">
                          <button class="btn btn-primary float-right mr-5 mt-3" type="submit" name="cari">Cari</button>
                          </div>
          
                          <div class="col">
                          <button class="btn btn-secondary ml-5 mt-3" type="submit" name="reset">Reset</button>
                            </div>
                      </div>
                 
                          
                         <a href="report2.php"><button type="button" name="cari" class="btn btn-primary float-right">Terlambat</button></a>
                         <a href="report.php"><button   type="button" name="reset" class="btn btn-secondary float-right mr-3 mb-4">Transaksi</button></a>
                        </form>
                              </div> 
            <table class="table  table-bordered ">
                            <thead class="bg-primary text-white">
                            <tr >
                            <th class="w-15">Id Transaksi</th>
                                <th class="w-15">NIS</th>
                                <th class="w-20">Nama Siswa</th>
                                <th class="w-10">Kelas/Jurusan</th>
                                <th class="w-15">Tanggal Pinjam</th>
                                <th class="w-10">Jumlah Buku</th>
                                <th>Telat(Hari)</th>
                              </tr>
                            </thead>
                            <tbody>                         
                            <?php
                           
                          $rowtransaksi = $tran->fetchAll();
                        foreach($rowtransaksi as $baris){
                          
                          echo "<tr>";
                          echo "<th>".$baris['idTransaksi']."</th>";
                          echo "<th>".$baris['nis']."</th>";
                          $siswa=$dbConn->prepare("SELECT * FROM siswa where nis ='".$baris['nis']."'");
                          $siswa->execute();
                          $rowsiswa = $siswa->fetch(PDO::FETCH_ASSOC);
                          echo "<td>".$rowsiswa['nama']."</td>";
                          echo "<td>".$rowsiswa['tingkat']."-".$rowsiswa['jurusan']."-".$rowsiswa['kelas']."</td>";
                          echo "<td>".$baris['tglPinjam']."</td>";
                          $jmlbuku=$dbConn->prepare("SELECT COUNT(idTransaksi) as jumlah FROM detailtransaksi where idTransaksi ='".$baris['idTransaksi']."'");
                          $jmlbuku->execute();
                          $rowjumlah = $jmlbuku->fetchAll();
                          
                        foreach($rowjumlah as $barisjumlah){}
                          echo"<td>".$barisjumlah['jumlah']."</td>";
                          $terlambat=$baris['lamaPinjam']-3;
                        echo"<td>".$terlambat."</td>";
                          echo "</tr>";
                        }
                         ?>
                            </tbody>
                          </table>      
        </div>
</section>
</body>
</html>
