<?php
  $env = array();
  $env['SITE_NAME'] = "Phybryd Blog";
  $env['SITE_DESCRIPTION'] = "This blog is made by Phybryd. It's a php and slim framework based simple blog generate engine.";
  
  $name = '';
  $email = '';
  $content = '';
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $content = $_POST['content'];
    
    if (!empty($name) && !empty($email) && !empty($content)) {
      // send mail
      
      header('Location: ./index.html');
      exit;
    }
  }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact us - <?php echo $env['SITE_NAME']; ?></title>
    <meta name="description" content="Contact us - <?php echo $env['SITE_DESCRIPTION']; ?>">
    
    <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js" type="text/javascript"></script>
      <script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js" type="text/javascript"></script>
    <![endif]-->
    
    <link rel="stylesheet" media="all" href="./assets/css/bootstrap.min.css" />
    <link rel="stylesheet" media="all" href="./assets/css/app.css" />
    
    <script src="./assets/js/jquery.min.js"></script>
    <script src="./assets/js/bootstrap.min.js"></script>
    <script src="./assets/js/app.js"></script>
  </head>
  <body>
    <nav id="header" class="navbar" role="navigation">
      <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          
          <div class="site-info">
            <h1>
              <a href="./index.html" class="navbar-brand"><?php echo $env['SITE_NAME']; ?></a>
            </h1>
            <p class="site-description">
              <?php echo $env['SITE_DESCRIPTION']; ?>
            </p>
          </div>
        </div>
        
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav">
            <li><a href="./index.html">Home</a></li>
            <li><a href="./contact.php">Contact</a></li>
          </ul>
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container -->
    </nav>
    
    <div class="contents-wrapper">
      <div class="container">
        <ol class="breadcrumb">
          <li><a href="./index.html">Home</a></li>
          <li class="active">Contact us</a></li>
        </ol>
        
        <div class="page">
          <form method="POST" class="form-horizontal">
            <div class="form-group">
              <label for="name">Name</label>
              <input type="text" class="form-control" name="name" id="name" value="<?php echo $name; ?>">
            </div>
            <div class="form-group">
              <label for="email">Email</label>
              <input type="text" class="form-control" name="email" id="email" value="<?php echo $email; ?>">
            </div>
            <div class="form-group">
              <textarea class="form-control" name="content" id="content" style="height: 160px;"><?php echo $content; ?></textarea>
            </div>
            <div class="form-group">
              <input type="submit" class="btn btn-default" value="send" name="submit_button">
            </div>
          </form>
        </div>
      </div>
    </div><!-- /.container -->
    
    <div id="footer">
      <div class="container">
        <div class="col-md-3">
          <div class="navi">
            <ul>
              <li><a href="./index.html">Home</a></li>
              <li><a href="./contact.php">Contact</a></li>
            </ul>
          </div><!-- /navi -->
        </div><!-- /col-md-3 -->
        
        <div class="col-md-9"></div><!-- /col-md-9 -->
        
        <div class="copyright">
          <p>copyright © 2015 Phybryd Blog. All rights reserved.</p>
        </div>
        <div class="powered">
          <p>powered by blog generate engine "Phybryd".</p>
        </div>
      </div>
    </div>
  </body>
</html>
