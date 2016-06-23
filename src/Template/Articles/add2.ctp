<h1>Add Article TESTER</h1>
<!-- Para probar I18N -->
<h2><?= __('Popular Articles') ?></h2>
<h2><?= __("Hello, my name is {0}, I'm {1} years old", ['Sara', 12]) ?></h2>
<h2><?= __('{0,plural,=0{No records found }=1{Found 1 record} other{Found # records}}', [1]) ?></h2>

<?php
    echo "Today is " . date("Y/m/d h:i:s") . "<br>";
    echo "Today is " . date("Y.m.d") . "<br>";
    echo "Today is " . date("Y-m-d") . "<br>";
    echo "Today is " . date("l");

    // Prints the day
    echo date("l") . "<br>";

    // Prints the day, date, month, year, time, AM or PM
    echo date("l jS \of F Y h:i:s A");
?>
