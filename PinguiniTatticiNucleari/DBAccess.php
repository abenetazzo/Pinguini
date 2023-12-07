<?php
    namespace DB;
    class DBAccess {
        private const DB_HOST = "localhost";
        private const DB_NAME = "abenetaz";
        private const DB_USER = "abenetaz";
        private const DB_PASS = "VoraL2ahf6Aixohs";

        private $connection;

        public function openDBConnection() {
            $this -> connection = mysqli_connect(
                self::DB_HOST,
                self::DB_USER,
                self::DB_PASS,
                self::DB_NAME
                );
            return mysqli_connect_errno() == 0;
        }

        public function closeDBConnection() {
            mysqli_close($this -> connection);
        }

        public function getListaAlbum() {
            $query = "SELECT ID, Titolo, idCss FROM Album ORDER BY DataPubblicazione DESC;";
            $queryResult = mysqli_query($this -> connection, $query)
                or die("Errore in DBAccess\n" .mysqli_error($this -> connection));
            if (mysqli_num_rows($queryResult) != 0) {
                $result = array();
                while ($row = mysqli_fetch_assoc($queryResult)) {
                    $result[] = $row;
                }
                $queryResult -> free();
                return $result;
            } else {
                return null;
            }
        }
    }
?>