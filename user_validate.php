<?php require_once 'header-top.php' ?>

<?php
  redirectAdminPage();
  $id = 0;

?>
<?php
$conn = connect_db();

$query = "SELECT codice_fiscale, CONCAT(nome,' ', cognome) AS nominativo, genere, data_nascita, residenza, ruolo, email, telefono, immagine_documento FROM `dati_persone` WHERE is_verificato=0";
$result = $conn -> query($query);
$conn -> close();


?>
<script type="text/javascript">
     $(function() {
      $("#table_validate").dataTable({
          "iDisplayLength": 10,
          "aLengthMenu": [[10, 25, 50, 100,  -1], [10, 25, 50, 100, "All"]]
         });
     });
</script>

<div class="container fill">
  <h2 class="h2">Utenti in attesa di verifica</h2>
  <div class="table-responsive">
    <table id="table_validate" class="table table-bordered table-striped table-hover">
      <caption>List of users</caption>
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Codice Fiscale</th>
          <th scope="col">Nominativo</th>
          <th scope="col">Genere</th>
          <th scope="col">Data Nascita</th>
          <th scope="col">Residenza</th>
          <th scope="col">Email</th>
          <th scope="col">Telefono</th>
          <th scope="col">Ruolo</th>
          <th scope="col">Documento</th>
          <th scope="col">Abilita</th>
        </tr>
        <tbody>
           <?php while($row = $result->fetch_array(MYSQLI_ASSOC)){
                  $image = $row['immagine_documento'];
                  $image_src = "user_docs/".$image;
                  $id++;
             ?>
              <tr class="align-middle">
                <td><?php echo $id ?></td>
                <td id = "cod_fis_<?php echo $id ?>" ><?php echo $row['codice_fiscale'] ?></td>
                <td><?php echo $row['nominativo'] ?></td>
                <td><?php echo genere($row['genere']); ?></td>
                <td><?php echo $row['data_nascita'] ?></td>
                <td><?php echo $row['residenza'] ?></td>
                <td><?php echo $row['email'] ?></td>
                <td><?php echo $row['telefono'] ?></td>
                <td><?php echo ucfirst( $row['ruolo']) ?></td>
                <td class="text-center"><button id="modal_button_<?php echo $id ?>" type="button" class="btn btn-warning">Mostra</button>
                  <div class="modal fade" id="document_modal_<?php echo $id ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLongTitle">Documento</h5>
                        </div>
                        <div class="modal-body">
                          <img src="<?php echo $image_src; ?>" alt="Documento">
                        </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </td>
                <td><button id="validate_button_<?php echo $id ?>" type="button" class="btn btn-success">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16">
                      <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                      <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"/>
                    </svg>
                </button>
                    <button id="delete_button_<?php echo $id ?>" type="button" class="btn btn-danger" >
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                      </svg>
                    </button></td>
              </tr>
           <?php } ?>
        </tbody>
      </thead>
    </table>
  </div>
</div>



<script type="text/javascript">

$(document).ready(function() {
  $("button[id^='validate_button_']").click(function(){
        var id = event.target.id;
        var identifier = id.replace("validate_button_", "#cod_fis_");
        $.ajax({
        type: "GET",
        url: "ajax.php",
        data: {
            action_on_user: "validate",
            codice_fiscale: $(identifier).text()
        },
        success: function(result) {
            location.reload();
        },
        error: function(result) {
            location.reload();
        }
      });
     });

    $("button[id^='delete_button_']").click(function(){
        var id = event.target.id;
        var identifier = id.replace("delete_button_", "#cod_fis_");
        $.ajax({
        type: "GET",
        url: "ajax.php",
        data: {
            action_on_user: "delete",
            codice_fiscale: $(identifier).text()
        },
        success: function(result) {
            location.reload();
        },
        error: function(result) {
            location.reload();
        }
        });
      });

      $("button[id^='modal_button_']").click(function(){
          var id = event.target.id;
          var identifier = id.replace("modal_button_", "#document_modal_");
          $(identifier).modal("show");
        });

        $('#table_id').DataTable();
});

</script>


<?php require_once 'footer.php' ?>
