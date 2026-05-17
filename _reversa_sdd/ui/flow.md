graph TD
    subgraph Login
        A[LoginForm]
    end

    subgraph Gerência
        B[Dashboard Gerencial]
        C[Cadastro Vendedores]
        D[Cadastro Objetivos/Metas]
    end

    subgraph Vendedor
        E[Dashboard Vendedor]
        F[MVC List]
        G[Agenda/Atendimento]
    end

    A -- "Perfil Admin/Supervisor" --> B
    A -- "Perfil Vendedor" --> E

    B -- "Ações" --> C
    B -- "Ações" --> D
    
    E -- "Análise de Cliente" --> F
    E -- "Gestão" --> G

    F -- "Navega para" --> G
