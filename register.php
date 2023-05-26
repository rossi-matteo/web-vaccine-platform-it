<?php require_once 'header-top.php' ?>
<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$conn = new mysqli( INDIRIZZODB , NOME_UTENTE, PASSWORD, DB);
if($conn -> connect_errno > 0)
  header("Location_error.php?tipo=dbconn");

//Handling Errore nel caso di campi assenti, Codice Fiscale uguale etc...
if( isset($_POST['register']) and $_POST['register']=="REGISTRATI" ){

      /*Document Image Retrieval*/
      $name = $_FILES['immagine_documento']['name'];
      $target_dir = "user_docs/";
      $target_file = $target_dir . basename($_FILES["immagine_documento"]["name"]);

      // Select file type
      $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

      // Valid file extensions
      $extensions_arr = array("jpg","jpeg","png","gif","pdf");

      // Check extension
      if( in_array($imageFileType,$extensions_arr) ){
         // Upload file
         move_uploaded_file($_FILES['immagine_documento']['tmp_name'],$target_dir.$name);
      }

    $stmt = $conn -> prepare("
    INSERT INTO `dati_persone`(`codice_fiscale`, `nome`, `cognome`, `genere`, `data_nascita`, `residenza`, `ruolo`, `password`, `istante_passwd`, `is_verificato`, `email`, `telefono`, `immagine_documento`) VALUES (?,?,?,?,?,?,?,SHA1(?),NOW(),0,?,?,?) ");
    $stmt -> bind_param("sssisssssss", $conn->real_escape_string(strtoupper($_POST['codice_fiscale'])), $conn->real_escape_string($_POST['nome']), $conn->real_escape_string($_POST['cognome']),
    $_POST['genere'], $_POST['data_nascita'], $conn->real_escape_string($_POST['residenza']), $_POST['ruolo'], $conn->real_escape_string($_POST['password']), $conn->real_escape_string($_POST['email']), $conn->real_escape_string($_POST['telefono']), $conn->real_escape_string($name));
    $stmt -> execute();
    $stmt -> close();


}
 ?>

<script type="text/javascript">
      $(document).ready(function(e) {
        $('#password, #confirm_password').on('keyup', function () {
        if ($('#password').val() == $('#confirm_password').val()) {
            $('#message').html('Matching').css('color', 'green');
        }
        else $('#message').html('Not Matching').css('color', 'red');
        });

       $('#redirect').click(function () {
        window.location.href = "index.php";
        });
            });
</script>

<?php
  if( isset($_POST['register'])){
      echo '<script type="text/javascript">'
      . '$( document ).ready(function() {'
      . '$("#register_modal").modal("show");'
      . '});'
      . '</script>';
    }
 ?>


<section id="hero" class="blog">
  <div id="main">
    <div class="container">
      <div class="row">
        <div class=".col-6 .col-md-4">
          <form id="form" action="register.php" method="POST"  enctype="multipart/form-data">
                <p class="h3">Modulo di Registrazione</p>

                <div class="row">
                   <h5 class="h5">Anagrafiche</h5>
                		<div class="form-group col-md-2">
                  		<input class="form-control" id="cod_fis" type="text" name="codice_fiscale" placeholder="Codice Fiscale" required>
                		</div>

                		<div class="form-group col-md-2">
                  		<input class="form-control" id="nome" type="text" name="nome" maxlength="50" placeholder="Nome" required>
                		</div>

                		<div class="form-group col-md-2">
                  		<input class="form-control" id="cognome" type="text" name="cognome" maxlength="50" placeholder="Cognome" required>
                		</div>
                </div>
                <br>

                <div class="row">
                  <div class="col-auto">
                  		<div class="input-group">
                          <div class="input-group-prepend">
                              <div class="input-group-text">Data Nascita</div>
                          </div>
                      		<input class="form-control" id="data_nascita" type="date" name="data_nascita" required>
                  		</div>
                  </div>
                  <br>

                  <div class="col-auto">
                  		<div class="input-group">
                      		<input class="form-control" id="residenza" type="text" name="residenza" maxlength="50" placeholder="Residenza">
                  		</div>
                  </div>
                </div>
                <br>

                <div class="row">
                    <div class="col-auto">
                    		<h5 class="h5">Genere</h5>
                    		<div class="form-check">
                    			<input class="form-check-input" type="radio" id="genere1" name="genere" value="0">
                    			<label class="form-check-label" for="genere1">Maschio</label>
                        </div>
                        <div class="form-check">
                    			<input class="form-check-input" type="radio" id="genere2" name="genere" value="1">
                    			<label class="form-check-label" for="genere2">Femmina</label>
                        </div>
                        <div class="form-check">
                    			<input class="form-check-input" type="radio" id="genere3" name="genere" value="null">
                    			<label class="form-check-label" for="genere3">Altro/Non Specificato</label>
                    	  </div>
                    </div>
                    <div class="col-auto">
                        <h5 class="h5">Ruolo/Categoria</h5>
                    		<div class="form-check">
                    			<input class="form-check-input" type="radio" id="ruolo1" name="ruolo" value="cittadino">
                    			<label class="form-check-label" for="ruolo1">Cittadino</label>
                        </div>
                        <div class="form-check">
                    			<input class="form-check-input" type="radio" id="ruolo2" name="ruolo" value="prioritario">
                    			<label class="form-check-label" for="ruolo2">Prioritario</label>
                    	   </div>
                     </div>
                 </div>
                 <br>

                 <div class="row">
                    <h5 class="h5">Password</h5>
                  	<div class="form-group col-md-4">
                  		<input class="form-control" type="password" id="password" name="password" placeholder="Inserisci Password">
                  	</div>
                  	<div class="form-group col-md-4">
                  		<input class="form-control" type="password" id="confirm_password" name="confirm_password" placeholder="Conferma Password">
                      <span class="badge badge-secondary" id='message'></span>
                  	</div>
                 </div>
                 <br>

            		<!-- Segnalare Istante Cambiamento Password -->

                <div class="row">
                    <h5 class="h5">Contatti</h5>
                		<div class="form-group col-md-4">
                			<input class="form-control" type="email" id="email" name="email" placeholder="Indirizzo Email">
                		</div>

                		<div class="form-group col-md-4">
                			<input class="form-control" type="telefono" id="telefono" name="telefono" placeholder="Telefono">
                		</div>
                </div>
                <br>

            		<div class="row">
                      <div class="col-auto">
                      <h5 class="h5">Immagine Documento di Riconoscimento</h5>
                			<input class="form-control" type="file" id="file" name="immagine_documento" required>
                      </div>
            		</div>
                <br>

            		<div class="mb-3">
            			<input class="btn btn-primary" type="submit" id="registrati" name="register" value="REGISTRATI">
            		</div>
            	</div>
        </div>
      </div>
    </div>
   </div>

   <!-- Modal -->

   <!-- Button trigger modal -->

   <!-- Button trigger modal -->

   <!-- Modal -->
   <div class="modal fade" id="register_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
     <div class="modal-dialog" role="document">
       <div class="modal-content">
         <div class="modal-header">
           <h5 class="modal-title" id="exampleModalLongTitle">Registrazione avvenuta!</h5>
         </div>
         <div class="modal-body">
           I tuoi dati sono stati registrati. Il tuo profilo verrà abilitato se ritenuto idoneo dagli amministratori.
         </div>
         <div class="modal-footer">
           <button id="redirect" type="button" class="btn btn-primary">Torna alla pagina principale</button>
         </div>
       </div>
     </div>
   </div>




</section>

<?php require_once 'footer.php' ?>
