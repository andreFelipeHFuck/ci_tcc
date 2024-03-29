<html>
<head>
    <!--Le CSS ==========================================================-->
        <link rel="stylesheet"  href="<?php echo base_url('assets/bootstrap/css/bootstrap.css')?>">
        <link rel="stylesheet" href="<?php echo base_url('assets/bootstrap/css/style.css')?>">
        <link rel="stylesheet" href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css')?>">
        <link rel="stylesheet" href="<?php echo base_url('assets/bootstrap/css/pagination.css')?>">
        <link rel="stylesheet" href="<?php echo base_url('assets/bootstrap/css/jquery-ui.css')?>"> 
        <link href="https://fonts.googleapis.com/css?family=Comfortaa&display=swap" rel="stylesheet">
     
    <!-- ==============================================================-->

    <!--Le JS ==========================================================-->
      <script type="text/javascript" src="<?php echo base_url('assets/jquery/jquery.js')?>"></script>
       <script type="text/javascript" src="<?php echo base_url('assets/jquery/jquery-3.1.0.min.js')?>"></script>
       <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
       <script type="text/javascript" src="<?php echo base_url('assets/jquery/jquery-ui.js')?>"></script>
      	<script type="text/javascript" src="<?php echo base_url('assets/jquery/jquery-ui.min.js')?>"></script>   

    	<script src="<?php echo base_url('assets/bootstrap/js/bootstrap.js')?>"></script>
    	<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js')?>"></script>
        <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js')?>"></script>
           <script src="<?php echo base_url('assets/bootstrap/js/pagination.js')?>"></script>
           <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote-bs4.css" rel="stylesheet">
		    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote-bs4.js"></script>
    <!-- ==============================================================-->
  
   
   
  		<!-- <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->

        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="pragma" content="no-cache" />
        <meta charset="utf-8">
        <link rel="icon" href="<?php echo base_url('assets/bootstrap/img/logo.png')?>">
 
</head>
<body class="bdg">
		<nav class="navbar navbar-expand-lg navbar-light sticky-top" id="sombra2">
		  <a class="navbar-brand" href="<?php echo site_url('')?>"><img src="<?php echo base_url('assets/bootstrap/img/logo.png')?>" class="imglogo"></a>
		  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		    <span class="navbar-toggler-icon"></span>
		  </button>

		  <div class="collapse navbar-collapse" id="navbarSupportedContent">
		    <ul class="navbar-nav mr-auto">
		      <li class="nav-item">
		        <a class="nav-link" href="<?php echo site_url('')?>">Home<span class="sr-only">(current)</span></a>
		      </li>
               
		      <li class="nav-item">
		        <a class="nav-link" href="<?php echo site_url('disciplinas')?>">Disciplinas</a>
		       <li class="nav-item">
		        <a class="nav-link" href="<?php echo site_url('artigos/artigos_listar') ?>">Artigos</a>
		      </li>
		     <li class="nav-item">
		        <a class="nav-link" href="<?php echo site_url('home/sobre') ?>">Sobre</a>
		      </li>
		    </ul>
		    <div class="row">
			     <form class="form-inline my-2 my-lg-0" action="<?php echo site_url('home/resultado')?>" method = "get">
			     	<div id="custom-search-input">
             
				     		<div class="input-group autocomplete">
				     			<input class="form-control mr-sm-2 ui-widget" type="text" name = "busca" placeholder="Pequise aqui..." aria-label="Search" id="termo" required>
				     		</div>
          
				    </div>
				    <button class="btn btn-outline-info my-2 my-sm-0" type="submit">Buscar</button>
			      
			    </form>
			</div>
		    <style type="text/css">
		    	a:hover{
		    		color: #343a40;
		    	}
		    </style>
        <div style="margin-left: 1%;"></div>
		    <form class="form-inline my-2 my-lg-0 registro">
                <?php 
                if($this->session->userdata('professores')):?>
                     <div class="dropdown">
                      <button type="button" id="dropdownMenu" class="btn dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php
                            $imgProfessor = $_SESSION['imgProfessor'];
                            if ($_SESSION['imgProfessor'] == null) {
                               ?><img src="<?php echo base_url('assets/bootstrap/img/user.png')?>" width="30"><?php
                            }else{
                                ?><img src="<?php echo base_url("upload/professores/$imgProfessor")?>" width="30"><?php
                            }
                        ?>
                        
                      </button>
                      <div class="dropdown-menu arrow_box " aria-labelledby="dropdownMenu2">
                         <a class="btn dropdown-item" href="<?php echo site_url('professores/professor_perfil')?>?codProfessor=<?php echo $_SESSION['professores']?>"><?php echo mb_strimwidth($_SESSION['nome'], 0, 15, "...")?></a>
                          <a href="<?php echo site_url('professores/professor_editar/')?>?codProfessor=<?php echo $_SESSION['professores'];?>" class="btn dropdown-item">Configurações</a>
                         <a href="<?php echo site_url('professores/artigos_view')?>?codProfessor=<?php echo $_SESSION['professores'];?>" class="btn dropdown-item">Artigos</a>
                          <a href="<?php echo site_url('login/sairProf')?>" class ="btn dropdown-item" style="color:#dc3545">Sair</a>
                      </div>
                    </div>
                <?php elseif ($this->session->userdata('alunos')):?>
                    <div class="dropdown">
                      <button type="button" id="dropdownMenu" class="btn dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                       <?php
                            $imgAluno = $_SESSION['imgAluno'];
                            if ($_SESSION['imgAluno'] == null) {
                               ?><img src="<?php echo base_url('assets/bootstrap/img/user.png')?>" width="30"><?php
                            }else{
                                ?><img src="<?php echo base_url("upload/alunos/$imgAluno")?>" width="30"><?php
                            }
                        ?>
                      </button>
                      <div class="dropdown-menu arrow_box" aria-labelledby="dropdownMenu2">
                        
                         <a class="btn dropdown-item" href="<?php echo site_url('alunos/aluno_perfil')?>?codAluno=<?php echo $_SESSION['alunos']?>"><?php echo mb_strimwidth($_SESSION['nome'], 0, 15, "...")?></a>
                          <a href="<?php echo site_url('alunos/aluno_editar/')?>?codAluno=<?php echo $_SESSION['alunos'];?>" class="btn dropdown-item">Configurações</a>
                         <a href="<?php echo site_url('alunos/artigos_view')?>?codAluno=<?php echo $_SESSION['alunos'];?>" class="btn dropdown-item">Artigos</a>
                          <a href="<?php echo site_url('login/sair')?>" class ="btn dropdown-item" style="color:#dc3545">Sair</a>
                      </div>
                    </div>
                <?php else :?>
                	<a class="regis" href="<?php echo site_url('home/opiCad')?>">Cadastre-se</a>
                    <a href="<?php echo site_url('home/login_home')?>" class="btn btn-outline-info my-2 my-sm-0"> Entrar</a>
                <?php endif;?>
		    </form>
		  </div>
		
<script>

  $(document).ready(function() { 
    $( "#termo" ).autocomplete({
       
        source: function(request, response) {
            $.ajax({
            url: "<?php echo site_url('home/procurar/');?>",
            data: {
                    term : request.term
             },
            dataType: "json",
            success: function(data){
               var resp = $.map(data,function(obj){
                    return obj.titulo;
               }); 
              console.log(resp);
               response(resp);
            }
            
        });
    },
    minLength: 1
 });
     $("#ui-id-1").addClass('sticky-top');
});	

var popper = new Popper(referenceElement, onPopper, {
    placement: 'bottom'
});

</script>
<script type="text/javascript">
   $('.dropdown-toggle').dropdown();
</script>
</nav>
<style type="text/css">
.dropdown-menu {
  border-color: #51c0cf;
}
.arrow_box {
  position: absolute;
  margin-top: 11px;
}
.arrow_box:after, .arrow_box:before {
  bottom: 100%;
  left: 16%;
  border: solid transparent;
  content: " ";
  height: 0;
  width: 0;
  position: absolute;
  pointer-events: none;
}

.arrow_box:after {
  border-width: 10px;
  margin-left: -10px;
}
.arrow_box:before {
  border-bottom-color: #51c0cf;
  border-width: 10px;
  margin-left: -10px;
}
</style>

