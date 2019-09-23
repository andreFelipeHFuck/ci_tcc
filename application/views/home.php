<?php 
	include "cabeca.php";
 ?>

<div class="espaco2"></div>

	<div class="conteiner0">
	 	<div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
		  <div class="carousel-inner">
		    <div class="carousel-item active">
		      <img class="d-block w-100" src="<?php echo base_url('assets/bootstrap/img/crisp.jpg')?>" alt="First slide">
		    </div>
		    <div class="carousel-item">
		      <img class="d-block w-100" src="<?php echo base_url('assets/bootstrap/img/bac.png')?>" alt="Second slide">
		    </div>
		    <div class="carousel-item">
		      <img class="d-block w-100" src="<?php echo base_url('assets/bootstrap/img/eng.png')?>" alt="Third slide">
		    </div>
		  </div>
		  <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
		    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
		    <span class="sr-only">Previous</span>
		  </a>
		  <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
		    <span class="carousel-control-next-icon" aria-hidden="true"></span>
		    <span class="sr-only">Next</span>
		  </a>
		</div>
		<script type="text/javascript" src="<?php echo base_url('assets/bootstrap/js/bootstrap.js')?>"></script>
		<script type="text/javascript" src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js')?>"></script>


	<section class="conteiner1" >

		<nav class="navbar navbar-light bg-light" id="sombra2">
		  <a class="navbar-brand" href="<?php echo site_url('disciplinas/disciplina_view/')?>?codDisciplina=1" align="middle">
		    <img src="<?php echo base_url('assets//bootstrap/img/bio.png')?>" width="30" height="30" class="d-inline-block align-top" alt="">
		    <h6>Biologia</h6>
		  </a>
		  <a class="navbar-brand" href="<?php echo site_url('disciplinas/disciplina_view/')?>?codDisciplina=2" align="middle">
		    <img src="<?php echo base_url('assets/bootstrap/img/fisica.png')?>" width="30" height="30" class="d-inline-block align-top" alt="">
		    <h6>Física</h6>
		  </a>
		  <a class="navbar-brand" href="<?php echo site_url('disciplinas/disciplina_view/')?>?codDisciplina=10" align="middle">
		    <img src="<?php echo base_url('assets/bootstrap/img/filo.png')?>" width="30" height="30" class="d-inline-block align-top" alt="">
		    <h6>Filosofia</h6>
		  </a>
		  <a class="navbar-brand" href="<?php echo site_url('disciplinas/disciplina_view/')?>?codDisciplina=4" align="middle">
		    <img src="<?php echo base_url('assets/bootstrap/img/geo.png')?>" width="30" height="30" class="d-inline-block align-top" alt="">
		    <h6>Geografia</h6>
		  </a>
		  <a class="navbar-brand" href="<?php echo site_url('disciplinas/disciplina_view/')?>?codDisciplina=5" align="middle">
		    <img src="<?php echo base_url('assets/bootstrap/img/historia.png')?>" width="30" height="30" class="d-inline-block align-top" alt="">
		    <h6>História</h6>
		  </a>
		  <a class="navbar-brand" href="<?php echo site_url('disciplinas/disciplina_view/')?>?codDisciplina=8" align="middle">
		    <img src="<?php echo base_url('assets/bootstrap/img/ing.png')?>" width="30" height="30" class="d-inline-block align-top" alt="">
		    <h6>Inglês</h6>
		  </a>
		  <a class="navbar-brand" href="<?php echo site_url('disciplinas/disciplina_view/')?>?codDisciplina=7" align="middle">
		    <img src="<?php echo base_url('assets/bootstrap/img/mat.png')?>" width="30" height="30" class="d-inline-block align-top" alt="">
		    <h6>Matemática</h6>
		  </a>
		  <a class="navbar-brand" href="<?php echo site_url('disciplinas/disciplina_view/')?>?codDisciplina=15" align="middle">
		    <img src="<?php echo base_url('assets/bootstrap/img/port.png')?>" width="30" height="30" class="d-inline-block align-top" alt="">
		    <h6>Potuguês</h6>
		  </a>
		  <a class="navbar-brand" href="<?php echo site_url('disciplinas/disciplina_view/')?>?codDisciplina=3" align="middle">
		    <img src="<?php echo base_url('assets/bootstrap/img/quimi.png')?>" width="30" height="30" class="d-inline-block align-top" alt="">
		    <h6>Química</h6>
		  </a>
		   <a class="navbar-brand" href="<?php echo site_url('disciplinas/disciplina_view/')?>?codDisciplina=11" align="middle">
		    <img src="<?php echo base_url('assets/bootstrap/img/soci.png')?>" width="30" height="30" class="d-inline-block align-top" alt="">
		    <h6>Sociologia</h6>
		  </a>
		</nav>	
	</section>
	<div class="espaco2"></div>
	<section class="conteiner2" id="sombra">
		<h2 style="font-size: 35px; border-bottom: solid 2px #17a2b8; margin-bottom: 2%; padding-bottom: 1%;">Artigos:</h2>
		<br>
			<?php foreach($artigos as $artigo){?>
				<article class="vidCont">
					<?php
                    if($artigo->imgArtigo == null){?>
						<img src="<?php echo base_url('assets/bootstrap/img/crisp.jpg')?>">
				<?php
                    }else{?>
                    	<img src="<?php echo base_url("upload/artigos/$artigo->imgArtigo")?>">
              <?php }?>

						<!--<img src="<?php echo base_url('assets/bootstrap/img/eng.png')?>">-->
						<div>
							<h3><?php echo $artigo->titulo?></h3>
							<p><?php echo $artigo->corpo?></p>
						</div>
				</article>
	<?php }?>
	</section>
	<div class="espaco2"></div>
	

<?php include "rodape.php"; ?>
