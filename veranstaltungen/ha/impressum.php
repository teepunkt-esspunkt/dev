<?php
require_once 'functions.php';

$ausgabe['titel'] = 'Impressum';
include TEMPLATES . 'htmlkopf.phtml';
?>
Beteiligte Personen: <br><br>

<div>
    <div>
       <img src="<?= BILDER . 'albert.jpg' ?>" alt="Vadim" class="klein"> Vadim Nasyrov <br>
    </div>
    <div>
       <img src="<?= BILDER . 'barbar.webp' ?>" alt="Shayan" class="klein"> Shayan Hamzavi-Fard <br>
    </div>
    <div>
       <img src="<?= BILDER . 'man.webp' ?>" alt="Tarek" class="klein"> Tarek Saleh <br>
    </div>
</div>

<?php
include TEMPLATES . 'htmlfuss.phtml';