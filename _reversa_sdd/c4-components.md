C4Component
    title Diagrama de Componentes (Nível 3) — App PHP

    Container_Boundary(app_php, "PHP Web Application") {
        Component(router, "Router", "Adianti Core", "Direciona requisições para os Controllers.")
        
        ComponentBoundary(controllers, "Controllers (app/control)") {
            Component(admin_ctrl, "Admin Controllers", "LoginForm, SystemUserForm", "Gestão de acesso.")
            Component(venda_ctrl, "Vendedor Controllers", "DashboardVendedor, AtendimentoForm", "Operação comercial.")
            Component(ger_ctrl, "Gerência Controllers", "DashboardGerencia, MetaForm", "Estratégia e metas.")
            Component(fin_ctrl, "Cobrança Controllers", "NegociacaoList, TituloForm", "Financeiro.")
        }

        ComponentBoundary(models, "Models (app/model)") {
            Component(ar, "Active Record", "TRecord", "Abstração de tabelas e persistência.")
            Component(traits, "Traits", "SystemChangeLogTrait", "Comportamentos transversais (auditoria).")
            Component(views, "BI Views", "TRecord (View)", "Indicadores e agregações pré-calculadas.")
        }

        ComponentBoundary(services, "Services (app/service)") {
            Component(auth_svc, "Auth Service", "CustomAuthenticationService", "Validação e 2FA.")
            Component(nfe_svc, "NFe Service", "NFePHP", "Comunicação com SEFAZ.")
            Component(print_svc, "Print Service", "Danfe, OpenBoleto", "Geração de PDFs.")
        }
    }

    Rel(router, controllers, "Invoca")
    Rel(controllers, models, "Usa")
    Rel(controllers, services, "Chama")
    Rel(services, models, "Consulta")
