<?php
 require_once ("koneksi.php"); 
 if( isset( $_POST['user_name'] ) )
{
    $name=$_POST['user_name'];
    $selectdata = " SELECT * FROM siswa WHERE nis = ".$name." ";
    $select_stmt=$dbConn->prepare($selectdata);
    $select_stmt->execute();
    $row=$select_stmt->fetch(PDO::FETCH_ASSOC);
    $count = $select_stmt->rowCount();  
    
   echo $row['nama'];
   echo ",";
    echo $row['tingkat']."-".$row['jurusan']."-".$row['kelas'];
}
?>