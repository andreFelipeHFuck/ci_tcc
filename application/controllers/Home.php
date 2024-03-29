<?php
/**
 * Created by PhpStorm.
 * User: Pichau
 * Date: 19/06/2019
 * Time: 19:14
 */

class Home extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('artigos_model');
        $this->load->model('busca_model');
        $this->load->model('disciplina_model');
    }

    public function index(){
        $data = array(
            'artigos' => null
        );
        $data['artigos'] = $this->artigos_model->get_artigos_by_data_aluno();
        $data['artigosProfessor'] = $this->artigos_model->get_artigos_by_data_professor();
        $this->load->view('home', $data);
    }

    public function resultado(){
         $buscar = $this->input->get('busca');
         $palavraChave =  array('Nenhuma','Biologia','Física','Química','Geografia','História','Pr.Textual','Portugues','Matemática','Inglês','Espanhol','Filosofia','Sociologia', 'Agropecuária','Informática','Química(tec)');
         foreach ($palavraChave as $key => $value) {
            if ($buscar == $value) {
                $disciplina =  $this->disciplina_model->get_by_nome($value);
                $url = "?codDisciplina=".$disciplina->codDisciplina;
                echo "$url";
                redirect ("disciplinas/disciplina_view/$url");
            }
          }
         $dados['listagem_aluno'] = $this->busca_model->buscar($buscar);
         $dados['listagem_professor'] = $this->busca_model->buscar_professor($buscar);
        $this->load->view('resultado', $dados);
    }

    public function procurar(){
         $term = $this->input->get('term');
         $this->db->select('titulo');
        $this->db->like('titulo', $term);
         $this->db->order_by("titulo", "asc");
        $this->db->limit(5);
        $data = $this->db->get("artigos")->result();
        echo json_encode( $data);
    }
    
    public function add(){
        $this->load->view('aluno_add');
    }

    public function login_home(){
            $this->load->view('login');
    }
    public function aluno_view(){
        $this->load->view('aluno_view');
    }

    public function aluno_editar(){
        $this->load->view('aluno_editar');
    }

    public function opiCad(){
        $this->load->view('opiCad');
    }

    public function aluno_add(){
        $this->load->view('aluno_add');
    }

    public function professor_add(){
        $this->load->view('professor_add');
    }

    public function artigo_page(){
        $this->load->view('artigo_page');
    }

    public function disciplina_view(){
        $this->load->view('disciplinas.php');
    }
    
    public function sobre(){
        $this->load->view('sobre_view');
    }

}
