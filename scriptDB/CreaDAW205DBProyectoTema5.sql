--Creacion de base de datos DB205DWESProyectoTema5
create database if not exists DB205DWESProyectoTema5;

--Creacion de la tabla Usuario en la base de datos DB205DWESProyectoTema5
create table if not exists DB205DWESProyectoTema5.T01_Usuario(
    T01_CodUsuario varchar(8),
    T01_Password varchar(64) NOT NULL,
    T01_DescUsuario varchar(255) NOT NULL,
    T01_FechaHoraUltimaConexion timestamp,
    T01_NumConexiones int default 0,
    T01_Perfil enum("administrador", "usuario") default "usuario",
    T01_ImagenUsuario mediumblob NULL,
    primary key(T01_CodUsuario)
)ENGINE=INNODB;

--Creacion de la tabla Departamento en la base de datos DB205DWESProyectoTema5
create table DB205DWESProyectoTema5.T02_Departamento(
    T02_CodDepartamento varchar(3),
    T02_DescDepartamento varchar(255) NOT NULL,
    T02_FechaCreacionDepartamento timestamp,
    T02_VolumenDeNegocio float NULL,
    T02_FechaBajaDepartamento date NULL,
    primary key(T02_CodDepartamento)
)ENGINE=INNODB;


--Creacion de usuario
create user 'user205DWESProyectoTema5'@'%' identified by 'P@ssw0rd';
grant all privileges on DB205DWESProyectoTema5.* to 'user205DWESProyectoTema5'@'%';