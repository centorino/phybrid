<?php
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SERVER['SCRIPT_NAME'] = substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], '/'));
    require '../phybrid_generator.php';
  }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Phybrid generator form</title>
    <?php if (isset($_GET['m']) && $_GET['m'] == '1' && file_exists('../your_web_site') && !file_exists('site.zip')) { ?>
    <meta http-equiv="refresh" content="3;URL=">
    <?php } ?>
    <style type="text/css">
      body {
        font-family: 游ゴシック, YuGothic, 'ヒラギノ角ゴ Pro W3', 'Hiragino Kaku Gothic Pro', メイリオ, Meiryo, 'ＭＳ Ｐゴシック', 'MS PGothic', sans-serif;
        font-size: 14px;
        background-color: #f0f0f0;
        margin: 0;
        padding: 0;
        color: #555;
      }
      div, p {
        margin: 0;
        padding: 0;
      }
      a {
        color: #115a97;
      }
      h1 {
        font-size: 18px;
        color: #777;
        font-weight: normal;
        text-align: center;
        border-bottom: solid 1px #888;
        padding-bottom: 15px;
        margin: 0 0 40px;
      }
      strong {
        color: #c33;
      }
      .container {
        max-width: 640px;
        margin-left: auto;
        margin-right: auto;
        background-color: #fff;
        padding: 20px;
        height: 210px;
        margin-top: 5%;
      }
      form {
        margin: 20px auto 40px;
        max-width: 480px;
        padding: 0;
      }
      input[type="text"],input[type="password"] {
        font-size: 14px;
        width: 100%;
        margin: 0;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        padding: 8px;
      }
      input[type="submit"] {
        font-size: 14px;
        width: 100%;
        margin: 0;
        padding: 15px 0;
        background: #115a97;
        color: #fff;
        -webkit-appearance: none;
        -webkit-border-radius: 0;
        -moz-border-radius: 0;
        border-radius: 0;
        border: none;
      }
      input[type="submit"]:hover, input[type="submit"]:focus {
        background: #216aa7;
        cursor: pointer;
      }
      form p {
        margin-bottom: 20px;
      }
    </style>
  </head>
  <body>
    <div class="container">
      <h1>Phybrid generator form</h1>
      <form action="?m=1" method="POST">
        <p><input type="submit" value="generate"></p>
      </form>
      
    <?php if (isset($_GET['m']) && $_GET['m'] == '1' && file_exists('../your_web_site') && !file_exists('site.zip')) { ?>
    <p>Plase wait for generating...</p>
    <?php } ?>
    <?php if ($_SERVER['REQUEST_METHOD'] == 'POST') { ?>
      <?php if (isset($_GET['m']) && $_GET['m'] == '1' && file_exists('../your_web_site') && !file_exists('site.zip')) { ?>
        <?php echo ''; ?>
      <?php } else { ?>
        <p><strong>Sorry, it has occured an error...</strong></p>
        <a href="<?php echo $_SERVER['SCRIPT_NAME']; ?>">Retry</a>
      <?php } ?>
    <?php } ?>
  
    <?php if (file_exists('site.zip')) { ?>
      <p>
        <b>Latest archived your site:</b>
        <a href="site.zip">Download</a>
      </p>
    <?php } ?>
    </div>
  </body>
</html>
