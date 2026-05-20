<?php

class LegendaVendedor extends TPage
{

    private $datagrid;
    
    public function __construct()
    {
        parent::__construct();
        
        // creates one datagrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->style = 'width: 100%';
        
        // add the columns
        //$this->datagrid->addColumn( new TDataGridColumn('code',  'Code', 'center', '20%') );
        //$this->datagrid->addColumn( new TDataGridColumn('task',  'Task', 'left',   '40%') );
        $column = $this->datagrid->addColumn( new TDataGridColumn('percent', 'Percentual', 'center', '100%') );
        
        // define the transformer method over image
        $column->setTransformer( function($percent) {
            $bar = new TProgressBar;
            $bar->setMask('<b>{value}</b>%');
            $bar->setValue($percent);
            
            if ($percent > 100) {
                $bar->setClass('progress-bar progress-bar-striped');
            }
            else if ($percent >= 75 and $percent < 100) {
                $bar->setClass('progress-bar progress-bar-striped bg-success');
            }
            else if ($percent >= 50 and $percent < 75) {
                $bar->setClass('progress-bar progress-bar-striped bg-info');
            }
            else if ($percent >= 25 and $percent < 50) {
                $bar->setClass('progress-bar progress-bar-striped bg-warning');
            }
            else if ($percent < 25) {
                $bar->setClass('progress-bar progress-bar-striped bg-danger');
            }
            return $bar;
        });
        
        // creates the datagrid model
        $this->datagrid->createModel();
        
        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        //$vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add(TPanelGroup::pack(_t('Legendas:'), $this->datagrid, '1.0'));
        parent::add($vbox);
    }
    
    /**
     * Load the data into the datagrid
     */
    function onReload()
    {
        $this->datagrid->clear();
        
        // add an regular object to the datagrid
        $item = new StdClass;
        $item->code      = '1';
        $item->task      = 'Install Ubuntu Server';
        $item->percent   = '101';
        $this->datagrid->addItem($item);
        
        // add an regular object to the datagrid
        $item = new StdClass;
        $item->code      = '2';
        $item->task      = 'Install Apache';
        $item->percent   = '76';
        $this->datagrid->addItem($item);
        
        // add an regular object to the datagrid
        $item = new StdClass;
        $item->code      = '3';
        $item->task      = 'Install PHP';
        $item->percent   = '51';
        $this->datagrid->addItem($item);
        
        // add an regular object to the datagrid
        $item = new StdClass;
        $item->code      = '4';
        $item->task      = 'Install PostgreSQL';
        $item->percent   = '26';
        $this->datagrid->addItem($item);
        
                $item = new StdClass;
        $item->code      = '5';
        $item->task      = 'Install PostgreSQL';
        $item->percent   = '10';
        $this->datagrid->addItem($item);
    }
    
    /**
     * shows the page
     */
    function show()
    {
        $this->onReload();
        parent::show();
    }
}
