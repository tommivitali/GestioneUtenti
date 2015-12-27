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

  $servername = "localhost";
  $dbname = "my_tomz";
  $logged = false;

  if(isset($_SESSION['user'], $_SESSION['tipo'], $_SESSION['logged'], $_SESSION['nome']))
    if($_SESSION['logged'])
      $logged = true;

  if(!$logged && isset($_POST['user'], $_POST['pass'])) {
    $conn = new mysqli($servername, "", "", $dbname);
    if (!($conn->connect_error)) {
      $result = $conn->query("SELECT * FROM utenti");
      while(($row = $result->fetch_assoc()) && !$logged) {
        if($row['user'] == $_POST['user'] && $row['pass'] == $_POST['pass']) {
          $logged = true;
          $_SESSION['user'] = $row['user'];
          $_SESSION['logged'] = true;
          $_SESSION['nome'] = $row['nome'];
          $_SESSION['tipo'] = $row['tipo'];
        }
      }
      $conn->close();

      if(!$logged) alert("Credenziali errate!");

    } else alert("Non sono riuscito a collegarmi al DB!");

  }

  if(!$logged) form();
  else {
    echo "<script>location.href = 'index.php';</script>";
    echo "Stai per venire reindirizzato, se non accade nulla <a href='http://$host$uri/index.php'>clicca qui</a>...";
  }
  
  require_once '../footer.php';
?>