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
$sqlpustakawan=("SELECT * FROM pustakawan p, login l where p.idPustakawan=l.idPustakawan AND  (p.idPustakawan like '%".$searchword."%' or p.nama like '%".$searchword."%' or p.alamat like '%".$searchword."%' or p.phone like '%".$searchword."%' or p.email like '%".$searchword."%' or l.hakUser like '%".$searchword."%')");
//ADD
if(isset($_POST['submitadd'])){
    try{
      $id=$_POST['idadd'];
      $nama=$_POST['nama'];
      $email=$_POST['email'];
      $telp=$_POST['telp'];
      $image_file=$_FILES["txt_file"]["name"];
      $type=$_FILES["txt_file"]["type"];
      $size=$_FILES["txt_file"]["size"];
      $temp=$_FILES["txt_file"]["tmp_name"];
      $alamat=$_POST['alamat'];
       $hakakses=$_POST['hakAkses'];
       $password=$_POST['password'];
       $confirmpassword=$_POST['confirmpassword'];
       $path="upload/pustakawan/".$image_file;//set upload folder path
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
    else if($password != $confirmpassword){
        $errorMsg="Please enter password correctly";
    }
    else if($type=="image/jpg" || $type=='image/jpeg' ||$type=='image/png'||$type=='image/gif')
    {

        if(!file_exists($path))
        {
            if($size<5000000)
            {
                //move upload file temprorary directory to your uoload folder
                move_uploaded_file($temp,"upload/pustakawan/".$image_file);
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
      $penerbitinsert="INSERT INTO pustakawan VALUES(:idPustakawan,:nama,:alamat,:phone,:email,:image)";
      $logininsert="INSERT into login VALUES(:idPustakawan,:username,:password,:hakUser)";
      $insertlogin=$dbConn->prepare($logininsert);
      $insertlogin->bindParam(':idPustakawan',$id);
      $insertlogin->bindParam(':username',$nama);
      $insertlogin->bindParam(':password',$password);
      $insertlogin->bindParam(':hakUser',$hakakses);

      $insert_stmt=$dbConn->prepare($penerbitinsert);
      $insert_stmt->bindParam(':idPustakawan',$id);
      $insert_stmt->bindParam(':nama',$nama);
      $insert_stmt->bindParam(':alamat',$alamat);
      $insert_stmt->bindParam(':phone',$telp);
      $insert_stmt->bindParam(':email',$email);
      $insert_stmt->bindParam(':image',$image_file);
      
       if($insertlogin->execute()){
           if($insert_stmt->execute()){
           echo "<script>alert('File UPLOAD Successfully !'); </script>";
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
// EDIT

if(isset($_POST['submitedit'])){
  try{
    $id=$_POST['idedit'];
    $sqlpustkawan="SELECT * from pustakawan where idPustakawan = '".$id."'";
      $syncronize=$dbConn->prepare($sqlpustkawan);
      $syncronize->execute();
      $baris=$syncronize->fetch(PDO::FETCH_ASSOC);
    $nama=$_POST['nama'];
    $email=$_POST['email'];
    $telp=$_POST['telp'];
    $image_file=$_FILES["txt_file"]["name"];
    $type=$_FILES["txt_file"]["type"];
    $size=$_FILES["txt_file"]["size"];
    $temp=$_FILES["txt_file"]["tmp_name"];
    $alamat=$_POST['alamat'];
     $hakakses=$_POST['hakAkses'];
     $password=$_POST['password'];
     $confirmpassword=$_POST['confirmpassword'];
     $path="upload/pustakawan/".$image_file;//set upload folder path
     $directory="upload/pustakawan/";
     if($image_file){
         if($type=="image/jpg" || $type=="image/jpeg" || $type=="image/png" || $type=="image/gif"){
             if(!file_exists($path)){
                 if($size < 5000000){
                     unlink($directory.$baris['image']);//unlink function remove previous file
                     //move upload file temporary directory to your upload folder
                     move_uploaded_file($temp,"upload/pustakawan/".$image_file);
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
  else if(empty($email)){
    $errorMsg="Please enter email";
  }
  else if(empty($telp)){
    $errorMsg="Please enter telp";
  }
  else if(empty($alamat)){
    $errorMsg="Please enter alamat";
  }
  else if($password != $confirmpassword){
      $errorMsg="Please enter password correctly";
  }
  if(!isset($errorMsg)){
    $pustakawaninsert="UPDATE pustakawan set nama=:nama,alamat=:alamat,phone=:phone,email=:email,image=:image where idPustakawan ='".$id."' ";
    $logininsert="UPDATE login set username=:username,password=:password,hakUser=:hakUser  where idPustakawan ='".$id."'";
    $insertlogin=$dbConn->prepare($logininsert);
    $insertlogin->bindParam(':username',$nama);
    $insertlogin->bindParam(':password',$password);
    $insertlogin->bindParam(':hakUser',$hakakses);

    $insert_stmt=$dbConn->prepare($pustakawaninsert);
    $insert_stmt->bindParam(':nama',$nama);
    $insert_stmt->bindParam(':alamat',$alamat);
    $insert_stmt->bindParam(':phone',$telp);
    $insert_stmt->bindParam(':email',$email);
    $insert_stmt->bindParam(':image',$image_file);
    
     if($insertlogin->execute()){
         if($insert_stmt->execute()){
         echo "<script>alert('File UPLOAD Successfully !'); </script>";
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
 // DELETE
 if(isset($_POST['delete'])){
  $id=$_GET['delete_id'];//get delete_id and stor $id variable
  $delete="SELECT * FROM login where idPustakawan=:id";
  $select_stmt=$dbConn->prepare($delete);
  $select_stmt->bindParam(':id',$id);
  $select_stmt->execute();
  $row=$select_stmt->fetch(PDO::FETCH_ASSOC);
  $deletepustakawan="SELECT * FROM pustakawan where idPustakawan=:id";
  $deletepustakawan=$dbConn->prepare($deletepustakawan);
  $deletepustakawan->bindParam(':id',$id);
  $deletepustakawan->execute();
  $rowpustakawan=$deletepustakawan->fetch(PDO::FETCH_ASSOC);
  unlink("upload/pustakawan/".$rowpustakawan['image']);
  $hapuspustakawan="DELETE FROM pustakawan WHERE idPustakawan=:id";
  $deletepus=$dbConn->prepare($hapuspustakawan);
  $deletepus->bindParam(':id',$id);
  $deletepus->execute();
  $hapuslogin="DELETE FROM login WHERE idPustakawan=:id";
  $delete_stmt=$dbConn->prepare($hapuslogin);
  $delete_stmt->bindParam(':id',$id);
  $delete_stmt->execute();
  

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
        <p class="ps fontheader pl-4">Librarian List</p>
            <div class="container-fluid p-4 pt-0">
                <button type="button" class="btn btn-success mb-2 float-left" data-toggle="modal" data-target="#exampleModal">Add</button>
                    <form method="GET" action="">
                    <div class="input-group w-25 float-right">

                <input type="text" name="search" class="form-control" id="search" >
                        <button class="btn btn-primary " type="submitedit">Cari</button>
                        </form>            
                    </div>
                    <!-- MODAL ADD -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="exampleModalLabel">Add Librarian</h5>
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
                                <?php
                                  $jumlahid=$dbConn->prepare("SELECT MAX(idPustakawan) AS id FROM pustakawan");
                                  $jumlahid->execute();
                                  foreach ($jumlahid -> fetchAll() as $jumlahaidi){
                                    $jumlahaidi['id']++;
                                  }
                                ?>
                                  <label for="exampleFormControlInput1">ID</label>
                                  <input required type="text" name="idadd" class="form-control" value="<?php echo $jumlahaidi['id']?>" id="exampleFormControlInput1" readonly>
                                       <div class="form-group">
                                              <label for="stok">Email</label>
                                               <input required type="email" name="email" min="1" class="form-control" id="stok">
                                         </div>
                                        <div class="form-group">
                                             <label for="writer">Nama/Username</label>
                                              <input required type="text" name="nama" class="form-control" id="writer">
                                        </div>
                                    </div>
                                </div>
                              <div class="row">
                                  <div class="col">
                               
                                        <label for="exampleFormControlSelect1">No. Telepon</label>
                                        <input required type="tel" name="telp" class="form-control" id="telephone">
                                        <label for="kelas">Hak Akses</label>
                                    <select class="form-control" name="hakAkses">
                                        <option value="Admin">Admin</option>
                                        <option value="Pustakawan">Pustakawan</option>
                                      
                                  </select>
                                  </div>
                                <div class="col">
                                <label for="exampleFormControlSelect1">Password</label>
                                        <input required type="password" name="password" class="form-control" id="password">
                                <label for="exampleFormControlSelect1">Confirm Password</label>
                                        <input required type="password" name="confirmpassword" class="form-control" id="confirmpassword">
                                        <span id='message'></span>
                                        <div class="form-group">
                                
                                   
                                    </div>
                                      </select>
                                </div>
                                
                              </div>
                              <div class="form-group">
                                <label for="exampleFormControlTextarea1">Alamat</label>
                                <textarea required class="form-control" id="kelas" rows="3" name="alamat"></textarea>
                             
                              </div>
                          </div>
                        
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                              <input type="submit" name="submitadd" class="btn btn-primary" value="Add Librarian">
                            </form>
                            </div>
                          </div>
                        </div>
                      </div>
                    <table class="table  table-bordered ">
                        <thead class="bg-primary text-white">
                        <tr >
                        <th class="w-15">Image</th>
                            <th class="w-15">Nama</th>
                            <th class="w-10">Hak User</th>
                            <th class="w-20">Alamat</th>
                            <th class="w-10">Phone</th>
                            <th class="w-15">Email</th>
                            <th class="w-10">action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                        $select_stmt=$dbConn->prepare($sqlpustakawan);
                          $select_stmt->execute();
                          $rowpernerbit = $select_stmt->fetchAll();
                        foreach($rowpernerbit as $baris){
                          
                          echo "<tr>";
                          echo "<td><img src='upload/pustakawan/".$baris['image']."' width='80' class='d-block ml-auto mr-auto'></td>";
                          echo "<th>".$baris['nama']."</th>";
                          $hak=$dbConn->prepare("SELECT hakUser FROM login where idPustakawan ='".$baris['idPustakawan']."' ");
                          $hak->execute();
                          $hakuser = $hak->fetch(PDO::FETCH_ASSOC);
                       
                          echo "<td>".$hakuser['hakUser']."</td>";
                          echo "<td>".$baris['alamat']."</td>";
                          echo "<td>".$baris['phone']."</td>";
                          echo "<td>".$baris['email']."</td>";
                          echo "<td> 
                              <img data-toggle='modal' class='pointer ml-1' data-target='#modal".$baris['idPustakawan']."' src='img/pencil.png' width='35'>
                             <img class=' pointer' data-toggle='modal' data-target='#delete".$baris['idPustakawan']."' src='img/bin.png' width='35'></td>";
                          echo "</tr>";
                        }
                         ?>
                        </tbody>
                      </table>
                      
            </div>
            <!-- MODAL EDIT -->
            <?php
             $select_stmt=$dbConn->prepare($sqlpustakawan);
             $select_stmt->execute();
             $rowpernerbit = $select_stmt->fetchAll();
           foreach($rowpernerbit as $baris){
            ?>
            <div class="modal fade" id="modal<?php echo $baris['idPustakawan']?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="exampleModalLabel">Edit Librarian</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body">
                              <form method="POST" action="" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col">
                                        <center>
                                          <img id="gambar<?php echo $baris['idPustakawan']?>" height="150" width="150" class="mb-3 mt-2" src="upload/pustakawan/<?php echo $baris['image'] ?>">
                                        </center>
            
                                      <div class="input-group mb-3">
                                             <div class="custom-file mt-3">
                                                  <input name="txt_file" type="file" value="<?php echo $baris['image'] ?>" class="custom-file-input"  id="inputGroupFile01" aria-describedby="inputGroupFileAddon01" onchange="document.getElementById('gambar<?php echo $baris['idPustakawan']?>').src=window.URL.createObjectURL(this.files[0])">
                                                      <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                                               </div>
                                        </div>
                            </div>
                            <div class="col">
                                  <label for="exampleFormControlInput1">ID</label>
                                  <input type="text" name="idedit" class="form-control" value="<?php echo $baris['idPustakawan']?>" id="exampleFormControlInput1" readonly>
                                       <div class="form-group">
                                              <label for="stok">Email</label>
                                               <input type="email" value="<?php echo $baris['email']?>" name="email" min="1" class="form-control" id="stok">
                                         </div>
                                        <div class="form-group">
                                             <label for="writer">Nama/Username</label>
                                              <input type="text" name="nama" value="<?php echo $baris['nama']?>" class="form-control" id="writer">
                                        </div>
                                    </div>
                                </div>
                              <div class="row">
                                  <div class="col">
                               
                                        <label for="exampleFormControlSelect1">No. Telepon</label>
                                        <input type="tel" name="telp" class="form-control" value="<?php echo $baris['phone']?>" id="telephone">
                                        <label for="kelas">Hak Akses</label>
                                    <select class="form-control" name="hakAkses">
                                    <?php
                        $cekHak=$dbConn->prepare("SELECT hakUser from login where idPustakawan = '".$baris['idPustakawan']."'");
                          $cekHak->execute();
                          $rowHak=$cekHak->fetchAll();
                          foreach($rowHak as $barishak){
                          }
                          if($barishak['hakUser']=="Admin"){
                            echo "<option selected value='Admin'>Admin</option>";
                          }else{
                            echo "<option value='Admin'>Admin</option>";
                          }
                          if($barishak['hakUser']=="Pustakawan"){
                            echo "<option selected value='Pustakawan'>Pustakawan</option>";
                          }else{
                            echo "<option value='Pustakawan'>Pustakawan</option>";
                          }
                        ?>
                                  </select>
                                  </div>
                                <div class="col">
                                <label for="exampleFormControlSelect1">New Password</label>
                                        <input type="password" name="password" class="form-control" id="password<?php echo $baris['idPustakawan']?>">
                                <label for="exampleFormControlSelect1">Confirm new Password</label>
                                        <input type="password" name="confirmpassword" class="form-control" id="confirmpassword<?php echo $baris['idPustakawan']?>">
                                        <span id='message<?php echo $baris['idPustakawan']?>'></span>
                                        <script>
                                                  $('#password<?php echo $baris['idPustakawan']?>, #confirmpassword<?php echo $baris['idPustakawan']?>').on('keyup', function () {
                                                  if ($('#password<?php echo $baris['idPustakawan']?>').val() == $('#confirmpassword<?php echo $baris['idPustakawan']?>').val()) {
                                                    $('#message<?php echo $baris['idPustakawan']?>').html('Matching').css('color', 'green');
                                                  } else 
                                                    $('#message<?php echo $baris['idPustakawan']?>').html('Not Matching').css('color', 'red');
                                                });
                                        </script>
                                        <div class="form-group">
                                
                                   
                                    </div>
                                      </select>
                                </div>
                                
                              </div>
                              <div class="form-group">
                                <label for="exampleFormControlTextarea1">Alamat</label>
                                <textarea class="form-control" id="kelas" rows="3" name="alamat"><?php echo $baris['alamat'] ?></textarea>
                               
                              </div>
                          </div>
                        
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                              <input type="submit" name="submitedit" class="btn btn-primary" value="Edit Librarian">
                            </form>
                            </div>
                          </div>
                        </div>
                      </div>
                       <!-- MODAL DELETE -->
                      <div class="modal fade" id="delete<?php echo $baris['idPustakawan']?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                              <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Hapus</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                  <div class="modal-body">
                                   Apakah anda yakin ingin menghapus data <?php echo $baris['nama']?> ?
                                  </div>
                                  <div class="modal-footer">
                                  <form action="librarian.php?delete_id=<?php echo $baris['idPustakawan']?>"  method='POST' >
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                                    <button type="submit" name="delete" class="btn btn-danger">Hapus Data</button>
                                  </form>
                                  </div>
                                </div>
                              </div>
                            </div>

                                <?php }?>
                               
                                
    </section>
    <script src="js/main.js"></script>
    </body>
</html>