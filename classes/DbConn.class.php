<?php
class DbConn {

  public $conn;
  public $result;
  public $sql;

  public function __construct() {

    $this->conn = new mysqli('localhost', 'root', '', 'bs23');
    // Check connection
    if ($this->conn->connect_error) {
      error_log("Failed to connect to database! " . $this->conn->connect_error, 0);
      die("Connection failed: " . $this->conn->connect_error);
    }
    return;
  }

  // encapsulate the query function of mysqli
  public function query($sql) {
    // error_log($sql);
    $this->sql    = $sql;
    $this->result = $this->conn->query($sql);
    return $this->result;
  }

  public function real_escape_string($string){
    // $city = "'s Hertogenbosch";
    // $city = $mysqli->real_escape_string($city);
    $resString = $this->conn->real_escape_string($string);
    return $resString;
  }  
  public function begin_transaction($flags = null, $name = null) {
    if ($name != null) {
      return $this->conn->begin_transaction($flags, $name);
    } elseif ($flags != null) {
      return $this->conn->begin_transaction($flags);
    } else {
      return $this->conn->begin_transaction();
    }
  }
  public function autocommit($mode) {
    return $this->conn->autocommit($mode);
  }
  public function commit($flags = null, $name = null) {
    if ($name != null) {
      return $this->conn->commit($flags, $name);
    } elseif ($flags != null) {
      return $this->conn->commit($flags);
    } else {
      return $this->conn->commit();
    }
  }
  public function close() {
    $closereturn = $this->conn->close();
    unset($this->conn);
    return $closereturn;
  }

  public function __destruct() {
    if (isset($this->conn)) {
      $this->conn->close();
    }
  }

    public function fuzzyLike($userentry, $fieldname, $operator) {
    $words = explode(" ", trim($userentry));
    foreach ($words as $word) {
      if (strlen($word) > 0) {
        $word    = str_replace("_", " ", $word);
        $likes[] = $fieldname . " LIKE '%" . chunk_split($word, 1, "%") . "'";
      }
    }
    if (isset($likes)) {
      return join(" " . $operator . " ", $likes);
    } else {
      return "TRUE";
    }
  }

  public function sanitizeSuperStrict($userentry) {
    // will remove anything that's not A-Z 0-9 ()-_ space
    $chars     = str_split($userentry);
    $sanitized = "";
    foreach ($chars as $char) {
      $charOrd = ord(strtoupper($char));
      if (($charOrd >= 65 && $charOrd <= 90) || // A-Z
        ($charOrd >= 48 && $charOrd <= 57) || // 0-9
        ($charOrd >= 40 && $charOrd <= 41) || // ()
        ($charOrd >= 44 && $charOrd <= 46) || // ,-.
        ($charOrd == 95) || // _
        ($charOrd == 32) // space
      ) {
        // -
        $sanitized .= $char;
      }
    }
    return $sanitized;
  }

  public function resultToArray($queryResult = null) {
    if ($queryResult === null) {
      $queryResult = $this->result;
    }
    $returnArray = array();
    while ($row = $queryResult->fetch_assoc()) {
      array_push($returnArray, $row);
    }
    return $returnArray;
  }

  public function resultToJson($queryResult = null) {
    if ($queryResult === null) {
      $queryResult = $this->result;
    }
    $returnArray = array();
    while ($row = $queryResult->fetch_assoc()) {
      array_push($returnArray, $row);
    }
    return json_encode(self::utf8ize($returnArray)); // static function utf8ize() solves JSON_ERROR_UTF8 error while encoding
  }

  public function resultToTable($queryResult = null) {
    if ($queryResult === null) {
      $queryResult = $this->result;
    }

    $returnArray = array();
    $header      = "";
    while ($row = $queryResult->fetch_assoc()) {
      $column = array();
      if ($header == "") {
        foreach ($row as $key => $value) {
          $column[] = $key;
        }
        $header = "<tr><th>" . join("</th><th>", $column) . "</th></tr>";
      }

      $column = array();
      foreach ($row as $key => $value) {
        $column[] = nl2br($value);
      }
      $returnArray[] = "<td>" . join("</td><td>", $column) . "</td>";
    }
    return "<table>
    " . $header . "
    <tr>" . join("</tr>
    <tr>", $returnArray) . "</tr>
  </table>";
  }

  public function sqlToJson($sql) {
    // For use in API

    $result = $this->conn->query($sql);

    if ($result->num_rows > 0) {
      $returnJSON = self::resultToJson($result);
    } else {
      //http_response_code(204);
      $returnJSON = "";
    }

    return $returnJSON;
  }

  public function sqlToArray_1r($sql) {
    // UNI-DIMENSIONAL
    // will only return the first row

    $result = $this->conn->query($sql);

    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
    } else {
      $row = array();
    }

    return $row;
  }

  public function sqlToTable($sql) {
    // For use in API

    $result = $this->conn->query($sql);

    if ($result->num_rows > 0) {
      $returnJSON = self::resultToTable($result);
    } else {
      //http_response_code(204);
      $returnJSON = "";
    }

    return $returnJSON;
  }

  public function showColumns($table) {
    $sql         = 'SHOW COLUMNS FROM `' . self::sanitizeSuperStrict($table) . '`;';
    $result      = $this->conn->query($sql);
    $returnArray = [];
    while ($row = $result->fetch_assoc()) {
      array_push($returnArray, $row);
    }
    return $returnArray;
  }

  public function getFields($table) {
    $fieldList  = self::showColumns($table);
    $fieldList2 = array();
    foreach ($fieldList as $key => $value) {
      $fieldList2[$value['Field']] = $value['Type'];
    }
    return $fieldList2;
  }

  static function utf8ize($mixed) {
    if (is_array($mixed)) {
      foreach ($mixed as $key => $value) {
        $mixed[$key] = self::utf8ize($value);
      }
    } else if (is_string($mixed)) {
      return utf8_encode($mixed);
    }
    return $mixed;
  }


}
?>