<?php 
  require_once ("koneksi.php"); 
  session_start();  
  if(isset($_SESSION["username"]))  
  {  
       $sqlpustkawan="SELECT * from pustakawan where idPustakawan = '".$_SESSION['idacc']."'";
       $syncronize=$dbConn->prepare($sqlpustkawan);
       $syncronize->execute();
       $acc=$syncronize->fetch(PDO::FETCH_ASSOC);
  }else  
  {  
       header("location:index.html");  
  }   

  $searchword="";
 
if(isset($_GET['search'])){
  $searchword=$_GET['search'];
}
if(isset($_POST['selesai'])){
  $sqltransaksi="SELECT * FROM transaksi,detailtransaksi where nis LIKE '%".$searchword."%'  AND detailtransaksi.status=1 AND  transaksi.idTransaksi=detailtransaksi.idTransaksi GROUP BY transaksi.idTransaksi";

}
else{
$sqltransaksi="SELECT * FROM transaksi,detailtransaksi where nis LIKE '%".$searchword."%'  AND detailtransaksi.status=0 AND  transaksi.idTransaksi=detailtransaksi.idTransaksi GROUP BY transaksi.idTransaksi";
}//ADD
if(isset($_POST['submitadd'])){
  try{
    $id=$_POST['idadd'];
    $nis=$_POST['nis'];
    $idpustakawan=$_SESSION['idacc'];
    $tanggal=$_POST['tanggal'];
    $idbuku1=$_POST['idbuku1'];
    $idbuku2=$_POST['idbuku2'];
    if(empty($id)){
      $errorMsg="Please Enter Your Name";
    }
  else if(empty($nis)){
    $errorMsg="Please enter email";
  }
  else if(empty($idpustakawan)){
    $errorMsg="Please enter telp";
  }
  else if(empty($tanggal)){
    $errorMsg="Please enter alamat";
  }
  
  if(!isset($errorMsg)){
    $sqlinsert="INSERT INTO transaksi VALUES(:idTransaksi,:nis,:idPustakawan,:tglPinjam)";
     $insert_stmt=$dbConn->prepare($sqlinsert);
    $insert_stmt->bindParam(':idTransaksi',$id);
    $insert_stmt->bindParam(':nis',$nis);
    $insert_stmt->bindParam(':idPustakawan',$idpustakawan);
    $insert_stmt->bindParam(':tglPinjam',$tanggal);
  
    
     if($insert_stmt->execute()){
          $insertDetail1 = $dbConn ->prepare("INSERT INTO detailtransaksi (idTransaksi, idBuku, status) VALUES (:idTransaksi,:idBuku,0)");
          $insertDetail1 -> bindparam(":idTransaksi",$id);
          $insertDetail1 -> bindparam(":idBuku",$idbuku1);
         
          if($insertDetail1->execute()){
                    $updateBook1 = $dbConn ->prepare("UPDATE buku SET qty=qty-1 WHERE idBuku=$idbuku1");
                    if($updateBook1 -> execute()){
                    
                              if($idbuku2 != ""){
                                $insertDetail2 = $dbConn ->prepare("INSERT INTO detailtransaksi (idTransaksi, idBuku, status) VALUES (:idTransaksi,:idBuku,0)");
                                $insertDetail2 -> bindparam(":idTransaksi",$id);
                                $insertDetail2 -> bindparam(":idBuku",$idbuku2);
                              
                                if($insertDetail2->execute()){
                                $updateBook2 = $dbConn ->prepare("UPDATE buku SET qty=qty-1 WHERE idBuku=$idbuku2");
                                   if($updateBook2 -> execute()){
                               echo "<script>alert('Data UPLOAD Successfully !'); </script>";
                            }
                     }
                   }
                else{
             echo "<script>alert('Data UPLOAD Successfully !'); </script>";
    }
                    }
          }
     }
     else{    

     }
 }else{
     echo"<script>alert('File Upload failed!')</script>";
     echo $errorMsg;
     
 }

  }
  catch(PDOException $e){
    echo $e->getMessage();
}
}
//KEMBALIKAN
if(isset($_POST['kembalikan'])){
  try{
    $idtransaksi=$_POST['idtransaksi'];
    $tglkembali=$_POST['tanggalKembali'];
    if(isset($_POST['check1'])){
    $idbuku=$_POST['check1'];
     $sqlreturn="UPDATE detailtransaksi SET tglKembali=:tglKembali,status=1 where idTransaksi='".$idtransaksi."' AND idBuku='".$idbuku."'"; 
     $return_stmt=$dbConn->prepare($sqlreturn);
     $return_stmt->bindParam(':tglKembali',$tglkembali);
          if($return_stmt->execute()){
                    $return_buku=$dbConn->prepare("UPDATE buku set qty=qty+1 where idBuku =$idbuku");
                    if($return_buku->execute()){
                      echo "<script>alert('Buku Berhasil Dikembalikan'); </script>";
                    }else{
                      echo "<script>alert('Buku Gagal Dikembalikan'); </script>";
                    }
              }else{
                echo "<script>alert('Buku Gagal Dikembalikan'); </script>";
              }
    }
    //BUKU KEDUA
    if(isset($_POST['check2'])){
      $idbuku2=$_POST['check2'];
       $sqlreturn="UPDATE detailtransaksi SET tglKembali=:tglKembali,status=1 where idTransaksi='".$idtransaksi."' AND idBuku='".$idbuku2."'"; 
       $return_stmt=$dbConn->prepare($sqlreturn);
       $return_stmt->bindParam(':tglKembali',$tglkembali);
            if($return_stmt->execute()){
                      $return_buku=$dbConn->prepare("UPDATE buku set qty=qty+1 where idBuku =$idbuku2");
                      if($return_buku->execute()){
                        echo "<script>alert('Buku Berhasil Dikembalikan'); </script>";
                      }else{
                        echo "<script>alert('Buku Gagal Dikembalikan'); </script>";
                      }
                }else{
                  echo "<script>alert('Buku Gagal Dikembalikan'); </script>";
                }
      }
    
  } catch(PDOException $e){
    echo $e->getMessage();
}
}
//UPDATEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEE
if(isset($_POST['submitupdate'])){
  try{
    $jumlahbk=$_POST['jumlah'];
    $id= $_POST['idedit'];
    $nis=$_POST['nis'];
    $idbuku1=$_POST['editbuku1'];
    $idasal=$_POST['idasal1'];

    if($jumlahbk==2){
    $idbuku2=$_POST['editbuku2'];
    $idasal2=$_POST['idasal2'];
    if($idbuku2==$idbuku1){
      $errorMsg="Tidak Bisa meminjam buku yang sama!";
    }
    }
    $tanggal=$_POST['tanggal'];
    if(empty($nis)){
      $errorMsg="Please Enter Your Name";
    }
  else if(empty($idbuku1)){
    $errorMsg="Please enter email";
  }
  
  // else if(empty($idbuku2)){
  //   $errorMsg="Please enter idbuku2";
  // }
  if(!isset($errorMsg)){
    $sqlinsert="UPDATE  transaksi set nis=:nis,tglPinjam=:tglPinjam WHERE idTransaksi='".$id."'";
     $insert_stmt=$dbConn->prepare($sqlinsert);
    $insert_stmt->bindParam(':nis',$nis);
    $insert_stmt->bindParam(':tglPinjam',$tanggal);
  
    
     if($insert_stmt->execute()){
          $insertDetail1 = $dbConn ->prepare("UPDATE detailtransaksi set idBuku=:idBuku where idTransaksi='".$id."' AND idBuku='".$idasal."'");
          $insertDetail1 -> bindparam(":idBuku",$idbuku1);
         
          if($insertDetail1->execute()){
      
                    $updateBook1 = $dbConn ->prepare("UPDATE buku SET qty=qty-1 WHERE idBuku=$idbuku1");
                        if($updateBook1 -> execute()){
                              $qtyadd = $dbConn ->prepare("UPDATE buku SET qty=qty+1 WHERE idBuku=$idasal");
                                   if( $qtyadd->execute()){
                                       if($jumlahbk == 2){
                                               $insertDetail2 = $dbConn ->prepare("UPDATE detailtransaksi set idBuku=:idBuku where idTransaksi='".$id."' AND idBuku='".$idasal2."'");
                                                   $insertDetail2 -> bindparam(":idBuku",$idbuku2);
                                                     if($insertDetail2->execute()){
                                                                $updateBook2 = $dbConn ->prepare("UPDATE buku SET qty=qty-1 WHERE idBuku=$idbuku2");
                                                                    if($updateBook2 -> execute()){
                                                                        $qtyadd2 = $dbConn ->prepare("UPDATE buku SET qty=qty+1 WHERE idBuku=$idasal2");
                                                                            if($qtyadd2->execute()){
                                                                           echo "<script>alert('Data UPLOAD Successfully !'); </script>";
                                                                                  }
                                                                                }
                                                            }
                              }else{
                                echo "<script>alert('Data UPLOAD Successfully !'); </script>";
                              }
                            }
                              else{
                                echo "<script>alert('Data UPLOAD Successfully a!'); </script>";
                              }
                    }
          }
     }
     else{
        
     }
 }else{
     echo"<script>alert('File Upload failed!')</script>";
     echo $errorMsg;
     
 }
  }catch(PDOException $e){
    echo $e->getMessage();
}
}
if(isset($_POST['delete'])){
  $id=$_GET['delete_id'];//get delete_id and stor $id variable
  $deletedetail=$dbConn->prepare("DELETE from detailtransaksi where idTransaksi='".$id."'");
  if($deletedetail->execute()){
    $deletetransaksi=$dbConn->prepare("DELETE from transaksi where idTransaksi='".$id."'");
    $deletetransaksi->execute();
  }
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
        <p class="ps fontheader pl-4">Transaction List</p>
            <div class="container-fluid p-4 pt-0">
                        <button type="button" class="btn btn-success mb-2 float-left" data-toggle="modal" data-target="#exampleModal">Add</button>
                        <form method="GET" action="">
                        <div class="input-group w-25 float-right">

                            <input type="text" name="search" class="form-control" id="search" >
                                    <button class="btn btn-primary " type="submitedit">Cari</button>
                                    
                                    </form>            
                        </div> 
                        <br><br>   
                        <form action="" method="POST">
                        <button type="submit" name="selesai" class="btn btn-primary">Selesai </button><span class="ps h5">&nbsp/&nbsp</span> <button name="terlambat"  type="submit" class="btn btn-secondary">Terlambat </button>
                        </form>
                       </form> <br>
                           <!-- MODAL ADD -->
                           <div class="modal fade add" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog " role="document">
                                  <div class="modal-content">
                                      <div class="modal-header">
                                      <h5 class="modal-title" id="exampleModalLabel">Add Transaction</h5>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                      </button>
                                      </div>
                                      <div class="modal-body">
                                      <form method="POST" action="" enctype="multipart/form-data">

                                              <div class="form-group">
                                                  <?php
                                                  // DAPATKAN ID
                                                      $sqlid="SELECT idTransaksi from transaksi order by idTransaksi DESC LIMIT 1";
                                                      $stmt=$dbConn->prepare($sqlid);
                                                      $stmt->execute();
                                                      $row=$stmt->fetch(PDO::FETCH_ASSOC);
              
                                                      if(empty($row)){
                                                          $angka=1;
                                                      }else{
                                                          $angka=preg_replace('/[^0-9]/','',$row['idTransaksi']);
                                                          $angka++;
                                                      }
                                                      $formmated_value="TR".sprintf("%03d", $angka);
                                                  ?>
                                                      <label for="id">ID</label>
                                                      <input type="text" value="<?php echo $formmated_value ?>" id="idadd" name="idadd" min="1" class="form-control"  readonly>
                                                  </div>
                                                <br>
                                                  <div class="input-group mb-3">
                                                    
                                                    <input required type="text" class="form-control" id="nis" name="nis" placeholder="Masukkan Nis" aria-label="Recipient's username" aria-describedby="button-addon2">
                                                    <div class="input-group-append">
                                                      <button type="button" class="btn btn-outline-secondary" onclick="loaddata();" name="carinis" id="carinis">Cari</button>
                                                    </div>
                                                  </div>      
                                                  <div class="row">
                                                    <div class="col">
                                                      <div class="form-group">
                                                        <label for="id">Nama</label>
                                                        <input type="text"  id="nama" name="nama" class="form-control" readonly>
                                                      </div> 
                                                    </div>
                                                      <div class="col">
                                                        <div class="form-group">
                                                          <label for="id">Kelas</label>
                                                          <input type="text" value="" id="kelas" name="kelas" class="form-control" readonly>
                                                        </div> 
                                                    </div>
                                            </div>
                                           
                                            
                                            <div class="form-group">
                                              <label for="id">Tanggal</label>
                                              <input type="date" value="<?php echo date('Y-m-d'); ?>" id="tanggal"  name="tanggal" class="form-control">
                                            </div> 
                                            <label>Jumlah Pinjaman:</label>
                                            <div class="form-check form-check-inline">
                                              <input name="JumlahBuku" class="form-check-input radiob" type="radio"  id="inlineRadio1" value="1" checked>
                                              <label class="form-check-label" for="inlineRadio1">1</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                              <input name="JumlahBuku" class="form-check-input radiob" type="radio"  id="inlineRadio2" value="2">
                                              <label class="form-check-label" for="inlineRadio2">2</label>
                                            </div>
                                            <div class="input-group mb-3">
                                              
                                              <input type="text" class="form-control" id="idbuku1" name="idbuku1" placeholder="Masukkan Id Buku"aria-describedby="button-addon2">
                                              <div class="input-group-append">
                                                <button type="button" class="btn btn-outline-secondary" onclick="loadbuku();" name="caribuku" id="caribuku">Cari</button>
                                              </div>
                                            </div>      
                                            <div class="form-group ">
                                              <input type="text" value="" id="judulbuku" name="judulbuku" class="form-control" readonly>
                                            </div> 
                                            <!-- DUA -->
                                            <div style="display: none;" id="show-me">
                                            <div class="input-group mb-3">
                                              
                                              <input type="text" class="form-control" name="idbuku2" id="idbuku2" placeholder="Masukkan Id Buku"aria-describedby="button-addon2">
                                              <div class="input-group-append">
                                                <button type="button" onclick="loadbuku2();" class="btn btn-outline-secondary" name="caribuku2" id="caribuku2">Cari</button>
                                              </div>
                                            </div>      
                                            <div class="form-group ">
                                              <input type="text" value="" id="judulbuku2" name="judulbuku2" class="form-control" readonly>
                                            </div>
                                          </div> 
                            </div>
                            
                                <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <input type="submit" name="submitadd" class="btn btn-primary" value="Add Transaction">
                                </form>
                                  </div>
                                </div>
                             </div>
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
                                <th class="w-15">action</th>
                              </tr>
                            </thead>
                            <tbody>                         
                            <?php
                        $select_stmt=$dbConn->prepare($sqltransaksi);
                          $select_stmt->execute();
                          $rowtransaksi = $select_stmt->fetchAll();
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
                          $jmlbuku=$dbConn->prepare("SELECT COUNT(idTransaksi) as jumlah FROM detailtransaksi where idTransaksi ='".$baris['idTransaksi']."' AND status = 0");
                          $jmlbuku->execute();
                          $rowjumlah = $jmlbuku->fetchAll();
                          
                        foreach($rowjumlah as $barisjumlah){}
                          echo"<td>".$barisjumlah['jumlah']."</td>";
                          echo "<td>"; 
                          if($barisjumlah['jumlah'] != '0'){
                          echo " <button type='button' class='btn btn-info' data-toggle='modal' data-target='#detail".$baris['idTransaksi']."'>Detail</button> ";
                        
                            echo "<img data-toggle='modal' class='pointer ml-1' data-target='#modal".$baris['idTransaksi']."' src='img/pencil.png' width='35'>";
                          }  
                        
                           echo "<img class=' pointer' data-toggle='modal' data-target='#delete".$baris['idTransaksi']."' src='img/bin.png' width='35'></td>";
                          echo "</tr>";
                        }
                         ?>
                            </tbody>
                          </table>  
            </div>
            <!-- MODAL DETAIL -->
            <?php
            $select_stmt=$dbConn->prepare($sqltransaksi);
                          $select_stmt->execute();
                          $rowtransaksi = $select_stmt->fetchAll();
                        foreach($rowtransaksi as $baris){?>
              <div class="modal fade add" id="detail<?php echo $baris['idTransaksi']?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog " role="document">
                                  <div class="modal-content">
                                      <div class="modal-header">
                                      <h5 class="modal-title" id="exampleModalLabel">Return Transaction</h5>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                      </button>
                                      </div>
                                      <div class="modal-body">
                                      <form method="POST" action="" enctype="multipart/form-data">

                                              <div class="form-group">

                                                      <label for="id">ID</label>
                                                      <input type="text" value="<?php echo $baris['idTransaksi'] ?>" id="idtransaksi" name="idtransaksi" min="1" class="form-control"  readonly>
                                                  </div>
                                             
                                                  <div class="form-group">
                                              <label for="id">NIS</label>
                                              <input type="text" value="<?php echo $baris['nis']; ?>" id="nis"  name="nis" class="form-control" readonly>
                                            </div> 
                                            <div class="form-group">
                                              <label for="id">Tanggal Pinjam</label>
                                              <input type="date" value="<?php echo $baris['tglPinjam']; ?>" id="tanggal"  name="tanggalPinjam" class="form-control" readonly>
                                            </div> 
                                            <div class="form-group">
                                              <label for="id">Tanggal Kembali</label>
                                              <input type="date" value="<?php echo date("Y-m-d") ?>" id="tanggalKembali"  name="tanggalKembali" class="form-control" readonly>
                                            </div> 
                                            <label>Jumlah Pinjaman:</label>
                                            <?php
                                        $select_stmt=$dbConn->prepare("SELECT * FROM detailtransaksi where idTransaksi ='".$baris['idTransaksi']."' AND status = 0");
                                                      $select_stmt->execute();
                                                      $rowdetail = $select_stmt->fetchAll();
                                                      $jumlahbuku=0;
                                                    foreach($rowdetail as $barisdetail){
                                                      $jumlahbuku=$jumlahbuku+1;
                                                      $select_buku=$dbConn->prepare("SELECT * FROM buku where idBuku ='".$barisdetail['idBuku']."'");
                                                      $select_buku->execute();
                                                      $rowbuku = $select_buku->fetchAll();
                                                    foreach($rowbuku as $barisbuku){
                                                    ?>
                                                    <?php
                                                    $today=date('Y-m-d');
                                                    $diff=$dbConn->prepare("SELECT TIMESTAMPDIFF(DAY,:tglPinjam,:tglKembali) AS selisih");
                                                    $diff->bindParam(':tglPinjam',$baris['tglPinjam']);
                                                    $diff->bindParam(':tglKembali',$today);
                                                    $diff->execute();
                                                    $hari=$diff->fetch(PDO::FETCH_ASSOC);
                                                    $selisih=$hari['selisih'];
                                                    if($selisih<=3){
                                                      $ket="-";
                                                      $denda="Rp 0";
                                                    }else{
                                                      $selisih=$selisih-3;
                                                      $ket="Terlambat ".$selisih." hari";
                                                      $den=$jumlahbuku*$selisih*1000;
                                                      $denda="Rp.".$den;
                                                    }
                                                    ?>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="<?php echo $barisbuku['idBuku']?>" name="check<?php echo $jumlahbuku?>">
                                                <label class="form-check-label" for="defaultCheck1">
                                                  <?php echo $barisbuku['judul']?>
                                                </label>
                                                <?php
                                                
                                                ?>
                                                
                                                <input type="text" value="<?php echo $ket?>" id="denda"  name="denda" class="form-control" readonly>
                                                <br>
                                              </div>
                        <?php }?>
                        <?php }?>
                                               <div class="form-group">
                                            <label for="denda">Denda</label>
                                              <input type="text" class="form-control" id="denda" value="<?php echo $denda ?>" name="denda" aria-describedby="button-addon2" readonly>
                                              
                                            </div> 
                            </div>
                            
                                <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <input type="submit" name="kembalikan" class="btn btn-primary" value="Return Book">
                                </form>
                                  </div>
                                </div>
                             </div>
                        </div>
                        <!-- MODAL DELETE -->
                        <div class="modal fade" id="delete<?php echo $baris['idTransaksi']?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                              <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                  <div class="modal-header bg-danger">
                                    <h5 class="modal-title" id="exampleModalLabel">Hapus</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                  <div class="modal-body">
                                   Apakah anda yakin ingin menghapus data Dengan id <?php echo $baris['idTransaksi']?> ?
                                  </div>
                                  <div class="modal-footer">
                                  <form action="transaksi.php?delete_id=<?php echo $baris['idTransaksi']?>"  method='POST' >
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                                    <button type="submit" name="delete" class="btn btn-danger">Hapus Data</button>
                                  </form>
                                  </div>
                                </div>
                              </div>
                            </div>
                        <?php }?>
                        <?php
            $select_stmt=$dbConn->prepare($sqltransaksi);
                          $select_stmt->execute();
                          $rowtransaksi = $select_stmt->fetchAll();
                          $jumlahbukuedit=0;
                        foreach($rowtransaksi as $baris){?>
                          <!-- MODAL EDIT -->
                          <div class="modal fade add" id="modal<?php echo $baris['idTransaksi']?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog " role="document">
                                  <div class="modal-content">
                                      <div class="modal-header">
                                      <h5 class="modal-title" id="exampleModalLabel">Edit Transaction</h5>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                      </button>
                                      </div>
                                      <div class="modal-body">
                                      <form method="POST" action="" enctype="multipart/form-data">

                                              <div class="form-group">
                                                  
                                                      <label for="id">ID</label>
                                                      <input type="text" value="<?php echo $baris['idTransaksi'] ?>" id="idadd" name="idedit" min="1" class="form-control"  readonly>
                                                  </div>
                                                <br>
                                                  <div class="input-group mb-3">
                                                    
                                                    <input type="text" class="form-control" id="nis<?php echo $baris['idTransaksi']?>" value="<?php echo $baris['nis']?>" name="nis" placeholder="Masukkan Nis" aria-label="Recipient's username" aria-describedby="button-addon2">
                                                    <div class="input-group-append">
                                                      <button type="button" class="btn btn-outline-secondary" onclick="loaddataedit<?php echo $baris['idTransaksi']?>();" name="carinis" id="carinis">Cari</button>
                                                   
                                                    </div>
                                                  </div>      
                                                  <div class="row">
                                                    <div class="col">
                                                      <div class="form-group">
                                                        <label for="id">Nama</label>
                                                        <?php
                                                          $siswa=$dbConn->prepare("SELECT * FROM siswa where nis ='".$baris['nis']."'");
                                                          $siswa->execute();
                                                          $rowsiswa = $siswa->fetch(PDO::FETCH_ASSOC);
                                                        ?>
                                                        <input type="text" value="<?php echo $rowsiswa['nama']?>"  id="nama<?php echo $baris['idTransaksi']?>" name="nama" class="form-control" readonly>
                                                      </div> 
                                                    </div>
                                                      <div class="col">
                                                        <div class="form-group">
                                                          <label for="id">Kelas</label>
                                                          <input type="text" value="<?php echo $rowsiswa['tingkat']."-".$rowsiswa['jurusan']."-".$rowsiswa['kelas']?>" id="kelas<?php echo $baris['idTransaksi']?>" name="kelas" class="form-control" readonly>
                                                        </div> 
                                                    </div>
                                            </div>
                                           
                                            
                                            <div class="form-group">
                                              <label for="id">Tanggal</label>
                                              <input type="date" value="<?php echo $baris['tglPinjam'] ?>" id="tanggal"  name="tanggal" class="form-control">
                                            </div> 
                                            <?php
                                              $select_stmt=$dbConn->prepare("SELECT * FROM detailtransaksi where idTransaksi ='".$baris['idTransaksi']."' AND status = 0");
                                              $select_stmt->execute();
                                              $rowdetail = $select_stmt->fetchAll();
                                              $jumlahbuku=0;
                                            foreach($rowdetail as $barisdetail){
                                              $jumlahbuku=$jumlahbuku+1;
                                              $select_buku=$dbConn->prepare("SELECT * FROM buku where idBuku ='".$barisdetail['idBuku']."'");
                                              $select_buku->execute();
                                              $rowbuku = $select_buku->fetchAll();
                                            foreach($rowbuku as $barisbuku){?>
                                            <div class="input-group mb-3">
                                              <!-- SET BUKU -->
                                              <input type="text" class="form-control" value="<?php echo $barisbuku['idBuku'] ?>" id="idbuku<?php echo $jumlahbuku.$baris['idTransaksi']?>" name="editbuku<?php echo $jumlahbuku?>" placeholder="Masukkan Id Buku">
                                              <div class="input-group-append">
                                                <button type="button" class="btn btn-outline-secondary" onclick="loadbukuedit<?php echo $jumlahbuku.$baris['idTransaksi']?>();" name="caribuku" id="caribuku">Cari</button>
                                              </div>
                                            </div>      
                                            <div class="form-group ">
                                              <input type="text" value="<?php echo $barisbuku['judul']?>" id="judulbuku<?php echo $jumlahbuku.$baris['idTransaksi']?>" name="judulbuku" class="form-control" readonly>
                                              <input type="text" class="d-none"  value="<?php echo $barisbuku['idBuku']?>" id="jumlah" name="idasal<?php echo $jumlahbuku?>" class="form-control" readonly>
                                            
                                            </div> 
                                            <?php }?>
                                            <?php }?>
                                            <input type="text" class="d-none" value="<?php echo $jumlahbuku?>" id="jumlah" name="jumlah" class="form-control" readonly>
                                            
                                          <script>
                                            // JAVASCRIPT NIS EDIT
                                            function loaddataedit<?php echo $baris['idTransaksi']?>()
                                          {
                                            var name=document.getElementById('nis<?php echo $baris['idTransaksi']?>').value;
                                              if(name != "")
                                              {
                                                $.ajax({
                                                type: 'post',
                                                url: 'load.php',	
                                                data: {
                                                  user_name:name,
                                                },
                                                success: function (response) {
                                                  var hasil=response;
                                                  var split=hasil.split(",");
                                                  document.getElementById('nama<?php echo $baris['idTransaksi']?>').value=split[0];
                                                  document.getElementById('kelas<?php echo $baris['idTransaksi']?>').value=split[1];
                                                }
                                                });
                                              }
                                              else
                                              {
                                                document.getElementById('nama<?php echo $baris['idTransaksi']?>').value='nis tidak ditemukan';
                                                  document.getElementById('kelas<?php echo $baris['idTransaksi']?>').value='nis tidak ditemukan';
                                              }
                                          }
                                          // JAVASCRIPT BUKU1EDIT
                                          function loadbukuedit1<?php echo $baris['idTransaksi']?>()
                                          {
                                            var name=document.getElementById('idbuku1<?php echo $baris['idTransaksi']?>').value;
                                              if(name != "")
                                              {
                                                $.ajax({
                                                type: 'post',
                                                url: 'loadbuku.php',	
                                                data: {
                                                  user_name:name,
                                                },
                                                success: function (response) {
                                                  document.getElementById('judulbuku1<?php echo $baris['idTransaksi']?>').value=response;
                                                }
                                                });
                                              }
                                              else
                                              {
                                              
                                              }
                                          }
                                          //JAVASCRIPT BUKU2EDIT
                                          function loadbukuedit2<?php echo $baris['idTransaksi']?>()
                                          {
                                            var name=document.getElementById('idbuku2<?php echo $baris['idTransaksi']?>').value;
                                              if(name != "")
                                              {
                                                $.ajax({
                                                type: 'post',
                                                url: 'loadbuku.php',	
                                                data: {
                                                  user_name:name,
                                                },
                                                success: function (response) {
                                                  document.getElementById('judulbuku2<?php echo $baris['idTransaksi']?>').value=response;
                                                }
                                                });
                                              }
                                              else
                                              {
                                              
                                              }
                                          }  
                                          </script>
                            </div>
                            
                                <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <input type="submit" name="submitupdate" class="btn btn-primary" value="Edit Transaction">
                                </form>
                                  </div>
                                </div>
                             </div>
                        </div>
                        <?php }?>
    </section>
    
<script src="js/main.js"></script>
<script>
      function loaddata()
    {
      var name=document.getElementById('nis').value;
         if(name != "")
        {
           $.ajax({
           type: 'post',
           url: 'load.php',	
           data: {
            user_name:name,
           },
           success: function (response) {
             var hasil=response;
             var split=hasil.split(",");
            document.getElementById('nama').value=split[0];
            document.getElementById('kelas').value=split[1];
           }
           });
        }
        else
        {
          document.getElementById('nama').value='nis tidak ditemukan';
            document.getElementById('kelas').value='nis tidak ditemukan';
        }
    }
    function loadbuku()
    {
      var name=document.getElementById('idbuku1').value;
         if(name != "")
        {
           $.ajax({
           type: 'post',
           url: 'loadbuku.php',	
           data: {
            user_name:name,
           },
           success: function (response) {
            document.getElementById('judulbuku').value=response;
           }
           });
        }
        else
        {
         
        }
    }
    function loadbuku2()
    {
      var name=document.getElementById('idbuku2').value;
         if(name != "")
        {
           $.ajax({
           type: 'post',
           url: 'loadbuku.php',	
           data: {
            user_name:name,
           },
           success: function (response) {
            document.getElementById('judulbuku2').value=response;
           }
           });
        }
        else
        {
         
        }
    }
</script>
</body>
</html>