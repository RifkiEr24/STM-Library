<?php
 require_once ("koneksi.php"); 
 if( isset( $_POST['user_name'] ) )
{
    $name=$_POST['user_name'];
    $selectdata = " SELECT * FROM buku WHERE idBuku = ".$name." ";
    $select_stmt=$dbConn->prepare($selectdata);
    $select_stmt->execute();
    $row=$select_stmt->fetch(PDO::FETCH_ASSOC);
  
    
   echo $row['judul'];
}
?>