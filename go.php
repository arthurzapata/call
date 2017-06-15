<?php
if(isset($_POST["check"]))
{
foreach($_POST["check"] as $campo => $valor)
{
echo "Valor devuelto por el array: $valor</br>";
}
}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Documento sin título</title>
</head>

<body>
<form method="post" id="form">
<input type="checkbox" name="check[0]" value="uno">
<input type="checkbox" name="check[1]" value="dos">
<input type="checkbox" name="check[2]" value="tres">
<input type="button" value="Enviar Array" id="btn">
</form>
<script>
$(function()
{
$("#btn").click(function()
{
$(":checkbox[name=check]").each(function(index)
{
if ($(this).is(":checked"))
{
alert($(this).val());
}
});
$("#form").submit();
});
});
</script>
</body>
</html>