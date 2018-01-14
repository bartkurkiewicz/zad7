 <?php
 $idu = $_GET['idu']; 
 $podkatalog = $_GET['ik'];
 echo'
 <form action="odbierz1.php?idu='.$idu.'&ik='.$podkatalog.'" method="POST" 
 ENCTYPE="multipart/form-data">
 <input type="file" name="plik"/>
 <input type="submit" value="Wyślij plik"/>
 </form>';
 ?>

