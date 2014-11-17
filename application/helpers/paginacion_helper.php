<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


// Helper paginador traido del viejo proyecto

function paginas($cantidadRegistros,$rowsPerPage,$pagina, $baseUrl)
{
		$paginas = array();
		$cantidadDePaginas  = ceil($cantidadRegistros / $rowsPerPage);
		for($i=1;$i<=$cantidadDePaginas ;$i++)
		{
				array_push($paginas,$i);
		}

		settype($pagina,"INT");
		$prev = 1;
		$next = 1;

		$inicio = 0;
		$final = $rowsPerPage;
		$resto =  ( $pagina / $rowsPerPage ) ;
		$numPagina = $pagina;
		if($resto >= 1)
		{
				$inicio = ( intval($resto) * $rowsPerPage ) -1;
				$final = $inicio + $rowsPerPage;
				if($final > $cantidadDePaginas)
				{
						$final = $cantidadDePaginas;
				}
		}
		if($numPagina == 0 and $cantidadDePaginas > 0)
		{
				$numPagina = 1;
		}

		if($cantidadDePaginas > 1)
		{
				if($pagina > 1){
						$prev = ($pagina -1);

				}
				$next = ($pagina +1);
		}
		if($next > $cantidadDePaginas){
				$next = $cantidadDePaginas;
		}

		$html = '
		<div class="dataTables_paginate paging_bootstrap pagination">
				<ul style="list-style-type:none;">
						<li class="float-left">
								<a href="'. $baseUrl . '/'. $prev .'">&lt; Anterior</a>
						</li>';
		foreach ($paginas as $_pagina){
				if($_pagina == $pagina) {
						$html .= '<li class="active float-left">
								<a href="#">'. $pagina .'</a>
								</li>';
				}
				else {
						$html .= '<li class="float-left">
								<a href="'. $baseUrl . '/'. $_pagina .'">'. $_pagina .'</a>
								</li>';
				}
		}
		$html .= '
				<li class="next">
						<a href="'. $baseUrl . '/'. $next .'"> Pr&oacute;xima &gt; </a>
				</li>
		</ul>
		</div>';

		return $html;
}


// PAGINADOR PARA EL FRONTEND, POR AHORA PARA MIS PUBLICACIONES.
function paginas_front($cantidadRegistros,$rowsPerPage,$pagina, $baseUrl)
{
	$paginas = array();
	$cantidadDePaginas  = ceil($cantidadRegistros / $rowsPerPage);
	for($i=1;$i<=$cantidadDePaginas ;$i++) {
		array_push($paginas,$i);
	}

	settype($pagina,"INT");
	$prev = 1;
	$next = 1;

	$inicio = 0;
	$final = $rowsPerPage;
	$resto =  ( $pagina / $rowsPerPage ) ;
	$numPagina = $pagina;

	if($resto >= 1) 	{
		$inicio = ( intval($resto) * $rowsPerPage ) -1;
		$final = $inicio + $rowsPerPage;
		if($final > $cantidadDePaginas) {
			$final = $cantidadDePaginas;
		}
	}

	if($numPagina == 0 and $cantidadDePaginas > 0) {
		$numPagina = 1;
	}

	if($cantidadDePaginas > 1) {
		if($pagina > 1) {
			$prev = ($pagina -1);
		}
		$next = ($pagina +1);
	}

	if($next > $cantidadDePaginas){
		$next = $cantidadDePaginas;
	}

	$html = '
		<div>
		<span class="float-left">
		<a href="'. $baseUrl . '/'. $prev .'">&lt; Anterior &nbsp;&nbsp;&nbsp;&nbsp;</a>
		</span>';
	foreach ($paginas as $_pagina){
		if($_pagina == $pagina) {
			$html .= '<span class="active">
						<a href="#">'. $pagina .'</a>&nbsp;
					  </span>';
		}else{
			$html .= '<span class="">
						<a href="'. $baseUrl . '/'. $_pagina .'">'. $_pagina .'</a>&nbsp;
					</span>';
		}
	}
	$html .= '
		<span class="next">
		<a href="'. $baseUrl . '/'. $next .'"> &nbsp;&nbsp;&nbsp;&nbsp;Siguiente &gt; </a>
		</span>
		</div>
		</div>';

	return $html;
}



function limit ($pagina, $filasPorPagina) {
		settype($pagina,"INT");
		$inicio = 0;
		if($pagina < 1){
				$pagina = 1;
		}
		$final = $filasPorPagina;
		$inicio = ($pagina - 1) * $filasPorPagina;
		return "LIMIT $inicio,$final";
}

function limitForArray ($pagina, $filasPorPagina) {
		settype($pagina,"INT");
		$inicio = 0;
		if($pagina < 1){
				$pagina = 1;
		}
		$final = $filasPorPagina;
		$inicio = ($pagina - 1) * $filasPorPagina;

		$limit['start'] 	= $inicio;
		$limit['end']		= $final;
		return $limit;
}

?>