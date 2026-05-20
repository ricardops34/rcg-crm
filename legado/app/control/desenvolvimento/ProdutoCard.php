<?php

class ProdutoCard extends TPage
{
    private $form; // form
    private $cardView; // listing
    private $pageNavigation;
    private $loaded;
    private $filter_criteria;
    private static $database = 'erp_online';
    private static $activeRecord = 'Produto';
    private static $primaryKey = 'id';
    private static $formName = 'form_ProdutoCard';
    private $showMethods = ['onReload', 'onSearch'];

    private $filtrarativo = false;
    private $filtrarbloqueado = false;
    private $comsaldo = false;
    private $semsaldo = false;

    /**
     * Class constructor
     * Creates the page, the form and the listing
     */
    public function __construct()
    {
        parent::__construct();
        
        $body = '';
        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);

        // define the form title
        $this->form->setFormTitle("Produtos");

        $cod_erp = new TEntry('cod_erp');
        $descricao = new TEntry('descricao');
        $filtro_situacao = new TCombo('filtro_situacao');
        $filtro_saldo = new TCombo('filtro_saldo');

        $filtro_saldo->addItems(["com"=>"Com Saldo","sem"=>"Sem Saldo"]);
        $filtro_situacao->addItems(["ativo"=>"Ativo","bloqueado"=>"Bloqueados"]);

        $cod_erp->setMaxLength(30);
        $descricao->setMaxLength(100);

        $filtro_saldo->enableSearch();
        $filtro_situacao->enableSearch();

        $cod_erp->setSize('100%');
        $descricao->setSize('100%');
        $filtro_saldo->setSize('100%');
        $filtro_situacao->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Código:", null, '14px', null, '100%'),$cod_erp],[new TLabel("Descrição:", null, '14px', null, '100%'),$descricao],[new TLabel("Status:", null, '14px', null, '100%'),$filtro_situacao],[new TLabel("Saldo:", null, '14px', null, '100%'),$filtro_saldo],[]);
        $row1->layout = [' col-sm-2',' col-sm-4','col-sm-2',' col-sm-2','col-sm-2'];

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );

        $startHidden = true;

        if(TSession::getValue('ProdutoCard_expand_start_hidden') === false)
        {
            $startHidden = false;
        }
        elseif(TSession::getValue('ProdutoCard_expand_start_hidden') === true)
        {
            $startHidden = true; 
        }
        $expandButton = $this->form->addExpandButton("Filtro", 'fas:filter #000000', $startHidden);
        $expandButton->addStyleClass('btn-default');
        $expandButton->setAction(new TAction([$this, 'onExpandForm'], ['static'=>1]), "Filtro");
        $this->form->addField($expandButton);

        $btn_onsearch = $this->form->addAction("Buscar", new TAction([$this, 'onSearch']), 'fas:search #ffffff');
        $this->btn_onsearch = $btn_onsearch;
        $btn_onsearch->addStyleClass('btn-primary'); 

        $body = "<div>
            <div>
                <b>Descrição: </b>{descricao}
            </div>
        </div>
        <div>
            <div style='float:left;width:50%;padding-right:10px'>
                <b>Estoque: </b>{saldo_estoque}<br>
            </div>
            <div style='float:right;width:50%'>
                <img style='height:100px;float:right;margin:5px' src='{imagem}'>
            </div>
        </div>";

        $this->cardView = new TCardView;

        $this->cardView->setContentHeight(200);
        $this->cardView->setTitleTemplate('<b>{cod_erp}</b>');//'({status} == "A" ? "" : "<font color="red"> - (Bloqueado)</font>")'
        $this->cardView->setItemTemplate($body);
        $this->cardView->setItemDatabase(self::$database);

        $this->filter_criteria = new TCriteria;

        $filterVar = 'SV';
        $this->filter_criteria->add(new TFilter('tipo', '<>', $filterVar));

        $action_ProdutoForm_onEdit = new TAction(['ProdutoForm', 'onEdit'], ['key'=> '{id}']);

        $this->cardView->addAction($action_ProdutoForm_onEdit, "Editar", 'far:edit #478fca'); 

        $action_ProdutoCard_onDelete = new TAction(['ProdutoCard', 'onDelete'], ['key'=> '{id}']);

        $this->cardView->addAction($action_ProdutoCard_onDelete, "Excluir", 'fas:trash-alt #dd5a43','ProdutoCard::PodeExcluir'); 

        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->enableCounters();
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));

        $panel = new TPanelGroup;
        $panel->add($this->cardView);

        $panel->addFooter($this->pageNavigation);

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add(TBreadCrumb::create(["Vendedor","Produtos"]));
        $container->add($this->form);
        $container->add($panel);

        parent::add($container);

    }

    public function onDelete($param = null) 
    { 
        if(isset($param['delete']) && $param['delete'] == 1)
        {
            try
            {
                // get the paramseter $key
                $key = $param['key'];
                // open a transaction with database
                TTransaction::open(self::$database);

                // instantiates object
                $object = new Produto($key, FALSE); 

                // deletes the object from the database
                $object->delete();

                // close the transaction
                TTransaction::close();

                // reload the listing
                $this->onReload( $param );
                // shows the success message
                new TMessage('info', AdiantiCoreTranslator::translate('Record deleted'));
            }
            catch (Exception $e) // in case of exception
            {
                // shows the exception error message
                new TMessage('error', $e->getMessage());
                // undo all pending operations
                TTransaction::rollback();
            }
        }
        else
        {
            // define the delete action
            $action = new TAction(array($this, 'onDelete'));
            $action->setParameters($param); // pass the key paramseter ahead
            $action->setParameter('delete', 1);
            // shows a dialog to the user
            new TQuestion(AdiantiCoreTranslator::translate('Do you really want to delete ?'), $action);   
        }
    }
    public static function PodeExcluir($object)
    {
        try 
        {
            if($object)
            {
                //return true;
            }

            return false;
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    /**
     * Register the filter in the session
     */
    public function onSearch()
    {
        // get the search form data
        $data = $this->form->getData();
        $filters = [];

        TSession::setValue(__CLASS__.'_filter_data', NULL);
        TSession::setValue(__CLASS__.'_filters', NULL);

        if (isset($data->cod_erp) AND ( (is_scalar($data->cod_erp) AND $data->cod_erp !== '') OR (is_array($data->cod_erp) AND (!empty($data->cod_erp)) )) )
        {

            $filters[] = new TFilter('cod_erp', 'like', "%{$data->cod_erp}%");// create the filter 
        }

        if (isset($data->descricao) AND ( (is_scalar($data->descricao) AND $data->descricao !== '') OR (is_array($data->descricao) AND (!empty($data->descricao)) )) )
        {

            $filters[] = new TFilter('descricao', 'like', "%{$data->descricao}%");// create the filter 
        }

        if($this->filtrarativo OR $data->filtro_situacao == "ativo")
        {
            $data->filtro_situacao = "ativo";
            $filters[] = new TFilter('status', '=', "A");// create the filter 
        }

        if($this->filtrarbloqueado OR $data->filtro_situacao == "bloqueado")
        {
            $data->filtro_situacao = "bloqueado";
            $filters[] = new TFilter('status', '=', "B");// create the filter 
        }

        if ( $this->comsaldo OR $data->filtro_saldo == "com" )
        {
            $data->filtro_saldo = "com";
            $filters[] = new TFilter('id', 'in', "(SELECT produto_id FROM estoque WHERE saldo > 0 )");// create the filter 
        }

        if ( $this->semsaldo OR $data->filtro_saldo == "sem" )
        {
            $data->filtro_saldo = "sem";
            $filters[] = new TFilter('id', 'in', "(SELECT produto_id FROM estoque WHERE saldo <= 0 )");// create the filter 
        }


        $param = array();
        $param['offset']     = 0;
        $param['first_page'] = 1;
        
        // fill the form with data again
        $this->form->setData($data);

        // keep the search data in the session
        TSession::setValue(__CLASS__.'_filter_data', $data);
        TSession::setValue(__CLASS__.'_filters', $filters);

        $this->onReload($param);
    }

    public function onReload($param = NULL)
    {
        try
        {

            // open a transaction with database 'erp_online'
            TTransaction::open(self::$database);

            // creates a repository for Produto
            $repository = new TRepository(self::$activeRecord);
            $limit = 20;

            $criteria = clone $this->filter_criteria;

            if (empty($param['order']))
            {
                $param['order'] = 'cod_erp';    
            }

            if (empty($param['direction']))
            {
                $param['direction'] = 'asc';
            }

            $criteria->setProperties($param); // order, offset
            $criteria->setProperty('limit', $limit);

            if($filters = TSession::getValue(__CLASS__.'_filters'))
            {
                foreach ($filters as $filter) 
                {
                    $criteria->add($filter);       
                }
            }

            // load the objects according to criteria
            $objects = $repository->load($criteria, FALSE);

            $this->cardView->clear();
            if ($objects)
            {
                // iterate the collection of active records
                foreach ($objects as $object)
                {

                    //var_dump($object->status);

                    $this->cardView->addItem($object);

                }
            }

            // reset the criteria for record count
            $criteria->resetProperties();
            $count= $repository->count($criteria);

            $this->pageNavigation->setCount($count); // count of records
            $this->pageNavigation->setProperties($param); // order, page
            $this->pageNavigation->setLimit($limit); // limit

            // close the transaction
            TTransaction::close();
            $this->loaded = true;
        }
        catch (Exception $e) // in case of exception
        {
            // shows the exception error message
            new TMessage('error', $e->getMessage());
            // undo all pending operations
            TTransaction::rollback();
        }
    }

    public function onShow($param = null)
    {

    }

    /**
     * method show()
     * Shows the page
     */
    public function show()
    {
        if (!$this->loaded AND (!isset($_GET['method']) OR !(in_array($_GET['method'],  $this->showMethods))) )
        {
            if (func_num_args() > 0)
            {
                $this->onReload( func_get_arg(0) );
            }
            else
            {
                $this->onReload();
            }
        }
        parent::show();
    }

    public static function onExpandForm($param = null)
    {
        try
        {
            $startHidden = true;

            if(TSession::getValue('ProdutoCard_expand_start_hidden') === false)
            {
                TSession::setValue('ProdutoCard_expand_start_hidden', true);
            }
            elseif(TSession::getValue('ProdutoCard_expand_start_hidden') === true)
            {
                TSession::setValue('ProdutoCard_expand_start_hidden', false);
            }
            else
            {
                TSession::setValue('ProdutoCard_expand_start_hidden', !$startHidden);
            }

        }
        catch(Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }

}

