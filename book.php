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
  
  $sql="SELECT * FROM penerbit";
  $sql2="SELECT * FROM kategori";
 
  $jumlahid=$dbConn->prepare("SELECT MAX(idBuku) AS id FROM buku");
  $jumlahid->execute();
  foreach ($jumlahid -> fetchAll() as $jumlahaidi){
    $jumlahaidi['id']++;
  }
$searchword="";
if(isset($_GET['search'])){
  $searchword=$_GET['search'];
}

$sql3="SELECT * FROM buku b, penerbit p, kategori k WHERE b.idPenerbit=p.idPenerbit AND b.idKategori=k.idKategori AND (b.idBuku LIKE '%".$searchword."%' OR b.judul LIKE '%".$searchword."%' OR b.penulis LIKE '%".$searchword."%' OR b.qty LIKE '%".$searchword."%' OR b.sinopsis LIKE '%".$searchword."%' OR p.nama LIKE '%".$searchword."%' OR k.kategoriBuku LIKE '%".$searchword."%')";

  
  if(isset($_POST['submit'])){
    try{
     
      $nama=$_POST['namabuku'];
      $stok=$_POST['stok'];
      $image_file=$_FILES["txt_file"]["name"];
      $type=$_FILES["txt_file"]["type"];
      $size=$_FILES["txt_file"]["size"];
      $temp=$_FILES["txt_file"]["tmp_name"];
      $penulis=$_POST['penulis'];
      $penerbit=$_POST['publisher'];
      $idpenerbit=$dbConn->prepare("SELECT idPenerbit as id from penerbit where nama = '$penerbit'");
      $idpenerbit->execute();
      foreach($idpenerbit -> fetchAll() as $idp){
      }
      $kategori=$_POST['category'];
      $idkategori=$dbConn->prepare("SELECT idKategori as id from kategori where kategoriBuku = '$kategori'");
      $idkategori->execute();
      foreach($idkategori -> fetchAll() as $idk){
      } 
      $kategoriid=$idkategori->fetchAll();
     
      $synopsis=$_POST['sinopsis'];
      $id=$_POST['id'];
    
      $path="upload/buku/".$image_file;//set upload folder path
   
      if(empty($nama)){
        $errorMsg="Please Enter Your Name";
      }
    else if(empty($image_file)){
        $errorMsg="Please Select image";
    }
    else if(empty($stok)){
      $errorMsg="Please enter stok";
    }
    else if(empty($penulis)){
      $errorMsg="Please enter penulis";
    }
    else if(empty($penerbit)){
      $errorMsg="Please enter penerbit";
    }
    else if(empty($kategori)){
      $errorMsg="Please enter kategori";
    }
    else if(empty($synopsis)){
      $errorMsg="Please enter sinopsis";
    }
    else if($type=="image/jpg" || $type=='image/jpeg' ||$type=='image/png'||$type=='image/gif')
    {

        if(!file_exists($path))
        {
            if($size<5000000)
            {
                //move upload file temprorary directory to your uoload folder
                move_uploaded_file($temp,"upload/buku/".$image_file);
            }
            else
            {

                $errorMsg="Your File to Large please upload 5MB size";
            }
        }
        else
        {
            //error message file not exist your upload folder path
            $errorMsg="File Already exist.. check upload folder";
        
        }
    }
    else
    {
        //error message file extension
        $errorMsg="Upload JPG, JPEG, PNG & GIF File Format.....Check File Extension";
    }
    if(!isset($errorMsg)){
      $sqlinsert="INSERT INTO buku VALUES(:idBuku,:idKategori,:judul,:idPenerbit,:penulis,:qty,:image,:sinopsis)";
       $insert_stmt=$dbConn->prepare($sqlinsert);
      $insert_stmt->bindParam(':idBuku',$id);
      $insert_stmt->bindParam(':idKategori',$idk['id']);
      $insert_stmt->bindParam(':judul',$nama);
      $insert_stmt->bindParam(':idPenerbit',$idp['id']);
      $insert_stmt->bindParam(':penulis',$penulis);
      $insert_stmt->bindParam(':qty',$stok);
       $insert_stmt->bindParam(':image',$image_file);
       $insert_stmt->bindParam(':sinopsis',$synopsis);
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
  // ISSET EDIT
  if(isset($_POST['submitedit'])){
    try{
      $idedit=$_POST['idbuku'];
      $sqlpustkawan="SELECT * from buku where idBuku = '".$idedit."'";
      $syncronize=$dbConn->prepare($sqlpustkawan);
      $syncronize->execute();
      $baris=$syncronize->fetch(PDO::FETCH_ASSOC);
      $nama=$_POST['namabukuedit'];
      $stok=$_POST['stokedit'];
      $image_file=$_FILES["txt_fileedit"]["name"];
      $type=$_FILES["txt_fileedit"]["type"];
      $size=$_FILES["txt_fileedit"]["size"];
      $temp=$_FILES["txt_fileedit"]["tmp_name"];
      $penulis=$_POST['penulisedit'];
      $penerbit=$_POST['publisheredit'];
      $idpenerbit=$dbConn->prepare("SELECT idPenerbit as id from penerbit where nama = '$penerbit'");
      $idpenerbit->execute();
      foreach($idpenerbit -> fetchAll() as $idp){
      }
      $kategori=$_POST['categoryedit'];
      $idkategori=$dbConn->prepare("SELECT idKategori as id from kategori where kategoriBuku = '$kategori'");
      $idkategori->execute();
      foreach($idkategori -> fetchAll() as $idk){
      } 
      $kategoriid=$idkategori->fetchAll();
     
      $synopsis=$_POST['sinopsisedit'];
      $id=$_POST['idedit'];
    
      $path="upload/buku/".$image_file;//set upload folder path
      $directory="upload/buku/";
      if($image_file){
        if($type=="image/jpg" || $type=="image/jpeg" || $type=="image/png" || $type=="image/gif"){
            if(!file_exists($path)){
                if($size < 5000000){
                    unlink($directory.$baris['image']);//unlink function remove previous file
                    //move upload file temporary directory to your upload folder
                    move_uploaded_file($temp,"upload/buku/".$image_file);
                }else{
                    $errorMsg="Your File to Large Pleas upload 5MB Size";
                }
            }else{
                $errorMsg="File Already Exist...Check Upload Folder";
            }
        }else{
            $errorMsg="Upload JPG,JPEG,PNG, and GIF file format.... CHECK FILE EXTENSION";
        }
    }else{
        //if you not select new image then use the previous page
        $image_file=$baris['image'];
    }
      if(empty($nama)){
        $errorMsg="Please Enter Your Name";
      }
    else if(empty($image_file)){
        $errorMsg="Please Select image";
    }
    else if(empty($stok)){
      $errorMsg="Please enter stok";
    }
    else if(empty($penulis)){
      $errorMsg="Please enter penulis";
    }
    else if(empty($penerbit)){
      $errorMsg="Please enter penerbit";
    }
    else if(empty($kategori)){
      $errorMsg="Please enter kategori";
    }
    else if(empty($synopsis)){
      $errorMsg="Please enter sinopsis";
    }
    if(!isset($errorMsg)){
      $sqlinsert="UPDATE buku SET idKategori=:idKategori,judul=:judul,idPenerbit=:idPenerbit,penulis=:penulis,qty=:qty,image=:image,sinopsis=:sinopsis WHERE idBuku=".$id."";
      $update_stmt=$dbConn->prepare($sqlinsert);
      $update_stmt->bindParam(':idKategori',$idk['id']);
      $update_stmt->bindParam(':judul',$nama);
      $update_stmt->bindParam(':idPenerbit',$idp['id']);
      $update_stmt->bindParam(':penulis',$penulis);
      $update_stmt->bindParam(':qty',$stok);
      $update_stmt->bindParam(':image',$image_file);
      $update_stmt->bindParam(':sinopsis',$synopsis);
       if($update_stmt->execute()){
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
                               Delete Gagal, Tolong Selesaikan Transaksi Peminjaman buku terlebih dahulu
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
            $id=$_GET['delete_id'];//get delete_id and stor $id variable
            $sql="SELECT * FROM buku where idBuku=:id";
            $select_stmt=$dbConn->prepare($sql);
            $select_stmt->bindParam(':id',$id);
            $select_stmt->execute();
            $row=$select_stmt->fetch(PDO::FETCH_ASSOC);
           
            $sqlhapus="DELETE FROM buku WHERE idBuku=:id";
            $delete_stmt=$dbConn->prepare($sqlhapus);
            $delete_stmt->bindParam(':id',$id);
            $delete_stmt->execute();
            unlink("upload/buku/".$row['image']);
            header("Location:book.php");
            }
            catch(PDOException $e){
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
        <p class="ps fontheader pl-4">Booklist</p>
        <div class="container-fluid p-4 pt-0">
          <form method="GET" action="">
          <div class="input-group w-25 float-right">

    <input type="text" name="search" class="form-control" id="search" >
              <button class="btn btn-primary " type="submitedit">Cari</button>
            </form>            
          </div>
          <button type="button" class="btn btn-success mb-2 float-left" data-toggle="modal" data-target="#exampleModal">Add</button>
          <!-- Modal -->
          <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Add Book</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <form method="POST" action="" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col">
                            <center>
                              <img id="gambar" height="150" width="150" class="mb-3 mt-2" src="img/avatar.png">
                            </center>

                          <div class="input-group mb-3">
                                 <div class="custom-file mt-3">
                                      <input required name="txt_file" type="file" class="custom-file-input"  id="inputGroupFile01" aria-describedby="inputGroupFileAddon01" onchange="document.getElementById('gambar').src=window.URL.createObjectURL(this.files[0])">
                                          <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                                   </div>
                            </div>
                </div>
                <div class="col">
                      <label for="exampleFormControlInput1">Book Title</label>
                      <input required type="text" name="namabuku" class="form-control" id="exampleFormControlInput1">
                           <div class="form-group">
                                  <label for="stok">Stock</label>
                                   <input required type="number" name="stok" min="1" class="form-control" id="stok">
                             </div>
                            <div class="form-group">
                                 <label for="writer">Books Writer Name</label>
                                  <input required type="text" name="penulis" class="form-control" id="writer">
                            </div>
                        </div>
                    </div>
                  <div class="row">
                      <div class="col">
                            <label for="exampleFormControlSelect1">Select Publisher</label>
                            <select class="form-control" id="exampleFormControlSelect1" name="publisher">
                            <?php
                            $select_stmt=$dbConn->prepare($sql);
                            $select_stmt->execute();
                            $row = $select_stmt->fetchAll();
                          foreach($row as $baris){
                              
                                echo "<option>".$baris['nama']."</option>";
                          }
                            ?>
                          </select>
                      </div>
                    <div class="col">
                            <label for="exampleFormControlSelect2">Select Category</label>
                            <select class="form-control" id="exampleFormControlSelect2" name="category">
                            <?php
                            $select_stmt=$dbConn->prepare($sql2);
                            $select_stmt->execute();
                            $row = $select_stmt->fetchAll();
                          foreach($row as $baris){
                              
                                echo "<option>".$baris['kategoriBuku']."</option>";
                          }
                            ?>
                          </select>
                    </div>
                    
                  </div>
                  <div class="form-group">
                    <label for="exampleFormControlTextarea1">Sinopsis</label>
                    <textarea required class="form-control" id="exampleFormControlTextarea1" rows="3" name="sinopsis"></textarea>
                    <input class="d-none" type="text" name="id" value="<?php echo $jumlahaidi['id'] ?>"  class="form-control" id="id">
                  </div>
              </div>
            
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <input type="submit" name="submit" class="btn btn-primary" value="Add Book">
                </form>
                </div>
              </div>
            </div>
          </div>

        <table class="table  table-bordered">
          <thead class="bg-primary text-white">
          <tr >
              <th class="w-10">Image</th>
              <th class="w-10">Title</th>
              <th class="w-10">Writer</th>
              <th class="w-10">Publisher</th>
              <th class="w-10">Category</th>
              <th class="w-10">Stock</th>
              <th >Synopsis</th>
              <th class="w-10">Action</th>
            </tr>
          </thead>
          <tbody>
          <?php
            $select_stmt=$dbConn->prepare($sql3);
            $select_stmt->execute();
            $rowbuku = $select_stmt->fetchAll();
          foreach($rowbuku as $baris){
            $penerbitTable=$dbConn->prepare("SELECT nama from penerbit where idPenerbit = '".$baris['idPenerbit']."'");
            $penerbitTable->execute();
            $rowpenerbit = $penerbitTable->fetchAll();
            foreach($rowpenerbit as $barispenerbit){
            }
            $kategoriTable=$dbConn->prepare("SELECT kategoriBuku from kategori where idKategori = '".$baris['idKategori']."'");
            $kategoriTable->execute();
            $rowkategori = $kategoriTable->fetchAll();
            foreach($rowkategori as $bariskategori){
            }
            
            echo "<tr>";
            echo "<td>"."<img src='upload/buku/".$baris['image']."' width='100'>"."</td>";
            echo "<td>".$baris['judul']."</td>";
            echo "<td>".$baris['penulis']."</td>";
            echo "<td>".$barispenerbit['nama']."</td>";
            echo "<td>".$bariskategori['kategoriBuku']."</td>";
            echo "<td>".$baris['qty']."</td>";
            echo "<td>".$baris['sinopsis']."</td>";
            echo "<td> 
                <img data-toggle='modal' class='pointer ml-1' data-target='#modal".$baris['idBuku']."' src='img/pencil.png' width='35'>
                  <img data-toggle='modal' data-target='#delete".$baris['idBuku']."' class=' pointer' src='img/bin.png' width='35'></td>";
            echo "</tr>";
          }
        
           ?>
          </tbody>
        </table>
      </div>
       <!-- QUERY EDIT --> 
      <!-- MODAL EDIT -->
      <?php
      $select_stmt=$dbConn->prepare($sql3);
      $select_stmt->execute();
      $rowbuku = $select_stmt->fetchAll();
    foreach($rowbuku as $baris){?>
     <!-- MODAL DELETE -->
     <div class="modal fade" id="delete<?php echo $baris['idBuku']?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                              <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                  <div class="modal-header bg-danger">
                                    <h5 class="modal-title" id="exampleModalLabel">Hapus</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                  <div class="modal-body">
                                   Apakah anda yakin ingin menghapus data Dengan Judul "<?php echo $baris['judul']?> "?
                                  </div>
                                  <div class="modal-footer">
                                  <form action="book.php?delete_id=<?php echo $baris['idBuku']?>"  method='POST' >
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                                    <button type="submit" name="delete" class="btn btn-danger">Hapus Data</button>
                                  </form>
                                  </div>
                                </div>
                              </div>
                            </div>
      <div class="modal fade" id="modal<?php echo $baris['idBuku']?>" tabindex="-1" role="dialog"  aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Edit Book</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
         
          <div class="modal-body">
            <form method="POST" action="" enctype="multipart/form-data">
              <div class="row">
                  <div class="col">
                      <center>
                        <img id="gambar<?php echo $baris['idBuku']?>" height="150" width="150" class="mb-3 mt-2" src="upload/buku/<?php echo $baris['image']?>">
                      </center>

                    <div class="input-group mb-3">
                           <div class="custom-file mt-3">
                                <input  name="txt_fileedit" type="file" class="custom-file-input"  id="inputGroupFile02" aria-describedby="inputGroupFileAddon01" onchange="document.getElementById('gambar<?php echo $baris['idBuku']?>').src=window.URL.createObjectURL(this.files[0])">
                                    <label class="custom-file-label" for="inputGroupFile02">Choose file</label>
                             </div>
                      </div>
          </div>
          <div class="col">
                <label for="exampleFormControlInput1">Book Title</label>
                <input type="text" required name="namabukuedit" value="<?php echo $baris['judul']?>" class="form-control" id="exampleFormControlInput1">
                     <div class="form-group">
                            <label for="stok">Stock</label>
                             <input type="number" name="stokedit" value="<?php echo $baris['qty']?>" min="1" class="form-control" id="stok">
                       </div>
                      <div class="form-group">
                           <label for="writer">Books Writer Name</label>
                            <input required type="text" name="penulisedit"  value="<?php echo $baris['penulis']?>" class="form-control" id="writer">
                            <input type="text" class="d-none" name="idbuku"  value="<?php echo $baris['idBuku']?>" class="form-control" id="writer">
                      </div>
                  </div>
              </div>
            <div class="row">
                <div class="col">
                      <label for="exampleFormControlSelect1" >Select Publisher</label>
             <?php
                      $penerbitTable=$dbConn->prepare("SELECT nama from penerbit where idPenerbit = '".$baris['idPenerbit']."'");
            $penerbitTable->execute();
            $rowpenerbit = $penerbitTable->fetchAll();
            foreach($rowpenerbit as $barispenerbit){
            }
              ?>
                      <select class="form-control"  value="<?php echo $barispenerbit['nama']?>" id="exampleFormControlSelect1" name="publisheredit">
                      <?php
                      $select_stmt=$dbConn->prepare($sql);
                      $select_stmt->execute();
                      $row = $select_stmt->fetchAll();
                    foreach($row as $barispublisher){
                      $selected='';
                        if($barispenerbit['nama']==$barispublisher['nama']){
                          $selected='selected';
                        }
                        else{
                          $selected='';
                        }
                          echo "<option ".$selected." value='".$barispublisher['nama']."'>".$barispublisher['nama']."</option>";
                    }
                      ?>
                    </select>
                </div>
              <div class="col">
                      <label for="exampleFormControlSelect2">Select Category</label>
                      <select class="form-control" id="exampleFormControlSelect2" name="categoryedit">
                      <?php
                      $kategoriTable=$dbConn->prepare("SELECT kategoriBuku from kategori where idKategori = '".$baris['idKategori']."'");
                      $kategoriTable->execute();
                      $rowkategori = $kategoriTable->fetchAll();
                      foreach($rowkategori as $bariskategori){
                      }
                      $select_stmt=$dbConn->prepare($sql2);
                      $select_stmt->execute();
                      $row = $select_stmt->fetchAll();
                    foreach($row as $bariskategoribuku){
                      $selected='';
                      if($bariskategori['kategoriBuku']==$bariskategoribuku['kategoriBuku']){
                        $selected='selected';
                      }
                      else{
                        $selected='';
                      }
                          echo "<option ".$selected.">".$bariskategoribuku['kategoriBuku']."</option>";
                    }
                      ?>
                    </select>
              </div>
              
            </div>
            <div class="form-group">
              <label for="exampleFormControlTextarea1">Sinopsis</label>
              <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="sinopsisedit"><?php echo $baris['sinopsis']?></textarea>
              <input class="d-none" type="text" name="idedit" value="<?php echo $baris['idBuku'] ?>"  class="form-control" id="id">
            </div>
        </div>
      
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <input type="submit" name="submitedit" class="btn btn-primary" value="Edit Book">
          </form>
          </div>
        </div>
      </div>
    </div>
    <?php }
      ?>
      
     
        </section>
</body>
</html>