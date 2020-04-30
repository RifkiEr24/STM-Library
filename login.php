<?php
require_once ("koneksi.php");
 session_start();  
 $message = ""; 
 
 ?>
<!DOCTYPE html>  
<html>
    <head>
        <link rel="stylesheet" href="css/bootstrap.css" type="text/css">  
        <link rel="stylesheet" href="css/style.css" type="text/css">
        <script src="js/jquery-3.4.1.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <title>Login-Perpus Librarian</title>
    </head>
    <body>
         <!-- SALAH -->
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
                               Username/Password Anda Salah
                                  </div>
                                  <div class="modal-footer">
                                 
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
                                 
                                
                                  </div>
                                </div>
                              </div>
        </div>
        <!-- KOSONG -->
        <div class="modal fade" id="kosong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                              <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                  <div class="modal-header bg-danger">
                                    <h5 class="modal-title" id="exampleModalLabel">Hapus</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                  <div class="modal-body">
                               Username/Password Harus Di isi
                                  </div>
                                  <div class="modal-footer">
                                 
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
                                 
                                
                                  </div>
                                </div>
                              </div>
        </div>
        <?php
        try  
        {  
             if(isset($_POST["submit"]))  
             {  
                  if(empty($_POST["username"]) || empty($_POST["password"]))  
                  {  
                    echo "<script>$('#kosong').modal('show');</script>";
                       $message = '<label>All fields are required</label>';  
                  }  
                  else  
                  {  
                       $query = "SELECT * FROM login WHERE username = :username AND password = :password";  
                       $statement = $dbConn->prepare($query);  
                       $statement->execute(  
                            array(
                                 'username'     =>     $_POST["username"],  
                                 'password'     =>     $_POST["password"]  
                            )  
                       );  
                       $row=$statement->fetch(PDO::FETCH_ASSOC);
                       $count = $statement->rowCount();  
                       if($count > 0)  
                       {        
                           $_SESSION["idacc"] = $row['idPustakawan'];  
                            $_SESSION["username"] = $_POST["username"];  
                            $_SESSION["role"]=$row['hakUser'];
                            header("location:dashboard.php");  
                       }  
                       else  
                       {  
                         echo "<script>$('#gagaldelete').modal('show');</script>";
                       }  
                  }  
             }
        }  
        catch(PDOException $error)  
        {  
             $message = $error->getMessage();  
        }
        ?>
        <section style="background-image: url(img/bglibrary.png); background-size: cover;"> 
        <a href="index.html"> <img src="img/back.png" style="width: 5%; z-index: 1;" class="m-3 position-absolute"></a>
        <div id="overlay"></div>
            <div class="divlogin">
            <form method="POST" action="">
                <div class="form-group">
                  <label for="exampleInputEmail1">Email address</label>
                  <input type="username" class="form-control" name="username" id="exampleInputEmail1" aria-describedby="emailHelp">
                  <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Password</label>
                  <input type="password" name="password" class="form-control" id="exampleInputPassword1">
                </div>
                <div class="form-group form-check">
                  <input type="checkbox" class="form-check-input" id="exampleCheck1">
                  <label class="form-check-label" for="exampleCheck1">Check me out</label>
                </div>
                <button type="submit" name="submit" class="btn btn-primary" >Login</button>
              </form>
            </div>
        </section>
    </body>
</html>