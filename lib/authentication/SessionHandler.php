<?php

class authentication_SessionHandler {

    #$DB = null;

    /**
     * Konstruktor
     */
    public function __construct()
    {   
        
        // Den SessionHandler auf die Methoden
        // dieser Klasse setzen
        session_set_save_handler(array ($this, '_open'), 
                                 array ($this, '_close'),
                                 array ($this, '_read'),
                                 array ($this, '_write'), 
                                 array ($this, '_destroy'), 
                                 array ($this, '_gc'));

        
        // Session starten 
        //session_start();
        //session_write_close();
        register_shutdown_function('session_write_close');
    }

    /**
     * Öffnen der Session
     * 
     * @return boolean Gibt immer true zurück
     */
    public function _open($path, $name) {    
        
        return true;
    }

    /**
     * Session schließen 
     * 
     * @return boolean Gibt immer true zurück
     */
    public function _close() {
            
        //Ruft den Garbage-Collector auf.
        $this->_gc(0);
        return true;
    }

    /**
     * Session-Daten aus der Datenbank auslesen
     * 
     * @return varchar Gibt entweder die Sitzungswerte oder einen leeren String zurück
     */
    public function _read($sesID) {
        
        $sessionStatement = "SELECT * FROM sessions"." WHERE id = '$sesID'";
        /*$result = $this->DB->query($sessionStatement);
        
        if ($result === false) {
            return '';
        }

        if (count($result) > 0) {

            return $result[0]["value"];
        } else {
            return '';
        }*/
    }

    /**
     * Neue Daten in die Datenbank schreiben
     * 
     * @param varchar eindeutige Sessionid
     * @param Array Alle Daten der Session
     * 
     * @return boolean Gibt den Status des Schreibens zurück
     */
    public function _write($sesID, $data) {
        //Nur schreiben, wenn Daten übergeben werden
        if($data == null)
        {
            return true;
        } 
         
        //Statement um eine bestehende Session "upzudaten"
        /*$sessionStatement = "UPDATE sessions "." SET lastUpdated='".time()."', value='$data' WHERE id='$sesID'";
        $result = $this->DB->query($sessionStatement);


        //Ergebnis prüfen
        if ($result === false) {
            //Fehler in der Datenbank
            return false;
        }
        if ($this->DB->MySQLiObj->affected_rows) {
            //bestehende Session "upgedated"
            return true;
        }
        
        //Ansonsten muss eine neue Session erstellt werden
        $sessionStatement = "INSERT INTO sessions "." (id, lastUpdated, start, value)"." VALUES ('$sesID', '".time()."', '".time()."', '$data')";
        $result = $this->DB->query($sessionStatement);

        //Ergebnis zurückgeben
        return $result;
		*/
    }

    /**
     * Session aus der Datenbank löschen
     *   
     * @param varchar eindeutige Session-Nr.
     * 
     * @return boolean Gibt den Status des Zerstörens zurück
     */
    public function _destroy($sesID) {
        
        $sessionStatement = "DELETE FROM sessions "." WHERE id = '$sesID'";
        
        /*$result = $this->DB->query($sessionStatement);
        //Ergebnis zurückgeben (true|false)
        return $result;*/
    }

    /**
     * Müll-Sammler ;-)
     * 
     * Löscht abgelaufene Sessions aus der Datenbank
     * 
     * @return boolean Gibt den Status des Bereinigens zurück
     */
    public function _gc($life) {
        
        //Zeitpunkt, zu dem die Session als abgelaufen gilt.
        //Hier 15 min
        $sessionLife = strtotime("-15 minutes");

        $sessionStatement = "DELETE FROM sessions "." WHERE lastUpdated < $sessionLife";
        /*$result = $this->DB->query($sessionStatement);
        //Ergebnis zurückgeben
        return $result;*/
    }
}
?> 