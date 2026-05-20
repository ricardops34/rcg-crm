SET IDENTITY_INSERT atendimento_tipo ON; 

INSERT INTO atendimento_tipo (id,cod_erp,descricao,cor,icone,dt_alteracao,dt_inclusao,retorno,editar,excluir,atendimento,venda,cadastro,cobranca) VALUES (1,'01','Venda','','',null,null,'S','N','N','N','S','N','N'); 

INSERT INTO atendimento_tipo (id,cod_erp,descricao,cor,icone,dt_alteracao,dt_inclusao,retorno,editar,excluir,atendimento,venda,cadastro,cobranca) VALUES (2,'02','Atualização Cadastral','','',null,null,'S','S','N','S','N','S','N'); 

INSERT INTO atendimento_tipo (id,cod_erp,descricao,cor,icone,dt_alteracao,dt_inclusao,retorno,editar,excluir,atendimento,venda,cadastro,cobranca) VALUES (3,'03','Prospecção','','',null,null,'S','S','N','S','N','N','N'); 

INSERT INTO atendimento_tipo (id,cod_erp,descricao,cor,icone,dt_alteracao,dt_inclusao,retorno,editar,excluir,atendimento,venda,cadastro,cobranca) VALUES (4,'04','Cobrança','','',null,null,'S','S','N','S','N','S','S'); 

INSERT INTO atendimento_tipo (id,cod_erp,descricao,cor,icone,dt_alteracao,dt_inclusao,retorno,editar,excluir,atendimento,venda,cadastro,cobranca) VALUES (5,'05','Reativação','','',null,null,'S','S','N','S','N','N','S'); 

INSERT INTO atendimento_tipo (id,cod_erp,descricao,cor,icone,dt_alteracao,dt_inclusao,retorno,editar,excluir,atendimento,venda,cadastro,cobranca) VALUES (6,'06','Campanha','','',null,null,'S','S','N','S','N','N','S'); 

INSERT INTO atendimento_tipo (id,cod_erp,descricao,cor,icone,dt_alteracao,dt_inclusao,retorno,editar,excluir,atendimento,venda,cadastro,cobranca) VALUES (7,'07','Retorno','','',null,null,'S','S','N','N','S','S','S'); 

SET IDENTITY_INSERT atendimento_tipo OFF; 

SET IDENTITY_INSERT empresa ON; 

INSERT INTO empresa (id,cod_erp,system_unit_id,nome,logo,status,dt_inclusao,dt_alteracao) VALUES (1,'99',1,'TESTE','','',null,null); 

SET IDENTITY_INSERT empresa OFF; 

SET IDENTITY_INSERT estado ON; 

INSERT INTO estado (id,cod_erp,sigla,descricao,codigo_ibge) VALUES (1,'11','RO','Rondônia',''); 

INSERT INTO estado (id,cod_erp,sigla,descricao,codigo_ibge) VALUES (2,'12','AC','Acre',''); 

INSERT INTO estado (id,cod_erp,sigla,descricao,codigo_ibge) VALUES (3,'13','AM','Amazonas',''); 

INSERT INTO estado (id,cod_erp,sigla,descricao,codigo_ibge) VALUES (4,'14','RR','Roraima',''); 

INSERT INTO estado (id,cod_erp,sigla,descricao,codigo_ibge) VALUES (5,'15','PA','Pará',''); 

INSERT INTO estado (id,cod_erp,sigla,descricao,codigo_ibge) VALUES (6,'16','AP','Amapá',''); 

INSERT INTO estado (id,cod_erp,sigla,descricao,codigo_ibge) VALUES (7,'17','TO','Tocantins',''); 

INSERT INTO estado (id,cod_erp,sigla,descricao,codigo_ibge) VALUES (8,'21','MA','Maranhão',''); 

INSERT INTO estado (id,cod_erp,sigla,descricao,codigo_ibge) VALUES (9,'22','PI','Piauí',''); 

INSERT INTO estado (id,cod_erp,sigla,descricao,codigo_ibge) VALUES (10,'23','CE','Ceará',''); 

INSERT INTO estado (id,cod_erp,sigla,descricao,codigo_ibge) VALUES (11,'24','RN','Rio Grande do Norte ',''); 

INSERT INTO estado (id,cod_erp,sigla,descricao,codigo_ibge) VALUES (12,'25','PB','Paraíba',''); 

INSERT INTO estado (id,cod_erp,sigla,descricao,codigo_ibge) VALUES (13,'26','PE','Pernambuco',''); 

INSERT INTO estado (id,cod_erp,sigla,descricao,codigo_ibge) VALUES (14,'27','AL','Alagoas',''); 

INSERT INTO estado (id,cod_erp,sigla,descricao,codigo_ibge) VALUES (15,'28','SE','Sergipe',''); 

INSERT INTO estado (id,cod_erp,sigla,descricao,codigo_ibge) VALUES (16,'29','BA','Bahia',''); 

INSERT INTO estado (id,cod_erp,sigla,descricao,codigo_ibge) VALUES (17,'31','MG','Minas Gerais	',''); 

INSERT INTO estado (id,cod_erp,sigla,descricao,codigo_ibge) VALUES (18,'32','ES','Espírito Santo',''); 

INSERT INTO estado (id,cod_erp,sigla,descricao,codigo_ibge) VALUES (19,'33','RJ','Rio de Janeiro',''); 

INSERT INTO estado (id,cod_erp,sigla,descricao,codigo_ibge) VALUES (20,'34','SP','São Paulo',''); 

INSERT INTO estado (id,cod_erp,sigla,descricao,codigo_ibge) VALUES (21,'41','PR','Paraná',''); 

INSERT INTO estado (id,cod_erp,sigla,descricao,codigo_ibge) VALUES (22,'42','SC','Santa Catarina',''); 

INSERT INTO estado (id,cod_erp,sigla,descricao,codigo_ibge) VALUES (23,'43','RS','Rio Grande do Sul',''); 

INSERT INTO estado (id,cod_erp,sigla,descricao,codigo_ibge) VALUES (24,'50','MS','Mato Grosso do Sul',''); 

INSERT INTO estado (id,cod_erp,sigla,descricao,codigo_ibge) VALUES (25,'51','MT','Mato Grosso',''); 

INSERT INTO estado (id,cod_erp,sigla,descricao,codigo_ibge) VALUES (26,'52','GO','Goiás',''); 

INSERT INTO estado (id,cod_erp,sigla,descricao,codigo_ibge) VALUES (27,'53','DF','Distrito Federal',''); 

SET IDENTITY_INSERT estado OFF; 

SET IDENTITY_INSERT motivo_bloqueio ON; 

INSERT INTO motivo_bloqueio (id,cod_erp,dt_alteracao,dt_inclusao,descricao) VALUES (1,'F',null,null,'ENCERRADA'); 

INSERT INTO motivo_bloqueio (id,cod_erp,dt_alteracao,dt_inclusao,descricao) VALUES (2,'S',null,null,'AUTOMATICO'); 

INSERT INTO motivo_bloqueio (id,cod_erp,dt_alteracao,dt_inclusao,descricao) VALUES (3,'I',null,null,'INAPTA'); 

INSERT INTO motivo_bloqueio (id,cod_erp,dt_alteracao,dt_inclusao,descricao) VALUES (4,'0',null,null,'FINANCEIRO'); 

INSERT INTO motivo_bloqueio (id,cod_erp,dt_alteracao,dt_inclusao,descricao) VALUES (5,'C',null,null,'COMPRA CONCORRENTE'); 

INSERT INTO motivo_bloqueio (id,cod_erp,dt_alteracao,dt_inclusao,descricao) VALUES (6,'P',null,null,'OUTROS'); 

INSERT INTO motivo_bloqueio (id,cod_erp,dt_alteracao,dt_inclusao,descricao) VALUES (7,'R',null,null,'REVISAO'); 

SET IDENTITY_INSERT motivo_bloqueio OFF; 

SET IDENTITY_INSERT orcamento_estado ON; 

INSERT INTO orcamento_estado (id,cod_erp,descricao,cor,cor_texto,editar,excluir,imprimir,cancelar,dt_inclusao,dt_alteracao,sistema,ordem,icone,exibir_regua) VALUES (1,'01','Aberto','#00FF00','#000000','S','S','S','S',null,null,'S',1,'far fa-calendar-alt','S'); 

INSERT INTO orcamento_estado (id,cod_erp,descricao,cor,cor_texto,editar,excluir,imprimir,cancelar,dt_inclusao,dt_alteracao,sistema,ordem,icone,exibir_regua) VALUES (2,'02','Faturado','#2196F3','#FFFFFF','N','N','S','N',null,null,'S',2,'far fa-calendar-check','S'); 

INSERT INTO orcamento_estado (id,cod_erp,descricao,cor,cor_texto,editar,excluir,imprimir,cancelar,dt_inclusao,dt_alteracao,sistema,ordem,icone,exibir_regua) VALUES (3,'03','Agendado','#8A2BE2','#FFFFFF','S','S','S','S',null,null,'S',3,'far fa-calendar-plus','S'); 

INSERT INTO orcamento_estado (id,cod_erp,descricao,cor,cor_texto,editar,excluir,imprimir,cancelar,dt_inclusao,dt_alteracao,sistema,ordem,icone,exibir_regua) VALUES (4,'05','Cancelado','#000000','#000000','N','N','N','N',null,null,'S',5,'far fa-calendar-minus','N'); 

INSERT INTO orcamento_estado (id,cod_erp,descricao,cor,cor_texto,editar,excluir,imprimir,cancelar,dt_inclusao,dt_alteracao,sistema,ordem,icone,exibir_regua) VALUES (5,'04','Outro Vendedor','#9E9E9E','#000000','S','S','S','S',null,null,'S',4,'far fa-calendar','N'); 

INSERT INTO orcamento_estado (id,cod_erp,descricao,cor,cor_texto,editar,excluir,imprimir,cancelar,dt_inclusao,dt_alteracao,sistema,ordem,icone,exibir_regua) VALUES (6,'99','Erro de integração','#FF0000','#000000','S','S','S','S',null,null,'S',6,'far fa-calendar-times','N'); 

SET IDENTITY_INSERT orcamento_estado OFF; 

SET IDENTITY_INSERT orcamento_proximo_estado ON; 

INSERT INTO orcamento_proximo_estado (id,orcamento_estado_id,proximo_estado_id,dt_alteracao,dt_inclusao) VALUES (1,1,3,null,null); 

INSERT INTO orcamento_proximo_estado (id,orcamento_estado_id,proximo_estado_id,dt_alteracao,dt_inclusao) VALUES (2,1,2,null,null); 

INSERT INTO orcamento_proximo_estado (id,orcamento_estado_id,proximo_estado_id,dt_alteracao,dt_inclusao) VALUES (3,1,99,null,null); 

INSERT INTO orcamento_proximo_estado (id,orcamento_estado_id,proximo_estado_id,dt_alteracao,dt_inclusao) VALUES (4,3,2,null,null); 

INSERT INTO orcamento_proximo_estado (id,orcamento_estado_id,proximo_estado_id,dt_alteracao,dt_inclusao) VALUES (5,3,3,null,null); 

INSERT INTO orcamento_proximo_estado (id,orcamento_estado_id,proximo_estado_id,dt_alteracao,dt_inclusao) VALUES (6,3,99,null,null); 

SET IDENTITY_INSERT orcamento_proximo_estado OFF; 

SET IDENTITY_INSERT pedido_estado ON; 

INSERT INTO pedido_estado (id,cod_erp,descricao,cor,cor_texto) VALUES (1,'01','Pedido em Aberto','#00FF00','#000000'); 

INSERT INTO pedido_estado (id,cod_erp,descricao,cor,cor_texto) VALUES (2,'02','Bloqueio de Pedido','#FF1493','#000000'); 

INSERT INTO pedido_estado (id,cod_erp,descricao,cor,cor_texto) VALUES (3,'03','Bloqueio de Crédito','#8A2BE2','#000000'); 

INSERT INTO pedido_estado (id,cod_erp,descricao,cor,cor_texto) VALUES (4,'04','Bloqueio de Estoque','#483D8B','#FFFFFF'); 

INSERT INTO pedido_estado (id,cod_erp,descricao,cor,cor_texto) VALUES (5,'05','Bloqueio de Regra','#00FFFF','#000000'); 

INSERT INTO pedido_estado (id,cod_erp,descricao,cor,cor_texto) VALUES (6,'06','Bloqueio de Verba','#FFA500','#000000'); 

INSERT INTO pedido_estado (id,cod_erp,descricao,cor,cor_texto) VALUES (7,'07','Pedido Liberado','#FFFF00','#000000'); 

INSERT INTO pedido_estado (id,cod_erp,descricao,cor,cor_texto) VALUES (8,'08','Pedido Faturado','#FF0000','#FFFFFF'); 

INSERT INTO pedido_estado (id,cod_erp,descricao,cor,cor_texto) VALUES (9,'09','Pedido Cancelado','#000000','#FFFFFF'); 

INSERT INTO pedido_estado (id,cod_erp,descricao,cor,cor_texto) VALUES (10,'99','Estado Não Cadastrado','#8B0000','#FFFFFF'); 

SET IDENTITY_INSERT pedido_estado OFF; 

SET IDENTITY_INSERT step ON; 

INSERT INTO step (id,grupo,sequencia,variavel,descricao,cor,column_6) VALUES (1,'orcamento',1,'1','Orçamento','',null); 

INSERT INTO step (id,grupo,sequencia,variavel,descricao,cor,column_6) VALUES (2,'venda',2,'2','Venda Assistida','',null); 

INSERT INTO step (id,grupo,sequencia,variavel,descricao,cor,column_6) VALUES (3,'retorno',3,'3','Retorno','',null); 

SET IDENTITY_INSERT step OFF; 

SET IDENTITY_INSERT tipo_cliente ON; 

INSERT INTO tipo_cliente (id,cod_erp,descricao,status) VALUES (1,'F','Cons.Final','A'); 

INSERT INTO tipo_cliente (id,cod_erp,descricao,status) VALUES (2,'L','Produtor Rural','A'); 

INSERT INTO tipo_cliente (id,cod_erp,descricao,status) VALUES (3,'R','Revendedor','A'); 

INSERT INTO tipo_cliente (id,cod_erp,descricao,status) VALUES (4,'S','Solidario','A'); 

INSERT INTO tipo_cliente (id,cod_erp,descricao,status) VALUES (5,'X','Exportacao','A'); 

SET IDENTITY_INSERT tipo_cliente OFF; 

SET IDENTITY_INSERT tipo_contato ON; 

INSERT INTO tipo_contato (id,cod_erp,descricao,dt_alteracao,dt_inclusao) VALUES (1,'01','Financeiro',null,null); 

INSERT INTO tipo_contato (id,cod_erp,descricao,dt_alteracao,dt_inclusao) VALUES (2,'02','Compras',null,null); 

INSERT INTO tipo_contato (id,cod_erp,descricao,dt_alteracao,dt_inclusao) VALUES (3,'03','Estoque',null,null); 

INSERT INTO tipo_contato (id,cod_erp,descricao,dt_alteracao,dt_inclusao) VALUES (4,'04','Diretor',null,null); 

INSERT INTO tipo_contato (id,cod_erp,descricao,dt_alteracao,dt_inclusao) VALUES (5,'05','Faturamento',null,null); 

INSERT INTO tipo_contato (id,cod_erp,descricao,dt_alteracao,dt_inclusao) VALUES (6,'06','Fiscal',null,null); 

SET IDENTITY_INSERT tipo_contato OFF; 
