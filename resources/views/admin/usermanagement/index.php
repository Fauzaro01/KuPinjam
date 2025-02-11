<?php 

function hitungMundur($nomor) {
    echo $nomor."<br>";
    if ($nomor > 0) {
        hitungMundur($nomor - 1);
    }
}

hitungMundur(10);

?>