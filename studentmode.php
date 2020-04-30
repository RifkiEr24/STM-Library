<?php 
  require_once ("koneksi.php"); 
  session_start();  
  
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
    
      $path="upload/".$image_file;//set upload folder path
   
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
                move_uploaded_file($temp,"upload/".$image_file);
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
    
      $path="upload/".$image_file;//set upload folder path
   
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
                move_uploaded_file($temp,"upload/".$image_file);
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
  if(isset($_POST['delete'])){
    $id=$_GET['delete_id'];//get delete_id and stor $id variable
    $sql="SELECT * FROM buku where idBuku=:id";
    $select_stmt=$dbConn->prepare($sql);
    $select_stmt->bindParam(':id',$id);
    $select_stmt->execute();
    $row=$select_stmt->fetch(PDO::FETCH_ASSOC);
    unlink("upload/".$row['image']);
    $sqlhapus="DELETE FROM buku WHERE idBuku=:id";
    $delete_stmt=$dbConn->prepare($sqlhapus);
    $delete_stmt->bindParam(':id',$id);
    $delete_stmt->execute();
    header("Location:book.php");
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
    <section  style="background-color: whitesmoke;">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <a class="navbar-brand" href="index.html">
            <img src="img/logo stm.png" width="30" height="30" class="d-inline-block align-top" alt="">
            STM LIBRARY
          </a>
            <ul class="navbar-nav mr-auto"> 
                </ul>
                <ul class="navbar-nav">
                <li class="nav-item dropdown ">
                 
                  </li>
                </ul>
        </nav>
        <p class="ps fontheader pl-4">Booklist</p>
        <div class="container-fluid p-4 pt-0">
          <form method="GET" action="">
          <div class="input-group w-25 float-right mb-4">

    <input type="text" name="search" class="form-control" id="search" >
              <button class="btn btn-primary " type="submitedit">Cari</button>
            </form>            
          </div>
     

        <table class="table  table-bordered ">
          <thead class="bg-primary text-white">
          <tr >
              <th class="w-10">Image</th>
              <th class="w-10">Title</th>
              <th class="w-10">Writer</th>
              <th class="w-10">Publisher</th>
              <th class="w-10">Category</th>
              <th class="w-10">Stock</th>
              <th >Synopsis</th>
            
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
            echo "</tr>";
          }
        
           ?>
          </tbody>
        </table>
      </div>    
      
     
        </section>
</body>
</html>