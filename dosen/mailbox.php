<?php
include "../session.php";
include "../koneksi.php";
if($_SESSION['level']!="dosen"){
  header('Location: ../login.php');
}
$user=$userOnSession;
$tipe="";
if(!isset($_GET['view'])||empty($_GET['view'])){            
  $tipe="INBOX";
}
else{
  $tipe=strtoupper($_GET['view']);
}

if($tipe!="INBOX"&&$tipe!="OUTBOX"&&$tipe!="FAVORITE"&&$tipe!="ALL"){
  $tipe="INBOX";
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Info Dosen - Pesan</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../dist/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Style Buatan -->
  <link rel="stylesheet" href="../dist/css/style.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="../dist/css/skins/_all-skins.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="../plugins/iCheck/flat/blue.css">
  <!-- Morris chart -->
  <link rel="stylesheet" href="../plugins/morris/morris.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="../plugins/jvectormap/jquery-jvectormap-1.2.2.css">
  <!-- Date Picker -->
  <link rel="stylesheet" href="../plugins/datepicker/datepicker3.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker-bs3.css">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="../plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="../plugins/iCheck/flat/blue.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="index.php" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>i</b>D</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>info</b>DOSEN</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>


      <?php
        $sql = "select * from pesan where penerima='".$user['nip']."' AND status='UNREAD'";
        $sqla=mysql_query($sql);
        $jumlahUNREAD=mysql_num_rows($sqla);
      ?>
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
          <li class="dropdown messages-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-envelope-o"></i>
              <?php
                if($jumlahUNREAD>0){
                  echo '<span class="label label-danger">'.$jumlahUNREAD.'</span>';
                }
              ?>
            </a>
            <ul class="dropdown-menu">
              <li class="header">Kamu punya <?php echo $jumlahUNREAD ?> pesan yang belum dibaca</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <?php
                    $sql = "select * from pesan where penerima='".$user['nip']."' or pengirim='".$user['nip']."'";
                    $sqla=mysql_query($sql);
                    $jumlahPesan=mysql_num_rows($sqla);
                    $sql = "select * from pesan where penerima='".$user['nip']."' ORDER BY status DESC";
                    $sqla=mysql_query($sql);
                    for($i=0; ($data = mysql_fetch_array($sqla))&&$i<5; $i++) {
                      $sqlpengirim = mysql_query("select * from mahasiswa where nim='".$data['pengirim']."'");
                      if(mysql_num_rows($sqlpengirim)<=0){
                        $sqlpengirim = mysql_query("select * from dosen where nip='".$data['pengirim']."'");
                      }
                      $pengirim = mysql_fetch_assoc($sqlpengirim);
                      $date = date_create($data['tanggal']);
                      $tanggalkirim= date_format($date,"d M Y");
                      $subject= substr($data['subject'],0,30);
                      if($data['status']=="UNREAD"){
                        echo '<li style="background: #FFEBEE">';
                      }
                      else{
                        echo '<li>';
                      }
                      echo '
                        <a href="read.php?id='.$data['idpesan'].'">
                          <div class="pull-left">
                            <img src="../assets/images/'.$pengirim['foto'].'" class="img-circle" alt="User Image">
                          </div>
                          <h4>
                            '.$pengirim['nama'].'
                            <small><i class="fa fa-clock-o"></i> '.$tanggalkirim.'</small>
                          </h4>
                          <p>'.$subject.'</p>
                        </a>
                      </li>';
                    }
                  ?>
                </ul>
              </li>
              <li class="footer"><a href="mailbox.php">Lihat Semua Pesan</a></li>
            </ul>
          </li>
         
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="../assets/images/<?php echo $user['foto'] ?>" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php echo $user['nama'];?></span>
              <i class="fa fa-sort-down pull-right"></i>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="../assets/images/<?php echo $user['foto'] ?>" class="img-circle" alt="User Image">

                <p>
                  <?php echo $user['nama'];?>
                  <small><?php echo $user['deskripsi'];?></small>
                </p>
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="profil.php" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="../logout.php" class="btn btn-default btn-flat">Keluar</a>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="../assets/images/<?php echo $user['foto'] ?>" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?php echo $user['nama'];?></p>
          <?php echo $user['deskripsi'];?>
        </div>
      </div>
      </form>
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
        <li class="header">NAVIGATION</li>
        <li class="treeview">
          <a href="index.php">
            <i class="fa fa-dashboard"></i>
            <span>Dashboard</span>
          </a>
        </li>
        <li class="active treeview">
          <a href="mailbox.php">
            <i class="fa fa-envelope"></i>
            <span>Pesan</span>
          </a>
        </li>
        <li class="header">CREDITS</li>
        <li><a href="aboutus.php"><i class="fa fa-users"></i> <span>Tentang Kami</span></a></li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-3">
          <a href="pesanbaru.php" class="btn btn-primary btn-block margin-bottom">Tulis Pesan</a>

          <div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Folders</h3>

              <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="box-body no-padding">
              <ul class="nav nav-pills nav-stacked">
                <li id="kotakmasuk" class="active"><a href="mailbox.php?view=INBOX"><i class="fa fa-inbox"></i> Kotak Masuk
                  <?php
                    if($jumlahUNREAD>0){
                      echo '<span class="label label-primary pull-right">'.$jumlahUNREAD.'</span></a></li>';
                    }
                  ?>
                <li id="kotakkeluar"><a href="mailbox.php?view=OUTBOX"><i class="fa fa-envelope-o"></i> Pesan Terkirim</a></li>
                <!-- Baru diubah aga  -->
                <li id="kotakfavorit"><a href="mailbox.php?view=FAVORITE"><i class="fa fa-star"></i> Favorite</a>
                <li id="kotaksemua"><a href="mailbox.php?view=ALL"><i class="fa fa-inbox"></i> Semua Pesan 
                  <span class="label label-warning pull-right"><?php echo $jumlahPesan ?></span></a>
                </li>
                
              </ul>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /. box -->
          
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"><?php echo $tipe; ?></h3>

              <div class="box-tools pull-right">
                <div class="has-feedback">
                  <input type="text" class="form-control input-sm" placeholder="Search Mail">
                  <span class="glyphicon glyphicon-search form-control-feedback"></span>
                </div>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="mailbox-controls">
                <!-- Check all button -->
                <button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i>
                </button>
                <div class="btn-group">
                  <button onclick="hapusBanyak()" type="button" class="btn btn-default btn-sm"><i class="fa fa-trash-o"></i></button>
                </div>
                <!-- /.btn-group -->
                <button onclick="refresh()" type="button" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>
                <div class="pull-right">
                  1-1/200
                  <div class="btn-group">
                    <button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></button>
                    <button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></button>
                  </div>
                  <!-- /.btn-group -->
                </div>
                <!-- /.pull-right -->
              </div>
              <div class="table-responsive mailbox-messages">
                <table class="table table-hover">
                  <tbody id="listpesan">
                    <?php
                      switch ($tipe) {
                        case 'OUTBOX':
                          $sql = "select * from pesan where pengirim='".$user['nip']."'";
                          break;
                        case 'FAVORITE':
                          $sql = "select * from pesan where (pengirim='".$user['nip']."' OR penerima='".$user['nip']."') AND favorit='FAVORITE'";
                          break;
                        case 'ALL':
                          $sql = "select * from pesan where pengirim='".$user['nip']."' OR penerima='".$user['nip']."'";
                          break;
                        default:
                          $sql = "select * from pesan where penerima='".$user['nip']."'";
                          break;
                      }
                      
                      $sqla=mysql_query($sql);
                      while($data = mysql_fetch_array($sqla)) {
                        $sqlpengirim = mysql_query("select * from mahasiswa where nim='".$data['pengirim']."'");
                        if(mysql_num_rows($sqlpengirim)<=0){
                          $sqlpengirim = mysql_query("select * from dosen where nip='".$data['pengirim']."'");
                        }
                        $pengirim = mysql_fetch_assoc($sqlpengirim);
                        $sqlpenerima = mysql_query("select * from mahasiswa where nim='".$data['penerima']."'");
                        if(mysql_num_rows($sqlpenerima)<=0){
                          $sqlpenerima = mysql_query("select * from dosen where nip='".$data['penerima']."'");
                        }
                        $penerima = mysql_fetch_assoc($sqlpenerima);
                        $date = date_create($data['tanggal']);
                        $tanggalkirim= date_format($date,"d M Y");
                        $subject= substr($data['subject'],0,50);
                        if($data['status']=="UNREAD"){
                          echo '<tr style="background: #E3F2FD" id="'.$data['idpesan'].'">';
                        }
                        else{
                          echo '<tr id="'.$data['idpesan'].'">';
                        }
                        echo '
                          <td><input type="checkbox"></td>';
                        if($data['favorit']=="FAVORITE"){
                          echo '<td class="mailbox-star"><a href="#"><i class="fa fa-star text-yellow"></i></a></td>';
                        }
                        else {
                          echo '<td class="mailbox-star"><a href="#"><i class="fa fa-star-o text-yellow"></i></a></td>';
                        }
                        echo '
                          <td class="mailbox-name"><a href="read.php?id='.$data['idpesan'].'"><b>'.$pengirim['nama'].'</b> > <b>'.$penerima['nama'].'</b></a></td>
                          <td class="mailbox-subject">'.$subject.'
                          </td>
                          <td class="mailbox-date">'.$tanggalkirim.'</td>
                        </tr>
                        ';
                      }
                    ?>

                  
                  </tbody>
                </table>
                <!-- /.table -->
              </div>
              <!-- /.mail-box-messages -->
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /. box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 1.0
    </div>
    <strong>Copyright &copy; 2016 <a href="#">infoDosen</a>.</strong> All rights reserved.
  </footer>
</div>
<!-- ./wrapper -->

<!-- jQuery 2.2.0 -->
<script src="../plugins/jQuery/jQuery-2.2.0.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.6 -->
<script src="../bootstrap/js/bootstrap.min.js"></script>
<!-- Morris.js charts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="../plugins/morris/morris.min.js"></script>
<!-- Sparkline -->
<script src="../plugins/sparkline/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="../plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="../plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- jQuery Knob Chart -->
<script src="../plugins/knob/jquery.knob.js"></script>
<!-- daterangepicker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="../plugins/daterangepicker/daterangepicker.js"></script>
<!-- datepicker -->
<script src="plugins/datepicker/bootstrap-datepicker.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="../plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Slimscroll -->
<script src="../plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="../plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/app.min.js"></script>
<!-- iCheck -->
<script src="../plugins/iCheck/icheck.min.js"></script>
<!-- Page Script -->
<script src="../assets/js/mailbox-dosen.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="../dist/js/pages/dashboard.js"></script>
</body>
</html>

<?php
mysql_close();
?>