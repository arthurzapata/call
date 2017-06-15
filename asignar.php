<?php
$tipo = isset($_POST['tipo']);
echo '<br>';
echo $tipo;
echo '<br>';

$checkbox2 = isset($_POST['casilla']) ? $_POST['casilla'] : NULL;

/*if (isset($POST)*/
for ($i=0;$i<sizeof($checkbox2);$i++)
{
	echo $checkbox2[$i];
	echo '<br>';
}
?>
