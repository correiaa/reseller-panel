<?php

namespace App;

use \PDO;
use App\Exceptions\ModelException;

abstract class Mapper
{
  protected $table;
  protected $allFields;

  protected $id;
  protected $findId;

  protected $pdo;

  protected $selectStmnt;
  protected $updateStmnt;
  protected $updateOnDuplicateKeyStmnt;
  protected $deleteStmnt;
  protected $findStmnt;
  protected $insertStmnt;
  protected $showColumnsStmnt;

  public function __construct(DatabaseCreditsInterface $db)
  {
    $dsn = $db->getType().':dbname='.$db->getName().';host='.$db->getHost();
    $options = [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ];

    $this->pdo = new PDO($dsn, $db->getUser(), $db->getPass());
    $this->table = $this->getTable();
    $this->findId = $this->id = $this->getIdAttribute();

    $this->selectStmnt = $this->pdo->prepare($this->getSelectSql());
    $this->findStmnt = $this->pdo->prepare($this->getFindSql());
    $this->deleteStmnt = $this->pdo->prepare($this->getDeleteSql());
    $this->updateStmnt = $this->pdo->prepare($this->getUpdateSql());
    $this->insertStmnt = $this->pdo->prepare($this->getInsertSql());
    $this->updateOnDuplicateKeyStmnt = $this->pdo->prepare($this->getUpdateOnDuplicateKeySql());
    $this->showColumnsStmnt = $this->pdo->prepare($this->getShowColumnsSql());
  }

  public function setFindId($id)
  {
    $this->findId = $id;
    $this->findStmnt = $this->pdo->prepare($this->getFindSql());
  }

  abstract protected function getFieldsToUpdate(): array;

  abstract protected function getFieldsToInsert(): array;

  abstract protected function getFieldsToUpdateOnDuplicateKey(): array;

  public function insert(&$objectModel)
  {
    $insertFields = $this->getFieldsToInsert();
    $insertData = $this->getData($objectModel, array_keys($insertFields));

    foreach($insertData as $key => $value)
    {
      $filteredValue = $this->filterValue($value, $insertFields[$key]);
      $this->insertStmnt->bindValue(":$key", $filteredValue, $insertFields[$key]);
    }

    $this->insertStmnt->execute();
    $objectModel->setId($this->pdo->lastInsertId());
  }

  public function insertArray(array $data)
  {
    $object = $this->createObject($data);
    $this->insert($object);
    return $object;
  }

  public function update($objectModel)
  {
    $updateFields = $this->getFieldsToUpdate();
    $updateData = $this->getData($objectModel, array_keys($updateFields));

    foreach($updateData as $key => $value)
    {
      $filteredValue = $this->filterValue($value, $updateFields[$key]);
      $this->updateStmnt->bindValue(":$key", $filteredValue, $updateFields[$key]);
    }

    $this->updateStmnt->execute();
  }

  public function insertOrUpdate(&$objectModel)
  {
    $insertFields = $this->getFieldsToInsert();
    $updateFields = $this->getFieldsToUpdateOnDuplicateKey();
    $insertData = $this->getData($objectModel, array_keys($insertFields));
    $updateData = $this->getData($objectModel, array_keys($updateFields));

    $data = array_merge($insertData, $updateData);
    $params = array_merge($insertFields, $updateFields);

    foreach($data as $key => $value)
    {
      $filteredValue = $this->filterValue($value, $params[$key]);
      $this->updateOnDuplicateKeyStmnt->bindValue(":$key", $filteredValue, $params[$key]);
    }

    $this->updateOnDuplicateKeyStmnt->execute();
    $id = $this->pdo->lastInsertId();

    if(intval($id) > 0)
    {
      $objectModel->setId($this->pdo->lastInsertId());
    }
  }

  public function find($id)
  {
    $this->findStmnt->execute([$id]);
    $data = $this->findStmnt->fetch();

    return is_array($data) ? $this->createObject($data) : null;
  }

  public function selectAll()
  {
    $this->selectStmnt->execute();
    $selection = $this->selectStmnt->fetchAll();
    if(is_array($selection))
    {
      foreach ($selection as $data)
      {
        if(is_array($data))
        {
          yield $this->createObject($data);
        }
      }
    }
  }

  public function delete($id)
  {
    $this->deleteStmnt->execute([$id]);
  }

  protected function getIdAttribute()
  {
    return 'id';
  }

  protected function getTable(): string
  {
    ($pos = strrpos(get_called_class(), '\\'))
      ? $fullClassName = substr(get_called_class(), $pos + 1)
      : $fullClassName = get_called_class();

    return strtolower(str_replace('_Mapper', '', ltrim(preg_replace("~[A-Z]~", "_$0", $fullClassName), "_")) . 's');
  }

  protected function getModelClass(): string
  {
    return str_replace('Mapper', 'Model', get_called_class());
  }

  protected function getShowColumnsSql(): string
  {
    return "SHOW COLUMNS FROM `$this->table`";
  }

  protected function getSelectSql(): string
  {
    return "SELECT * FROM `$this->table`";
  }

  protected function getFindSql(): string
  {
    return "SELECT * FROM `$this->table` WHERE `$this->findId`=?";
  }

  protected function getUpdateSql(): string
  {
    $fields = array_keys($this->getFieldsToUpdate());
    $set = '';
    foreach($fields as $key)
    {
      if($key == 'id')
      {
        continue;
      }

      $set .= "`$key` = :$key,";
    }

    $set = rtrim($set, ',');
    return sprintf("UPDATE `$this->table` SET %s WHERE `$this->id` = :id", $set, $fields);
  }

  protected function getInsertSql(): string
  {
    $keys = array_keys($this->getFieldsToInsert());
    $values = preg_filter('~^~', ':', $keys);

    $sql = sprintf("INSERT INTO `$this->table`(%s) VALUES(%s)", implode(',', $keys), implode(',', $values));

    return $sql;
  }

  protected function getUpdateOnDuplicateKeySql()
  {
    $keys = array_keys($this->getFieldsToUpdateOnDuplicateKey());
    $values = [];
    foreach ($keys as $key)
    {
      $values[] = "$key=:$key";
    }
    $sql = sprintf($this->getInsertSql() . " ON DUPLICATE KEY UPDATE %s", implode(',', $values));
    return $sql;
  }

  protected function getDeleteSql(): string
  {
    return "DELETE FROM `$this->table` WHERE `$this->id`=?";
  }

  protected function getAllFields(): array
  {
    if(!$this->allFields)
    {
      $this->showColumnsStmnt->execute();
      $this->allFields = $this->showColumnsStmnt->fetchAll(PDO::FETCH_COLUMN);
    }
    return $this->allFields;
  }

  public function createObject(array $data): Model
  {
    $modelClass = $this->getModelClass();
    $fields = $this->getAllFields();
    $objectModel = new $modelClass($fields);

    foreach($fields as $key)
    {
      if(isset($data[$key]))
      {
        $method = $this->getMethodByColumnName('set', $key);
        call_user_func([$objectModel, $method], $data[$key]);
      }
    }

    return $objectModel;
  }

  protected function getMethodByColumnName($prefix, $columnName)
  {
    return lcfirst(str_replace(' ', '', ucwords(strtr($prefix."_".$columnName, '_-', ' '))));
  }

  protected function ensureModelObjectType($objectModel)
  {
    $modelClass = $this->getModelClass();
    if( !($objectModel instanceof $modelClass) )
    {
      throw new ModelException(get_class($objectModel) . " not instance of " . get_called_class(), 1);
    }
  }

  protected function getData($objectModel, array $allowedFields)
  {
    $this->ensureModelObjectType($objectModel);
    $data = [];
    foreach ($allowedFields as $field)
    {
      $method = $this->getMethodByColumnName('get', $field);
      $data[$field] = call_user_func([$objectModel, $method]);
    }
    return $data;
  }

  protected function filterValue($value, $param = PDO::PARAM_STR)
  {
    switch($param)
    {
      case PDO::PARAM_INT:
        $value = (int)$value;
        break;
      case PDO::PARAM_BOOL:
        $value = ($value == true) ? 1 : 0;
        break;
      case PDO::PARAM_NULL:
        $value = null;
        break;
      default:
        $value = (string)$value;
        break;
    }
    return ($value === '') ? null : $value;
  }

}

 ?>
