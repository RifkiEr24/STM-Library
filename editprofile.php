<?php
require_once ("koneksi.php"); 
  session_start();  
  if(isset($_SESSION["username"]))  
  {  
       $sqlpustkawan="SELECT * from pustakawan where idPustakawan = '".$_SESSION['idacc']."'";
       $syncronize=$dbConn->prepare($sqlpustkawan);
       $syncronize->execute();
       $baris=$syncronize->fetch(PDO::FETCH_ASSOC);
  }  
  if(isset($_POST['submitedit'])){
    try{
      $id=$_POST['idedit'];
      $nama=$_POST['nama'];
      $email=$_POST['email'];
      $telp=$_POST['telp'];
      $image_file=$_FILES["txt_file"]["name"];
      $type=$_FILES["txt_file"]["type"];
      $size=$_FILES["txt_file"]["size"];
      $temp=$_FILES["txt_file"]["tmp_name"];
      $alamat=$_POST['alamat'];
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
      $logininsert="UPDATE login set username=:username,password=:password  where idPustakawan ='".$id."'";
      $insertlogin=$dbConn->prepare($logininsert);
      $insertlogin->bindParam(':username',$nama);
      $insertlogin->bindParam(':password',$password);
      
  
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
                      <img style="object-fit: cover" src="upload/pustakawan/<?php echo $baris['image']?>"  class="rounded-circle" width="40" height="40">
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                      <a class="dropdown-item" href="editprofile.php">Edit Account</a>
                      <div class="dropdown-divider"></div>
                      <a class="dropdown-item" href="login.php">Log Out</a>
                    </div>
                  </li>
                </ul>
        </nav>
            <div class="container-fluid p-4 pt-0">
            <form method="POST" action="" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col">
                                        <p class="ps h2">Edit Profile</p>
                                        <center>
                                          <img style="object-fit: cover" id="gambar<?php echo $baris['idPustakawan']?>" height="190" width="190" class="mb-3 mt-2 rounded-circle" src="upload/pustakawan/<?php echo $baris['image'] ?>">
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
                                               <input required type="email" value="<?php echo $baris['email']?>" name="email" min="1" class="form-control" id="stok">
                                         </div>
                                        <div class="form-group">
                                             <label for="writer">Nama</label>
                                              <input required type="text" name="nama" value="<?php echo $baris['nama']?>" class="form-control" id="writer">
                                              
                                        </div>
                                        <label for="exampleFormControlSelect1">New Password</label>
                                        <input required type="password" name="password" class="form-control" id="password">
                                    </div>
                                    
                                </div>
                              <div class="row">
                                  <div class="col">
                               
                                        <label for="exampleFormControlSelect1">No. Telepon</label>
                                        <input required type="tel" name="telp" class="form-control" value="<?php echo $baris['phone']?>" id="telephone"> 
                                  </div>
                                  <div class="col">
                                
                                <label for="exampleFormControlSelect1">Confirm new Password</label>
                                        <input required type="password" name="confirmpassword" class="form-control" id="confirmpassword">
                                        <span id='message'></span>
                                        <script>
                                                  $('#passwor, #confirmpassword').on('keyup', function () {
                                                  if ($('#password').val() == $('#confirmpassword').val()) {
                                                    $('#message').html('Matching').css('color', 'green');
                                                  } else 
                                                    $('#message').html('Not Matching').css('color', 'red');
                                                });
                                        </script>
                                        <div class="form-group">
                                
                                   
                                    </div>
                                      </select>
                                </div>
                                
                              </div>
                              <div class="form-group">
                                <label for="exampleFormControlTextarea1">Alamat</label>
                                <textarea required class="form-control" id="kelas" rows="3" name="alamat"><?php echo $baris['alamat'] ?></textarea>
                               
                              </div>
                <input type="submit" name="submitedit" class="btn btn-primary float-right" value="Update Profile">
                          </div>
                     </div>
            </form>   
        </div>
</section>
</body>
</html>