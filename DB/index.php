<?php
  session_start();

  function alert ($messaggio) { ?>
    <div class="six columns">
      <p class="alert alert-error u-full-width">
        <i class="fa fa-exclamation-triangle fa-lg"></i>&nbsp; <?php echo $messaggio; ?>
      </p>
    </div>
  <?php }

  function logout_button() { ?>
    <a style="width:250px;" class="button button-primary" href="logout.php"><i class="fa fa-sign-out fa-lg"></i>&nbsp;&nbsp;&nbsp;Logout</a>
  <?php }

  function back_button() { ?>
    <a style="width:250px;" class="button button-primary" href="index.php"><i class="fa fa-arrow-left fa-lg"></i>&nbsp;&nbsp;&nbsp;Back to Menu</a>
  <?php }

  function menu_admin() { ?>
    <button style="width:250px;" class="button" onclick="cambia('A');"><i class="fa fa-user fa-lg"></i>&nbsp;&nbsp;&nbsp;Aggiungi Utente</button>
    <button style="width:250px;" class="button" onclick="cambia('E');"><i class="fa fa-list-ol fa-lg"></i>&nbsp;&nbsp;&nbsp;Elenca gli Utenti</button>
    <button style="width:250px;" class="button" onclick="cambia('M');"><i class="fa fa-pencil fa-lg"></i>&nbsp;&nbsp;&nbsp;Modifica degli Utenti</button>
    <button style="width:250px;" class="button" onclick="cambia('R');"><i class="fa fa-trash-o fa-lg"></i>&nbsp;&nbsp;&nbsp;Rimuovi Utenti</button>
    <form id="fcambia" method="POST">
      <input type='hidden' name='action' id='action'>
    </form>
    <script>
      function cambia(cosa) {
        document.getElementById('action').value = cosa;
        document.getElementById('fcambia').submit();
      }
    </script>
  <?php }

  function menu_user() { ?>
    <button style="width:250px;" class="button" onclick="cambia('M');"><i class="fa fa-pencil fa-lg"></i>&nbsp;&nbsp;&nbsp;Modifica la Password</button>
    <form id="fcambia" method="POST">
      <input type='hidden' name='action' id='action'>
    </form>
  <?php }

  function elenco($servername,$dbname) { 
    echo '<div id="users">';
    echo '<center><input class="search" placeholder="Cerca..." /></center>';
    echo '<table class="u-full-width">';
    echo '<thead>';
    echo '<tr>';
    echo '<th class="sort" data-sort="user"><i class="fa fa-sort"></i>&nbsp;&nbsp;User</th>';
    echo '<th class="sort" data-sort="pass"><i class="fa fa-sort"></i>&nbsp;&nbsp;Password</th>';
    echo '<th class="sort" data-sort="nome"><i class="fa fa-sort"></i>&nbsp;&nbsp;Nome</th>';
    echo '<th class="sort" data-sort="tipo"><i class="fa fa-sort"></i>&nbsp;&nbsp;Tipo</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody class="list">';

    $conn = new mysqli($servername, "", "", $dbname);
    if (!($conn->connect_error)) {
      $result = $conn->query("SELECT * FROM utenti");
      while(($row = $result->fetch_assoc())) {
        echo '<tr>';
        echo '<td class="user">' . $row['user'] . '</td>';
        echo '<td class="pass">' . $row['pass'] . '</td>';
        echo '<td class="nome">' . $row['nome'] . '</td>';
        echo '<td class="tipo">' . $row['tipo'] . '</td>';
        echo '</tr>';
      }
      $conn->close();
    } else alert("Non sono riuscito a collegarmi al DB!");
            
    echo '</tbody>';
    echo '</table>';
    echo '</div>';
    echo '<script>';
    echo 'var options = {';
    echo "valueNames: [ 'user', 'pass', 'nome', 'tipo' ]";
    echo '};';
    echo "var userList = new List('users', options);";
    echo '</script>';
  }

  function form_aggiungi() { ?>
    <form method="POST">
      <div class="row">
        <input type="hidden" name="action" value="A">
        <label>Nome</label>
        <input class="u-full-width" type="text" name="nome" required autofocus />
        <label>User</label>
        <input class="u-full-width" type="text" name="user" required />
        <label>Password</label>
        <input class="u-full-width" type="password" name="pass" required />
        <center>
          <select name="tipo">
            <option value="U">User</option>
            <option value="A">Admin</option>
          </select>
        </center>
        <center>
          <input class="button" type="submit" value="Aggiungi" />
          <input class="button" type="reset" value="Reset" />
        </center>
      </div>
    </form>
  <?php }

  function aggiungi_utente($filename) {
    $libero = true;

    $conn = new mysqli($servername, "", "", $dbname);
    if (!($conn->connect_error)) {
      $result = $conn->query("SELECT * FROM utenti WHERE user='" . $_POST['user'] . "'");
      if ($result->num_rows > 0) $libero = false;
      
      if($libero) {
        $conn->query("INSERT INTO utenti VALUES('".$_POST['user']."', '".$_POST['pass']."', '".$_POST['nome']."', '".$_POST['tipo']."')");
        echo "<p>L'utente " . $_POST['user'] . " è stato aggiunto correttamente.</p>";
      }

      $conn->close();
    } else alert("Non sono riuscito a collegarmi al DB!");
  }

  function form_rimuovi($filename) { 

    echo '<div id="users">';
    echo '<center><input class="search" placeholder="Cerca..." /></center>';
    echo '<table class="u-full-width">';
    echo '<thead>';
    echo '<tr>';
    echo '<th class="sort" data-sort="user"><i class="fa fa-sort"></i>&nbsp;&nbsp;User</th>';
    echo '<th class="sort" data-sort="nome"><i class="fa fa-sort"></i>&nbsp;&nbsp;Nome</th>';
    echo '<th class="sort" data-sort="tipo"><i class="fa fa-sort"></i>&nbsp;&nbsp;Tipo</th>';
    echo '<th></th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody class="list">';

    if (($handle = @fopen($filename, "r")) !== FALSE) {
      while (($linecsv = fgetcsv($handle, 1000, ";")) !== FALSE) {
        echo '<tr>';
        echo '<td class="user">' . $linecsv[0] . '</td>';
        echo '<td class="nome">' . $linecsv[2] . '</td>';
        echo '<td class="tipo">' . $linecsv[3] . '</td>';
        echo "<td class='trash' onclick='" . 'cambia("' . $linecsv[0] . '");' . "'><i class='fa fa-trash-o'></i></td>";
        echo '</tr>';
      }
      fclose($handle);
    } else alert("Non sono riuscito ad aprire il file degli utenti!");
            
    echo '</tbody>';
    echo '</table>';
    echo '</div>';
    echo '<script>';
    echo 'var options = {';
    echo "valueNames: [ 'user', 'pass', 'nome', 'tipo' ]";
    echo '};';
    echo "var userList = new List('users', options);";
    echo '</script>';

    ?>
    <form id="fcambia" method="POST">
      <input type='hidden' name='action' value='R' />
      <input type='hidden' name='user' id='user' />
    </form>
    <script>
      function cambia(cosa) {
        document.getElementById('user').value = cosa;
        document.getElementById('fcambia').submit();
      }
    </script>
  <?php }

  function rimuovi_utente($filename) {
    if (($handle = @fopen($filename, "r")) !== FALSE) {
      $temp = fopen("temp.csv","w");
      while (($linecsv = fgetcsv($handle, 1000, ";")) !== FALSE)
        if(!($_POST['user'] == $linecsv[0]))
          fputcsv($temp,$linecsv,";");
      fclose($handle);
      fclose($temp);
      unlink($filename);
      rename("temp.csv",$filename);
      echo "<p>L'utente " . $_POST['user'] . " è stato eliminato correttamente.</p>";
    } else alert("Non sono riuscito ad aprire il file degli utenti!");
  }

  function form_modifica($filename) { 

    echo '<div id="users">';
    echo '<table class="u-full-width">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>User</th>';
    echo '<th>Password</th>';
    echo '<th>Nome</th>';
    echo '<th>Tipo</th>';
    echo '<th></th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody class="list">';

    if (($handle = @fopen($filename, "r")) !== FALSE) {
      while (($linecsv = fgetcsv($handle, 1000, ";")) !== FALSE) {
        echo "\n\n";
        
        echo '<tr>';
        echo '<form method="POST">';
        echo '<td>' . $linecsv[0] . '</td>';
        echo '<td><input name="pass" type="text" value="' . $linecsv[1] . '" required /></td>';
        echo '<td><input name="nome" type="text" value="' . $linecsv[2] . '" required /></td>';
        echo '<td>';
        if($linecsv[3] == 'A') {
          echo '<select name="tipo">';
          echo '<option value="A">Admin</option>';
          echo '<option value="U">User</option>';
          echo '</select>';
        } else {
          echo '<select name="tipo">';
          echo '<option value="U">User</option>';
          echo '<option value="A">Admin</option>';
          echo '</select>';
        }
        echo '</td>';
        echo '<input type="hidden" name="user" value="' . $linecsv[0] . '" />';
        echo '<input type="hidden" name="action" value="M" />';
        echo '<td><button type="submit"><i class="fa fa-check"></i></button></td>';
        echo '</form>';
        echo '</tr>';
        
        echo "\n\n";
      }
      fclose($handle);
    } else alert("Non sono riuscito ad aprire il file degli utenti!");
            
    echo '</tbody>';
    echo '</table>';
    echo '</div>';
  }

  function modifica_utente($filename) {
    if (($handle = @fopen($filename, "r")) !== FALSE) {
      $temp = fopen("temp.csv","w");
      while (($linecsv = fgetcsv($handle, 1000, ";")) !== FALSE) {
        if($_POST['user'] == $linecsv[0]) {
          $linecsv[1] = $_POST['pass'];
          $linecsv[2] = $_POST['nome'];
          $linecsv[3] = $_POST['tipo'];
        }
        fputcsv($temp,$linecsv,";");
      }
      fclose($handle);
      fclose($temp);
      unlink($filename);
      rename("temp.csv",$filename);
      echo "<p>L'utente " . $_POST['user'] . " è stato modificato correttamente.</p>";
    } else alert("Non sono riuscito ad aprire il file degli utenti!");
  }
  
  require_once '../header.php';

  $servername = "localhost";
  $dbname = "my_tomz";
  $logged = false;

  if(isset($_SESSION['user'], $_SESSION['tipo'], $_SESSION['logged'], $_SESSION['nome']))
    if($_SESSION['logged'])
      $logged = true;

  if(!$logged) {
    echo "<script>location.href = 'login.php';</script>";
    echo "Stai per venire reindirizzato, se non accade nulla <a href='login.php'>clicca qui</a>...";
  } else {
    echo "<h2>Bentornato, " . $_SESSION['nome'] . "!</h2>";

    if($_SESSION['tipo'] == "A") {

      if(isset($_POST['action'])) {
        switch($_POST['action']) {

          case 'E':
            elenco($servername,$dbname);
            break;

          case 'A':
            if(!isset($_POST['nome'], $_POST['user'], $_POST['pass'], $_POST['tipo'])) form_aggiungi();
            else aggiungi_utente($filename);
            break;

          case 'M':
            if(!isset($_POST['nome'], $_POST['user'], $_POST['pass'], $_POST['tipo'])) form_modifica($filename);
            else modifica_utente($filename);
            break;

          case 'R':
            if(!isset($_POST['user'])) form_rimuovi($filename);
            else rimuovi_utente($filename);
            break;

          default:
            echo '<p>Scelta non valida</p>';
        }

        back_button();

      } else {
        echo "<p>Sei un utente di tipo admin.</p>";
        menu_admin();
      }

    } else {

      if(isset($_POST['action'])) {
        switch($_POST['action']) {

          default:
            echo '<p>Scelta non valida</p>';
        }
        back_button();
      } else {
        echo "<p>Sei un utente normale.</p>";
        //menu_user();
      }
    }

    logout_button();
  }
  
  require_once '../footer.php';
?>
