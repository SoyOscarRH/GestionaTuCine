# ======================================================
# =================     TABLES   =======================
# ======================================================

DROP DATABASE IF EXISTS Proyect;
CREATE DATABASE Proyect;
USE Proyect;

# ======================================================
# =================  EMPLOYEES   =======================
# ======================================================
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



# ======================================================
# =================   GENERAL SELL    ==================
# ======================================================
CREATE TABLE Venta (
    ID                  INT NOT NULL PRIMARY KEY,
    Fecha               DATETIME, 
    Total               REAL,
    IDEmpleado          INT,

    FOREIGN KEY (IDEmpleado)
        REFERENCES Empleado (ID)
            ON DELETE CASCADE
            ON UPDATE CASCADE
);



# ======================================================
# =================   MOVIES     =======================
# ======================================================
CREATE TABLE Pelicula (
    ID                  INT NOT NULL PRIMARY KEY,
    Nombre              VARCHAR(50),
    Clasificacion       ENUM('AA', 'A', 'B', 'B15', 'C', 'D'),
    Duracion            INT,
    Genero              SET(
                            'Accion y Aventura', 'Familiar', 'Comedia', 
                            'Documental', 'Drama', 'Terror', 'Fantasia',
                            'Romantica', 'CienciaFiccion', 'Deportes', 
                            'Suspenso'),
    Descripcion         VARCHAR(400),
    Exhibicion          ENUM('En Exhibición', 'No En Exhibición')
); 

CREATE TABLE Sala (
    NumeroSala          INT NOT NULL PRIMARY KEY,
    Tipo                ENUM('Normal', '3D', '4D'),
    NumeroAsientos      INT
);


CREATE TABLE Funcion (
    ID                  INT NOT NULL PRIMARY KEY,
    Hora                TIME,
    NumeroSala          INT,
    Precio              REAL,
    IDPelicula          INT, 
    TipoFuncion         ENUM('Funcion Activa', 'Funcion Antigua'),

    FOREIGN KEY (NumeroSala)
        REFERENCES Sala(NumeroSala),

    FOREIGN KEY (IDPelicula)
        REFERENCES Pelicula (ID)
            ON DELETE CASCADE
            ON UPDATE CASCADE

);



CREATE TABLE TicketBoleto (
    Costo               REAL,
    Cantidad            INT,
    IDVenta             INT,
    IDFuncion           INT,
    DiaFuncion          DATE,

    FOREIGN KEY (IDVenta)
        REFERENCES Venta (ID)
            ON DELETE CASCADE
            ON UPDATE CASCADE,

    FOREIGN KEY (IDFuncion)
        REFERENCES Funcion (ID)
            ON DELETE CASCADE
            ON UPDATE CASCADE,  

    PRIMARY KEY (IDVenta, IDFuncion)
);



# ======================================================
# =================   CANDY SHOP     ===================
# ======================================================
CREATE TABLE  ProductoDulceria (
    ID                  INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    Stock               INT UNSIGNED,
    Nombre              VARCHAR(50),
    Costo               REAL
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