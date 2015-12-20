<?php 
  require_once 'header.php';
?>
  <div class="row row-centered">
    <h1>Gestione Utenti realizzata in PHP</h1>
  </div>
  <div class="row row-centered">
    <div class="col-xs-6 col-centered">
      <button class="btn btn-lg btn-primary btn-block" onclick="location.href='./CSV/'"><span class="glyphicon glyphicon-file"></span> Gestione con CSV</button>
    </div>
    <div class="col-xs-6 col-centered">
      <button class="btn btn-lg btn-primary btn-block" onclick="location.href='./DB/'"><span class="glyphicon glyphicon-oil"></span> Gestione con DB</button>
    </div>
  </div>
<?php
  require_once 'footer.php';
?>