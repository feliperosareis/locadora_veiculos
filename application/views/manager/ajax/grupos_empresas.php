<div id="campo_busca">
    <input type="text" id="filtra_busca">
</div>
<div id="lista_grupos">
    <?php 
    $grupo = '';
//    print_r($empresas);
    foreach($empresas as $key => $empresa){
        if($empresa['GRUPO'] != $grupo){
            $grupo = $empresa['GRUPO'];
            echo '<div id="grupos" nome="'.$empresa['GRUPO'].'">
                    <div class="grupo" marcar="sim">'.$empresa['GRUPO'].'</div>';
        }
        echo '<div id="empresas" grupo="'.$empresa['GRUPO'].'" nome="'.$empresa['NOME'].'"><input type="checkbox" name="empresas_id[]" '.@$empresa['marcado'].' value="'.$empresa['ID'].'">'.$empresa['NOME'].'</div>';
        if(@$empresas[$key + 1]['GRUPO'] != $grupo){
            echo '</div>';
        }
    }
    ?>
    </div>    

<style>
#lista_grupos{
    width: 510px;
    min-height: 0px;
    overflow-y: scroll;
    border: rgb(143, 140, 140) 1px solid;
    max-height: 130px    
}
#empresas input{
    margin: 5px;
}

[marcar]{
    background: rgb(148, 148, 148);
    font-weight: bold;
    cursor: pointer;
}
</style>
<script>
    $(document).ready(function() {    
        $("#filtra_busca").on("keyup", function() {
            $( "#lista_grupos div#empresas:not(:contains('"+$(this).val()+"'))").each(function() {
                $(this).hide();
                $('#grupos[nome="'+$(this).attr('grupo')+'"]').hide();
            });
            $( "#lista_grupos div#empresas:contains('"+$(this).val()+"')").each(function() {
                $('#grupos[nome="'+$(this).attr('grupo')+'"]').show();        
                $(this).show();
            });
        });
        
        $(".grupo").on("click", function() {
            if($(this).attr('marcar') == 'sim'){
                $( "#grupos[nome='"+$(this).html()+"']" ).find( "div#empresas input:visible(:not(:checked))").attr("checked", true);
                $(this).attr('marcar','não');
                $("#empresas input").trigger("change");
            }else{
                $( "#grupos[nome='"+$(this).html()+"']" ).find( "div#empresas input:visible(:checked)").attr("checked", false);
                $("#empresas input").trigger("change");
                $(this).attr('marcar','sim');
                $("#empresas input").trigger("change");
            }
            
        });
    });   
</script>