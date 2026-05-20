<?php

class BlogView extends TPage
{
    public function __construct($param)
    {
        parent::__construct();
        TPage::include_css('app/lib/include/css/blogview.css');
//var_dump($param);
        
        $banner = new THtmlRenderer('app/resources/BlogBanner.html');
        $conteudo = new THtmlRenderer('app/resources/BlogView.html');
        $conteudo->disableHtmlConversion();
        
        $banner->enableSection('main', array());
        $conteudo->enableSection('main', array());
        
        
        
        TTransaction::open('portal_erp');
        $criteria = new TCriteria;
        $criteria->setProperty('limit' , 5);
        $criteria->setProperty('order' , 'id');
        $criteria->setProperty('direction' , 'desc');
        $repository = new TRepository('BlogNoticias');
        $criteria->add(new TFilter('ativo',   '=',      1));
        $dados_noticias = $repository->load($criteria);
        TTransaction::close();

        if(!empty($dados_noticias)){
            
            $noticias = array();
            foreach($dados_noticias as $noticia){
                $date = str_replace('-"', '/', $noticia->data_postagem);
                $newDate = date("d/m/Y", strtotime($date));
                
                $noticias[] = array(
                                    'titulo' => $noticia->titulo,
                                    'texto'=> $noticia->texto,
                                    'data_postagem' => $newDate,
                                    'autor' => $noticia->autor,
                                    'imagem' => $noticia->imagem,
                                    );
            }
            
            $conteudo->enableSection('blog-noticias', $noticias, TRUE);
            
        }
        
        
        
        
        $hoje = date('Y-m-d');
        $hj = date('w',strtotime($hoje));
        if($hj == "0"){
            $domingo = $hoje;
            $segunda = date('Y-m-d',strtotime('+1 days'));
            $terca = date('Y-m-d',strtotime('+2 days'));
            $quarta = date('Y-m-d',strtotime('+3 days'));
            $quinta = date('Y-m-d',strtotime('+4 days'));
            $sexta = date('Y-m-d',strtotime('+5 days'));
            $sabado = date('Y-m-d',strtotime('+6 days'));
        }else{
            $domingo = date('Y-m-d',strtotime('last sunday'));
            $segunda = date('Y-m-d',strtotime('last sunday +1 days'));
            $terca = date('Y-m-d',strtotime('last sunday +2 days'));
            $quarta = date('Y-m-d',strtotime('last sunday +3 days'));
            $quinta = date('Y-m-d',strtotime('last sunday +4 days'));
            $sexta = date('Y-m-d',strtotime('last sunday +5 days'));
            $sabado = date('Y-m-d',strtotime('last sunday +6 days'));
        }
        //$hj = date('w',strtotime($sabado));
        //var_dump($hj); 
 
        
        TTransaction::open('portal_erp');
        $dia = date('d',strtotime($domingo));
        $mes = date('m',strtotime($domingo));
        $criteria = new TCriteria;
        $criteria->add( new TFilter( 'dia', '=', $dia ));
        $criteria->add( new TFilter( 'mes', '=', $mes ));
        $criteria->setProperty('order' , 'id');
        $repository = new TRepository('BlogAniversarios');
        $dados_aniv_domingo = $repository->load($criteria);
        TTransaction::close();
        
        TTransaction::open('portal_erp');
        $dia = date('d',strtotime($segunda));
        $mes = date('m',strtotime($segunda));
        $criteria = new TCriteria;
        $criteria->add( new TFilter( 'dia', '=', $dia ));
        $criteria->add( new TFilter( 'mes', '=', $mes ));
        $criteria->setProperty('order' , 'id');
        $repository = new TRepository('BlogAniversarios');
        $dados_aniv_segunda = $repository->load($criteria);
        TTransaction::close();
        
        TTransaction::open('portal_erp');
        $dia = date('d',strtotime($terca));
        $mes = date('m',strtotime($terca));
        $criteria = new TCriteria;
        $criteria->add( new TFilter( 'dia', '=', $dia ));
        $criteria->add( new TFilter( 'mes', '=', $mes ));
        $criteria->setProperty('order' , 'id');
        $repository = new TRepository('BlogAniversarios');
        $dados_aniv_terca = $repository->load($criteria);
        TTransaction::close();
        
        TTransaction::open('portal_erp');
        $dia = date('d',strtotime($quarta));
        $mes = date('m',strtotime($quarta));
        $criteria = new TCriteria;
        $criteria->add( new TFilter( 'dia', '=', $dia ));
        $criteria->add( new TFilter( 'mes', '=', $mes ));
        $criteria->setProperty('order' , 'id');
        $repository = new TRepository('BlogAniversarios');
        $dados_aniv_quarta = $repository->load($criteria);
        TTransaction::close();
        
        TTransaction::open('portal_erp');
        $dia = date('d',strtotime($quinta));
        $mes = date('m',strtotime($quinta));
        $criteria = new TCriteria;
        $criteria->add( new TFilter( 'dia', '=', $dia ));
        $criteria->add( new TFilter( 'mes', '=', $mes ));
        $criteria->setProperty('order' , 'id');
        $repository = new TRepository('BlogAniversarios');
        $dados_aniv_quinta = $repository->load($criteria);
        TTransaction::close();
        
        TTransaction::open('portal_erp');
        $dia = date('d',strtotime($sexta));
        $mes = date('m',strtotime($sexta));
        $criteria = new TCriteria;
        $criteria->add( new TFilter( 'dia', '=', $dia ));
        $criteria->add( new TFilter( 'mes', '=', $mes ));
        $criteria->setProperty('order' , 'id');
        $repository = new TRepository('BlogAniversarios');
        $dados_aniv_sexta = $repository->load($criteria);
        TTransaction::close();
        
        TTransaction::open('portal_erp');
        $dia = date('d',strtotime($sabado));
        $mes = date('m',strtotime($sabado));
        $criteria = new TCriteria;
        $criteria->add( new TFilter( 'dia', '=', $dia ));
        $criteria->add( new TFilter( 'mes', '=', $mes ));
        $criteria->setProperty('order' , 'id');
        $repository = new TRepository('BlogAniversarios');
        $dados_aniv_sabado = $repository->load($criteria);
        TTransaction::close();
        
        if(!empty($dados_aniv_domingo) || !empty($dados_aniv_segunda) || !empty($dados_aniv_terca) || !empty($dados_aniv_quarta) || !empty($dados_aniv_quinta) || !empty($dados_aniv_sexta) || !empty($dados_aniv_sabado)){
             $conteudo->enableSection('blog-anivcard', array());
             
             
             $aniversarios = array();
             
             if(!empty($dados_aniv_domingo)){
                 foreach($dados_aniv_domingo as $aniversario){
                     if($hj == 0){
                         $diasem = 'Hoje';
                     }else{
                         $diasem = 'Domingo';
                     }
                     TTransaction::open('portal_erp');
                     $fil = new Filial($aniversario->filial_id);
                     TTransaction::close();
                     $aniversarios[] = array(
                                         'nome' => mb_convert_case($aniversario->nome, MB_CASE_TITLE, "UTF-8"),
                                         'filial' => mb_convert_case($fil->municipio, MB_CASE_TITLE, "UTF-8"),
                                         'dia' => $diasem,
                                         );
                 }
             }
             if(!empty($dados_aniv_segunda)){
                 foreach($dados_aniv_segunda as $aniversario){
                     if($hj == 1){
                         $diasem = 'Hoje';
                     }else{
                         $diasem = 'Segunda';
                     }
                     TTransaction::open('portal_erp');
                     $fil = new Filial($aniversario->filial_id);
                     TTransaction::close();
                     $aniversarios[] = array(
                                         'nome' => mb_convert_case($aniversario->nome, MB_CASE_TITLE, "UTF-8"),
                                         'filial' => mb_convert_case($fil->municipio, MB_CASE_TITLE, "UTF-8"),
                                         'dia' => $diasem,
                                         );
                 }
             }
             if(!empty($dados_aniv_terca)){
                 foreach($dados_aniv_terca as $aniversario){
                     if($hj == 2){
                         $diasem = 'Hoje';
                     }else{
                         $diasem = 'Terça';
                     }
                     TTransaction::open('portal_erp');
                     $fil = new Filial($aniversario->filial_id);
                     TTransaction::close();
                     $aniversarios[] = array(
                                         'nome' => mb_convert_case($aniversario->nome, MB_CASE_TITLE, "UTF-8"),
                                         'filial' => mb_convert_case($fil->municipio, MB_CASE_TITLE, "UTF-8"),
                                         'dia' => $diasem,
                                         );
                 }
             }
             if(!empty($dados_aniv_quarta)){
                 foreach($dados_aniv_quarta as $aniversario){
                     if($hj == 3){
                         $diasem = 'Hoje';
                     }else{
                         $diasem = 'Quarta';
                     }
                     TTransaction::open('portal_erp');
                     $fil = new Filial($aniversario->filial_id);
                     TTransaction::close();
                     $aniversarios[] = array(
                                         'nome' => mb_convert_case($aniversario->nome, MB_CASE_TITLE, "UTF-8"),
                                         'filial' => mb_convert_case($fil->municipio, MB_CASE_TITLE, "UTF-8"),
                                         'dia' => $diasem,
                                         );
                 }
             }
             if(!empty($dados_aniv_quinta)){
                 foreach($dados_aniv_quinta as $aniversario){
                     if($hj == 4){
                         $diasem = 'Hoje';
                     }else{
                         $diasem = 'Quinta';
                     }
                     TTransaction::open('portal_erp');
                     $fil = new Filial($aniversario->filial_id);
                     TTransaction::close();
                     $aniversarios[] = array(
                                         'nome' => mb_convert_case($aniversario->nome, MB_CASE_TITLE, "UTF-8"),
                                         'filial' => mb_convert_case($fil->municipio, MB_CASE_TITLE, "UTF-8"),
                                         'dia' => $diasem,
                                         );
                 }
             }
             if(!empty($dados_aniv_sexta)){
                 foreach($dados_aniv_sexta as $aniversario){
                     if($hj == 5){
                         $diasem = 'Hoje';
                     }else{
                         $diasem = 'Sexta';
                     }
                     TTransaction::open('portal_erp');
                     $fil = new Filial($aniversario->filial_id);
                     TTransaction::close();
                     $aniversarios[] = array(
                                         'nome' => mb_convert_case($aniversario->nome, MB_CASE_TITLE, "UTF-8"),
                                         'filial' => mb_convert_case($fil->municipio, MB_CASE_TITLE, "UTF-8"),
                                         'dia' => $diasem,
                                         );
                 }
             }
             if(!empty($dados_aniv_sabado)){
                 foreach($dados_aniv_sabado as $aniversario){
                     if($hj == 6){
                         $diasem = 'Hoje';
                     }else{
                         $diasem = 'Sabado';
                     }
                     TTransaction::open('portal_erp');
                     $fil = new Filial($aniversario->filial_id);
                     TTransaction::close();
                     $aniversarios[] = array(
                                         'nome' => mb_convert_case($aniversario->nome, MB_CASE_TITLE, "UTF-8"),
                                         'filial' => mb_convert_case($fil->municipio, MB_CASE_TITLE, "UTF-8"),
                                         'dia' => $diasem,
                                         );
                 }
             }

             $conteudo->enableSection('blog-anivlist', $aniversarios, TRUE);
        }
        
        
        
        

        
        


        TTransaction::open('portal_erp');
        $criteria = new TCriteria;
        $criteria->setProperty('limit' , 10);
        //$criteria->setProperty('order' , 'id');
        //$criteria->setProperty('direction' , 'desc');
        $criteria->setProperty('order' , 'ordenacao');
        $repository = new TRepository('BlogComunicados');
        $criteria->add(new TFilter('ativo',   '=',      1));
        $dados_comun = $repository->load($criteria);
        TTransaction::close();

        if(!empty($dados_comun)){
            
            $comunicados = array();
            foreach($dados_comun as $comunicado){
                $date = str_replace('-"', '/', $comunicado->data_postagem);
                $newDate = date("d/m/Y", strtotime($date));
                if(empty($comunicado->link_texto) && !empty($comunicado->link_externo)){
                    $comunicado->link_texto = $comunicado->link_externo;
                }
    
                $comunicados[] = array(
                                        'titulo' => $comunicado->titulo,
                                        'texto'=> $comunicado->texto,
                                        'data_postagem' => $newDate,
                                        'link_externo' => $comunicado->link_externo,
                                        'link_texto' => $comunicado->link_texto,
                                        );
            }
            
            $conteudo->enableSection('blog-comunicados', $comunicados, TRUE);
            
        }



        $vbox = TVBox::pack($banner, $conteudo);
        $vbox->style = 'display:block; width: 100%; margin 0 !important; padding 0 !important;';
        parent::add($vbox);
    }
    
    // função executa ao clicar no item de menu
    public function onShow($param = null)
    {
        
        

        
    }
}
