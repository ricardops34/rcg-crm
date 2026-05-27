-- Migração para Uploads, Galeria de Fotos de Produtos e Cota por Tenant

-- 1. Alteração da tabela system_unit (Banco de Segurança: rcg_security)
-- Adiciona o limite de armazenamento em disco em megabytes para cada filial/tenant.
ALTER TABLE public.system_unit ADD COLUMN IF NOT EXISTS limite_disco_mb INTEGER DEFAULT 1000;

-- 2. Alteração da tabela atendimento (Banco Principal: rcgcrm)
-- Adiciona a coluna para armazenar o caminho do arquivo de anexo/comprovante.
ALTER TABLE public.atendimento ADD COLUMN IF NOT EXISTS anexo VARCHAR(255);

-- 3. Alteração da tabela cliente (Banco Principal: rcgcrm)
-- Adiciona a coluna para armazenar o caminho da logo do cliente.
ALTER TABLE public.cliente ADD COLUMN IF NOT EXISTS logo VARCHAR(255);

-- 4. Criação da tabela produto_imagem (Banco Principal: rcgcrm)
-- Tabela para gerenciar a galeria de múltiplas fotos de produtos.
CREATE TABLE IF NOT EXISTS public.produto_imagem (
  id SERIAL PRIMARY KEY,
  system_unit_id INTEGER DEFAULT 1,
  produto_id INTEGER NOT NULL REFERENCES public.produto(id) ON DELETE CASCADE,
  caminho VARCHAR(255) NOT NULL,
  principal CHAR(1) DEFAULT 'N', -- 'S' = Principal, 'N' = Galeria
  ordem INTEGER DEFAULT 0,        -- Ordenação
  dt_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  dt_alteracao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Índice composto para otimizar buscas por tenant e produto
CREATE INDEX IF NOT EXISTS idx_prod_img_tenant_prod ON public.produto_imagem(system_unit_id, produto_id);
