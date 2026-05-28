import { Injectable } from '@angular/core';

export interface RouteRegistryItem {
  value: string;
  label: string;
  path: string;
  icon: string;
  module?: string;
}

@Injectable({
  providedIn: 'root'
})
export class RoutesRegistryService {
  private readonly routesRegistry: Array<RouteRegistryItem> = [
    // CRM / Comercial
    { value: 'DashboardVendedor', label: 'Dashboard de Vendas', path: '/dashboard', icon: 'an an-house', module: 'Atendimento' },
    { value: 'MvcList', label: 'Cockpit de Vendas (MCV)', path: '/mvc', icon: 'an an-chart-line', module: 'Atendimento' },
    { value: 'ClienteList', label: 'Cadastro de Clientes', path: '/clientes', icon: 'an an-user', module: 'Atendimento' },
    { value: 'PosisaoClienteFormView', label: 'Visao 360 do Cliente', path: '/clientes/360', icon: 'an an-eye', module: 'Atendimento' },
    { value: 'AgendaAtendimentoList', label: 'Agenda Comercial', path: '/agenda-atendimento', icon: 'an an-calendar', module: 'Atendimento' },
    { value: 'MetaList', label: 'Metas de Vendas', path: '/metas', icon: 'an an-target', module: 'Gerencial' },
    { value: 'NegociacaoList', label: 'Inadimplentes e Negociacoes', path: '/financeiro/negociacoes', icon: 'an an-list', module: 'Atendimento' },

    // Cadastros Mestre
    { value: 'VendedorList', label: 'Cadastro de Vendedores', path: '/vendedores', icon: 'an an-user-plus', module: 'Gerencial' },
    { value: 'ProdutoList', label: 'Catalogo de Produtos', path: '/produtos', icon: 'an an-grid-four', module: 'Atendimento' },
    { value: 'TabelaPrecoList', label: 'Tabelas de Precos', path: '/tabelas-precos', icon: 'an an-list', module: 'Gerencial' },
    { value: 'CategoriaList', label: 'Categorias de Produtos', path: '/categorias', icon: 'an an-layers', module: 'Gerencial' },
    { value: 'PedidoEstadoList', label: 'Status de Pedidos', path: '/pedidos/estados', icon: 'an an-sidebar-simple', module: 'Gerencial' },

    // Financeiro / Faturamento
    { value: 'TituloReceberList', label: 'Contas a Receber (Titulos)', path: '/financeiro/titulos', icon: 'an an-list', module: 'Gerencial' },
    { value: 'NotaFiscalList', label: 'Notas Fiscais de Saida', path: '/faturamento/notas', icon: 'an an-envelope', module: 'Gerencial' },
    { value: 'ComodatoList', label: 'Ativos em Comodato', path: '/faturamento/comodatos', icon: 'an an-desktop', module: 'Gerencial' },

    // Administracao
    { value: 'SystemUserList', label: 'Usuarios do Sistema', path: '/admin/users', icon: 'an an-user', module: 'Administracao' },
    { value: 'SystemGroupList', label: 'Perfis de Acesso (Grupos)', path: '/admin/groups', icon: 'an an-lock', module: 'Administracao' },
    { value: 'SystemUnitList', label: 'Unidades de Negocio (Filiais)', path: '/admin/units', icon: 'an an-buildings', module: 'Administracao' },
    { value: 'SystemModuleList', label: 'Modulos do Sistema', path: '/admin/modules', icon: 'an an-layers', module: 'Administracao' },
    { value: 'SystemMenuEditor', label: 'Manutencao de Menus', path: '/admin/menu-editor', icon: 'an an-sidebar-simple', module: 'Administracao' },
    { value: 'SystemScreenConfigurator', label: 'Configurador de Telas', path: '/admin/configurador', icon: 'an an-sidebar-simple', module: 'Configurador' },
    { value: 'SystemProgramList', label: 'Rotinas do Sistema', path: '/admin/programs', icon: 'an an-list', module: 'Administracao' },
    { value: 'SystemParameterList', label: 'Parametros do Sistema', path: '/admin/parameters', icon: 'an an-sliders', module: 'Administracao' },
    { value: 'SystemSettings', label: 'Configurações do Sistema', path: '/admin/settings', icon: 'an an-gear-six', module: 'Configurações' },
    { value: 'SystemProfileForm', label: 'Meu Perfil', path: '/profile', icon: 'an an-user-circle', module: 'Administracao' }
  ];

  getRoutes(): Array<RouteRegistryItem> {
    return this.routesRegistry;
  }

  getPathByController(controller: string): string | null {
    if (!controller) return null;
    const cleanController = controller.trim();
    const route = this.routesRegistry.find(
      r => r.value.toLowerCase() === cleanController.toLowerCase()
    );
    return route ? route.path : null;
  }

  getRouteByController(controller: string): RouteRegistryItem | null {
    if (!controller) return null;
    const cleanController = controller.trim();
    return this.routesRegistry.find(
      r => r.value.toLowerCase() === cleanController.toLowerCase()
    ) || null;
  }
}
