<?php
  session_start();

  function alert ($messaggio) { ?>
    <div class="six columns">
      <p class="alert alert-error u-full-width">
        <i class="fa fa-exclamation-triangle fa-lg"></i>&nbsp; <?php echo $messaggio; ?>
      </p>
    </div>
  <?php }

  function form () { ?>
    <h2>Effettua il login</h2>
    <form method="POST">
      <div class="row">
        <div class="six columns">
          <label>Username</label>
          <input class="u-full-width" type="text" name="user" required autofocus />
        </div>
        <div class="six columns">
          <label>Password</label>
          <input class="u-full-width" type="password" name="pass" required />
        </div>
        <center>
          <input class="button-primary" type="submit" value="Login" />
          <input class="button" type="reset" value="Reset" />
        </center>
      </div>
    </form>
  <?php }
  
  require_once '../header.php';

  /*FORMATO: user;pass;nome;tipo (A/U)*/
  $filename = 'utenti.csv';
  $logged = false;

  if(isset($_SESSION['user'], $_SESSION['tipo'], $_SESSION['logged'], $_SESSION['nome']))
    if($_SESSION['logged'])
      $logged = true;

  if(!$logged && isset($_POST['user'], $_POST['pass'])) {

    if (($handle = @fopen($filename, "r")) !== FALSE) {
      while (($linecsv = fgetcsv($handle, 1000, ";")) !== FALSE && !$logged) {
        if($linecsv[0] == $_POST['user'] && $linecsv[1] == $_POST['pass']) {
          $logged = true;
          $_SESSION['user'] = $linecsv[0];
          $_SESSION['logged'] = true;
          $_SESSION['nome'] = $linecsv[2];
          $_SESSION['tipo'] = $linecsv[3];
        }
      }
      fclose($handle);

      if(!$logged) alert("Credenziali errate!");

    } else alert("Non sono riuscito ad aprire il file degli utenti!");

  }

  if(!$logged) form();
  else {
    echo "<script>location.href = 'index.php';</script>";
    echo "Stai per venire reindirizzato, se non accade nulla <a href='http://$host$uri/index.php'>clicca qui</a>...";
  }
  
  require_once '../footer.php';
?>