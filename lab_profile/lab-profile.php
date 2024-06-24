<?php 
  session_start() ; 
  $con = mysqli_connect("localhost","root","") ;
  if (!$con){
    die ("connection error : ". mysqli_connect_error()) ;
  }

  mysqli_select_db($con,"mydb") ;

  $query = "SELECT id FROM lab_users where user_name = ?" ;
  $stmt = mysqli_prepare($con, $query);
  mysqli_stmt_bind_param($stmt,"s",$_SESSION['username']) ;
  mysqli_stmt_execute($stmt) ;
  mysqli_stmt_bind_result($stmt, $id) ;
  mysqli_stmt_fetch($stmt) ;
  mysqli_stmt_close($stmt) ;
  // we will use this later
  $_SESSION['id'] = $id ;

  $query = "SELECT lab_name, first_name, last_name, lab_type, full_address, phone_number FROM lab_users where user_name = ?" ;
  $stmt = mysqli_prepare($con, $query) ;
  mysqli_stmt_bind_param($stmt, "s", $_SESSION['username'] ) ;
  mysqli_stmt_execute($stmt) ;
  mysqli_stmt_bind_result($stmt, $lab_name, $first_name, $last_name, $lab_type, $full_address, $phone_number) ;
  mysqli_stmt_fetch($stmt) ;
  mysqli_stmt_close($stmt) ;
  // we will use this later
  $_SESSION['lab_type'] = $lab_type ;

  // selecting columns names to generate inputs depending on the columns names .
  $tableName = "prices";
  $query = "SELECT column_name FROM information_schema.columns WHERE table_name = ? ";
  $stmt = mysqli_prepare($con, $query);
  mysqli_stmt_bind_param($stmt, "s", $tableName) ;
  mysqli_stmt_execute($stmt);
  mysqli_stmt_store_result($stmt);
  mysqli_stmt_bind_result($stmt, $columnName);
  // we will fetch ($stmt) inside html section .
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="CSS/all.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  <link rel="stylesheet" href="css/lab-profile.css">
  <title>الحساب الشخصي</title>
</head>

<body>
  <main>
    <!-- first navbar will be visible on all screens except small screen -->
    <nav class="navbar d-none d-sm-flex navbar-expand navbar-fixed-right align-items-start">
      <div class="container-fluid  p-0 flex-column align-items-start">
        <a class="navbar-brand" href="#">DentalX</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo02"
          aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
          <ul class="navbar-nav flex-column gap-1 p-0 me-3 mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link active" href="#">
                <i class="fa-solid fa-user"></i>
                الحساب الشخصي
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../lab_orders/lab-orders.php">
                <i class="fa-solid fa-bag-shopping"></i>
                قائمة الطلبات
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../lab_about/lab-about.html">
                <i class="fa-solid fa-circle-question"></i>
                حول
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../index.html">
                <i class="fa-solid fa-right-from-bracket"></i>
                تسجيل الخروج
              </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- second navbar will be visible on small screen only -->
    <nav class="navbar d-sm-none  bg-body-tertiary fixed-top">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">DentalX</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
          aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
          <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasNavbarLabel">DentalX</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
          </div>
          <div class="offcanvas-body">
            <ul class="navbar-nav justify-content-end flex-grow-1 gap-1 pe-3">
              <li class="nav-item">
                <a class="nav-link active" href="#">
                  <i class="fa-solid fa-user"></i>
                  الحساب الشخصي
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="../lab_orders/lab-orders.php">
                  <i class="fa-solid fa-bag-shopping"></i>
                  قائمة الطلبات
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="../lab_about/lab-about.html">
                  <i class="fa-solid fa-circle-question"></i>
                  حول
                </a>
              <li class="nav-item">
                <a class="nav-link" href="../index.html">
                  <i class="fa-solid fa-right-from-bracket"></i>
                  تسجيل الخروج
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </nav>

    <div class="container-fluid pt-3 pe-5 ps-5 ">
      <!-- info section -->
      <div class="card fs-5">
        <div class="card-body">
          <p class="card-text">الاسم :
            <span class="text-secondary">
              <?php echo $first_name.' '.$last_name ; ?>
            </span>
          </p>
          <p class="card-text">اسم المخبر :
            <span class="text-secondary">
              <?php echo $lab_name ; ?>
            </span>
          </p>
          <p class="card-text">نوع المخبر :
            <span class="text-secondary">
              <?php echo $lab_type ; ?>
            </span>
          </p>
          <p class="card-text">المدينة :
            <span class="text-secondary">
              <?php echo $full_address ; ?>
            </span>
          </p>
          <p class="card-text">رقم الهاتف :
            <span class="text-secondary">
              <?php echo $phone_number ; ?>
            </span>
          </p>
        </div>
        <div class="card-footer text-center">
          <a href="../lab_profile_edit/lab-profile-edit.php">
            <button class="btn btn-secondary edit w-50 border-0">تعديل المعلومات</button>
          </a>
        </div>
      </div>
      <!-- info section -->
      <hr>
      <!-- prices section -->
      <div class="accordion">
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
              قائمة المواد
            </button>
          </h2>
          <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
            <div class="accordion-body">
              <div class="container-fluid">
                <form action="" method="post">
                  <div class="row row-cols-1 row-cols-xl-2">
                    <?php
                      $i = -2 ;
                      $k = 0 ;
                      if($lab_type=="تعويضات ثابتة"){   
                        while (mysqli_stmt_fetch($stmt)) {
                          // we use $i so we ignore the first two column in the table (id,lab_id,user_name)
                          // first 14 columns are for fixed prosthodontists (ignoring id,lab_id,user_name)
                          if($i >= 1 && $i <= 14){
                            // increasing k which is the input number , it will start at 1 , 
                            // so the first input will have the (id and name) => input1 , and second will have input2 .. etc..
                            $k++ ;
                            echo '
                            <div class="col">
                              <label for="input'.$k.'" class="mb-1 text-secondary d-md-none">'.$columnName.'</label>
                              <div class="input-group mb-3" dir="ltr">
                                <span class="input-group-text">ل.س</span>
                                <input type="number" class="form-control" dir="rtl" min="0" id="input'.$k.'" name="input'.$k.'">
                                <span class="input-group-text d-none d-md-inline-block">'.$columnName.'</span>
                              </div>
                            </div>' ;
                          }
                          $i++ ;
                          if($i==16){
                            break ;
                          }
                        }
                      }

                      elseif($lab_type=="تعويضات متحركة"){   
                        while (mysqli_stmt_fetch($stmt)) {
                          // we use $i so we ignore the first two column in the table (id,lab_id,user_name)
                          // columns from 15 to 49 are for removable prosthodontists (ignoring id,lab_id,user_name)
                          if($i >= 15 && $i <= 49){
                            // increasing k which is the input number , it will start at 1 , 
                            // so the first input will have the (id and name) => input1 , and second will have input2 .. etc..
                            $k++ ;
                            echo '
                            <div class="col">
                              <label for="input'.$k.'" class="mb-1 text-secondary d-md-none">'.$columnName.'</label>
                              <div class="input-group mb-3" dir="ltr">
                                <span class="input-group-text">ل.س</span>
                                <input type="number" class="form-control" dir="rtl" min="0" id="input'.$k.'" name="input'.$k.'">
                                <span class="input-group-text d-none d-md-inline-block">'.$columnName.'</span>
                              </div>
                            </div>' ;
                          }
                          $i++ ;
                          if($i==50){
                            break ;
                          }
                        }
                      }

                      else{   
                        while (mysqli_stmt_fetch($stmt)) {
                          // we use $i so we ignore the first two column in the table (id,lab_id,user_name)
                          // columns from 1 to 49 are for fixed/removable prosthodontists (ignoring id,lab_id,user_name)
                          if($i >= 1 && $i <= 49){
                            // increasing k which is the input number , it will start at 1 , 
                            // so the first input will have the (id and name) => input1 , and second will have input2 .. etc..
                            $k++ ;
                            echo '
                            <div class="col">
                              <label for="input'.$k.'" class="mb-1 text-secondary d-md-none">'.$columnName.'</label>
                              <div class="input-group mb-3" dir="ltr">
                                <span class="input-group-text">ل.س</span>
                                <input type="number" class="form-control" dir="rtl" min="0" id="input'.$k.'" name="input'.$k.'">
                                <span class="input-group-text d-none d-md-inline-block">'.$columnName.'</span>
                              </div>
                            </div>' ;
                          }
                          $i++ ;
                          if($i==50){
                            break ;
                          }
                        }
                      }
                    ?>
                  </div>
                  <div class="w-100 text-center">
                    <button class="btn w-50 border-0 save text-light">حفظ</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div> 
      </div>

    </div>
    <!-- end of the main container -->
  </main>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
    crossorigin="anonymous"></script>
</body>

</html>


<!-- we have to put on all input .. 

 two variables , which will be oposite from each others .. or empty 
 so 
 if lab-type is fixed => state-x = disabled , state-y = empty 
 and if lab-type is removable => state-x = empty , state-y = disabled
 else state-x=state-y=empty

 and we will put state x or y on all inputs , lets make them $fixed and $removable insted of x , y
 on all inputs for fixed .. we will put fixed , so if it's fixed it will be empty and $removable will be disabled

<div class="col <?php
//  echo $removable ?>">
                          <label for="" class="mb-1 text-secondary d-md-none">تجربة المعدن</label>
                          <div class="input-group mb-3" dir="ltr">
                            <span class="input-group-text">ل.س</span>
                            <input type="number" class="form-control" dir="rtl" min="0" >
                            <span class="input-group-text d-none d-md-inline-block">تجربة المعدن</span>
                          </div>
                        </div> 

-->