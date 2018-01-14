 <?php
 $idu = $_GET['idu']; 
 echo'
 <form action="odbierz.php?idu='.$idu.'" method="POST" 
 ENCTYPE="multipart/form-data">
 <input type="file" name="plik"/>
 <input type="submit" value="Wyślij plik"/>
 </form>';
 ?>

