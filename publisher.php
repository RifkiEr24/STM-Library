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
$sqlpenerbit="SELECT * FROM penerbit where idPenerbit like '%".$searchword."%' or nama like '%".$searchword."%' or alamat like '%".$searchword."%' or phone like '%".$searchword."%' or email like '%".$searchword."%'";

//ADD
if(isset($_POST['submitadd'])){
  try{
    $id=$_POST['id'];
    $nama=$_POST['nama'];
    $email=$_POST['email'];
    $telp=$_POST['telp'];
    $alamat=$_POST['alamat'];
 
    if(empty($nama)){
      $errorMsg="Please Enter Your Name";
    }
  else if(empty($email)){
    $errorMsg="Please enter email";
  }
  else if(empty($telp)){
    $errorMsg="Please enter telp";
  }
  else if(empty($alamat)){
    $errorMsg="Please enter alamat";
  }
  
  if(!isset($errorMsg)){
    $sqlinsert="INSERT INTO penerbit VALUES(:idPenerbit,:nama,:alamat,:phone,:email)";
     $insert_stmt=$dbConn->prepare($sqlinsert);
    $insert_stmt->bindParam(':idPenerbit',$id);
    $insert_stmt->bindParam(':nama',$nama);
    $insert_stmt->bindParam(':alamat',$alamat);
    $insert_stmt->bindParam(':phone',$telp);
    $insert_stmt->bindParam(':email',$email);
    
     if($insert_stmt->execute()){
         echo "<script>alert('File UPLOAD Successfully !'); </script>";
    
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
//EDIT
if(isset($_POST['submitedit'])){
  try{
    $id=$_POST['id'];
    $nama=$_POST['nama'];
    $email=$_POST['email'];
    $telp=$_POST['telp'];
    $alamat=$_POST['alamat'];
 
    if(empty($nama)){
      $errorMsg="Please Enter Your Name";
    }
  else if(empty($email)){
    $errorMsg="Please enter email";
  }
  else if(empty($telp)){
    $errorMsg="Please enter telp";
  }
  else if(empty($alamat)){
    $errorMsg="Please enter alamat";
  }
  
  if(!isset($errorMsg)){
    $sqledit="UPDATE penerbit set nama=:nama,alamat=:alamat,phone=:phone,email=:email WHERE idPenerbit='".$id."'";
     $insert_stmt=$dbConn->prepare($sqledit);
  
    $insert_stmt->bindParam(':nama',$nama);
    $insert_stmt->bindParam(':alamat',$alamat);
    $insert_stmt->bindParam(':phone',$telp);
    $insert_stmt->bindParam(':email',$email);
 
     if($insert_stmt->execute()){
         echo "<script>alert('File UPDATE Successfully !'); </script>";
    
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
<div class="modal fade" id="gagaldelete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                              <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                  <div class="modal-header bg-danger">
                                    <h5 class="modal-title" id="exampleModalLabel">Hapus</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                  <div class="modal-body">
                               Delete Gagal, Tolong pastikan tidak ada buku yg terkait/diterbitkan oleh penerbit
                                  </div>
                                  <div class="modal-footer">
                                 
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
                                 
                                
                                  </div>
                                </div>
                              </div>
        </div>
  <?php
if(isset($_POST['delete'])){
  try{
  $iddel=$_GET['delete_id'];//get delete_id and stor $id variable
  $delete="SELECT * FROM penerbit where idPenerbit=:id";
  $select_stmt=$dbConn->prepare($delete);
  $select_stmt->bindParam(':id',$iddel);
  $select_stmt->execute();
  $row=$select_stmt->fetch(PDO::FETCH_ASSOC);
  $sqlhapus="DELETE FROM penerbit WHERE idPenerbit=:id";
  $delete_stmt=$dbConn->prepare($sqlhapus);
  $delete_stmt->bindParam(':id',$iddel);
  $delete_stmt->execute();
  header("Location:publisher.php");
  }  catch(PDOException $e){
    echo "<script>$('#gagaldelete').modal('show');</script>";
}
}
?>
<section  style="background-color: whitesmoke;">
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
        <p class="ps fontheader pl-4">Publisher List</p>
        <div class="container-fluid p-4 pt-0">
                    <button type="button" class="btn btn-success mb-2 float-left" data-toggle="modal" data-target="#exampleModal">Add</button>
                    <form method="GET" action="">
                    <div class="input-group w-25 float-right">

                <input type="text" name="search" class="form-control" id="search" >
                        <button class="btn btn-primary " type="submitedit">Cari</button>
                        </form>            
                    </div>
                    <!-- MODAL ADD -->
                            <div class="modal fade add" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Publisher</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                        <div class="modal-body">
                        <form method="POST" action="" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col">
                                <div class="form-group">
                                    <?php
                                    // DAPATKAN ID
                                        $sqlid="SELECT idPenerbit from penerbit order by idPenerbit DESC LIMIT 1";
                                        $stmt=$dbConn->prepare($sqlid);
                                        $stmt->execute();
                                        $row=$stmt->fetch(PDO::FETCH_ASSOC);

                                        if(empty($row)){
                                            $angka=1;
                                        }else{
                                            $angka=preg_replace('/[^0-9]/','',$row['idPenerbit']);
                                            $angka++;
                                        }
                                        $formmated_value="pen".sprintf("%d", $angka);
                                    ?>
                                        <label for="id">ID</label>
                                        <input required type="text" value="<?php echo $formmated_value ?>" id="id" name="id" min="1" class="form-control"  readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="stok">No telp</label>
                                        <input required type="tel" name="telp" min="1" class="form-control" id="nama">
                                    </div>
                              
                        </div>
                        <div class="col"> 
                                <div class="form-group">
                                        <label for="exampleFormControlInput1">Nama</label>
                                        <input required type="text" name="nama" class="form-control" id="exampleFormControlInput1">
                                </div>
                                    <div class="form-group">
                                            <label for="stok">Email</label>
                                            <input required type="email" name="email" min="1" class="form-control" id="stok">
                                        </div>
                                    </div>
                            </div>
                 
                        <div class="form-group">
                            <label for="exampleFormControlTextarea1">Alamat</label>
                            <textarea required class="form-control" id="kelas" rows="3" name="alamat"></textarea>
                        </div>
                    </div>
                    
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <input  type="submit" name="submitadd" class="btn btn-primary" value="Add Publisher">
                        </form>
                          </div>
                        </div>
                     </div>
                </div>
                <table class="table  table-bordered ">
          <thead class="bg-primary text-white">
          <tr >
              <th class="w-15">Nama</th>
              <th >Alamat</th>
              <th class="w-15">Phone</th>
              <th class="w-15">Email</th>
              <th class="w-10">action</th>
            </tr>
          </thead>
          <tbody>
            <?php
          $select_stmt=$dbConn->prepare($sqlpenerbit);
            $select_stmt->execute();
            $rowpernerbit = $select_stmt->fetchAll();
          foreach($rowpernerbit as $baris){
            
            echo "<tr>";
            echo "<th>".$baris['nama']."</th>";
            echo "<td>".$baris['alamat']."</td>";
            echo "<td>".$baris['phone']."</td>";
            echo "<td>".$baris['email']."</td>";
            echo "<td> 
                <img data-toggle='modal' class='pointer ml-1' data-target='#modal".$baris['idPenerbit']."' src='img/pencil.png' width='35'>
                <img data-toggle='modal' data-target='#delete".$baris['idPenerbit']."' class=' pointer' src='img/bin.png' width='35'></td>";
            echo "</tr>";
          }
           ?>
          </tbody>
        </table>
        </div>
        <?php
        $select_stmt=$dbConn->prepare($sqlpenerbit);
        $select_stmt->execute();
        $rowpernerbit=$select_stmt->fetchAll();
        foreach($rowpernerbit as $baris){
      ?>
       <!-- MODAL DELETE -->
    <div class="modal fade" id="delete<?php echo $baris['idPenerbit']?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                              <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content ">
                                  <div class="modal-header bg-danger">
                                    <h5 class="modal-title" id="exampleModalLabel">Hapus</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                  <div class="modal-body">
                                   Apakah anda yakin ingin menghapus data Publisher Dengan Nama "<?php echo $baris['nama']?> "?
                                  </div>
                                  <div class="modal-footer">
                                  <form action="publisher.php?delete_id=<?php echo $baris['idPenerbit']?>"  method='POST' >
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                                    <button type="submit" name="delete" class="btn btn-danger">Hapus Data</button>
                                  </form>
                                  </div>
                                </div>
                              </div>
                            </div>
       <div class="modal fade" id="modal<?php echo $baris['idPenerbit'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit Publisher</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                        <div class="modal-body">
                        <form method="POST" action="" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col">
                                <div class="form-group">
                                        <label for="id">ID</label>
                                        <input type="text" value="<?php echo $baris['idPenerbit'] ?>" id="id" name="id"  class="form-control"  readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="stok">No telp</label>
                                        <input type="tel" name="telp" min="1" value="<?php echo $baris['phone'] ?>" class="form-control" id="nama">
                                    </div>
                              
                        </div>
                        <div class="col"> 
                                <div class="form-group">
                                        <label for="exampleFormControlInput1">Nama</label>
                                        <input type="text" name="nama" value="<?php echo $baris['nama'] ?>" class="form-control" id="exampleFormControlInput1">
                                </div>
                                    <div class="form-group">
                                            <label for="stok">Email</label>
                                            <input type="email" name="email" value="<?php echo $baris['email'] ?>" min="1" class="form-control" id="stok">
                                        </div>
                                    </div>
                            </div>
                 
                        <div class="form-group">
                            <label for="exampleFormControlTextarea1">Alamat</label>
                            <textarea class="form-control" id="kelas" rows="3" name="alamat"><?php echo $baris['alamat'] ?></textarea>
                        </div>
                    </div>
                    
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <input type="submit" name="submitedit" class="btn btn-primary" value="Edit Publisher">
                        </form>
                          </div>
                        </div>
                     </div>
          </div>
    
        <?php }
        
        // DELETE

        ?>
            
</section>

</body>
</html>