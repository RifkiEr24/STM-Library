<?php  
  require_once ("koneksi.php"); 
 //login_success.php  
 session_start();  
 if(isset($_SESSION["username"]))  
 {  
      $sqlpustkawan="SELECT * from pustakawan where idPustakawan = '".$_SESSION['idacc']."'";
      $syncronize=$dbConn->prepare($sqlpustkawan);
      $syncronize->execute();
      $acc=$syncronize->fetch(PDO::FETCH_ASSOC);
      
 }  
 else  
 {  
      header("location:index.html");  
 }  
 ?>  
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="css/bootstrap.css" type="text/css">  
<link rel="stylesheet" href="css/style.css" type="text/css">
        <script src="js/jquery-3.4.1.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/Chart.bundle.min.js"></script>
        <script src="js/Chart.bundle.js"></script>
<title>Dashboard-Library</title>
</head>
<body>
    <section  style="background-color: whitesmoke;">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <a class="navbar-brand" href="#">
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
        
        <p class="fontheader ps text-black pl-5">Welcome <?php echo $_SESSION["username"] ?> </p>
        <p class="fontheader2 ps text-black pl-5">U're A <?php echo $_SESSION["role"] ?></p>
            <div class="row p-0 m-0">
                <div class="col-md-3">
                    
                        <div class="bg-white w-100 p-20 containdash">
                            <p class="fontheader2 ps ml-3 " style="padding-bottom: 0 !important;">Student</p>
                            
                            <canvas id="myChartBar" style="width: 655px; height: 300px;"></canvas> 
                            <a href="student.php" class="ps text-black text-decoration-none">
                            <hr class="m-0 p-0" style="border: 1px solid #007bff;"> 
                            <p class="fontbody ps pl-2 text-center text-primary">Manage Student</p>
                        </a>
                           
                 
                           
                        
                        </div>
                    
                </div>
                <div class="col-md-6">
                    <div class="bg-white  containdash">
                    <p class="fontheader2 ps ml-3 " style="padding-bottom: 0 !important;">Book</p>
                    <div style="height: 140px;">
                    <canvas id="myChart"></canvas>
                    </div>
                    <hr class="m-0 p-0" style="border: 1px solid #007bff;">
                            <a href="book.php" class="ps text-black text-decoration-none">
                               
                                <p class="fontbody ps pl-2 text-center text-black text-primary">Manage Book</p>
                            </a>
                    </div>
                </div>
                <div class="col-md-3">
                        <div class="bg-white w-100 containdash3">
                        <p class="fontheader2 ps ml-3 " style="padding-bottom: 0 !important;">Publisher</p>
                        <p class="fontheader2 ps ml-3 text-center" style="padding-bottom: 0 !important;">Jumlah</p>
               <?php
                        $totalpenerbit=$dbConn->prepare("SELECT COUNT(idPenerbit) as totalpenerbit FROM penerbit");
             $totalpenerbit->execute();
             foreach($totalpenerbit -> fetchAll() as $sumtpenerbit){
            }  ?>
                        <p class="fontheader3 ps ml-3 text-center count" style="padding-bottom: 0 !important;"><?php echo $sumtpenerbit['totalpenerbit']?></p>
                        <a href="publisher.php" class="ps text-black text-decoration-none">
                            <hr class="m-0 mt-4 p-0 " style="border: 1px solid #007bff;"> 
                            <p class="fontbody ps pl-2 text-center text-primary">Manage Publisher</p>
                        </a>
                        </div>
                </div>
            </div>
            <div class="row p-0 m-0">
                <div class="col-md-4">
                    <div>
                        <div class="bg-white w-100 p-20 containdash">
                        <p class="fontheader2 ps ml-3 " style="padding-bottom: 0 !important;">Librarian</p>
                        <div class="row">
                          <div class="col-4">    <img src="upload/pustakawan/<?php echo $acc['image'] ?>" width="120" height="120" style="object-fit: cover;" class="rounded-circle ml-4 p-2"> </div>
                          <div class="col-8 ps">
                          <p class="fontheader2 font-weight-bold">  <?php echo $acc['nama'] ?></p>
                          <p class="">  <?php echo $acc['email'] ?></p>
                          <p class="">  <?php echo $acc['phone'] ?></p>
                          <p class="fontheader2 font-weight-bold">  <?php echo $_SESSION['role'] ?></p>
                          </div>
                        </div>
                        <?php
                        if($_SESSION['role']=="Pustakawan" ||  $_SESSION['role']=="Pustakawan"){
                            echo"  <a href='editprofile.php' class='ps text-black text-decoration-none'>";
                        }else{
                          echo"  <a href='librarian.php' class='ps text-black text-decoration-none'>";
                        }
                        ?>
                      
                            <hr class="m-0 mt-4 p-0 " style="border: 1px solid #007bff;"> 
                            <p class="fontbody ps pl-2 text-center text-primary">Manage Librarian</p>
                        </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bg-white  containdash2">
                    <p class="fontheader2 ps ml-3 " style="padding-bottom: 0 !important;">Transaction</p>
                      <div>
                    <canvas id="myChartLine" style="height: 144px;"></canvas>
                    </div><?php
                     if($_SESSION['role']=="Admin" ||  $_SESSION['role']=="admin"){ 
                      echo"  <a data-toggle='modal' data-target='#warning' class='ps pointer text-black text-decoration-none'>";
                     }else{
                      echo"  <a  href='transaksi.php' class='ps text-black text-decoration-none'>";
                     }?>
                 <div class="modal fade" id="warning" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                              <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Hapus</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                  <div class="modal-body">
                                  Hanya Pustakawan yang bisa mengakses Menu Transaksi
                                  </div>
                                  <div class="modal-footer">
                                  
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
                                   
                                  </div>
                                </div>
                              </div>
                            </div>
                            <hr class="m-0  p-0 " style="border: 1px solid #007bff;"> 
                            <p class="fontbody ps pl-2 text-center text-primary">Manage trasanction</p>
                        </a>
                                        </div>
                </div>
                <div class="col-md-4">
                    <div class="bg-white  containdash2">
                    <p class="fontheader2 ps ml-3 " style="padding-bottom: 0 !important;">Report</p>
                    <div style="height: 144px;">
                    <canvas id="myChartPolar" height="110">
                    
                    </div>
                    <a href="report.php" class="ps text-black text-decoration-none">
                            <hr class="m-0  p-0 " style="border: 1px solid #007bff;"> 
                            <p class="fontbody ps pl-2 text-center text-primary">Manage report</p>
                        </a>
                    </div>
                </div>
            </div>
        </section>
        <script >
            // CHART BUKU
            document.getElementById("myChart");

var oilData = {
    labels: [
        <?php
         $buku=$dbConn->prepare("SELECT * FROM buku");
         $buku->execute();
         foreach($buku -> fetchAll() as $barisbuku){
            echo "'".$barisbuku['judul']."',";
        }
      echo"'Total Buku,'";
        

  ?>
       
    ],
    datasets: [
        {
            data: [
                <?php $totalbuku=$dbConn->prepare("SELECT SUM(qty) as total FROM buku");
                 $totalbuku->execute();
                 foreach($totalbuku -> fetchAll() as $sumbuku){
                }  
                 $buku=$dbConn->prepare("SELECT * FROM buku");
                 $buku->execute();
                 foreach($buku -> fetchAll() as $barisbuku){
                echo $barisbuku['qty'].",";
                 } echo $sumbuku['total'].",";
                
                      
                ?>
            ],
            backgroundColor: [
                "#FF6384",
                "#63FF84",
                "#84FF63",
                "#8463FF",
                "#6384FF",
                "#347deb",
                "#eb3449",
                "#eb8f34",
                "#9115cf",
                "#14d92e"
            ]
        }]
};

var pieChart = new Chart(myChart, {
  type: 'pie',
  data: oilData,
   options: {
    legend: {
      position:'bottom',
      labels:{
        boxWidth:5,
      }
    },
    maintainAspectRatio:false,
    responsive:true,
    }
});
//BARR
var ctd = document.getElementById("myChartBar");
var myChartBar = new Chart(ctd, {
  type: 'bar',
  data: {
    labels: ["Tingkat 1", "Tingkat 2", "Tingkat 3","Total"],
    datasets: [{
        <?php
             $totaltk1=$dbConn->prepare("SELECT COUNT(tingkat) as tingkat FROM siswa WHERE tingkat = '10'");
             $totaltk1->execute();
             foreach($totaltk1 -> fetchAll() as $sumtk1){
            }  
            $totaltk2=$dbConn->prepare("SELECT COUNT(tingkat) as tingkat FROM siswa WHERE tingkat = '11'");
            $totaltk2->execute();
            foreach($totaltk2 -> fetchAll() as $sumtk2){
           }  
           $totaltk3=$dbConn->prepare("SELECT COUNT(tingkat) as tingkat FROM siswa WHERE tingkat = '12'");
           $totaltk3->execute();
           foreach($totaltk3 -> fetchAll() as $sumtk3){
          }   $totalsiswa=$dbConn->prepare("SELECT COUNT(nis) as total FROM siswa");
          $totalsiswa->execute();
          foreach($totalsiswa -> fetchAll() as $sumsiswa){
         }  
            ?>
      label: 'Jumlah Siswa',        
      data: [<?php echo $sumtk1['tingkat'].",".$sumtk2['tingkat'].",".$sumtk3['tingkat'].",".$sumsiswa['total']?>],
      backgroundColor: [
        'rgba(255, 99, 132, 0.2)',
        'rgba(54, 162, 235, 0.2)',
        'rgba(255, 206, 86, 0.2)',
        'rgba(75, 192, 192, 0.2)',
        'rgba(153, 102, 255, 0.2)',
        'rgba(255, 159, 64, 0.2)',
        'rgba(255, 99, 132, 0.2)',
        'rgba(54, 162, 235, 0.2)',
        'rgba(255, 206, 86, 0.2)',
        'rgba(75, 192, 192, 0.2)',
        'rgba(153, 102, 255, 0.2)',
        'rgba(255, 159, 64, 0.2)'
      ],
      borderColor: [
        'rgba(255,99,132,1)',
        'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)',
        'rgba(75, 192, 192, 1)',
        'rgba(153, 102, 255, 1)',
        'rgba(255, 159, 64, 1)',
        'rgba(255,99,132,1)',
        'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)',
        'rgba(75, 192, 192, 1)',
        'rgba(153, 102, 255, 1)',
        'rgba(255, 159, 64, 1)'
      ],
      borderWidth: 1
    }]
  },
  options: {
    responsive: true,
    scales: {
  
      yAxes: [{
        ticks: {
          beginAtZero: true
        }
      }]
    }
  }
});
//LINEEEEEEEEEEEEEEEEEEEEEEEEEEEEE
var ctl = document.getElementById("myChartLine").getContext('2d');


var myChartLine = new Chart(ctl,{
    type: 'line',
    data: {
        labels: 
        
        [
          <?php
         $transaksi=$dbConn->prepare("SELECT Distinct tglPinjam FROM transaksi order by tglPinjam");
         $transaksi->execute();
         foreach($transaksi -> fetchAll() as $rowtransaksi){
            echo "'".$rowtransaksi['tglPinjam']."',";
        }
        ?>
        ],
        datasets: [{
            label: 'Total Transaksi', // Name the series
            data: [
          <?php
              $transaksi=$dbConn->prepare("SELECT Distinct tglPinjam FROM transaksi order by tglPinjam");
         $transaksi->execute();
         foreach($transaksi -> fetchAll() as $rowtransaksi){
          $tanggal=$dbConn->prepare("SELECT Count(tglPinjam) as tanggal FROM transaksi where tglPinjam ='".$rowtransaksi['tglPinjam']."'");
          $tanggal->execute();
          foreach($tanggal -> fetchAll() as $rowtanggal){
            echo $rowtanggal['tanggal'].",";
           
         }
        }
        ?>
            ], // Specify the data values array
            fill: false,
            borderColor: '#2196f3', // Add custom color border (Line)
            backgroundColor: '#2196f3', // Add custom color background (Points and Fill)
            borderWidth: 1 // Specify bar border width
        }]},
    options: {
      responsive: true, // Instruct chart js to respond nicely.
      maintainAspectRatio: false, // Add to prevent default behaviour of full-width/height 
    }
});
//POLAR chart
var data = {
    datasets: [{
        data: [
          <?php
            $sqltotaltr="SELECT * FROM transaksi,detailtransaksi where  transaksi.idTransaksi=detailtransaksi.idTransaksi GROUP BY transaksi.idTransaksi";
            $hitungtotaltr=$dbConn->prepare($sqltotaltr);
            $hitungtotaltr->execute();
            $baris=$hitungtotaltr->fetch(PDO::FETCH_ASSOC);
            $hitung=$hitungtotaltr->rowCount();

            $sqltotaltrb="SELECT * FROM transaksi,detailtransaksi where detailtransaksi.status=0 AND  transaksi.idTransaksi=detailtransaksi.idTransaksi GROUP BY transaksi.idTransaksi";
            $hitungtotaltrb=$dbConn->prepare($sqltotaltrb);
            $hitungtotaltrb->execute();
            $baris=$hitungtotaltrb->fetch(PDO::FETCH_ASSOC);
            $hitung2=$hitungtotaltrb->rowCount();

            $sqltotaltrs="SELECT * FROM transaksi,detailtransaksi where detailtransaksi.status=1 AND  transaksi.idTransaksi=detailtransaksi.idTransaksi GROUP BY transaksi.idTransaksi";
            $hitungtotaltrs=$dbConn->prepare($sqltotaltrs);
            $hitungtotaltrs->execute();
            $baris=$hitungtotaltrs->fetch(PDO::FETCH_ASSOC);
            $hitung3=$hitungtotaltrs->rowCount();

            echo $hitung.",";
            echo $hitung2.",";
            echo $hitung3.",";
            ?>
        ],
        backgroundColor: [
            "#FF6384",
            "#4BC0C0",
            "#FFCE56",
            "#E7E9ED",
            "#36A2EB"
        ],
        label: 'My dataset' // for legend
    }],
    labels: [
        "Total Transaksi",
        "Total dikembalikan",
        "Total Belum Dikembalikan",
    ]
};
var ctk = $("#myChartPolar");
new Chart(ctk, {
    data: data,
    type: 'polarArea',
    options: {
    legend: {
      position:'left',
      labels:{
        boxWidth:5,
      }
    },
  }
});
        </script>
        <script src="js/main.js"></script>
</body>
</html>