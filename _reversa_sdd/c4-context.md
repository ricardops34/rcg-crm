C4Context
    title Diagrama de Contexto (Nível 1) — rcg

    Person(vendedor, "Vendedor", "Realiza atendimentos, consultas e orçamentos.")
    Person(supervisor, "Supervisor", "Gerencia equipes, metas e dashboards estratégicos.")
    Person(admin, "Administrador", "Gerencia o sistema, usuários e configurações.")
    Person(cliente, "Cliente Final", "Acessa o portal B2B para consultar notas e financeiro.")

    System(rcg, "Sistema rcg", "ERP de Gestão Comercial e Força de Vendas.")

    System_Ext(sefaz, "SEFAZ", "Serviço governamental para validação de Notas Fiscais.")
    System_Ext(banco, "Bradesco", "Serviço bancário para liquidação de boletos.")
    System_Ext(madbuilder, "MadBuilder Services", "Serviços de suporte e infraestrutura.")

    Rel(vendedor, rcg, "Usa", "HTTPS")
    Rel(supervisor, rcg, "Gerencia", "HTTPS")
    Rel(admin, rcg, "Configura", "HTTPS")
    Rel(cliente, rcg, "Consulta", "HTTPS")

    Rel(rcg, sefaz, "Transmite NFes", "XML/SOAP")
    Rel(rcg, banco, "Gera Boletos", "HTML/PDF")
    Rel(rcg, madbuilder, "Sincroniza", "REST/API")
