<?php include 'cabeca.php';?>
<?php 
  $teste = isset($_SESSION['professores']);
  if($teste == TRUE){
?>
<div class="container">
    <br><br><br/><br/>
    <table id="" class="table table-striped table-bordered" >

        <thead>
        <tr>

            <th>Nome</th>
            <th>Email</th>
            <th>Excluir</th>
        </tr>
        </thead>
        <tbody>

        <?php foreach($professores as $professor){?>
            <?php if($_SESSION['professores'] != $professor->codProfessor):?>
                <tr>
                    <td><a href="<?php echo site_url('professores/professor_perfil/')?>?codProfessor=<?php echo $professor->codProfessor;?>"><?php echo $professor->nomeProfessor;?></a></td>
                    <td><?php echo $professor->email;?></td>
                    <td>
                        <button class="btn btn-danger" onclick="delete_professor(<?php echo $professor->codProfessor;?>)"><i class="glyphicon glyphicon-remove"></i>EXCLUIR</button>
                    </td>
                </tr>
            <?php endif?>
        <?php }?>

    </table>
</div>
<?php }else{?>

        <meta http-equiv="refresh" content="0;url=<?php echo site_url('')?>" />
<?php } ?>
<?php include "rodape.php"; ?>


<script>
    $(document).ready( function () {
        $('#codProfessor').DataTable();
    } );
    var save_method; //for save method string
    var table;
    function add_professor()
    {
        save_method = 'add';
        $('#form')[0].reset(); // reset form on modals
        $('#modal_form').modal('show'); // show bootstrap modal
        //$('.modal-title').text('Add Person'); // Set Title to Bootstrap modal title
    }
    function edit_professor(codProfessor)
    {
        save_method = 'update';
        $('#form')[0].reset(); // reset form on modals
        //Ajax Load data from ajax
        $.ajax({
            url : "<?php echo site_url('professores/ajax_edit')?>/" + codProfessor,
            type: "GET",
            dataType: "JSON",
            success: function(data)
            {
                $('[name="codProfessor"]').val(data.codProfessor);
                $('[name="nomeProfessor"]').val(data.nomeProfessor);
                $('[name="email"]').val(data.email);
                $('[name="senha"]').val(data.senha);


                $('#modal_form').modal('show'); // show bootstrap modal when complete
                loaded
                $('.modal-title').text('Editar Professor'); // Set title to Bootstrap modal
                title
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Erro no ajax');
            }
        });
    }
    function save()
    {
        var url;
        if(save_method == 'add')
        {
            url = "<?php echo site_url('professores/professor_add')?>";
        }else{
            url = "<?php echo site_url('professores/professor_update')?>";
        }
        // ajax adding data to database
        $.ajax({
            url : url,
            type: "POST",
            data: $('#form').serialize(),
            dataType: "JSON",
            success: function(data)
            {
                //if success close modal and reload ajax table
                $('#modal_form').modal('hide');
                location.reload();// for reload a page
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error adding / update data');
            }
        });
    }
    function delete_professor(codProfessor)
    {
        if(confirm('Voce quer deletar o professor?'))
        {
            // ajax delete data from database
            $.ajax({
                url : "<?php echo site_url('professores/professor_delete')?>/" + codProfessor,
                type: "POST",
                dataType: "JSON",
                success: function(data)
                {
                    location.reload();
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Erro ao deletar');
                }
            });
        }
    }
</script>
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Professor Form</h3>
                <button type="button" class="close" data-dismiss="modal" aria-
                        label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="codProfessor"/>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Nome</label>
                            <div class="col-md-9">

                                <input name="nomeProfessor" placeholder="Book ISBN" class="form-
    control" type="text">

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">email</label>
                            <div class="col-md-9">

                                <input name="email" placeholder="Book_title" class="form-
    control" type="text">

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">senha</label>
                            <div class="col-md-9">

                                <input name="senha" placeholder="Book Author" class="form-control" type="password">
                            </div>
                        </div>

                    </div>
                </form>
            </div>
            <div class="modal-footer">

                <button type="button" id="btnSave" onclick="save()" class="btn btn-
                        primary">Save</button>

                <button type="button" class="btn btn-danger" data-
                        dismiss="modal">Cancel</button>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->
