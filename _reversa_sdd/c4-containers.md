C4Container
    title Diagrama de Containers (Nível 2) — rcg

    Person(user, "Usuário", "Vendedor, Supervisor ou Admin")
    Person(customer, "Cliente", "Acesso B2B")

    Container_Boundary(rcg_boundary, "rcg System") {
        Container(frontend, "Web Frontend", "Bootstrap, jQuery, Adianti Plugins", "Interface rica para desktop e mobile.")
        Container(app, "PHP Web Application", "PHP 7+, Adianti Framework", "Processa lógica de negócio e renderiza telas.")
        Container(api, "REST API", "PHP, MadRest", "Interface programática para integrações.")
        
        ContainerDb(db_erp, "ERP Database", "MySQL/MSSQL", "Dados de Clientes, Pedidos, Títulos e Metas.")
        ContainerDb(db_permission, "Security Database", "MySQL", "Usuários, Grupos, Programas e Unidades.")
        ContainerDb(db_log, "Audit Database", "MySQL", "Logs de SQL e Alterações de Dados.")
        
        Container(filesystem, "File Storage", "Filesystem", "XMLs, PDFs e imagens.")
    }

    System_Ext(sefaz, "SEFAZ", "Validação de NFes")

    Rel(user, frontend, "Usa", "HTTPS")
    Rel(customer, frontend, "Usa", "HTTPS")
    Rel(frontend, app, "Chama", "HTTPS/PHP")
    
    Rel(app, db_erp, "Lê/Escreve", "PDO/SQL")
    Rel(app, db_permission, "Lê/Escreve", "PDO/SQL")
    Rel(app, db_log, "Escreve", "PDO/SQL")
    Rel(app, filesystem, "Lê/Escreve", "IO")
    
    Rel(api, db_erp, "Lê/Escreve", "PDO/SQL")
    Rel(app, sefaz, "Chama", "SOAP")
