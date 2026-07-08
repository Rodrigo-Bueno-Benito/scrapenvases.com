<?php
if (!class_exists('conexionBBDD')) {
    class conexionBBDD {
        public $conn;

        function __construct($usuario, $contra, $servidor, $bbdd) {
            // Establecer la conexión a la base de datos
            $this->conn = new mysqli($servidor, $usuario, $contra, $bbdd);

            // Verificar si hay errores de conexión
            if ($this->conn->connect_error) {
                die("Error de conexión a la base de datos: " . $this->conn->connect_error);
            }
        }

        // Método para obtener datos sin parámetros
        function obtenerDatos($consulta) {
            $resultado = $this->conn->query($consulta);

            if (!$resultado) {
                die("Error en la consulta: " . $this->conn->error);
            }

            return $resultado;
        }

        // Nuevo método para obtener datos usando consultas preparadas
        function obtenerDatosConParametros($consulta, $parametros = []) {
            $stmt = $this->conn->prepare($consulta);
            if ($stmt === false) {
                die("Error en la preparación de la consulta: " . $this->conn->error);
            }

            if (!empty($parametros)) {
                $tipos = $this->determinarTipos($parametros);
                $stmt->bind_param($tipos, ...$parametros);
            }

            if (!$stmt->execute()) {
                die("Error en la ejecución de la consulta: " . $stmt->error);
            }

            $resultado = $stmt->get_result();
            if ($resultado === false) {
                die("Error al obtener el resultado: " . $stmt->error);
            }

            return $resultado;
        }

        function convertirDatos($respuesta) {
            $arrayDatos = [];
            while ($dato = $respuesta->fetch_object()) {
                $arrayDatos[] = $dato;
            }
            return $arrayDatos;
        }

        // Método para cerrar la conexión
        function cerrarConexion() {
            if ($this->conn->ping()) {
                $this->conn->close();
            }
        }

        // Método para ejecutar consultas preparadas que no devuelven datos (INSERT, UPDATE, DELETE)
        function ejecutarConsultaPreparada($query, $parametros) {
            $stmt = $this->conn->prepare($query);
            if ($stmt === false) {
                die("Error en la preparación de la consulta: " . $this->conn->error);
            }

            // Determinar el tipo de los parámetros
            $tipos = $this->determinarTipos($parametros);

            // Asociar los parámetros
            $stmt->bind_param($tipos, ...$parametros);

            // Ejecutar la consulta
            if (!$stmt->execute()) {
                die("Error en la ejecución de la consulta: " . $stmt->error);
            }

            $stmt->close();
        }

        // Método para ejecutar consultas preparadas que devuelven datos (SELECT)
        function obtenerConsultaPreparada($query, $parametros) {
            $stmt = $this->conn->prepare($query);
            if ($stmt === false) {
                die("Error en la preparación de la consulta: " . $this->conn->error);
            }

            // Determinar el tipo de los parámetros
            $tipos = $this->determinarTipos($parametros);

            // Asociar los parámetros
            $stmt->bind_param($tipos, ...$parametros);

            // Ejecutar la consulta
            if (!$stmt->execute()) {
                die("Error en la ejecución de la consulta: " . $stmt->error);
            }

            $resultado = $stmt->get_result();
            if ($resultado === false) {
                die("Error al obtener el resultado: " . $stmt->error);
            }

            $arrayDatos = [];
            while ($dato = $resultado->fetch_object()) {
                $arrayDatos[] = $dato;
            }

            $stmt->close();
            return $arrayDatos;
        }

        // Método para determinar los tipos de los parámetros
        private function determinarTipos($parametros) {
            $tipos = '';
            foreach ($parametros as $param) {
                if (is_int($param)) {
                    $tipos .= 'i'; // tipo entero
                } elseif (is_double($param)) {
                    $tipos .= 'd'; // tipo double
                } else {
                    $tipos .= 's'; // tipo string
                }
            }
            return $tipos;
        }
    }
}
?>
