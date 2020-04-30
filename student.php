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
$sqlsiswa="SELECT * FROM SISWA where nis like '%".$searchword."%' or nama like '%".$searchword."%' or alamat like '%".$searchword."%' or jurusan like '%".$searchword."%' or tingkat like '%".$searchword."%' or kelas like '%".$searchword."%' or phone like '%".$searchword."%' or phone like '%".$searchword."%' or email like '%".$searchword."%'";

// ADD
  if(isset($_POST['submitadd'])){
    try{
     
      $nama=$_POST['nama'];
      $nis=$_POST['nis'];
      $image_file=$_FILES["txt_file"]["name"];
      $type=$_FILES["txt_file"]["type"];
      $size=$_FILES["txt_file"]["size"];
      $temp=$_FILES["txt_file"]["tmp_name"];
      $email=$_POST['email'];
      $telp=$_POST['telp'];
      $jurusan=$_POST['jurusan'];
      $kelasabjad=$_POST['kelasabjad'];
      $kelasangka=$_POST['kelasangka'];
      $alamat=$_POST['alamat'];
      $path="upload/siswa/".$image_file;//set upload folder path
   
      if(empty($nama)){
        $errorMsg="Please Enter Your Name";
      }
    else if(empty($image_file)){
        $errorMsg="Please Select image";
    }
    else if(empty($nis)){
      $errorMsg="Please enter nis";
    }
    else if(empty($email)){
      $errorMsg="Please enter email";
    }
    else if(empty($telp)){
      $errorMsg="Please enter telp";
    }
    else if(empty($jurusan)){
      $errorMsg="Please enter jurusan";
    }
    else if(empty($kelasabjad)){
      $errorMsg="Please enter kelasabjad";
    }
    else if(empty($kelasangka)){
      $errorMsg="Please enter kelasangka";
    }
    else if($type=="image/jpg" || $type=='image/jpeg' ||$type=='image/png'||$type=='image/gif')
    {

        if(!file_exists($path))
        {
            if($size<5000000)
            {
                //move upload file temprorary directory to your uoload folder
                move_uploaded_file($temp,"upload/siswa/".$image_file);
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
      $sqlinsert="INSERT INTO siswa VALUES(:nis,:nama,:alamat,:jurusan,:tingkat,:kelas,:phone,:email,:image)";
       $insert_stmt=$dbConn->prepare($sqlinsert);
      $insert_stmt->bindParam(':nis',$nis);
      $insert_stmt->bindParam(':nama',$nama);
      $insert_stmt->bindParam(':alamat',$alamat);
      $insert_stmt->bindParam(':jurusan',$jurusan);
      $insert_stmt->bindParam(':tingkat',$kelasangka);
      $insert_stmt->bindParam(':kelas',$kelasabjad);
      $insert_stmt->bindParam(':phone',$telp);
      $insert_stmt->bindParam(':email',$email);
       $insert_stmt->bindParam(':image',$image_file);
      
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
  // EDIT
  if(isset($_POST['submitedit'])){
    try{
    
      $nisedit=$_POST['nisedit'];
      $sqlpustkawan="SELECT * from siswa where nis = '".$nisedit."'";
      $syncronize=$dbConn->prepare($sqlpustkawan);
      $syncronize->execute();
      $baris=$syncronize->fetch(PDO::FETCH_ASSOC);
      $nama=$_POST['nama'];
      $nis=$_POST['nis'];
      $image_file=$_FILES["txt_file"]["name"];
      $type=$_FILES["txt_file"]["type"];
      $size=$_FILES["txt_file"]["size"];
      $temp=$_FILES["txt_file"]["tmp_name"];
      $email=$_POST['email'];
      $telp=$_POST['telp'];
      $jurusan=$_POST['jurusan'];
      $kelasabjad=$_POST['kelasabjad'];
      $kelasangka=$_POST['kelasangka'];
      $alamat=$_POST['alamat'];
      $path="upload/siswa/".$image_file;//set upload folder path
      $directory="upload/siswa/";
       if($image_file){
           if($type=="image/jpg" || $type=="image/jpeg" || $type=="image/png" || $type=="image/gif"){
               if(!file_exists($path)){
                   if($size < 5000000){
                       unlink($directory.$baris['image']);//unlink function remove previous file
                       //move upload file temporary directory to your upload folder
                       move_uploaded_file($temp,"upload/siswa/".$image_file);
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
    else if(empty($nis)){
      $errorMsg="Please enter nis";
    }
    else if(empty($email)){
      $errorMsg="Please enter email";
    }
    else if(empty($telp)){
      $errorMsg="Please enter telp";
    }
    else if(empty($jurusan)){
      $errorMsg="Please enter jurusan";
    }
    else if(empty($kelasabjad)){
      $errorMsg="Please enter kelasabjad";
    }
    else if(empty($kelasangka)){
      $errorMsg="Please enter kelasangka";
    }
   
    if(!isset($errorMsg)){
      $sqledit="UPDATE siswa set nis=:nis,nama=:nama,alamat=:alamat,jurusan=:jurusan,tingkat=:tingkat,kelas=:kelas,phone=:phone,email=:email,image=:image where nis ='".$nisedit."'";
       $insert_stmt=$dbConn->prepare($sqledit);
      $insert_stmt->bindParam(':nis',$nis);
      $insert_stmt->bindParam(':nama',$nama);
      $insert_stmt->bindParam(':alamat',$alamat);
      $insert_stmt->bindParam(':jurusan',$jurusan);
      $insert_stmt->bindParam(':tingkat',$kelasangka);
      $insert_stmt->bindParam(':kelas',$kelasabjad);
      $insert_stmt->bindParam(':phone',$telp);
      $insert_stmt->bindParam(':email',$email);
       $insert_stmt->bindParam(':image',$image_file);
      
       if($insert_stmt->execute()){
           echo "<script>alert('File UPDATE Successfully !'); </script>";
           header('location:student.php');
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
                               Delete Gagal, Tolong Selesaikan Transaksi terlebih dahulu
                                  </div>
                                  <div class="modal-footer">
                                 
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
                                 
                                
                                  </div>
                                </div>
                              </div>
        </div>
        <?php
        // DELETE
  if(isset($_POST['delete'])){
    try{
    $id=$_GET['delete_id'];//get delete_id and stor $id variable
    $delete="SELECT * FROM siswa where nis=:nis";
    $select_stmt=$dbConn->prepare($delete);
    $select_stmt->bindParam(':nis',$id);
    $select_stmt->execute();
    $row=$select_stmt->fetch(PDO::FETCH_ASSOC);
   
    $sqlhapus="DELETE FROM siswa WHERE nis=:nis";
    $delete_stmt=$dbConn->prepare($sqlhapus);
    $delete_stmt->bindParam(':nis',$id);
    $delete_stmt->execute();
    unlink("upload/siswa/".$row['image']);
    header("Location:student.php");
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
        <p class="ps fontheader pl-4">Student</p>
        <div class="container-fluid p-4 pt-0">
        
          
          <button type="button" class="btn btn-success mb-2 float-left" data-toggle="modal" data-target="#exampleModal">Add</button>
          <form method="GET" action="">
          <div class="input-group w-25 float-right">

    <input type="text" name="search" class="form-control" id="search" >
              <button class="btn btn-primary " type="submitedit">Cari</button>
            </form>            
          </div>
          <!-- Modal -->
          <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Add Student</h5>
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
                      <label for="exampleFormControlInput1">Nis</label>
                      <input required type="text" name="nis" class="form-control" id="exampleFormControlInput1">
                           <div class="form-group">
                                  <label for="stok">Email</label>
                                   <input required type="email" name="email" min="1" class="form-control" id="stok">
                             </div>
                            <div class="form-group">
                                 <label for="writer">Nama</label>
                                  <input required type="text" name="nama" class="form-control" id="writer">
                            </div>
                        </div>
                    </div>
                  <div class="row">
                      <div class="col">
                            <label for="exampleFormControlSelect1">No. Telepon</label>
                            <input required type="tel" name="telp" class="form-control" id="telephone">
                      </div>
                    <div class="col">
                      
                        <div class="form-group">
                        <label for="kelas">Kelas</label>
                        <div class="row">

                        <div class="col">
                        <select class="form-control" name="kelasangka">
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                              
                          </select>
                        </div>
                        <div class="col">
                        <select class="form-control" id="jurusan"  name="jurusan">
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
                        <div class="col">
                        <select class="form-control"   name="kelasabjad">
                                <option value="A">A</option>
                                <option value="B">B</option>          
                          </select>
                        </div>
                     
                      </div>
                       
                        </div>
                          </select>
                    </div>
                    
                  </div>
                  <div class="form-group">
                    <label for="exampleFormControlTextarea1">Alamat</label>
                    <textarea required class="form-control" id="kelas" rows="3" name="alamat"></textarea>
                    <input class="d-none" type="text" name="id" value="">
                  </div>
              </div>
            
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <input type="submit" name="submitadd" class="btn btn-primary" value="Add Student">
                </form>
                </div>
              </div>
            </div>
          </div>

        <table class="table  table-bordered ">
          <thead class="bg-primary text-white">
          <tr >
              <th class="w-10">Image</th>
              <th >Nis</th>
              <th class="w-15">Nama</th>
              <th class="w-20">Email</th>
              <th class="w-10">No Hp</th>
              <th class="w-10">Kelas</th>
              <th c>Alamat</th>
              <th class="w-10">Action</th>
            </tr>
          </thead>
          <!-- <form action='student.php?delete_id=".$baris['nis']."' method='POST'></form> -->
          <tbody>
            <?php
          $select_stmt=$dbConn->prepare($sqlsiswa);
            $select_stmt->execute();
            $rowsiswa = $select_stmt->fetchAll();
          foreach($rowsiswa as $baris){
            
            echo "<tr>";
            echo "<td>"."<img src='upload/siswa/".$baris['image']."' width='70'>"."</td>";
            echo "<th>".$baris['nis']."</th>";
            echo "<td>".$baris['nama']."</td>";
            echo "<td>".$baris['email']."</td>";
            echo "<td>".$baris['phone']."</td>";
            echo "<td>".$baris['tingkat']."-".$baris['jurusan']."-".$baris['kelas']."</td>";
            echo "<td>".$baris['alamat']."</td>";
            echo "<td> 
                <img data-toggle='modal' class='pointer ml-1' data-target='#modal".$baris['nis']."' src='img/pencil.png' width='35'>
           <img data-toggle='modal' data-target='#delete".$baris['nis']."' class=' pointer' src='img/bin.png' width='35'></td>";
            echo "</tr>";
          }
           ?>
          </tbody>
        </table>

        <!-- MODALEDIT -->
    <?php 
    $select_stmt=$dbConn->prepare($sqlsiswa);
    $select_stmt->execute();
    $rowsiswa=$select_stmt->fetchAll();
    foreach($rowsiswa as $baris){
    ?>
    <!-- MODAL DELETE -->
    <div class="modal fade" id="delete<?php echo $baris['nis']?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                              <div class="modal-dialog modal-center" role="document">
                                <div class="modal-content">
                                  <div class="modal-header bg-danger">
                                    <h5 class="modal-title" id="exampleModalLabel">Hapus</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                  <div class="modal-body">
                                   Apakah anda yakin ingin menghapus data Dengan NIS <?php echo $baris['nis']?> ?
                                  </div>
                                  <div class="modal-footer">
                                  <form action="student.php?delete_id=<?php echo $baris['nis']?>"  method='POST' >
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                                    <button type="submit" name="delete" class="btn btn-danger">Hapus Data</button>
                                  </form>
                                  </div>
                                </div>
                              </div>
                            </div>
     <div class="modal fade" id="modal<?php echo $baris['nis']?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Edit Student</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <form method="POST" action="" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col">
                            <center>
                              <img id="gambar<?php echo $baris['nis']?>" height="200" width="150" class="mb-3 mt-2" src="upload/siswa/<?php echo $baris['image']?>">
                            </center>

                          <div class="input-group mb-3">
                                 <div class="custom-file mt-3">
                                      <input  name="txt_file" value="<?php echo $baris['image']?>" type="file" class="custom-file-input"  id="inputGroupFile01" aria-describedby="inputGroupFileAddon01" onchange="document.getElementById('gambar<?php echo $baris['nis']?>').src=window.URL.createObjectURL(this.files[0])">
                                          <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                                   </div>
                            </div>
                </div>
                <div class="col">
                      <label for="exampleFormControlInput1" class="mt-5">Nis</label>
                      <input type="text"class="d-none" name="nisedit" value="<?php echo $baris['nis']?>" class="form-control" id="exampleFormControlInput1">
                      <input type="text" name="nis" readonly value="<?php echo $baris['nis']?>" class="form-control" id="exampleFormControlInput1">
                           <div class="form-group">
                                  <label for="stok">Email</label>
                                   <input required type="email" value="<?php echo $baris['email']?>" name="email" min="1" class="form-control" id="stok">
                             </div>
                            <div class="form-group">
                                 <label for="writer">Nama</label>
                                  <input required type="text" value="<?php echo $baris['nama']?>" name="nama" class="form-control" id="writer">
                            </div>
                        </div>
                    </div>
                  <div class="row">
                      <div class="col">
                            <label for="exampleFormControlSelect1">No. Telepon</label>
                            <input required type="tel" value="<?php echo $baris['phone']?>" name="telp" class="form-control" id="telephone">
                      </div>
                    <div class="col">
                      
                        <div class="form-group">
                        <label for="kelas">Kelas</label>
                        <div class="row">

                        <div class="col">
                        <select class="form-control" name="kelasangka">
                          <?php
                          $cekTingkat=$dbConn->prepare("SELECT tingkat from siswa where nis = '".$baris['nis']."'");
                          $cekTingkat->execute();
                          $rowtingkat=$cekTingkat->fetchAll();
                          foreach($rowtingkat as $baristingkat){
                          }
                          if($baristingkat['tingkat']==10){
                            echo "<option selected value='10'>10</option>";
                          }else{
                            echo "<option value='10'>10</option>";
                          }
                          if($baristingkat['tingkat']==11){
                            echo "<option selected value='11'>11</option>";
                          }else{
                            echo "<option value='11'>11</option>";
                          }
                          if($baristingkat['tingkat']==12){
                            echo "<option selected value='12'>12</option>";
                          }else{
                            echo "<option value='12'>12</option>";
                          }
                         

                          ?>
                               
                              
                          </select>
                        </div>
                        <div class="col">
                        <select class="form-control" id="jurusan"  name="jurusan">
                          <?php
                        $cekJurusan=$dbConn->prepare("SELECT jurusan from siswa where nis = '".$baris['nis']."'");
                          $cekJurusan->execute();
                          $rowjurusan=$cekJurusan->fetchAll();
                          foreach($rowjurusan as $barisjurusan){
                          }
                          if($barisjurusan['jurusan']=="TPTU"){
                            echo "<option selected value='TPTU'>TPTU</option>";
                          }else{
                            echo "<option value='TPTU'>TPTU</option>";
                          }
                          if($barisjurusan['jurusan']=="TOI"){
                            echo "<option selected value='TOI'>TOI</option>";
                          }else{
                            echo "<option value='TOI'>TOI</option>";
                          }
                          if($barisjurusan['jurusan']=="KP"){
                            echo "<option selected value='KP'>KP</option>";
                          }else{
                            echo "<option value='KP'>KP</option>";
                          }
                          if($barisjurusan['jurusan']=="KM"){
                            echo "<option selected value='KM'>KM</option>";
                          }else{
                            echo "<option value='KM'>KM</option>";
                          }
                          if($barisjurusan['jurusan']=="TEDK"){
                            echo "<option selected value='TEDK'>TEDK</option>";
                          }else{
                            echo "<option value='TEDK'>TEDK</option>";
                          }
                          if($barisjurusan['jurusan']=="EIND"){
                            echo "<option selected value='EIND'>EIND</option>";
                          }else{
                            echo "<option value='EIND'>EIND</option>";
                          }
                          if($barisjurusan['jurusan']=="SIJA"){
                            echo "<option selected value='SIJA'>SIJA</option>";
                          }else{
                            echo "<option value='SIJA'>SIJA</option>";
                          }
                          if($barisjurusan['jurusan']=="RPL"){
                            echo "<option selected value='RPL'>RPL</option>";
                          }else{
                            echo "<option value='RPL'>RPL</option>";
                          }
                          if($barisjurusan['jurusan']=="PFPT"){
                            echo "<option selected value='PFPT'>PFPT</option>";
                          }else{
                            echo "<option value='PFPT'>PFPT</option>";
                          }
                          ?>
                      
                          </select>
                        </div>
                        <div class="col">
                        <select class="form-control"   name="kelasabjad">
                          <?php
                        $cekKelas=$dbConn->prepare("SELECT kelas from siswa where nis = '".$baris['nis']."'");
                          $cekKelas->execute();
                          $rowKelas=$cekKelas->fetchAll();
                          foreach($rowKelas as $bariskelas){
                          }
                          if($bariskelas['kelas']=="A"){
                            echo "<option selected value='A'>A</option>";
                          }else{
                            echo "<option value='A'>A</option>";
                          }
                          if($bariskelas['kelas']=="B"){
                            echo "<option selected value='B'>B</option>";
                          }else{
                            echo "<option value='B'>B</option>";
                          }
                        ?>
                                  
                          </select>
                        </div>
                     
                      </div>
                       
                        </div>
                          </select>
                    </div>
                    
                  </div>
                  <div class="form-group">
                    <label for="exampleFormControlTextarea1">Alamat</label>
                    <textarea required class="form-control" id="kelas" rows="3" name="alamat"><?php echo $baris['alamat']?></textarea>
                    <input class="d-none" type="text" name="id" value="">
                  </div>
              </div>
            
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <input type="submit" name="submitedit" class="btn btn-primary" value="Edit Student">
                </form>
                </div>
              </div>
            </div>
          </div>
    <?php } ?>
</body>
</html>