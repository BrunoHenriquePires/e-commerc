CREATE DATABASE IF NOT EXISTS e_commerce;
USE e_commerce;


--------------------------------------------------------------
-- TABELA USUARIO
--------------------------------------------------------------
CREATE TABLE IF NOT EXISTS Usuario 
( 
    idUsuario INT PRIMARY KEY AUTO_INCREMENT,  
    nome VARCHAR(40) NOT NULL,  
    email VARCHAR(60) NOT NULL UNIQUE,
    senha VARCHAR(15) NOT NULL
); 

--------------------------------------------------------------
-- TABELA ENDERECO
--------------------------------------------------------------
CREATE TABLE IF NOT EXISTS Endereco
(
    idEndereco INT PRIMARY KEY AUTO_INCREMENT,
    rua VARCHAR(40) NOT NULL,
    cidade VARCHAR(40) NOT NULL,
    bairro VARCHAR(40) NOT NULL,
    numero INT NOT NULL,
    Estado CHAR(2) NOT NULL
);

--------------------------------------------------------------
-- TABELA CLIENTE
--------------------------------------------------------------
CREATE TABLE IF NOT EXISTS Cliente 
( 
    cpf VARCHAR(14) PRIMARY KEY,
    cartao VARCHAR(50) UNIQUE,  
    idEndereco INT,
    idUsuario INT NOT NULL,

    FOREIGN KEY(idUsuario) REFERENCES Usuario(idUsuario),
    FOREIGN KEY(idEndereco) REFERENCES Endereco(idEndereco)
); 

--------------------------------------------------------------
-- TABELA ADMINISTRADOR (FUNCIONÁRIO)
--------------------------------------------------------------
CREATE TABLE IF NOT EXISTS Administrador 
( 
    cod_funcionario INT PRIMARY KEY AUTO_INCREMENT,  
    permissao INT NOT NULL,  
    idUsuario INT NOT NULL,
    FOREIGN KEY(idUsuario) REFERENCES Usuario(idUsuario)
); 

--------------------------------------------------------------
-- TABELA PRODUTO
--------------------------------------------------------------
CREATE TABLE IF NOT EXISTS Produto 
( 
    idProduto INT PRIMARY KEY AUTO_INCREMENT,  
    nome VARCHAR(40) NOT NULL,  
    quantidade INT NOT NULL,  
    valor DECIMAL(10,2),
    descricao VARCHAR(252) NOT NULL,
    imagem LONGBLOB NOT NULL
); 

--------------------------------------------------------------
-- TABELA VENDA
--------------------------------------------------------------
CREATE TABLE IF NOT EXISTS Venda 
( 
    idVenda INT PRIMARY KEY AUTO_INCREMENT,  
    idProduto INT,  
    idUsuario INT,  
    quantidade INT NOT NULL,  
    total DECIMAL(10,2) NOT NULL,
    data_venda DATE,

    FOREIGN KEY (idProduto) REFERENCES Produto(idProduto),
    FOREIGN KEY (idUsuario) REFERENCES Usuario(idUsuario)
);

--------------------------------------------------------------
-- TABELA CARRINHO
--------------------------------------------------------------
CREATE TABLE IF NOT EXISTS Carrinho (
    idCarrinho INT PRIMARY KEY AUTO_INCREMENT,
    idUsuario INT NOT NULL,
    dataCriacao DATETIME DEFAULT CURRENT_TIMESTAMP,
    status ENUM('ativo', 'finalizado') DEFAULT 'ativo',
    FOREIGN KEY (idUsuario) REFERENCES Usuario(idUsuario)
);

--------------------------------------------------------------
-- TABELA ITENS DO CARRINHO
--------------------------------------------------------------
CREATE TABLE IF NOT EXISTS ItemCarrinho (
    idItemCarrinho INT PRIMARY KEY AUTO_INCREMENT,
    idCarrinho INT NOT NULL,
    idProduto INT NOT NULL,
    quantidade INT NOT NULL DEFAULT 1,

    FOREIGN KEY (idCarrinho) REFERENCES Carrinho(idCarrinho),
    FOREIGN KEY (idProduto) REFERENCES Produto(idProduto)
);

--------------------------------------------------------------
-- TABELA ITENS DA VENDA
--------------------------------------------------------------

	CREATE TABLE ItemVenda (
	idVenda INT NOT NULL,
    idProduto INT NOT NULL,
    nome varchar(75) not null,
    quantidade numeric(10,2) not null,
    valor numeric (4,2) not null,
    FOREIGN KEY (idVenda) references Venda(idVenda),
	FOREIGN KEY (idProduto) references Produto(idProduto),
    PRIMARY KEY(idVenda, idProduto)
    );

--------------------------------------------------------------
-- POPULAR BANCO PARA TESTES
--------------------------------------------------------------

-- Usuário administrador
INSERT INTO Usuario (nome, email, senha) 
VALUES ('Administrador', 'admin@gmail.com', '123');

-- Ligando usuário ao administrador
INSERT INTO Administrador (permissao, idUsuario)
VALUES (1, 1);

-- Usuário cliente
INSERT INTO Usuario (nome, email, senha) 
VALUES ('Cliente Teste', 'cliente@gmail.com', '123');

-- Endereço do cliente
INSERT INTO Endereco (rua, cidade, bairro, Estado, numero)
VALUES ('Rua Central', 'Birigui', 'Centro','SP', 100);

-- Vincular cliente ao endereço e usuário
INSERT INTO Cliente (cpf, cartao, idEndereco, idUsuario)
VALUES ('123.456.789-00', '1111-2222-3333-4444', 1, 2);

select * from Administrador;
select * from Usuario;
select * from Produto;

