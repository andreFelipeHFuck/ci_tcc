<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Alunos extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
        $this->load->library('session');
		$this->load->model('aluno_model');
		$this->load->model('artigos_model');
		$this->load->model('comentarios_model');

	}

	public function index()
    {
 		$aluno = $this->session->userdata("alunos");
		if(empty($aluno)){
		    redirect("home/login_home");
       	}
        //Teste
        $data['alunos'] = $this->aluno_model->get_all_alunos();
        $this->load->view('aluno_view', $data);
    }

	public function aluno_add(){
		 $this->load->helper('language');
		 $imgAluno = $_FILES['imgAluno'];
		 //VERIFICA EMAIL
            $marcador['email'] = $this->aluno_model->check_email($this->input->post('email'));
            if ($marcador['email'] == FALSE) {
                $this->form_validation->set_rules('email', 'E-mail', 'required|valid_email', array('required' => 'O campo E-mail é obrigatorio.'));
            }
            
        //
		 ///
			$ponto_img = explode(".", $imgAluno['name']);
			@$ponto_img = $ponto_img[1];
		//

		//VALIDAR FORMULARIO
		$this->load->library('form_validation');

    	$this->form_validation->set_rules('nomeAluno', 'Nome Completo', 'min_length[3]|max_length[50]', array('required' => 'O campo Nome Completo é obrigatorio.'));	
     	$this->form_validation->set_rules('senha', 'Senha', 'min_length[8]', array('required' => 'Você deve preencher a %s.'));
     	$this->form_validation->set_rules('senhaconf', 'Confirmar Senha', 'matches[senha]', array('required' => 'O campo Confirmar senha é obrigatorio'));
    	 if ($marcador['email'] == TRUE) {
            if ($this->form_validation->run() == TRUE) {
                $this->load->view('aluno_add', $marcador);
            }      
        }else{
		    	if ($this->form_validation->run() == FALSE) {
		          //$erros = array('mensagens' => validation_errors());
		           $this->load->view('aluno_add');

			    }else{
			    	//SE O ALUNO NÂO QUISER ENVIAR UMA FOTO DE PERFIL
		          	if($imgAluno['name'] == null) {
		          		//ENVIAR PARA O BANCO
				           	$data = array(
								'nomeAluno' => $this->input->post('nomeAluno'),
								'dataNasc' => $this->input->post('dataNasc'),
								'anoLetivo' => $this->input->post('anoLetivo'),
								'curso' => $this->input->post('curso'),
								'email' => $this->input->post('email'),
								'senha' => md5($this->input->post('senha')),
								'tipo' => 0,
							);
							$insert = $this->aluno_model->aluno_add($data);

							//ENVIAR PARA A PAGINA PERFIL
							  	$this->db->where('email', $data['email']);
					           	$this->db->where('senha', $data['senha']);
					            $query = $this->db->get('alunos');

					            if ($query->num_rows() == 1){
					                $aluno = $query->row();
					                $this->session->set_userdata("alunos", $aluno->codAluno);
					                 $this->session->set_userdata("imgAluno", null);
					                 $this->session->set_userdata("nome", $data['nomeAluno']);
					                $codAluno = $this->aluno_model->get_by_login($email, $senha);
					                $url = "?codAluno=".$aluno->codAluno;
					               redirect ("alunos/aluno_perfil/$url");
					            }
					//SE O ALUNO QUISER ENVIAR UMA FOTO DE PERFIL
		          	}elseif(!empty($imgAluno['name'])){
		          		 echo "Formulário enviado com sucesso.";
			           //ENVIANDO IMAGEM PRO BANCO
		          		$ponto = explode(".", $imgAluno['name']);
			           $config = array(
			           	'upload_path' => './upload/alunos',
			             'allowed_types' =>  'gif|jpg|png',//Arrumar essa parte
			           	'file_name' => md5(time()),
			           	'max_size' => '3000'
			           );

			           /*
			           CONFIGURAÇÔES PARA UPLOAD DE IMAGEM
			           max_width:
			           max_height:
			           */

			           $this->load->library('upload');
			           $this->upload->initialize($config);

			           if ($this->upload->do_upload('imgAluno')){
		        			echo 'Arquivo salvo com sucesso.';

			        		//ENVIAR PARA O BANCO
				           	$data = array(
								'nomeAluno' => $this->input->post('nomeAluno'),
								'dataNasc' => $this->input->post('dataNasc'),
								'imgAluno' => $config['file_name'].".".$ponto_img,
								'anoLetivo' => $this->input->post('anoLetivo'),
								'curso' => $this->input->post('curso'),
								'email' => $this->input->post('email'),
								'senha' => md5($this->input->post('senha')),
								'tipo' => 0,
							);
							$insert = $this->aluno_model->aluno_add($data);

							//ENVIAR PARA A PAGINA PERFIL
							  	$this->db->where('email', $data['email']);
					           	$this->db->where('senha', $data['senha']);
					            $query = $this->db->get('alunos');

					            if ($query->num_rows() == 1){
					                $aluno = $query->row();
					                $this->session->set_userdata("alunos", $aluno->codAluno);
					                 $this->session->set_userdata("imgAluno", $data['imgAluno']);
					                 $this->session->set_userdata("nome", $data['nomeAluno']);
					                $codAluno = $this->aluno_model->get_by_login($email, $senha);
					                $url = "?codAluno=".$aluno->codAluno;
					               redirect ("alunos/aluno_perfil/$url");
					            }

		    			}else{	
                        	$erro['erro'] = $this->upload->display_errors();
                         	$this->load->view('aluno_add', $erro);
                        }
		          	}
			          
			    }
	    }
	    	 if ($this->form_validation->run() == FALSE) {
                $this->load->view('aluno_add', $marcador);
            } 
    }

	public function ajax_edit($codAluno)
	{
		$data = $this->aluno_model->get_by_id($codAluno);
		echo json_encode($data);
	}

	public function aluno_update_perfil(){
		$url = "?codAluno=".$this->input->post('codAluno');
		$imgAluno = $_FILES['imgAluno'];
		$vereficaSenha = $this->input->post('senha');
		///
			$ponto_img = explode(".", $imgAluno['name']);
			@$ponto_img = $ponto_img[1];
		//

		 //VERIFICA EMAIL
			$marcador['email'] = $this->aluno_model->check_email($this->input->post('email'));
			if($this->input->post('email') != $this->input->post('email_teste')){
				$marcador['email'] = TRUE;
			}else{
				$marcador['email'] = FALSE;
			}

			$email_erro = "O email escolhido já pertence a outro usuário, se quiser trocar de email, tente outro";
	 	//

		//SE O ALUNO NÂO TROCAR A SENHA
		if ($vereficaSenha == null) {
			$this->load->library('form_validation');

			$this->form_validation->set_rules('nomeAluno', 'Alterar Nome', 'required|min_length[3]|max_length[50]', array('required' => 'O campo Nome Aluno é obrigatorio.'));
    		$this->form_validation->set_rules('email', 'E-mail', 'required|valid_email', array('required' => 'O campo E-mail é obrigatorio.'));
    		//
    		
    		if ($marcador['email'] == TRUE) {
	            if ($this->form_validation->run() == FALSE) {
	           		$form_nome = form_error('nomeAluno');	
	            //Mandando mensagem de erro
		          	$this->session->set_flashdata('form_nome', "$form_nome" );
	            	$this->session->set_flashdata('email_erro', "$email_erro");
	               redirect("alunos/aluno_editar/$url");
	            ///
	            }else{
	            	$this->session->set_flashdata('email_erro', "$email_erro");
	               redirect("alunos/aluno_editar/$url");
	            }      
        	}else{
		    	if ($this->form_validation->run() == FALSE) {
		    		$form_nome = form_error('nomeAluno');	
		          $this->session->set_flashdata('form_nome', "$form_nome" );
		           redirect("alunos/aluno_editar/$url");
			    }else{
				    //SE O ALUNO NÂO QUISER TROCAR DE FOTO DE PERFIL
	          		if($imgAluno['name'] == null) {
	          			$data = array(
						'nomeAluno' => $this->input->post('nomeAluno'),
						'dataNasc' => $this->input->post('dataNasc'),
						'anoLetivo' => $this->input->post('anoLetivo'),
						'curso' => $this->input->post('curso'),
						'email' => $this->input->post('email'),
						);
						$this->aluno_model->aluno_update(array('codAluno' => $this->input->post('codAluno')), $data);
						$this->session->set_userdata('nome', $data['nomeAluno']);

						echo json_encode(array("status" => TRUE));
						//ENVIAR PARA A PAGINA PERFIL
						redirect ("alunos/aluno_perfil/$url");

	        		//SE O ALUNO QUISER TROCAR A FOTO DE PERFIL
	        		}elseif(!empty($imgAluno['name'])){
			           //ENVIANDO IMAGEM PRO BANCO
			           $config = array(
			           	'upload_path' => './upload/alunos',
			             'allowed_types' =>  'gif|jpg|png',//Arrumar essa parte
			           	'file_name' => md5(time()),
			           	'max_size' => '3000'
			           );

			           /*
			           CONFIGURAÇÔES PARA UPLOAD DE IMAGEM
			           max_width:
			           max_height:
			           */

			           $this->load->library('upload');
			           $this->upload->initialize($config);

			           if($this->upload->do_upload('imgAluno')){
			           			//EXCLUIR FOTO ANTIGA DE PERFIL
			        			$aluno = $this->aluno_model->get_img($this->input->post('codAluno'));
								$img = $aluno->imgAluno;	
								$caminho = "upload/alunos/$img";
								$this->aluno_model->delete_img($this->input->post('codAluno'));
								echo json_encode(array("status" => TRUE));
								unlink($caminho);
								///
			        		//ENVIAR PARA O BANCO
				           	$data = array(
								'nomeAluno' => $this->input->post('nomeAluno'),
								'dataNasc' => $this->input->post('dataNasc'),
								'imgAluno' => $config['file_name'].".".$ponto_img,
								'anoLetivo' => $this->input->post('anoLetivo'),
								'curso' => $this->input->post('curso'),
								'email' => $this->input->post('email'),
								
							);
							$this->aluno_model->aluno_update(array('codAluno' => $this->input->post('codAluno')), $data);
							$this->session->set_userdata('imgAluno', $data['imgAluno']);
							$this->session->set_userdata('nome', $data['nomeAluno']);
							

							echo json_encode(array("status" => TRUE));

							//ENVIAR PARA A PAGINA PERFIL
						        redirect ("alunos/aluno_perfil/$url");
	        			}else{
	        				$upload_erro = $this->upload->display_errors();
                         	$this->session->set_flashdata('upload_erro', "$upload_erro");
		           			redirect("alunos/aluno_editar/$url");
	        			}
	        		}
			    }
			    echo "Teste";
          		
			}

		//SE O ALUNO TROCAR A SENHA
		}elseif ($vereficaSenha != null){
			$this->load->library('form_validation');

			$this->form_validation->set_rules('nomeAluno', 'Nome Completo', 'required|min_length[3]|max_length[20]', array('required' => 'O campo Nome Completo é obrigatorio.'));
    		$this->form_validation->set_rules('email', 'E-mail', 'required|valid_email', array('required' => 'O campo E-mail é obrigatorio.'));
    		$this->form_validation->set_rules('senha', 'Senha', 'required|min_length[8]', array('required' => 'Você deve preencher a %s.'));
     		$this->form_validation->set_rules('senhaconf', 'Confirmar Senha', 'required|matches[senha]', array('required' => 'O campo Confirmar senha é obrigatorio'));

    		if ($marcador['email'] == TRUE) {
	            if ($this->form_validation->run() == FALSE) {
	           		$form_nome = form_error('nomeAluno');
	           		$form_senha = form_error('senha');
	           		$form_senhaconf = form_error('senhaconf');
	            //Mandando mensagem de erro
	           		$this->session->set_flashdata('form_senhaconf', "$form_senhaconf" );
	           		$this->session->set_flashdata('form_senha', "$form_senha" );
		          	$this->session->set_flashdata('form_nome', "$form_nome" );
	            	$this->session->set_flashdata('email_erro', "$email_erro");
	               redirect("alunos/aluno_editar/$url");
	            ///
	            }      
        	}else{
		    	if ($this->form_validation->run() == FALSE) {
		    		$form_nome = form_error('nomeAluno');
		    		$form_senha = form_error('senha');
		    		$form_senhaconf = form_error('senhaconf');
		    		//	
		    	   $this->session->set_flashdata('form_senha', "$form_senha" );
		           $this->session->set_flashdata('form_nome', "$form_nome" );
		           $this->session->set_flashdata('form_senhaconf', "$form_senhaconf" );
		           redirect("alunos/aluno_editar/$url");
			    }else{

					if($imgAluno['name'] == null) {
	          			$data = array(
						'nomeAluno' => $this->input->post('nomeAluno'),
						'dataNasc' => $this->input->post('dataNasc'),
						'anoLetivo' => $this->input->post('anoLetivo'),
						'curso' => $this->input->post('curso'),
						'email' => $this->input->post('email'),
						'senha' => md5($this->input->post('senha')),
						);
						$this->aluno_model->aluno_update(array('codAluno' => $this->input->post('codAluno')), $data);
						$this->session->set_userdata('nome', $data['nomeAluno']);

						echo json_encode(array("status" => TRUE));
						//ENVIAR PARA A PAGINA PERFIL
						redirect ("alunos/aluno_perfil/$url");

	        		//SE O ALUNO QUISER TROCAR A FOTO DE PERFIL
	        		}elseif(!empty($imgAluno['name'])){
			           //ENVIANDO IMAGEM PRO BANCO
		          		$ponto = explode(".", $imgAluno['name']);
			           $config = array(
			           	'upload_path' => './upload/alunos',
			             'allowed_types' =>  'gif|jpg|png',//Arrumar essa parte
			           	'file_name' => md5(time()),
			           	'max_size' => '3000'
			           );

			           /*
			           CONFIGURAÇÔES PARA UPLOAD DE IMAGEM
			           max_width:
			           max_height:
			           */

			           $this->load->library('upload');
			           $this->upload->initialize($config);

			           if ($this->upload->do_upload('imgAluno')){
				           	 //EXCLUIR FOTO ANTIGA DE PERFIL
		        			$aluno = $this->aluno_model->get_img($this->input->post('codAluno'));
							$img = $aluno->imgAluno;	
							$caminho = "upload/alunos/$img";
							$this->aluno_model->delete_img($this->input->post('codAluno'));
							echo json_encode(array("status" => TRUE));
							unlink($caminho);
							///
		        			echo 'Arquivo salvo com sucesso.';

			        		//ENVIAR PARA O BANCO
				           	$data = array(
								'nomeAluno' => $this->input->post('nomeAluno'),
								'dataNasc' => $this->input->post('dataNasc'),
								'imgAluno' => $config['file_name'].".".$ponto_img,
								'anoLetivo' => $this->input->post('anoLetivo'),
								'curso' => $this->input->post('curso'),
								'email' => $this->input->post('email'),
								'senha' => md5($this->input->post('senha')),
							);
							
							$this->aluno_model->aluno_update(array('codAluno' => $this->input->post('codAluno')), $data);
							$this->session->set_userdata('nome', $data['nomeAluno']);
							$this->session->set_userdata('imgAluno', $data['imgAluno']);

							echo json_encode(array("status" => TRUE));

							//ENVIAR PARA A PAGINA PERFIL
						        redirect ("alunos/aluno_perfil/$url");
	        			}else{
	        				$upload_erro = $this->upload->display_errors();
                         	$this->session->set_flashdata('upload_erro', "$upload_erro");
		           			redirect("alunos/aluno_editar/$url");
	        			}
	        		}
				}
			}
		}
		
	}

	public function aluno_delete_img($codAluno){
		$aluno = $this->aluno_model->get_img($codAluno);
		$img = $aluno->imgAluno;	
		$caminho = "upload/alunos/$img";
		$this->aluno_model->delete_img($codAluno);
		echo json_encode(array("status" => TRUE));
		unlink($caminho);
		$this->session->set_userdata('imgAluno', '');
	}

	public function aluno_delete($codAluno){
		$this->comentarios_model->delete_all_aluno($codAluno);
		$this->artigos_model->delete_all_aluno($codAluno);
		$this->aluno_model->delete_by_id($codAluno);
		echo json_encode(array("status" => TRUE));
		$this->session->set_userdata('alunos');
	}

	public function aluno_perfil(){
		$codAluno = $this->input->get('codAluno');
		$aluno['perfil'] = $this->aluno_model->get_by_id($codAluno);
		$aluno['artigos'] = $this->artigos_model->get_all_id_aluno($codAluno);
		$this->load->view('aluno_perfil', $aluno);
	}

	public function aluno_editar(){
		$codAluno = $this->input->get('codAluno');
        $aluno['perfil'] = $this->aluno_model->get_by_id($codAluno);
        $this->load->view('aluno_editar', $aluno);
	}

	 public function artigos_add(){
         $codAluno = $this->input->get('codAluno');
        $aluno['perfil'] = $this->aluno_model->get_by_id($codAluno);
        $this->load->view('artigo_add_aluno', $aluno);
    }

    public function artigos_view(){
        $codAluno = $this->input->get('codAluno');
    	$this->load->library('pagination');
    	$config = array(
			"base_url" => "http://localhost/ci_tcc/index.php/alunos/artigos_view",
			"per_page" => 20,
			"num_links" => 3,
			"uri_segment" => 3,
			"total_rows" => 200,
			"full_tag_open" => "<ul class='pagination'>",
			"full_tag_close" => "</ul>",
			"first_link" => FALSE,
			"last_link" => FALSE,
			"reuse_query_string" => TRUE,
			//"use_page_numbers" => TRUE,
			"first_tag_open" => "<li>",
			"first_tag_close" => "</li>",
			"prev_link" => "Anterior",
			"prev_tag_open" => "<li class='prev'>",
			"prev_tag_close" => "</li>",
			"next_link" => "Próxima",
			"next_tag_open" => "<li class='next'>",
			"next_tag_close" => "</li>",
			"last_tag_open" => "<li>",
			"last_tag_close" => "</li>",
			"cur_tag_open" => "<li class='active'><a href='#'>",
			"cur_tag_close" => "</a></li>",
			"num_tag_open" => "<li>",
			"num_tag_close" => "</li>"
		);
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		//if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$this->pagination->initialize($config);
		$aluno['pagination'] = $this->pagination->create_links();
		$offset = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $aluno['artigos'] = $this->artigos_model->get_all_id_aluno_pag($codAluno,$config['per_page'],$offset);
        $this->load->view('artigos_view_aluno', $aluno);
    }


}



