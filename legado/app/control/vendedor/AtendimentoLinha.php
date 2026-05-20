<?php

class AtendimentoLinha extends TPage
{
    private static $database = 'erp_online';
    private static $activeRecord = 'Atendimento';
    private static $primaryKey = 'id';

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param = null )
    {
        try
        {
            parent::__construct();

            TTransaction::open(self::$database);

            if(!empty($param['target_container']))
            {
                $this->adianti_target_container = $param['target_container'];
            }

            $this->timeline = new TTimeline;
            $this->timeline->setItemDatabase(self::$database);
            $this->timelineCriteria = new TCriteria;

            if(!empty($param["cliente_id"] ?? ""))
        {
            TSession::setValue(__CLASS__.'load_filter_cliente_id', $param["cliente_id"] ?? "");
        }
        $filterVar = TSession::getValue(__CLASS__.'load_filter_cliente_id');
            $this->timelineCriteria->add(new TFilter('cliente_id', '=', $filterVar));

            $limit = 20;

            $this->timelineCriteria->setProperty('limit', $limit);
            $this->timelineCriteria->setProperty('order', 'id desc');

            $objects = Atendimento::getObjects($this->timelineCriteria);

            if ($objects)
            {
                // iterate the collection of active records
                foreach ($objects as $object)
                {

                    $id = $object->id;
                    $title = "{atendimento_tipo->id}";
                    $htmlTemplate = " {observacao} ";
                    $date = $object->horario_inicial;
                    $icon = 'fa:arrow-left bg-green';
                    $position = 'left';

                    if(empty($positionValue[$object->id]))
                    {
                        $lastPosition = (empty($lastPosition) || $lastPosition == 'right') ? 'left' : 'right';
                        $bg = ($lastPosition == 'left') ? 'bg-green' : 'bg-blue';

                        $positionValue[$object->id]['position'] = $lastPosition;
                        $positionValue[$object->id]['bg'] = $bg;
                        $position = $positionValue[$object->id]['position'];
                        $icon = "fa:arrow-{$lastPosition} {$bg}";
                    }
                    else
                    {
                        $position = $positionValue[$object->id]['position'];
                        $lastPosition = $position;
                        $icon = "fa:arrow-{$lastPosition} {$positionValue[$object->id]['bg']}";
                    }

                    $oAtendimento = AtendimentoTipo::find( $object->atendimento_tipo->id ) ;   
                    //$title = "{atendimento_tipo->descricao}";
                    if($oAtendimento){

                        //$htmlTemplate = var_dump($oAtendimento);

                        $icone = new TElement('i');
                        $icone->style="; color:'{$oAtendimento->cor}';"; //; 
                        $icone->class="{$oAtendimento->icone}";

                        $icon = $icone;

                        $div = new TElement('span');
                        $div->class="label label-default";
                        $div->style="background-color:{$oAtendimento->cor} "; //width:120px; text-shadow:none;
                        $div->add($icone);
                        $div->add(" ");
                        $div->add($oAtendimento->descricao);

                        $title = $div;
                        /*
                        $cReturn  = "<span class='label label-default' ";
                        $cReturn .= "style='background-color:{$oEstado->cor} '>"; 
                        $cReturn .= "<i class='{$oEstado->icone}' ; style='; color: {$oEstado->cor_texto}'>{$oEstado->descricao}</i> ";
                        $cReturn .= "</span>";
                        */
                    }                    

                    $this->timeline->addItem($id, $title, $htmlTemplate, $date, $icon, $position, $object);

                }
            }

            $this->timeline->setUseBothSides();
            $this->timeline->setTimeDisplayMask('dd/mm/yyyy');
            $this->timeline->setFinalIcon( 'fas:flag-checkered #ffffff #de1414' );

            $action_AtendimentoForm_onEdit = new TAction(['AtendimentoForm', 'onEdit'], ['key'=> '{id}']);

            $action_AtendimentoForm_onEdit->setProperty('btn-class', 'btn btn-default');
            $this->timeline->addAction($action_AtendimentoForm_onEdit, "Editar", 'fas:edit #000000'); 

            $container = new TVBox;

            $container->style = 'width: 100%';
            $container->class = 'form-container';
            if(empty($param['target_container']))
            {    
                $container->add(TBreadCrumb::create(["Vendedor","AtendimentoLinha"]));
            }
            $container->add($this->timeline);

            TTransaction::close();

            parent::add($container);
        }
        catch(Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }

    public function onShow($param = null)
    {

    } 

}

