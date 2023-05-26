<?php require_once 'header-top.php' ?>
<?php redirectAdminPage(); ?>
<?php
$id = 0;
function sql_query(){
$conn = connect_db();
$query = "SELECT * FROM `dati_code` ORDER BY `is_attiva` DESC";
$result_code = $conn -> query($query);
$conn -> close();

return $result_code;
}


//Invio Dati Creazione della Coda
if( isset($_POST['submit']) and $_POST['submit']=="CREATE" ){
  $conn = connect_db();

  $stmt = $conn -> prepare("
  INSERT INTO `dati_code`(`data_inizio`, `data_fine`, `ruolo`, `range_eta_inizio`, `range_eta_fine`, `is_attiva`) VALUES (?,?,?,?,?,1)");
  $stmt -> bind_param("sssss",  $_POST['data_inizio_coda'],  $_POST['data_fine_coda'], $_POST['ruolo'], $_POST['range_eta_inizio'], $_POST['range_eta_fine']);
  $stmt -> execute();
  $stmt -> close();

  $conn -> close();
  echo "<div class='alert alert-success' role='alert'>
  Nuova Coda di Prenotazione Registrata!
    </div>";
}

?>

<nav id="navbar" class="navbar light navbar-expand justify-content-center sticky-top" style="background-color: white;">
  <ul>
    <li><a class="nav-link" href="user_validate.php">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-people-fill" viewBox="0 0 16 16">
        <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
        <path fill-rule="evenodd" d="M5.216 14A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1h4.216z"/>
        <path d="M4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z"/>
      </svg>
      &nbsp;Validazione Utenti
    </a></li>
    <li><a class="nav-link" id="create_queue_button">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle-fill" viewBox="0 0 16 16">
        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z"/>
      </svg>
      &nbsp;Creazione Coda di Prenotazione</a></li>
    <li><a class="nav-link" href="#manage_queue">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
        <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
      </svg>
      &nbsp;Modifica Parametri Coda</a></li>
    <li><a class="nav-link" href="#view_reservation">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
      </svg>
      &nbsp;Visualizzazione Utenti in Coda</a></li>
  </ul>
  <i class="bi bi-list mobile-nav-toggle"></i>
</nav>

<script type="text/javascript">
     $(function() {
      $("table[id^='table_']").dataTable({
          "iDisplayLength": 10,
          "aLengthMenu": [[10, 25, 50, 100,  -1], [10, 25, 50, 100, "All"]]
         });
     });
</script>

<div id="manage_queue" class="container" >
  <h2 class="h2">Code di Prenotazione</h2>
  <div class="table-responsive">
    <table id="table_queue" class="table table-bordered table-striped table-hover">
      <caption>Elenco Code Prenotazione</caption>
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Data Inizio</th>
          <th scope="col">Data Fine</th>
          <th scope="col">Ruolo</th>
          <th scope="col">Range Inizio Età</th>
          <th scope="col">Range Fine Età</th>
          <th scope="col">Attiva</th>
          <th scope="col">Azioni</th>
        </tr>
        <tbody>
        <?php
        $result_code = sql_query();
        while($row = $result_code->fetch_array(MYSQLI_ASSOC)){ ?>
              <tr class="align-middle">
                <td id = "queue_codice_coda_<?php echo $row['codice_coda'] ?>" ><?php echo $row['codice_coda'] ?></td>
                <td>
                  <input id="queue_data_inizio_<?php echo $row['codice_coda'] ?>" type="date" value="<?php echo $row['data_inizio'] ?>">
                </td>
                <td>
                  <input id="queue_data_fine_<?php echo $row['codice_coda'] ?>" type="date" value="<?php echo $row['data_fine'] ?>">
                </td>
                <td><?php echo ucfirst($row['ruolo']); ?></td>
                <td><?php echo $row['range_eta_inizio'] ?></td>
                <td><?php echo $row['range_eta_fine'] ?></td>
                <td>
                <input id="queue_is_attiva_<?php echo $row['codice_coda'] ?>" type="number" value="<?php echo $row['is_attiva'] ?>" min=0 max=1></td>
                <td class="text-center"><button id="queue_edit_button_<?php echo $row['codice_coda'] ?>" type="button" class="btn btn-warning queue_edit_button">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                    <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
                  </svg></button>
                  <?php //LE CODE NON DOVREBBERO CONTENERE UTENTI PER ESSERE MODIFICATE/ELIMINATE ?>
                    <button id="queue_remove_button_<?php echo $row['codice_coda'] ?>" type="button" class="btn btn-danger">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                        <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"/>
                      </svg>
                    </button>
                </td>
              </tr>
            <?php } ?>
        </tbody>
      </thead>
    </table>
  </div>
</div>

<br><br>
<div id="select_prenotazioni" class="container" >
  <h2 class="h2">Utenti Registrati in coda</h2>
  <div class="row" style="padding-bottom: 10px;">
      <div class="col-sm-4">
        <select class="form-select" aria-label="Scelta della coda" id="queue_selector">
          <option selected>Seleziona una coda</option>
          <?php
          $result_code = sql_query();
          while($row = $result_code->fetch_array(MYSQLI_ASSOC)){
            $id++;?>
            <option value="<?php echo $row['codice_coda'] ?>"><?php echo (date('Y')-$row['range_eta_fine']) . "-" . (date('Y')-$row['range_eta_inizio']) . " Anni" ?></option>
          <?php } ?>
        </select>
      </div>
      <div class="col-sm-4">
        <button id="search_button" type="button" class="btn btn-primary">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
          </svg>
        </button>
      </div>
  </div>



<?php //Programmare campo datetime e esito modificabili (input), aggiornabili quindi con il tasto edit ?>
  <div id="view_reservation" class="container" >
  </div>

<!-- Modals -->
<div class="modal fade" id="create_queue_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Crea una nuova coda di prenotazione</h5>
      </div>
      <div class="modal-body">
        <form id="create_queue_form" name="create_queue_form" class="form"  action="admin.php" method="POST" onsubmit="return validateForm()">
            <div class="form-group">
              <h5 class="h5">Date Relative alla coda</h5>
                  <div class="input-group">
                      <div class="input-group-prepend">
                          <div class="input-group-text">Data Inizio Coda</div>
                      </div>
                        <input class="form-control" id="data_inizio_coda" type="date" name="data_inizio_coda" required>

                      <div class="input-group-prepend">
                          <div class="input-group-text">Data Fine Coda</div>
                      </div>
                      <input class="form-control" id="data_fine_coda" type="date" name="data_fine_coda" required>
                  </div>
            </div>
            <br>

            <div class="form-group">
              <h5 class="h5">Range Età</h5>
                  <div class="input-group">
                      <div class="input-group-prepend">
                          <div class="input-group-text">Inizio Range Età Iscrizione</div>
                      </div>
                      <select class="form-control" name="range_eta_inizio" id="range_eta_inizio" required></select>
                      <div class="input-group-prepend">
                          <div class="input-group-text">Fine Range Età Iscrizione</div>
                      </div>
                      <select class="form-control" name="range_eta_fine" id="range_eta_fine" required></select>
                  </div>
              </div>

            <br>
            <div class="form-group">
                    <h5 class="h5">Categorie della Coda</h5>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" id="ruolo1" name="ruolo" value="cittadino">
                      <label class="form-check-label" for="genere1">Aperta ai cittadini</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" id="ruolo2" name="ruolo" value="prioritario">
                      <label class="form-check-label" for="genere2">Riservata a categorie prioritarie</label>
                    </div>
                </div>
            <br>
          <div class="mb-3">
            <input class="btn btn-primary" type="submit" id="modal_create_queue_button" name="submit" value="CREATE">
          </div>
        </form>

      </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
  $("#create_queue_button").click(function(){
      $("#create_queue_modal").modal("show");
    });

    $(document).on('click', '.delete_button',function(){
          var id = event.target.id;
          var identifier = id.replace("reservation_remove_button_", "#codice_prenotazione_");
          $.ajax({
          type: "GET",
          url: "ajax.php",
          data: {
              action_on_reservation: "remove_reservation",
              codice_prenotazione: $(identifier).text()
          },
          success: function(result) {
              location.reload();
          },
          error: function(result) {
              location.reload();
          }
        });
       });

       $("#search_button").click(function(){
         var e = document.getElementById("queue_selector");
         var search_queue = e.value;
         $.ajax({
         type: "GET",
         url: "ajax.php",
         dataType: "html",
         data: {
             parameter: search_queue
         },
         success: function(data){
           $('#view_reservation').html(data);
           $("#table_reservation").dataTable({
                "iDisplayLength": 10,
                "aLengthMenu": [[10, 25, 50, 100,  -1], [10, 25, 50, 100, "All"]]
               });
         }
       });
      });

      $("button[id^='queue_remove_button_']").click(function(){
            var id = event.target.id;
            var identifier = id.replace("queue_remove_button_", "#queue_codice_coda_");
            $.ajax({
            type: "GET",
            url: "ajax.php",
            data: {
                action_on_queue: "remove_queue",
                codice_coda: $(identifier).text()
            },
            success: function(result) {
                location.reload();
            },
            error: function(result) {
                location.reload();
            }
          });
         });

        $("button[id^='queue_edit_button_']").click(function(){
               var id = event.target.id;
               var codice_coda = id.replace("queue_edit_button_", "#queue_codice_coda_");
               var data_inizio = id.replace("queue_edit_button_", "#queue_data_inizio_");
               var data_fine = id.replace("queue_edit_button_", "#queue_data_fine_");
               var is_attiva = id.replace("queue_edit_button_", "#queue_is_attiva_");

               $.ajax({
               type: "GET",
               url: "ajax.php",
               data: {
                   action_on_queue: "edit_queue",
                   codice_coda: $(codice_coda).text(),
                   data_inizio: $(data_inizio).val(),
                   data_fine: $(data_fine).val(),
                   is_attiva: $(is_attiva).val()
               },
               success: function(result) {
                   location.reload();
               },
               error: function(result) {
                   location.reload();
               }
             });
            });


});

$(document).on('click', '.edit_button',function(){
      var id = event.target.id;
      var codice_prenotazione = id.replace("reservation_edit_button_", "#codice_prenotazione_");
      var codice_sede = id.replace("reservation_edit_button_", "#sede_selector_");
      var date_time = id.replace("reservation_edit_button_", "#datetime_");
      var esito = id.replace("reservation_edit_button_", "#esito_");

      //UTC Time Converter{
           $(date_time).oninput = function() {
            var datetimeLocal =  $(date_time).value;
            var datetimeUTC = moment.utc(datetimeLocal).format();
            $(date_time).value = datetimeUTC;
          }

      $.ajax({
      type: "GET",
      url: "ajax.php",
      data: {
          action_on_reservation: "edit_reservation",
          codice_prenotazione: $(codice_prenotazione).text(),
          codice_sede: $(codice_sede).val(),
          datetime: $(date_time).val(),
          esito: $(esito).val()
      },
      success: function(result) {
          location.reload();
      },
      error: function(result) {
          location.reload();
      }
    });
   });
</script>
<script type="text/javascript">
function validateForm() {
  var data_inizio_coda = document.forms["create_queue_form"]["data_inizio_coda"].value;
  var data_fine_coda = document.forms["create_queue_form"]["data_fine_coda"].value;
  var range_eta_inizio = document.forms["create_queue_form"]["range_eta_inizio"].value;
  var range_eta_fine = document.forms["create_queue_form"]["range_eta_fine"].value;

  if(new Date(data_fine_coda) >= new Date(data_inizio_coda) && new Date(range_eta_fine) >= new Date(range_eta_inizio))
      return true;
   else
      return false;
 }
</script>
<script type="text/javascript">
    let startYear = 1800;
    let endYear = new Date().getFullYear();
    for (i = endYear; i > startYear; i--)
    {
      $("select[id^='range_eta_']").append($('<option />').val(i).html(i));
    }
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.19.3/moment-with-locales.min.js"></script>
<?php require_once 'footer.php' ?>
