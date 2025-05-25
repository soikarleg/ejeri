<?php
class Model
{
  private $db;
  private $secteur;
  private $iduser;

  public function __construct($secteur, $iduser)
  {
    $this->secteur = $secteur;
    $this->iduser = $iduser;

    // Connexion à la base de données MySQL
    $this->db = new mysqli('localhost', 'username', 'password', 'database_name');

    if ($this->db->connect_error) {
      die("Connection failed: " . $this->db->connect_error);
    }
  }

  // Fonction d'insertion
  public function insert($table, $data)
  {
    $columns = implode(", ", array_keys($data));
    $values = implode("', '", array_values($data));

    $sql = "INSERT INTO $table ($columns) VALUES ('$values')";

    if ($this->db->query($sql) === TRUE) {
      return "New record created successfully";
    } else {
      return "Error: " . $sql . "<br>" . $this->db->error;
    }
  }

  // Fonction de mise à jour
  public function update($table, $data, $where)
  {
    $set = "";
    foreach ($data as $column => $value) {
      $set .= "$column='$value', ";
    }
    $set = rtrim($set, ", ");

    $sql = "UPDATE $table SET $set WHERE $where";

    if ($this->db->query($sql) === TRUE) {
      return "Record updated successfully";
    } else {
      return "Error: " . $sql . "<br>" . $this->db->error;
    }
  }

  // Fonction de sélection
  public function select($table, $columns = "*", $where = "1")
  {
    $sql = "SELECT $columns FROM $table WHERE $where";
    $result = $this->db->query($sql);

    if ($result->num_rows > 0) {
      return $result->fetch_all(MYSQLI_ASSOC);
    } else {
      return "No records found";
    }
  }

  // Fonction de suppression
  public function delete($table, $where)
  {
    $sql = "DELETE FROM $table WHERE $where";

    if ($this->db->query($sql) === TRUE) {
      return "Record deleted successfully";
    } else {
      return "Error: " . $sql . "<br>" . $this->db->error;
    }
  }

  // Fermeture de la connexion à la base de données
  public function __destruct()
  {
    $this->db->close();
  }
}
