<?php
/**
 * Created by PhpStorm.
 * User: Pichau
 * Date: 06/07/2019
 * Time: 20:41
 */
 defined('BASEPATH') OR exit('No direct script access allowed');

class Professores extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('professor_model');
        $this->load->model('artigos_model');
        $this->load->model('professores_has_disciplinas_model');
         $this->load->model('disciplina_model');
        $this->load->model('comentarios_model');

        /*$professor = $this->session->userdata("professores");
        if (empty($professor)) {
            redirect("home/login_home");
        }*/

    }
    public function index()
    {
    
    }

    
    public function professor_add(){
        $this->load->library('form_validation');
        //VERIFICA EMAIL
            $marcador['email'] = $this->professor_model->check_email($this->input->post('email'));
            if ($marcador['email'] == FALSE) {
                $this->form_validation->set_rules('email', 'E-mail', 'required|valid_email', array('required' => 'O campo E-mail é obrigatorio.'));
            }
            $verifica_email_professor = explode("@", $this->input->post('email'));
            $email_certo = ['ifc.edu.br'];
            foreach ($email_certo as $key => $value) {
                if ($value != $verifica_email_professor[1]) {
                   $email_verifica['email_verifica'] = TRUE;
                }
            }
        //
        $imgProfessor = $_FILES['imgProfessor'];
         ///
            $ponto_img = explode(".", $imgProfessor['name']);
            @$ponto_img = $ponto_img[1];
        //

        //VALIDAR FORMULARIO
        $this->form_validation->set_rules('nomeProfessor', 'Nome Completo', 'required|min_length[3]|max_length[20]', array('required' => 'O campo Nome Completo é obrigatorio.'));
        $this->form_validation->set_rules('senha', 'Senha', 'required|min_length[8]', array('required' => 'Você deve preencher a %s.'));
        $this->form_validation->set_rules('senhaconf', 'Confirmar Senha', 'required|matches[senha]', array('required' => 'O campo Confirmar senha é obrigatorio'));
        $this->form_validation->set_rules('miniCurriculo', 'Mini Curriculo', 'required|max_length[240]');
        //VERIFICA SE O EMAIL È IGAUL
        if (isset($email_verifica)) {
            if ($email_verifica['email_verifica'] == TRUE) {
                 if ($this->form_validation->run() == TRUE) {
                    $this->load->view('professor_add', $email_verifica);
                }    
            } 
        }elseif ($marcador['email'] == TRUE) {
            if ($this->form_validation->run() == TRUE) {
                $this->load->view('professor_add', $marcador);
            }      
        }else{
            if ($this->form_validation->run() == FALSE) {
            
                $this->load->view('professor_add', $marcador);

            }else{
                //SE O PROFESSOR NÂO QUISER ENVIAR UMA FOTO DE PERFIL
                if($imgProfessor['name'] == null) {
                    //ENVIAR PARA O BANCO
                        $data = array(
                            'nomeProfessor' => $this->input->post('nomeProfessor'),
                            'dataNasc' => $this->input->post('dataNasc'),
                            'miniCurriculo' => $this->input->post('miniCurriculo'),
                            'instituicao' => $this->input->post('instituicao'),
                            'email' => $this->input->post('email'),
                            'senha' => md5($this->input->post('senha')),
                            'tipo' => 1,
                        );
                    //VERIFICAR ADIMIN
                        $admin = $this->professor_model->check_professor($this->input->post('disciplina_codDisciplina'));
                        if ($admin == TRUE) {
                            $data['admin'] = 1;
                        }


                    //
                        $insert = $this->professor_model->professor_add($data);
                        $this->db->where('email', $data['email']);
                        $this->db->where('senha', $data['senha']);
                        $query = $this->db->get('professores');

                        if ($query->num_rows() == 1){
                            $professor = $query->row();
                            $this->session->set_userdata("professores", $professor->codProfessor);
                             $this->session->set_userdata("imgProfessor", null);
                            $this->session->set_userdata("nome", $data['nomeProfessor']);
                            $codProfessor = $this->professor_model->get_by_login($professor->email, $professor->senha);
                            ////
                            $data_prof_disc = array(
                                'professores_codProfessor' => $professor->codProfessor,
                                'disciplina_codDisciplina' => $this->input->post('disciplina_codDisciplina')
                            );
                             $insert = $this->professores_has_disciplinas_model->prof_disc_add($data_prof_disc);
                             ////
                            $url = "?codProfessor=".$professor->codProfessor;
                           redirect ("professores/professor_perfil/$url");
                        }
                //SE O PROFESSOR QUISER ENVIAR UMA FOTO DE PERFIL
                }elseif(!empty($imgProfessor['name'])){
                   //ENVIANDO IMAGEM PRO BANCO
                   $config = array(
                    'upload_path' => './upload/professores',
                    'allowed_types' => 'gif|jpg|png',//Arrumar essa parte
                    'file_name' => md5(time()),
                    'max_size' => '3000'
                   );
                   $this->load->library('upload');
                   $this->upload->initialize($config);

                   if ($this->upload->do_upload('imgProfessor')){
                        echo 'Arquivo salvo com sucesso.';
                        $data = array(
                            'nomeProfessor' => $this->input->post('nomeProfessor'),
                            'dataNasc' => $this->input->post('dataNasc'),
                            'imgProfessor' => $config['file_name'].".".$ponto_img,
                            'miniCurriculo' => $this->input->post('miniCurriculo'),
                            'instituicao' => $this->input->post('instituicao'),
                            'email' => $this->input->post('email'),
                            'senha' => md5($this->input->post('senha')),
                            'tipo' => 1,
                        );

                        //VERIFICAR ADIMIN
                        $admin = $this->professor_model->check_professor($this->input->post('disciplina_codDisciplina'));
                        if ($admin == TRUE) {
                            $data['admin'] = 1;
                        }

                        $insert = $this->professor_model->professor_add($data);

                        $this->db->where('email', $data['email']);
                        $this->db->where('senha', $data['senha']);
                        $query = $this->db->get('professores');

                        if ($query->num_rows() == 1){
                            $professor = $query->row();
                            $this->session->set_userdata("professores", $professor->codProfessor);
                             $this->session->set_userdata("imgProfessor", $data['imgProfessor']);
                             $this->session->set_userdata("nome", $data['nomeProfessor']);
                            $codProfessor = $this->professor_model->get_by_login($professor->email, $professor->senhaconf);
                             ////
                            $data_prof_disc = array(
                                'professores_codProfessor' => $professor->codProfessor,
                                'disciplina_codDisciplina' => $this->input->post('disciplina_codDisciplina')
                            );
                             $insert = $this->professores_has_disciplinas_model->prof_disc_add($data_prof_disc);
                             ////
                            $url = "?codProfessor=".$professor->codProfessor;
                           redirect ("professores/professor_perfil/$url");
                            
                        }
                    }else{
                        $erro['erro'] = $this->upload->display_errors();
                         $this->load->view('professor_add', $erro);
                    }
                }  
            }
        }
            if ($this->form_validation->run() == FALSE) {
                $this->load->view('professor_add', $marcador);
            }
    }

    public function ajax_edit($codProfessor)
    {
        $data = $this->professor_model->get_by_id($codProfessor);
        echo json_encode($data);
    }

    public function professor_update_perfil(){
        $url = "?codProfessor=".$this->input->post('codProfessor');
        $imgProfessor = $_FILES['imgProfessor'];
        $vereficaSenha = $this->input->post('senha');
         ///
            $ponto_img = explode(".", $imgProfessor['name']);
            @$ponto_img = $ponto_img[1];
        //
        //VERIFICA EMAIL
            $marcador['email'] = $this->professor_model->check_email($this->input->post('email'));
            if($this->input->post('email') != $this->input->post('email_teste')){
                $marcador['email'] = TRUE;
            }else{
                $marcador['email'] = FALSE;
            }

            $verifica_email_professor = explode("@", $this->input->post('email'));
            $email_certo = ['ifc.edu.br'];
            foreach ($email_certo as $key => $value) {
                if ($value != $verifica_email_professor[1]) {
                   $email_verifica['email_verifica'] = TRUE;
                }
            }

            $email_erro = "O email escolhido já pertence a outro usuário, se quiser trocar de email, tente outro";
            $email_marcador = "O seu email não pertence a instituição";
        //

        //SE O PROFESSOR NÂO TROCAR A SENHA
        if($vereficaSenha == null) {
            $this->load->library('form_validation');

            $this->form_validation->set_rules('nomeProfessor', 'Nome Completo', 'required|min_length[3]|max_length[20]', array('required' => 'O campo Nome Completo é obrigatorio.'));
            $this->form_validation->set_rules('email', 'E-mail', 'required|valid_email', array('required' => 'O campo E-mail é obrigatorio.'));
            $this->form_validation->set_rules('miniCurriculo', 'Mini Curriculo', 'required|max_length[240]');

            //VERIFICA SE O EMAIL È IGAUL
            if (isset($email_verifica)) {
                if ($email_verifica['email_verifica'] == TRUE) {
                     if ($this->form_validation->run() == FALSE) {
                        $form_nome = form_error('nomeProfessor'); 
                        $form_minicurriculo = form_error('miniCurriculo');    
                        //Mandando mensagem de erro
                        $this->session->set_flashdata('form_nome', "$form_nome" );
                        $this->session->set_flashdata('email_marcador', "$email_marcador");
                        $this->session->set_flashdata('form_minicurriculo', "$form_minicurriculo");
                        redirect("professores/professor_editar/$url");
                    }else{
                          $this->session->set_flashdata('email_marcador', "$email_marcador");
                          redirect("professores/professor_editar/$url");
                    }    
                } 
            }elseif ($marcador['email'] == TRUE) {
                if ($this->form_validation->run() == FALSE) {
                    $form_nome = form_error('nomeProfessor'); 
                    $form_minicurriculo = form_error('miniCurriculo');  
                    //Mandando mensagem de erro
                    $this->session->set_flashdata('form_nome', "$form_nome" );
                    $this->session->set_flashdata('email_erro', "$email_erro");
                    $this->session->set_flashdata('form_minicurriculo', "$form_minicurriculo");
                    redirect("professores/professor_editar/$url");
                }else{
                     $this->session->set_flashdata('email_erro', "$email_erro");
                     redirect("professores/professor_editar/$url");
                }      
            }else{
                if ($this->form_validation->run() == FALSE) {
                    $form_nome = form_error('nomeProfessor');  
                    $form_minicurriculo = form_error('miniCurriculo');  
                    $this->session->set_flashdata('form_nome', "$form_nome" );
                    $this->session->set_flashdata('form_minicurriculo', "$form_minicurriculo");
                    redirect("professores/professor_editar/$url");
                }else{
                        //SE O PROFESSOR NÂO QUISER TROCAR DE FOTO DE PERFIL
                        if($imgProfessor['name'] == null) {
                            $data = array(
                               'nomeProfessor' => $this->input->post('nomeProfessor'),
                                'dataNasc' => $this->input->post('dataNasc'),
                                'miniCurriculo' => $this->input->post('miniCurriculo'),
                                'instituicao' => $this->input->post('instituicao'),
                                'email' => $this->input->post('email'),
                            );
                            ///
                            if($this->input->post('disciplina_codDisciplina') != $this->input->post('verifica_disciplina_codDisciplina') ){
                                $data['admin'] = 0;
                            }
                            ///
                            $this->professor_model->professor_update(array('codProfessor' => $this->input->post('codProfessor')), $data);
                            ////
                                $data_prof_disc = array(
                                    'professores_codProfessor' => $this->input->post('codProfessor'),
                                    'disciplina_codDisciplina' => $this->input->post('disciplina_codDisciplina')
                                );
                                
                                $this->session->set_userdata('nome', $data['nomeProfessor']);
                                $this->professores_has_disciplinas_model->prof_disc_update(array('professores_codProfessor' => $this->input->post('codProfessor')), $data_prof_disc);
                            ////

                            echo json_encode(array("status" => TRUE));
                            //ENVIAR PARA A PAGINA PERFIL
                            redirect ("professores/professor_perfil/$url");

                        //SE O PROFESSOR QUISER TROCAR A FOTO DE PERFIL
                        }elseif(!empty($imgProfessor['name'])){
                            
                            $config = array(
                            'upload_path' => './upload/professores',
                            'allowed_types' => 'gif|jpg|png',//Arrumar essa parte
                            'file_name' => md5(time()),
                            'max_size' => '3000'
                            );
                            $this->load->library('upload');
                            $this->upload->initialize($config);
                            if ($this->upload->do_upload('imgProfessor')){
                                //EXCLUINDO A FOTO DE PERFIL ANTERIOR
                                $professor = $this->professor_model->get_img($this->input->post('codProfessor'));
                                $img = $professor->imgProfessor;    
                                $caminho = "upload/professores/$img";
                                $this->professor_model->delete_img($this->input->post('codProfessor'));
                                 echo json_encode(array("status" => TRUE));
                                unlink($caminho);
                                ///
                                echo 'Arquivo salvo com sucesso.';
                                $data = array(
                                    'nomeProfessor' => $this->input->post('nomeProfessor'),
                                    'dataNasc' => $this->input->post('dataNasc'),
                                    'imgProfessor' => $config['file_name'].".".$ponto_img,
                                    'miniCurriculo' => $this->input->post('miniCurriculo'),
                                    'instituicao' => $this->input->post('instituicao'),
                                    'email' => $this->input->post('email'),
                                );

                                ///
                                if($this->input->post('disciplina_codDisciplina') != $this->input->post('verifica_disciplina_codDisciplina') ){
                                    $data['admin'] = 0;
                                }
                                ///
                                $this->professor_model->professor_update(array('codProfessor' => $this->input->post('codProfessor')), $data);
                                $this->session->set_userdata('imgProfessor', $data['imgProfessor']);
                                $data_prof_disc = array(
                                    'professores_codProfessor' => $this->input->post('codProfessor'),
                                    'disciplina_codDisciplina' => $this->input->post('disciplina_codDisciplina')
                                );
                                 $this->professores_has_disciplinas_model->prof_disc_update(array('professores_codProfessor' => $this->input->post('codProfessor')), $data_prof_disc);
                                 $this->session->set_userdata('nome', $data['nomeProfessor']);
                                 ////

                                echo json_encode(array("status" => TRUE));

                                //ENVIAR PARA A PAGINA PERFIL
                                    redirect ("professores/professor_perfil/$url");
                            }else{
                               $upload_erro = $this->upload->display_errors();
                                $this->session->set_flashdata('upload_erro', "$upload_erro");
                                redirect("professores/professor_editar/$url");
                            }
                        }
                }
            }
            //SE O PROFESSOR QUISER TROCAR A SENHA
        }elseif($vereficaSenha != null){
                $this->load->library('form_validation');

                $this->form_validation->set_rules('nomeProfessor', 'Nome Completo', 'required|min_length[3]|max_length[20]', array('required' => 'O campo Nome Completo é obrigatorio.'));
                $this->form_validation->set_rules('email', 'E-mail', 'required|valid_email', array('required' => 'O campo E-mail é obrigatorio.'));
                $this->form_validation->set_rules('senha', 'Senha', 'required|min_length[8]', array('required' => 'Você deve preencher a %s.'));
                $this->form_validation->set_rules('senhaconf', 'Confirmar Senha', 'required|matches[senha]', array('required' => 'O campo Confirmar senha é obrigatorio'));

                if (isset($email_verifica)) {
                    if ($email_verifica['email_verifica'] == TRUE) {
                         if ($this->form_validation->run() == FALSE) {
                            $form_nome = form_error('nomeProfessor'); 
                            $form_senha = form_error('senha');
                            $form_senhaconf = form_error('senhaconf'); 
                            $form_minicurriculo = form_error('miniCurriculo');    
                            //Mandando mensagem de erro
                            $this->session->set_flashdata('form_senhaconf', "$form_senhaconf" );
                            $this->session->set_flashdata('form_senha', "$form_senha" );
                            $this->session->set_flashdata('form_nome', "$form_nome" );
                            $this->session->set_flashdata('email_marcador', "$email_marcador");
                             $this->session->set_flashdata('form_minicurriculo', "$form_minicurriculo");
                            redirect("professores/professor_editar/$url");
                        }    
                    } 
                }elseif ($marcador['email'] == TRUE) {
                    if ($this->form_validation->run() == FALSE) {
                        $form_nome = form_error('nomeProfessor'); 
                        $form_senha = form_error('senha');
                         $form_senhaconf = form_error('senhaconf');  
                        $form_nome = form_error('nomeProfessor'); 
                        $form_minicurriculo = form_error('miniCurriculo');   
                        //Mandando mensagem de erro
                        $this->session->set_flashdata('form_senhaconf', "$form_senhaconf" );
                        $this->session->set_flashdata('form_senha', "$form_senha" );
                        $this->session->set_flashdata('form_nome', "$form_nome" );
                        $this->session->set_flashdata('email_erro', "$email_erro");
                        $this->session->set_flashdata('form_minicurriculo', "$form_minicurriculo");
                        redirect("professores/professor_editar/$url");
                    }      
                }else{
                    if ($this->form_validation->run() == FALSE) {
                        $form_nome = form_error('nomeProfessor');   
                        $form_senha = form_error('senha');
                        $form_senhaconf = form_error('senhaconf');  
                        $form_nome = form_error('nomeProfessor');
                        $form_minicurriculo = form_error('miniCurriculo'); 
                        $this->session->set_flashdata('form_senhaconf', "$form_senhaconf" );
                        $this->session->set_flashdata('form_senha', "$form_senha" );  
                        $this->session->set_flashdata('form_nome', "$form_nome" );
                        $this->session->set_flashdata('form_minicurriculo', "$form_minicurriculo");
                        redirect("professores/professor_editar/$url");
                    }else{
                            if($imgProfessor['name'] == null) {
                                $data = array(
                                    'nomeProfessor' => $this->input->post('nomeProfessor'),
                                    'dataNasc' => $this->input->post('dataNasc'),
                                    'miniCurriculo' => $this->input->post('miniCurriculo'),
                                    'instituicao' => $this->input->post('instituicao'),
                                    'email' => $this->input->post('email'),
                                    'senha' => md5($this->input->post('senha')),
                                );

                                ///
                                if($this->input->post('disciplina_codDisciplina') != $this->input->post('verifica_disciplina_codDisciplina') ){
                                 $data['admin'] = 0;
                                }
                                ///
                                $this->professor_model->professor_update(array('codProfessor' => $this->input->post('codProfessor')), $data);
                                $this->session->set_userdata('nome', $data['nomeProfessor']);
                                echo json_encode(array("status" => TRUE));
                                //ENVIAR PARA A PAGINA PERFIL
                                redirect ("professores/professor_perfil/$url");

                            //SE O PROFESSOR QUISER TROCAR A FOTO DE PERFIL
                            }elseif(!empty($imgProfessor['name'])){
                                 //EXCLUINDO A FOTO DE PERFIL ANTERIOR
                                    $professor = $this->professor_model->get_img($this->input->post('codProfessor'));
                                    $img = $professor->imgProfessor;    
                                    $caminho = "upload/professores/$img";
                                    $this->professor_model->delete_img($this->input->post('codProfessor'));
                                    echo json_encode(array("status" => TRUE));
                                    unlink($caminho);
                                    ///
                                $config = array(
                                'upload_path' => './upload/professores',
                                'allowed_types' => 'gif|jpg|png',//Arrumar essa parte
                                'file_name' => md5(time()),
                                'max_size' => '3000'
                                );
                                $this->load->library('upload');
                                $this->upload->initialize($config);
                                if ($this->upload->do_upload('imgProfessor')){
                                    echo 'Arquivo salvo com sucesso.';
                                    $data = array(
                                       'nomeProfessor' => $this->input->post('nomeProfessor'),
                                        'dataNasc' => $this->input->post('dataNasc'),
                                        'imgProfessor' => $config['file_name'].".".$ponto_img,
                                        'miniCurriculo' => $this->input->post('miniCurriculo'),
                                        'instituicao' => $this->input->post('instituicao'),
                                        'email' => $this->input->post('email'),
                                        'senha' => md5($this->input->post('senha')),
                                    );
                                    ///
                                     if($this->input->post('disciplina_codDisciplina') != $this->input->post('verifica_disciplina_codDisciplina') ){
                                        $data['admin'] = 0;
                                    }
                                    ///
                                    $this->professor_model->professor_update(array('codProfessor' => $this->input->post('codProfessor')), $data);
                                    $this->session->set_userdata('imgProfessor', $data['imgProfessor']);
                                    $this->session->set_userdata('nome', $data['nomeProfessor']);
                                    echo json_encode(array("status" => TRUE));

                                    //ENVIAR PARA A PAGINA PERFIL
                                        redirect ("professores/professor_perfil/$url");
                                }else{
                                    $upload_erro = $this->upload->display_errors();
                                    $this->session->set_flashdata('upload_erro', "$upload_erro");
                                    redirect("professores/professor_editar/$url");
                                }
                            }
                    } 
                }  
        }
    }

    public function professor_delete_img($codProfessor)
    {
        $professor = $this->professor_model->get_img($codProfessor);
        $img = $professor->imgProfessor;    
        $caminho = "upload/professores/$img";
        $this->professor_model->delete_img($codProfessor);
        echo json_encode(array("status" => TRUE));
        unlink($caminho);
        $this->session->set_userdata('imgProfessor', '');
    }

    public function professor_delete($codProfessor)
    {
        $this->professores_has_disciplinas_model->delete_by_id($codProfessor);
        $this->comentarios_model->delete_all_professor($codProfessor);
        $this->artigos_model->delete_all_professor($codProfessor);
        $this->professor_model->delete_by_id($codProfessor);
        echo json_encode(array("status" => TRUE));
        $this->session->set_userdata('professores');
    }

    public function professor_delete_admin($codProfessor)
    {
        $this->professores_has_disciplinas_model->delete_by_id($codProfessor);
        $this->comentarios_model->delete_all_professor($codProfessor);
        $this->artigos_model->delete_all_professor($codProfessor);
        $this->professor_model->delete_by_id($codProfessor);
        echo json_encode(array("status" => TRUE));
    }

    public function professor_perfil(){
        $codProfessor = $this->input->get('codProfessor');
        $professor['perfil'] = $this->professor_model->get_by_id($codProfessor);
        $professor['artigos'] = $this->artigos_model->get_all_id_professor($codProfessor);
        $this->load->view('professor_perfil', $professor);
        //print_r($professor);
    }

    public function professor_editar(){
        $codProfessor = $this->input->get('codProfessor');
        $professor['perfil'] = $this->professor_model->get_by_id($codProfessor);
        $this->load->view('professor_editar', $professor);
    }

    public function professor_admin(){
        $codProfessor = $this->input->get('codProfessor');
        $codDisciplina = $this->input->get('codDisciplina');
        $professor['admin'] = $this->professor_model->get_by_id($codProfessor);
        $professor['professores'] = $this->professor_model->get_all_professor($codDisciplina);
        $this->load->view('professor_view', $professor);
    }

    public function professor_admin_artigos(){
        $codProfessor = $this->input->get('codProfessor');
        $codDisciplina = $this->input->get('codDisciplina');
        $professor['admin'] = $this->professor_model->get_by_id($codProfessor);
        $professor['artigos_aluno'] = $this->disciplina_model->listar_artigos($codDisciplina);
        $professor['artigos_professor'] = $this->disciplina_model->listar_artigos_professor($codDisciplina);
        $this->load->view('professor_admin_artigos', $professor);
    }

    public function artigos_add(){
         $codProfessor = $this->input->get('codProfessor');
        $professor['perfil'] = $this->professor_model->get_by_id($codProfessor);
        $this->load->view('artigo_add', $professor);
    }

    public function artigos_view(){
        $codProfessor = $this->input->get('codProfessor');
        $this->load->library('pagination');
        $config = array(
            "base_url" => "http://localhost/ci_tcc/index.php/professores/artigos_view",
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
        $professor['pagination'] = $this->pagination->create_links();
        $offset = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $professor['artigos'] = $this->artigos_model->get_all_id_professor_pag($codProfessor, $config['per_page'],$offset );
        
        $this->load->view('artigos_view_professor', $professor);
    }

}