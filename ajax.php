<?php
  require_once 'functions.php';
     $conn = connect_db();

 if(isset($_GET['action_on_user']) ){
   switch($_GET['action_on_user']){
        case "validate":
            $query = "UPDATE dati_persone SET is_verificato='1' WHERE codice_fiscale = ?";
            break;

        case "delete":
            $query = "DELETE FROM dati_persone WHERE codice_fiscale = ?";
            break;

        default:
          break;
   }

   $stmt = $conn -> prepare($query);
   $stmt -> bind_param("s", $conn->real_escape_string(strtoupper($_GET['codice_fiscale'])) );
   $stmt -> execute();
   $stmt -> close();
 }

 if(isset($_GET['action_on_queue']) ){
   switch($_GET['action_on_queue']){

        case "edit_queue":
          $query = "UPDATE dati_code SET data_inizio = ?, data_fine = ?, is_attiva = ? WHERE codice_coda = ?";
          $stmt = $conn -> prepare($query);
          $stmt -> bind_param("ssii", $_GET['data_inizio'],  $_GET['data_fine'], intval($_GET['is_attiva']),  intval($_GET['codice_coda']));
          $stmt -> execute();
          $stmt -> close();
          break;

        case "remove_queue":
            $query = "DELETE FROM dati_code WHERE codice_coda = ?";
            $stmt = $conn -> prepare($query);
            $stmt -> bind_param("i", intval($_GET['codice_coda']) );
            $stmt -> execute();
            $stmt -> close();
            break;

        case "subscribe_queue":
            $query = "INSERT INTO dati_prenotazioni (codice_persona, codice_sede, codice_coda, esito) VALUES (?, ?, ?, 0)";
            $stmt = $conn -> prepare($query);
            $stmt -> bind_param("sss", $_GET['codice_persona'], $_GET['codice_sede'], $_GET['codice_coda'] );
            $stmt -> execute();
            $stmt -> close();
            break;

        default:
          break;
   }
 }

 if(isset($_GET['action_on_reservation']) ){
   switch($_GET['action_on_reservation']){

        case "edit_reservation":
            $query = "UPDATE dati_prenotazioni SET codice_sede = ?, datetime = ?, esito = ? WHERE codice_prenotazione = ?";
            $stmt = $conn -> prepare($query);
            $stmt -> bind_param("ssii", $_GET['codice_sede'], $_GET['datetime'], intval($_GET['esito']), intval($_GET['codice_prenotazione']) );
            $stmt -> execute();
            $stmt -> close();
            break;

        case "remove_reservation":
            $query = "DELETE FROM dati_prenotazioni WHERE codice_prenotazione = ?";
            $stmt = $conn -> prepare($query);
            $stmt -> bind_param("i", intval($_GET['codice_prenotazione']) );
            $stmt -> execute();
            $stmt -> close();
            break;

        default:
          break;
   }
 }

//UPDATE DELLE PRENOTAZIONI DALLA TABELLA DI admin.php

 if(isset($_GET['parameter']) ){
   $query = "SELECT codice_prenotazione, codice_persona, codice_sede, datetime, esito, range_eta_fine, range_eta_inizio FROM dati_prenotazioni, dati_code
             WHERE dati_prenotazioni.codice_coda = dati_code.codice_coda AND dati_prenotazioni.codice_coda = ? AND dati_code.is_attiva = 1";
   $stmt = $conn -> prepare($query);
   $stmt -> bind_param("s", intval($_GET['parameter']) );
   $stmt -> execute();
   $result = $stmt->get_result();

   $query = "SELECT codice_sede FROM dati_sedi";
   $lista_sedi = $conn -> query($query);
   for ($set = array(); $row = $lista_sedi->fetch_assoc(); $set[] = $row['codice_sede']);
   $stmt -> close();
 ?>

     <h2 class="h2">Prenotazioni dei Cittadini</h2>
     <div class="table-responsive">
     <table id="table_reservation" class="table table-bordered table-striped table-hover">
       <caption>Prenotazioni Registrate per la coda selezionata</caption>
       <thead>
         <tr>
           <th scope="col">#</th>
           <th scope="col">Persona</th>
           <th scope="col">Sede</th>
           <th scope="col">Coda (Range Eta)</th>
           <th scope="col">Data</th>
           <th scope="col">Esito</th>
           <th scope="col">Azioni</th>
         </tr>
         <tbody>
         <?php while($row = $result->fetch_array(MYSQLI_ASSOC)){ ?>
                <tr class="align-middle">
                 <td id = "codice_prenotazione_<?php echo $row['codice_prenotazione'] ?>"><?php echo $row['codice_prenotazione'] ?></td>
                 <td><?php echo $row['codice_persona'] ?></td>
                 <td>
                   <select class="form-select" aria-label="Scelta della sede" id="sede_selector_<?php echo $row['codice_prenotazione'] ?>">
                     <option selected><?php echo $row['codice_sede'] ?></option>
                     <?php foreach($set as $sede){
                          if($sede == $row['codice_sede'])
                              continue;
                       ?>
                       <option value="<?php echo $sede ?>"><?php echo $sede ?></option>
                     <?php } ?>
                   </select>
                 </td>
                 <td><?php echo (date('Y')-$row['range_eta_fine']) . "-" . (date('Y')-$row['range_eta_inizio']) . " Anni" ?></td>
                 <td>
                   <input id="datetime_<?php echo $row['codice_prenotazione'] ?>" type="datetime-local" name="datetime" value="<?php echo str_replace(" ", "T", $row['datetime']) ?>">
                 </td>
                 <td><input id="esito_<?php echo $row['codice_prenotazione'] ?>" name="esito" type="number" value="<?php echo $row['esito'] ?>" min=0 max=1>
                 </td>
                 <td class="text-center"><button id="reservation_edit_button_<?php echo $row["codice_prenotazione"] ?>" type="button" class="btn btn-warning edit_button">
                   <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                     <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
                   </svg></button>
                     <button id="reservation_remove_button_<?php echo $row["codice_prenotazione"] ?>" type="button" class="btn btn-danger delete_button">
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

<?php }

   $conn -> close();


 ?>
