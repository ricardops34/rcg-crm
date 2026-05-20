<?php

class Produto extends TRecord
{
    const TABLENAME  = 'produto';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const CREATEDAT  = 'dt_inclusao';
    const UPDATEDAT  = 'dt_alteracao';

    private Filial $filial;
    private Categoria $categoria;
    private Armazem $armazem;
    private Fabricante $fabricante;
    private SubCategoria $sub_categoria;
    private TipoEntradaSaida $te;
    private TipoEntradaSaida $ts;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('filial_id');
        parent::addAttribute('cod_erp');
        parent::addAttribute('descricao');
        parent::addAttribute('tipo');
        parent::addAttribute('um');
        parent::addAttribute('categoria_id');
        parent::addAttribute('sub_categoria_id');
        parent::addAttribute('fabricante_id');
        parent::addAttribute('armazem_id');
        parent::addAttribute('codigo_barras');
        parent::addAttribute('codigo_fabricante');
        parent::addAttribute('qtd_embalagem');
        parent::addAttribute('observacao');
        parent::addAttribute('foto');
        parent::addAttribute('status');
        parent::addAttribute('ncm');
        parent::addAttribute('origem');
        parent::addAttribute('peso_bruto');
        parent::addAttribute('peso');
        parent::addAttribute('marca');
        parent::addAttribute('te_id');
        parent::addAttribute('ts_id');
        parent::addAttribute('sinc');
        parent::addAttribute('ponto_pedido');
        parent::addAttribute('estoque_seguranca');
        parent::addAttribute('dt_ult_compra');
        parent::addAttribute('ult_preco');
        parent::addAttribute('informacoes_tecnicas');
        parent::addAttribute('dados_tecnicos');
        parent::addAttribute('system_unit_id');
        parent::addAttribute('dt_inclusao');
        parent::addAttribute('dt_alteracao');
    
    }

    /**
     * Method set_filial
     * Sample of usage: $var->filial = $object;
     * @param $object Instance of Filial
     */
    public function set_filial(Filial $object)
    {
        $this->filial = $object;
        $this->filial_id = $object->id;
    }

    /**
     * Method get_filial
     * Sample of usage: $var->filial->attribute;
     * @returns Filial instance
     */
    public function get_filial()
    {
    
        // loads the associated object
        if (empty($this->filial))
            $this->filial = new Filial($this->filial_id);
    
        // returns the associated object
        return $this->filial;
    }
    /**
     * Method set_categoria
     * Sample of usage: $var->categoria = $object;
     * @param $object Instance of Categoria
     */
    public function set_categoria(Categoria $object)
    {
        $this->categoria = $object;
        $this->categoria_id = $object->id;
    }

    /**
     * Method get_categoria
     * Sample of usage: $var->categoria->attribute;
     * @returns Categoria instance
     */
    public function get_categoria()
    {
    
        // loads the associated object
        if (empty($this->categoria))
            $this->categoria = new Categoria($this->categoria_id);
    
        // returns the associated object
        return $this->categoria;
    }
    /**
     * Method set_armazem
     * Sample of usage: $var->armazem = $object;
     * @param $object Instance of Armazem
     */
    public function set_armazem(Armazem $object)
    {
        $this->armazem = $object;
        $this->armazem_id = $object->id;
    }

    /**
     * Method get_armazem
     * Sample of usage: $var->armazem->attribute;
     * @returns Armazem instance
     */
    public function get_armazem()
    {
    
        // loads the associated object
        if (empty($this->armazem))
            $this->armazem = new Armazem($this->armazem_id);
    
        // returns the associated object
        return $this->armazem;
    }
    /**
     * Method set_fabricante
     * Sample of usage: $var->fabricante = $object;
     * @param $object Instance of Fabricante
     */
    public function set_fabricante(Fabricante $object)
    {
        $this->fabricante = $object;
        $this->fabricante_id = $object->id;
    }

    /**
     * Method get_fabricante
     * Sample of usage: $var->fabricante->attribute;
     * @returns Fabricante instance
     */
    public function get_fabricante()
    {
    
        // loads the associated object
        if (empty($this->fabricante))
            $this->fabricante = new Fabricante($this->fabricante_id);
    
        // returns the associated object
        return $this->fabricante;
    }
    /**
     * Method set_sub_categoria
     * Sample of usage: $var->sub_categoria = $object;
     * @param $object Instance of SubCategoria
     */
    public function set_sub_categoria(SubCategoria $object)
    {
        $this->sub_categoria = $object;
        $this->sub_categoria_id = $object->id;
    }

    /**
     * Method get_sub_categoria
     * Sample of usage: $var->sub_categoria->attribute;
     * @returns SubCategoria instance
     */
    public function get_sub_categoria()
    {
    
        // loads the associated object
        if (empty($this->sub_categoria))
            $this->sub_categoria = new SubCategoria($this->sub_categoria_id);
    
        // returns the associated object
        return $this->sub_categoria;
    }
    /**
     * Method set_tipo_entrada_saida
     * Sample of usage: $var->tipo_entrada_saida = $object;
     * @param $object Instance of TipoEntradaSaida
     */
    public function set_te(TipoEntradaSaida $object)
    {
        $this->te = $object;
        $this->te_id = $object->id;
    }

    /**
     * Method get_te
     * Sample of usage: $var->te->attribute;
     * @returns TipoEntradaSaida instance
     */
    public function get_te()
    {
    
        // loads the associated object
        if (empty($this->te))
            $this->te = new TipoEntradaSaida($this->te_id);
    
        // returns the associated object
        return $this->te;
    }
    /**
     * Method set_tipo_entrada_saida
     * Sample of usage: $var->tipo_entrada_saida = $object;
     * @param $object Instance of TipoEntradaSaida
     */
    public function set_ts(TipoEntradaSaida $object)
    {
        $this->ts = $object;
        $this->ts_id = $object->id;
    }

    /**
     * Method get_ts
     * Sample of usage: $var->ts->attribute;
     * @returns TipoEntradaSaida instance
     */
    public function get_ts()
    {
    
        // loads the associated object
        if (empty($this->ts))
            $this->ts = new TipoEntradaSaida($this->ts_id);
    
        // returns the associated object
        return $this->ts;
    }

    /**
     * Method getNotaSaidaItems
     */
    public function getNotaSaidaItems()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('produto_id', '=', $this->id));
        return NotaSaidaItem::getObjects( $criteria );
    }
    /**
     * Method getPedidoItems
     */
    public function getPedidoItems()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('produto_id', '=', $this->id));
        return PedidoItem::getObjects( $criteria );
    }
    /**
     * Method getEstoques
     */
    public function getEstoques()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('produto_id', '=', $this->id));
        return Estoque::getObjects( $criteria );
    }
    /**
     * Method getOrcamentoItems
     */
    public function getOrcamentoItems()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('produto_id', '=', $this->id));
        return OrcamentoItem::getObjects( $criteria );
    }
    /**
     * Method getTabelaPrecoItems
     */
    public function getTabelaPrecoItems()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('produto_id', '=', $this->id));
        return TabelaPrecoItem::getObjects( $criteria );
    }
    /**
     * Method getVendaMesProdutos
     */
    public function getVendaMesProdutos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('produto_id', '=', $this->id));
        return VendaMesProduto::getObjects( $criteria );
    }
    /**
     * Method getProdutoCaracteristicas
     */
    public function getProdutoCaracteristicas()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('produto_id', '=', $this->id));
        return ProdutoCaracteristica::getObjects( $criteria );
    }
    /**
     * Method getFichaTecnicas
     */
    public function getFichaTecnicas()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('produto_id', '=', $this->id));
        return FichaTecnica::getObjects( $criteria );
    }

    public function set_nota_saida_item_nota_saida_to_string($nota_saida_item_nota_saida_to_string)
    {
        if(is_array($nota_saida_item_nota_saida_to_string))
        {
            $values = NotaSaida::where('id', 'in', $nota_saida_item_nota_saida_to_string)->getIndexedArray('id', 'id');
            $this->nota_saida_item_nota_saida_to_string = implode(', ', $values);
        }
        else
        {
            $this->nota_saida_item_nota_saida_to_string = $nota_saida_item_nota_saida_to_string;
        }

        $this->vdata['nota_saida_item_nota_saida_to_string'] = $this->nota_saida_item_nota_saida_to_string;
    }

    public function get_nota_saida_item_nota_saida_to_string()
    {
        if(!empty($this->nota_saida_item_nota_saida_to_string))
        {
            return $this->nota_saida_item_nota_saida_to_string;
        }
    
        $values = NotaSaidaItem::where('produto_id', '=', $this->id)->getIndexedArray('nota_saida_id','{nota_saida->id}');
        return implode(', ', $values);
    }

    public function set_nota_saida_item_produto_to_string($nota_saida_item_produto_to_string)
    {
        if(is_array($nota_saida_item_produto_to_string))
        {
            $values = Produto::where('id', 'in', $nota_saida_item_produto_to_string)->getIndexedArray('descricao', 'descricao');
            $this->nota_saida_item_produto_to_string = implode(', ', $values);
        }
        else
        {
            $this->nota_saida_item_produto_to_string = $nota_saida_item_produto_to_string;
        }

        $this->vdata['nota_saida_item_produto_to_string'] = $this->nota_saida_item_produto_to_string;
    }

    public function get_nota_saida_item_produto_to_string()
    {
        if(!empty($this->nota_saida_item_produto_to_string))
        {
            return $this->nota_saida_item_produto_to_string;
        }
    
        $values = NotaSaidaItem::where('produto_id', '=', $this->id)->getIndexedArray('produto_id','{produto->descricao}');
        return implode(', ', $values);
    }

    public function set_nota_saida_item_tes_to_string($nota_saida_item_tes_to_string)
    {
        if(is_array($nota_saida_item_tes_to_string))
        {
            $values = TipoEntradaSaida::where('id', 'in', $nota_saida_item_tes_to_string)->getIndexedArray('id', 'id');
            $this->nota_saida_item_tes_to_string = implode(', ', $values);
        }
        else
        {
            $this->nota_saida_item_tes_to_string = $nota_saida_item_tes_to_string;
        }

        $this->vdata['nota_saida_item_tes_to_string'] = $this->nota_saida_item_tes_to_string;
    }

    public function get_nota_saida_item_tes_to_string()
    {
        if(!empty($this->nota_saida_item_tes_to_string))
        {
            return $this->nota_saida_item_tes_to_string;
        }
    
        $values = NotaSaidaItem::where('produto_id', '=', $this->id)->getIndexedArray('tes_id','{tes->id}');
        return implode(', ', $values);
    }

    public function set_nota_saida_item_cliente_to_string($nota_saida_item_cliente_to_string)
    {
        if(is_array($nota_saida_item_cliente_to_string))
        {
            $values = Cliente::where('id', 'in', $nota_saida_item_cliente_to_string)->getIndexedArray('razao', 'razao');
            $this->nota_saida_item_cliente_to_string = implode(', ', $values);
        }
        else
        {
            $this->nota_saida_item_cliente_to_string = $nota_saida_item_cliente_to_string;
        }

        $this->vdata['nota_saida_item_cliente_to_string'] = $this->nota_saida_item_cliente_to_string;
    }

    public function get_nota_saida_item_cliente_to_string()
    {
        if(!empty($this->nota_saida_item_cliente_to_string))
        {
            return $this->nota_saida_item_cliente_to_string;
        }
    
        $values = NotaSaidaItem::where('produto_id', '=', $this->id)->getIndexedArray('cliente_id','{cliente->razao}');
        return implode(', ', $values);
    }

    public function set_nota_saida_item_vendedor1_to_string($nota_saida_item_vendedor1_to_string)
    {
        if(is_array($nota_saida_item_vendedor1_to_string))
        {
            $values = Vendedor::where('id', 'in', $nota_saida_item_vendedor1_to_string)->getIndexedArray('nome', 'nome');
            $this->nota_saida_item_vendedor1_to_string = implode(', ', $values);
        }
        else
        {
            $this->nota_saida_item_vendedor1_to_string = $nota_saida_item_vendedor1_to_string;
        }

        $this->vdata['nota_saida_item_vendedor1_to_string'] = $this->nota_saida_item_vendedor1_to_string;
    }

    public function get_nota_saida_item_vendedor1_to_string()
    {
        if(!empty($this->nota_saida_item_vendedor1_to_string))
        {
            return $this->nota_saida_item_vendedor1_to_string;
        }
    
        $values = NotaSaidaItem::where('produto_id', '=', $this->id)->getIndexedArray('vendedor1_id','{vendedor1->nome}');
        return implode(', ', $values);
    }

    public function set_nota_saida_item_vendedor2_to_string($nota_saida_item_vendedor2_to_string)
    {
        if(is_array($nota_saida_item_vendedor2_to_string))
        {
            $values = Vendedor::where('id', 'in', $nota_saida_item_vendedor2_to_string)->getIndexedArray('nome', 'nome');
            $this->nota_saida_item_vendedor2_to_string = implode(', ', $values);
        }
        else
        {
            $this->nota_saida_item_vendedor2_to_string = $nota_saida_item_vendedor2_to_string;
        }

        $this->vdata['nota_saida_item_vendedor2_to_string'] = $this->nota_saida_item_vendedor2_to_string;
    }

    public function get_nota_saida_item_vendedor2_to_string()
    {
        if(!empty($this->nota_saida_item_vendedor2_to_string))
        {
            return $this->nota_saida_item_vendedor2_to_string;
        }
    
        $values = NotaSaidaItem::where('produto_id', '=', $this->id)->getIndexedArray('vendedor2_id','{vendedor2->nome}');
        return implode(', ', $values);
    }

    public function set_pedido_item_pedido_to_string($pedido_item_pedido_to_string)
    {
        if(is_array($pedido_item_pedido_to_string))
        {
            $values = Pedido::where('id', 'in', $pedido_item_pedido_to_string)->getIndexedArray('id', 'id');
            $this->pedido_item_pedido_to_string = implode(', ', $values);
        }
        else
        {
            $this->pedido_item_pedido_to_string = $pedido_item_pedido_to_string;
        }

        $this->vdata['pedido_item_pedido_to_string'] = $this->pedido_item_pedido_to_string;
    }

    public function get_pedido_item_pedido_to_string()
    {
        if(!empty($this->pedido_item_pedido_to_string))
        {
            return $this->pedido_item_pedido_to_string;
        }
    
        $values = PedidoItem::where('produto_id', '=', $this->id)->getIndexedArray('pedido_id','{pedido->id}');
        return implode(', ', $values);
    }

    public function set_pedido_item_produto_to_string($pedido_item_produto_to_string)
    {
        if(is_array($pedido_item_produto_to_string))
        {
            $values = Produto::where('id', 'in', $pedido_item_produto_to_string)->getIndexedArray('descricao', 'descricao');
            $this->pedido_item_produto_to_string = implode(', ', $values);
        }
        else
        {
            $this->pedido_item_produto_to_string = $pedido_item_produto_to_string;
        }

        $this->vdata['pedido_item_produto_to_string'] = $this->pedido_item_produto_to_string;
    }

    public function get_pedido_item_produto_to_string()
    {
        if(!empty($this->pedido_item_produto_to_string))
        {
            return $this->pedido_item_produto_to_string;
        }
    
        $values = PedidoItem::where('produto_id', '=', $this->id)->getIndexedArray('produto_id','{produto->descricao}');
        return implode(', ', $values);
    }

    public function set_pedido_item_armazem_to_string($pedido_item_armazem_to_string)
    {
        if(is_array($pedido_item_armazem_to_string))
        {
            $values = Armazem::where('id', 'in', $pedido_item_armazem_to_string)->getIndexedArray('descricao', 'descricao');
            $this->pedido_item_armazem_to_string = implode(', ', $values);
        }
        else
        {
            $this->pedido_item_armazem_to_string = $pedido_item_armazem_to_string;
        }

        $this->vdata['pedido_item_armazem_to_string'] = $this->pedido_item_armazem_to_string;
    }

    public function get_pedido_item_armazem_to_string()
    {
        if(!empty($this->pedido_item_armazem_to_string))
        {
            return $this->pedido_item_armazem_to_string;
        }
    
        $values = PedidoItem::where('produto_id', '=', $this->id)->getIndexedArray('armazem_id','{armazem->descricao}');
        return implode(', ', $values);
    }

    public function set_estoque_filial_to_string($estoque_filial_to_string)
    {
        if(is_array($estoque_filial_to_string))
        {
            $values = Filial::where('id', 'in', $estoque_filial_to_string)->getIndexedArray('apelido', 'apelido');
            $this->estoque_filial_to_string = implode(', ', $values);
        }
        else
        {
            $this->estoque_filial_to_string = $estoque_filial_to_string;
        }

        $this->vdata['estoque_filial_to_string'] = $this->estoque_filial_to_string;
    }

    public function get_estoque_filial_to_string()
    {
        if(!empty($this->estoque_filial_to_string))
        {
            return $this->estoque_filial_to_string;
        }
    
        $values = Estoque::where('produto_id', '=', $this->id)->getIndexedArray('filial_id','{filial->apelido}');
        return implode(', ', $values);
    }

    public function set_estoque_produto_to_string($estoque_produto_to_string)
    {
        if(is_array($estoque_produto_to_string))
        {
            $values = Produto::where('id', 'in', $estoque_produto_to_string)->getIndexedArray('descricao', 'descricao');
            $this->estoque_produto_to_string = implode(', ', $values);
        }
        else
        {
            $this->estoque_produto_to_string = $estoque_produto_to_string;
        }

        $this->vdata['estoque_produto_to_string'] = $this->estoque_produto_to_string;
    }

    public function get_estoque_produto_to_string()
    {
        if(!empty($this->estoque_produto_to_string))
        {
            return $this->estoque_produto_to_string;
        }
    
        $values = Estoque::where('produto_id', '=', $this->id)->getIndexedArray('produto_id','{produto->descricao}');
        return implode(', ', $values);
    }

    public function set_estoque_armazem_to_string($estoque_armazem_to_string)
    {
        if(is_array($estoque_armazem_to_string))
        {
            $values = Armazem::where('id', 'in', $estoque_armazem_to_string)->getIndexedArray('descricao', 'descricao');
            $this->estoque_armazem_to_string = implode(', ', $values);
        }
        else
        {
            $this->estoque_armazem_to_string = $estoque_armazem_to_string;
        }

        $this->vdata['estoque_armazem_to_string'] = $this->estoque_armazem_to_string;
    }

    public function get_estoque_armazem_to_string()
    {
        if(!empty($this->estoque_armazem_to_string))
        {
            return $this->estoque_armazem_to_string;
        }
    
        $values = Estoque::where('produto_id', '=', $this->id)->getIndexedArray('armazem_id','{armazem->descricao}');
        return implode(', ', $values);
    }

    public function set_orcamento_item_orcamento_to_string($orcamento_item_orcamento_to_string)
    {
        if(is_array($orcamento_item_orcamento_to_string))
        {
            $values = Orcamento::where('id', 'in', $orcamento_item_orcamento_to_string)->getIndexedArray('dt_faturamento', 'dt_faturamento');
            $this->orcamento_item_orcamento_to_string = implode(', ', $values);
        }
        else
        {
            $this->orcamento_item_orcamento_to_string = $orcamento_item_orcamento_to_string;
        }

        $this->vdata['orcamento_item_orcamento_to_string'] = $this->orcamento_item_orcamento_to_string;
    }

    public function get_orcamento_item_orcamento_to_string()
    {
        if(!empty($this->orcamento_item_orcamento_to_string))
        {
            return $this->orcamento_item_orcamento_to_string;
        }
    
        $values = OrcamentoItem::where('produto_id', '=', $this->id)->getIndexedArray('orcamento_id','{orcamento->dt_faturamento}');
        return implode(', ', $values);
    }

    public function set_orcamento_item_produto_to_string($orcamento_item_produto_to_string)
    {
        if(is_array($orcamento_item_produto_to_string))
        {
            $values = Produto::where('id', 'in', $orcamento_item_produto_to_string)->getIndexedArray('descricao', 'descricao');
            $this->orcamento_item_produto_to_string = implode(', ', $values);
        }
        else
        {
            $this->orcamento_item_produto_to_string = $orcamento_item_produto_to_string;
        }

        $this->vdata['orcamento_item_produto_to_string'] = $this->orcamento_item_produto_to_string;
    }

    public function get_orcamento_item_produto_to_string()
    {
        if(!empty($this->orcamento_item_produto_to_string))
        {
            return $this->orcamento_item_produto_to_string;
        }
    
        $values = OrcamentoItem::where('produto_id', '=', $this->id)->getIndexedArray('produto_id','{produto->descricao}');
        return implode(', ', $values);
    }

    public function set_tabela_preco_item_tabela_preco_to_string($tabela_preco_item_tabela_preco_to_string)
    {
        if(is_array($tabela_preco_item_tabela_preco_to_string))
        {
            $values = TabelaPreco::where('id', 'in', $tabela_preco_item_tabela_preco_to_string)->getIndexedArray('descricao', 'descricao');
            $this->tabela_preco_item_tabela_preco_to_string = implode(', ', $values);
        }
        else
        {
            $this->tabela_preco_item_tabela_preco_to_string = $tabela_preco_item_tabela_preco_to_string;
        }

        $this->vdata['tabela_preco_item_tabela_preco_to_string'] = $this->tabela_preco_item_tabela_preco_to_string;
    }

    public function get_tabela_preco_item_tabela_preco_to_string()
    {
        if(!empty($this->tabela_preco_item_tabela_preco_to_string))
        {
            return $this->tabela_preco_item_tabela_preco_to_string;
        }
    
        $values = TabelaPrecoItem::where('produto_id', '=', $this->id)->getIndexedArray('tabela_preco_id','{tabela_preco->descricao}');
        return implode(', ', $values);
    }

    public function set_tabela_preco_item_produto_to_string($tabela_preco_item_produto_to_string)
    {
        if(is_array($tabela_preco_item_produto_to_string))
        {
            $values = Produto::where('id', 'in', $tabela_preco_item_produto_to_string)->getIndexedArray('descricao', 'descricao');
            $this->tabela_preco_item_produto_to_string = implode(', ', $values);
        }
        else
        {
            $this->tabela_preco_item_produto_to_string = $tabela_preco_item_produto_to_string;
        }

        $this->vdata['tabela_preco_item_produto_to_string'] = $this->tabela_preco_item_produto_to_string;
    }

    public function get_tabela_preco_item_produto_to_string()
    {
        if(!empty($this->tabela_preco_item_produto_to_string))
        {
            return $this->tabela_preco_item_produto_to_string;
        }
    
        $values = TabelaPrecoItem::where('produto_id', '=', $this->id)->getIndexedArray('produto_id','{produto->descricao}');
        return implode(', ', $values);
    }

    public function set_venda_mes_produto_produto_to_string($venda_mes_produto_produto_to_string)
    {
        if(is_array($venda_mes_produto_produto_to_string))
        {
            $values = Produto::where('id', 'in', $venda_mes_produto_produto_to_string)->getIndexedArray('descricao', 'descricao');
            $this->venda_mes_produto_produto_to_string = implode(', ', $values);
        }
        else
        {
            $this->venda_mes_produto_produto_to_string = $venda_mes_produto_produto_to_string;
        }

        $this->vdata['venda_mes_produto_produto_to_string'] = $this->venda_mes_produto_produto_to_string;
    }

    public function get_venda_mes_produto_produto_to_string()
    {
        if(!empty($this->venda_mes_produto_produto_to_string))
        {
            return $this->venda_mes_produto_produto_to_string;
        }
    
        $values = VendaMesProduto::where('produto_id', '=', $this->id)->getIndexedArray('produto_id','{produto->descricao}');
        return implode(', ', $values);
    }

    public function set_produto_caracteristica_caracteristica_to_string($produto_caracteristica_caracteristica_to_string)
    {
        if(is_array($produto_caracteristica_caracteristica_to_string))
        {
            $values = Caracteristica::where('id', 'in', $produto_caracteristica_caracteristica_to_string)->getIndexedArray('id', 'id');
            $this->produto_caracteristica_caracteristica_to_string = implode(', ', $values);
        }
        else
        {
            $this->produto_caracteristica_caracteristica_to_string = $produto_caracteristica_caracteristica_to_string;
        }

        $this->vdata['produto_caracteristica_caracteristica_to_string'] = $this->produto_caracteristica_caracteristica_to_string;
    }

    public function get_produto_caracteristica_caracteristica_to_string()
    {
        if(!empty($this->produto_caracteristica_caracteristica_to_string))
        {
            return $this->produto_caracteristica_caracteristica_to_string;
        }
    
        $values = ProdutoCaracteristica::where('produto_id', '=', $this->id)->getIndexedArray('caracteristica_id','{caracteristica->id}');
        return implode(', ', $values);
    }

    public function set_produto_caracteristica_produto_to_string($produto_caracteristica_produto_to_string)
    {
        if(is_array($produto_caracteristica_produto_to_string))
        {
            $values = Produto::where('id', 'in', $produto_caracteristica_produto_to_string)->getIndexedArray('descricao', 'descricao');
            $this->produto_caracteristica_produto_to_string = implode(', ', $values);
        }
        else
        {
            $this->produto_caracteristica_produto_to_string = $produto_caracteristica_produto_to_string;
        }

        $this->vdata['produto_caracteristica_produto_to_string'] = $this->produto_caracteristica_produto_to_string;
    }

    public function get_produto_caracteristica_produto_to_string()
    {
        if(!empty($this->produto_caracteristica_produto_to_string))
        {
            return $this->produto_caracteristica_produto_to_string;
        }
    
        $values = ProdutoCaracteristica::where('produto_id', '=', $this->id)->getIndexedArray('produto_id','{produto->descricao}');
        return implode(', ', $values);
    }

    public function set_ficha_tecnica_produto_to_string($ficha_tecnica_produto_to_string)
    {
        if(is_array($ficha_tecnica_produto_to_string))
        {
            $values = Produto::where('id', 'in', $ficha_tecnica_produto_to_string)->getIndexedArray('descricao', 'descricao');
            $this->ficha_tecnica_produto_to_string = implode(', ', $values);
        }
        else
        {
            $this->ficha_tecnica_produto_to_string = $ficha_tecnica_produto_to_string;
        }

        $this->vdata['ficha_tecnica_produto_to_string'] = $this->ficha_tecnica_produto_to_string;
    }

    public function get_ficha_tecnica_produto_to_string()
    {
        if(!empty($this->ficha_tecnica_produto_to_string))
        {
            return $this->ficha_tecnica_produto_to_string;
        }
    
        $values = FichaTecnica::where('produto_id', '=', $this->id)->getIndexedArray('produto_id','{produto->descricao}');
        return implode(', ', $values);
    }

   
    public function get_imagem(){
    
        $cLocal = '/sistema/produto/';
        $cSemImagen = 'semimagem.png';
        $cFoto = ltrim(rtrim($this->cod_erp));
    
        $cRetorno = $cLocal.$cSemImagen;
    
        if (file_exists($_SERVER['DOCUMENT_ROOT'].$cLocal.$cFoto.'.PNG')) {
        
            $cRetorno = $cLocal.$cFoto.'.PNG';

        }elseif (file_exists($_SERVER['DOCUMENT_ROOT'].$cLocal.$cFoto.'.JPG')) {
        
            $cRetorno = $cLocal.$cFoto.'.JPG';
        }
    
        return $cRetorno;
    
    }

    public function get_saldo_estoque()
    {
        $nSaldo = 0 ;

        $oSaldos = Estoque::where('produto_id',  '=', $this->id)
                     ->orderBy('id')
                     ->load();    

        if ($oSaldos)
            {
                foreach($oSaldos  as $oSaldo )
                {
                     $nSaldo += $oSaldo->saldo;
                }
            }
        return $nSaldo;
    }

   public function get_descricao_formatada(){
    
        $font = new TElement('font');
        if( $this->status== 'A' ){
            //$font->style="color:'#FF0000';"; //#FF0000 
            $font->add($this->cod_erp.' - '.ltrim(rtrim($this->descricao)));
        }else{
            $font->style="color: #FF0000; "; //#FF0000 
            $font->add($this->cod_erp.' - '.ltrim(rtrim($this->descricao)).' (Bloqueado)');
        }
    
   
        return $font;
   }

                                                
}

