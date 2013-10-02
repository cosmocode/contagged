<?php
class Geocoder
{
    public function __construct()
    {
        $file = __DIR__ . '/../cache/geocode.sq3';
        $new = !file_exists($file);
        $this->db = new PDO('sqlite:' . $file);
        if ($new) {
            $this->db->exec(<<<SQL
                CREATE TABLE coords (
                    address TEXT UNIQUE,
                    lat REAL,
                    lon REAL,
                    expiration TEXT
                )
SQL
            );
        }
        $this->fetchStmt = $this->db->prepare(
            'SELECT address, lat, lon FROM coords WHERE address = :address'
        );
        $this->insertStmt = $this->db->prepare(
            'INSERT INTO coords'
            . '(address, lat, lon, expiration)'
            . ' VALUES '
            . '(:address, :lat, :lon, :expiration)'
        );
    }

    public function getPrivateCoords($entry)
    {
        return $this->getCoords($entry['homestreet']);
    }

    public function getBusinessCoords($entry)
    {
        if (!isset($entry['street']) || !isset($entry['location'])) {
            return null;
        }
        return $this->getCoords(
            $entry['street'] . ', ' . $entry['zip'] . ' ' . $entry['location']
            . ', ' . $entry['state'] . ', ' . $entry['country']
        );
    }

    public function getCoords($strAddress)
    {
        $strAddress = str_replace(array("\r", "\n", ' OT '), ' ', $strAddress);
        $this->fetchStmt->execute(array(':address' => $strAddress));
        $coords = $this->fetchStmt->fetchObject();
        if ($coords !== false) {
            if ($coords->lat === null) {
                return null;
            }
            return $coords;
        }

        $coords = $this->geoCode($strAddress);
        if ($coords === null) {
            $this->insertStmt->execute(
                array(
                    'address' => $strAddress,
                    'lat' => null,
                    'lon' => null,
                    'expiration' => time() + 86400
                )
            );
            return null;
        }

        $this->insertStmt->execute(
            array(
                'address' => $strAddress,
                'lat' => $coords->lat,
                'lon' => $coords->lon,
                'expiration' => time() + 86400 * 7
            )
        );
        return $coords;
    }

    public function geoCode($strAddress)
    {
        //Docs: http://open.mapquestapi.com/nominatim/
        $url = 'http://open.mapquestapi.com/nominatim/v1/search'
            . '?format=json'
            . '&limit=1'
            . '&q=' . urlencode($strAddress);

        $context = stream_context_create(
            array(
                'http' => array(
                    'user_agent' => 'ConTagged LDAP address book'
                )
            )
        );
        $json = file_get_contents($url, false, $context);
        if ($json === false) {
            return null;
        }
        $data = json_decode($json);
        if ($data === null || $data === false) {
            return null;
        }

        if (count($data) == 0) {
            return null;
        }

        return (object) array(
            'lat' => $data[0]->lat,
            'lon' => $data[0]->lon,
            'address' => $strAddress
        );
    }

    public function cleanup()
    {
        $this->db->query(
            'DELETE FROM coords WHERE expiration < ' . time()
        );
    }
}
?>
