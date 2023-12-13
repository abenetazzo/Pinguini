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
            $query = "SELECT ID, Titolo, Copertina, idCss FROM Album ORDER BY DataPubblicazione DESC;";
            $queryResult = mysqli_query($this -> connection, $query)
                or die("Errore in DBAccess" .mysqli_error($this -> connection));
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

        public function getAlbum($id) {
            $query = "SELECT Album.ID,
                        Album.Titolo,
                        Album.Copertina,
                        Album.DataPubblicazione,
                        SEC_TO_TIME(SUM(TIME_TO_SEC(Traccia.Durata))) as DurataAlbum
                        FROM Album
                        JOIN Traccia ON Album.ID = Traccia.Album
                        WHERE Album.ID = $id";
            $queryResult = mysqli_query($this -> connection, $query)
                or die("Errore in DBAccess" .mysqli_error($this -> connection));
            if (mysqli_num_rows($queryResult) != 0) {
                $result = mysqli_fetch_assoc($queryResult);
                $queryResult -> free();
                return array($result["ID"],
                                $result["Titolo"],
                                $result["Copertina"],
                                $result["DataPubblicazione"],
                                $result["DurataAlbum"]);
            } else {
                return null;
            }
        }

        public function getTracceAlbum($id) {
            $query = "SELECT ID, Titolo, Durata FROM Traccia ORDER BY ID ASC;";
            $queryResult = mysqli_query($this -> connection, $query)
                or die("Errore in DBAccess" .mysqli_error($this -> connection));
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