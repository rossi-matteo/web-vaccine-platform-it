<?php require_once 'header-top.php' ?>
<?php require_once 'functions.php' ?>
<?php redirectNotLogged(); ?>

<?php
$query = "SELECT codice_prenotazione, dati_prenotazioni.codice_sede, datetime, esito, range_eta_fine, range_eta_inizio, indirizzo_sede FROM dati_prenotazioni, dati_code, dati_sedi
          WHERE dati_prenotazioni.codice_coda = dati_code.codice_coda AND dati_prenotazioni.codice_persona = ? AND dati_prenotazioni.codice_sede = dati_sedi.codice_sede" ;
$conn = connect_db();
$stmt = $conn -> prepare($query);
$stmt -> bind_param("s", $_SESSION['utente'][0] );
$stmt -> execute();
$result = $stmt->get_result();


$query = "SELECT DISTINCT codice_coda, range_eta_inizio, range_eta_fine, dati_persone.ruolo AS role FROM dati_code, dati_persone
          WHERE ? NOT IN (SELECT codice_persona FROM dati_prenotazioni WHERE esito = 0)
          AND dati_code.is_attiva = 1 AND YEAR(dati_persone.data_nascita) BETWEEN range_eta_inizio AND range_eta_fine";
$stmt = $conn -> prepare($query);
$stmt -> bind_param("s", $_SESSION['utente'][0]);
$stmt -> execute();
$result_queue = $stmt->get_result();

$query = "SELECT codice_sede FROM dati_sedi";
$lista_sedi = $conn -> query($query);
for ($set = array(); $row = $lista_sedi->fetch_assoc(); $set[] = $row['codice_sede']);

$stmt -> close();
$conn -> close();
 ?>

<nav id="navbar" class="navbar light navbar-admin navbar-expand justify-content-center sticky-top" style="background-color: white;">
  <ul>
    <li><a class="nav-link" href="#your_reservations">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clock-history" viewBox="0 0 16 16">
          <path d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022l-.074.997zm2.004.45a7.003 7.003 0 0 0-.985-.299l.219-.976c.383.086.76.2 1.126.342l-.36.933zm1.37.71a7.01 7.01 0 0 0-.439-.27l.493-.87a8.025 8.025 0 0 1 .979.654l-.615.789a6.996 6.996 0 0 0-.418-.302zm1.834 1.79a6.99 6.99 0 0 0-.653-.796l.724-.69c.27.285.52.59.747.91l-.818.576zm.744 1.352a7.08 7.08 0 0 0-.214-.468l.893-.45a7.976 7.976 0 0 1 .45 1.088l-.95.313a7.023 7.023 0 0 0-.179-.483zm.53 2.507a6.991 6.991 0 0 0-.1-1.025l.985-.17c.067.386.106.778.116 1.17l-1 .025zm-.131 1.538c.033-.17.06-.339.081-.51l.993.123a7.957 7.957 0 0 1-.23 1.155l-.964-.267c.046-.165.086-.332.12-.501zm-.952 2.379c.184-.29.346-.594.486-.908l.914.405c-.16.36-.345.706-.555 1.038l-.845-.535zm-.964 1.205c.122-.122.239-.248.35-.378l.758.653a8.073 8.073 0 0 1-.401.432l-.707-.707z"/>
          <path d="M8 1a7 7 0 1 0 4.95 11.95l.707.707A8.001 8.001 0 1 1 8 0v1z"/>
          <path d="M7.5 3a.5.5 0 0 1 .5.5v5.21l3.248 1.856a.5.5 0 0 1-.496.868l-3.5-2A.5.5 0 0 1 7 9V3.5a.5.5 0 0 1 .5-.5z"/>
        </svg>
      &nbsp;Le tue prenotazioni
    </a></li>
    <li><a class="nav-link" href="#">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
      </svg>
      &nbsp;Iscrizione a una coda vaccinale</a></li>
    <li><a class="nav-link" href="#">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
        <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
      </svg>
      &nbsp;Anagrafica</a></li>
  </ul>
  <i class="bi bi-list mobile-nav-toggle"></i>
</nav>

<?php //Le tue prenotazioni, fatte e non, comprendono anche le iscrizioni a code senza data assegnata ?>
      <div id="your_reservations" class="container" >
        <h2 class="h2">Le tue prenotazioni</h2>
        <div class="table-responsive">
          <table id="table_user_reservations" class="table table-bordered table-striped table-hover">
            <caption>Elenco Appuntamenti</caption>
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Indirizzo Sede</th>
                <th scope="col">Descrizione Coda</th>
                <th scope="col">Data</th>
                <th scope="col">Esito</th>
              </tr>
              <tbody>
              <?php
              while($row = $result->fetch_array(MYSQLI_ASSOC)){ ?>
                    <tr class="align-middle">
                        <td><?php echo $row['codice_prenotazione'] ?></td>
                        <td><?php echo $row['codice_sede'] .", ". $row['indirizzo_sede']?></td>
                        <td><?php echo (date('Y')-$row['range_eta_fine']) . "-" . (date('Y')-$row['range_eta_inizio']) . " Anni" ?></td>
                        <td><?php echo $row['datetime'] ?></td>
                        <td class="text-center" ><?php echo svg_esito($row['esito']) ?></td>
                    </tr>
                  <?php } ?>
              </tbody>
            </thead>
          </table>
        </div>
      </div>

<?php //Le code a cui puoi registrarti, con dei semplici dati ?>
      <div id="avaiable_queue" class="container" >
        <h2 class="h2">Le code disponibili per l'iscrizione</h2>
        <div class="d-flex flex-row table-responsive">
          <table id="table_avaiable_queue" class="table table-bordered table-striped table-hover">
            <caption>Elenco Code a Te Disponibili</caption>
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Descrizione Coda</th>
                <th scope="col">Sede</th>
                <th scope="col">Iscriviti</th>
              </tr>
              <tbody>
              <?php
              while($row = $result_queue->fetch_array(MYSQLI_ASSOC)){ ?>
                    <tr class="align-middle">
                        <td id="codice_coda_<?php echo $row['codice_coda'] ?>"><?php echo $row['codice_coda'] ?></td>
                        <td><?php echo ucfirst($row['role']) . ", " . (date('Y')-$row['range_eta_fine']) . "-" . (date('Y')-$row['range_eta_inizio']) . " Anni" ?></td>
                        <td>
                          <select class="form-select" aria-label="Scelta della sede" id="sede_selector_<?php echo $row['codice_coda'] ?>">
                            <option selected>Seleziona una sede</option>
                            <?php foreach($set as $sede){
                              ?>
                              <option value="<?php echo $sede ?>"><?php echo $sede ?></option>
                            <?php } ?>
                          </select>
                        </td>
                        <td class="text-center">
                          <button id="subscribe_<?php echo $row['codice_coda'] ?>" type="button" class="btn btn-info">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                              <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                              <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                            </svg></button>
                        </td>
                    </tr>
                  <?php } ?>
              </tbody>
            </thead>
          </table>
        </div>
      </div>

<?php //Extra, Anagrafica di Profilo ?>


<script type="text/javascript">

$(document).ready(function() {

        $(function() {
         $("table[id^='table_']").dataTable({
             "iDisplayLength": 10,
             "aLengthMenu": [[10, 25, 50, 100,  -1], [10, 25, 50, 100, "All"]]
            });
        });

        $("button[id^='subscribe_']").click(function(){
              var id = event.target.id;
              var codice_coda = id.replace("subscribe_", "#codice_coda_");
              var codice_sede = id.replace("subscribe_", "#sede_selector_");

              $.ajax({
              type: "GET",
              url: "ajax.php",
              data: {
                  action_on_queue: "subscribe_queue",
                  codice_persona: "<?php echo $_SESSION['utente'][0] ?>",
                  codice_coda: $(codice_coda).text(),
                  codice_sede: $(codice_sede).val()
              },
              success: function(result){
                  location.reload();
              },
              error: function(result){
                  location.reload();
              }
            });
           });
});
</script>

<?php require_once 'footer.php' ?>
