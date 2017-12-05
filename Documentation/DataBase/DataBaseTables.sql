# ======================================================
# =================     TABLES   =======================
# ======================================================

DROP DATABASE IF EXISTS Proyect;
CREATE DATABASE Proyect;
USE Proyect;


CREATE TABLE Pelicula (
    ID                  INT NOT NULL PRIMARY KEY,
    Nombre              VARCHAR(50),
    Clasificacion       ENUM('AA', 'A', 'B', 'B15', 'C', 'D'),
    Duracion            INT,
    Genero              SET(
                            'AccionYAventura', 'Familiar', 'Comedia', 'Documental',
                            'Drama', 'Terror', 'Fantasia', 'Romantica', 'CienciaFiccion',
                            'Deportes', 'Suspenso'),
    Descripcion         VARCHAR(400)
); 


CREATE TABLE Sala (
    NumeroSala          INT NOT NULL PRIMARY KEY,
    NumeroAsientos      INT
);



CREATE TABLE Empleado (
    ID                  INT NOT NULL AUTO_INCREMENT,
    Sueldo              REAL,
    Turno               ENUM('Matutino', 'Vespetirno'),
    Genero              ENUM('Masculino', 'Femenino'),
    Nombre              VARCHAR(15),
    ApellidoPaterno     VARCHAR(15),
    ApellidoMaterno     VARCHAR(15),
    Correo              VARCHAR(30),
    Contrasena          VARCHAR(100),
    RolActual           ENUM('Dulceria', 'Taquilla', 'Gerente'),
    IDGerente           INT,

    PRIMARY KEY(ID),

    FOREIGN KEY (IDGerente)
        REFERENCES Empleado(ID)
        ON DELETE CASCADE
        ON UPDATE CASCADE

);


CREATE TABLE EmpleadoSala (
    IDEmpleado          INT,
    NumeroSala          INT,

    FOREIGN KEY (IDEmpleado)
        REFERENCES Empleado(ID)
            ON DELETE CASCADE
            ON UPDATE CASCADE,

    FOREIGN KEY (NumeroSala)
        REFERENCES Sala(NumeroSala)
            ON DELETE CASCADE
            ON UPDATE CASCADE,

    PRIMARY KEY (IDEmpleado, NumeroSala)
);


CREATE TABLE Proveedor (
    ID                  INT NOT NULL PRIMARY KEY,
    Nombre              VARCHAR(15),
    ApellidoPaterno     VARCHAR(15),
    ApellidoMaterno     VARCHAR(15),
    Correo              VARCHAR(30),
    Contrasena          VARCHAR(100)
);


CREATE TABLE Funcion (
    Hora                TIME,
    Dia                 DATE,
    NumeroSala          INT,
    Precio              REAL,
    Tipo                ENUM('Normal', '3D', '4D'),
    IDPelicula          INT, 

    FOREIGN KEY (NumeroSala)
        REFERENCES Sala(NumeroSala),

    FOREIGN KEY (IDPelicula)
        REFERENCES Pelicula (ID)
            ON DELETE CASCADE
            ON UPDATE CASCADE,

    PRIMARY KEY (Hora, Dia, NumeroSala)
);


CREATE TABLE Venta (
    ID                  INT NOT NULL PRIMARY KEY,
    Fecha               DATE, 
    Total               REAL,
    IDEmpleado          INT,

    FOREIGN KEY (IDEmpleado)
        REFERENCES Empleado (ID)
            ON DELETE CASCADE
            ON UPDATE CASCADE
);


CREATE TABLE TicketBoleto (
    NumeroBoleto        INT,
    Costo               REAL,
    Hora                TIME,
    Dia                 DATE,
    NumeroSala          INT,
    IDVenta             INT,

    FOREIGN KEY (IDVenta)
        REFERENCES Venta (ID)
            ON DELETE CASCADE
            ON UPDATE CASCADE,  
    
    FOREIGN KEY (Hora, Dia, NumeroSala)
        REFERENCES Funcion (Hora, Dia, NumeroSala)
            ON DELETE CASCADE
            ON UPDATE CASCADE,

    PRIMARY KEY (IDVenta, Hora, Dia, NumeroSala)
);


CREATE TABLE  ProductoDulceria (
    ID                  INT NOT NULL PRIMARY KEY,
    Stock               INT,
    Nombre              VARCHAR(50),
    Costo               REAL,
    IDProveedor         INT,

    FOREIGN KEY (IDProveedor) 
        REFERENCES Proveedor (ID)
            ON DELETE CASCADE
            ON UPDATE CASCADE
);


CREATE TABLE TicketDulceria (
    Costo               REAL,
    Cantidad            INT,
    IDVenta             INT,
    IDProducto          INT,

    FOREIGN KEY (IDVenta)
        REFERENCES Venta (ID)
            ON DELETE CASCADE
            ON UPDATE CASCADE, 

    FOREIGN KEY(IDProducto)
        REFERENCES ProductoDulceria (ID)
            ON DELETE CASCADE
            ON UPDATE CASCADE,

    PRIMARY KEY (IDVenta, IdProducto)
);


CREATE TABLE TicketProveedor (
    Cantidad            INT,
    Costo               REAL,
    Total               REAL,
    IDProducto          INT,
    IDProveedor         INT,

    FOREIGN KEY (IDProducto)
        REFERENCES ProductoDulceria (ID)
            ON DELETE CASCADE
            ON UPDATE CASCADE,

    FOREIGN KEY (IDProveedor)
        REFERENCES Proveedor (ID)
            ON DELETE CASCADE
            ON UPDATE CASCADE,

    PRIMARY KEY (IDProducto, IDProveedor)
);