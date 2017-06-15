<style>
body{
font-size:12px;
font-family:Verdana;
}
.record {
background:none repeat scroll 0 0 #EEEEEE;
border-top:1px solid #CCCCCC;
padding:10px;
width:400px;
}
.delete {
color:#FF0000;
display:block;
float:right;
}

</style>
<div id="content-left">		
	<div id="record-1" class="record">
		<a class="delete" href="?delete=1"><img src="delete.png" border=0 height="20px"/></a>
		<strong>Top 10 librerías para gráficos en php</strong>
	</div>
	<div id="record-2" class="record">
		<a class="delete" href="?delete=2"><img src="delete.png" border=0 height="20px"/></a>
		<strong>5 librerias para generar PDF con PHP</strong>
	</div>
	<div id="record-3" class="record">
		<a class="delete" href="?delete=3"><img src="delete.png" border=0 height="20px"/></a>
		<strong>Uploadify: upload multiple con jQuery</strong>
	</div>
	<div id="record-4" class="record">
		<a class="delete" href="?delete=4"><img src="delete.png" border=0 height="20px"/></a>
		<strong>Tutorial MVC con PHP</strong>
	</div>
	<div id="record-5" class="record">
		<a class="delete" href="?delete=5"><img src="delete.png" border=0 height="20px"/></a>
		<strong>El mejor IDE para PHP</strong>
	</div>
	<div id="record-6" class="record">
		<a class="delete" href="?delete=6"><img src="delete.png" border=0 height="20px"/></a>
		<strong>Expresiones regulares mas usadas en php</strong>
	</div>
	<div id="record-7" class="record">
		<a class="delete" href="?delete=7"><img src="delete.png" border=0 height="20px"/></a>
		<strong>jsTree: Componente Treeview para jQuery</strong>
	</div>
</div>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script src="jquery.color.js"></script>
<script>
$(document).ready(function() {
	
	$('a.delete').click(function(e) {
		if(confirm('Seguro de Eliminar este registro?')){
			e.preventDefault();
			var parent = $(this).parent();
			$.ajax({
				type: 'get',
				url: 'index.php',
				data: 'ajax=1&delete=' + parent.attr('id').replace('record-',''),
				beforeSend: function() {
					parent.animate({'backgroundColor':'#fb6c6c'},300);
				},
				success: function() {
					parent.slideUp(300,function() {
						parent.remove();
					});
				}
			});
		}
	});
	
	
});
</script>