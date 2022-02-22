<?php

if (ISINCLUDED != '1') {
  die('ACCESS DENIED!');
}

/**
 * Class ServerDaraProvider.
 *
 * I don't know why I created class, it is useless.
 */
class ServerDataProvider {

  /**
   * @var \PDO
   */
  private $db;

  /**
   * @param \PDO $db
   */
  public function __construct(PDO $db) {
    $this->db = $db;
  }


  /**
   * TODO: Don't use SQL.
   * TODO: Refactor to user type of sql, and return only data array.
   * TODO: Remove this function, i don't like it.
   *
   * @param string $sql
   *
   * @return false|PDOStatement
   */
  protected function getDataBySql(string $sql) {
    return $this->db->query($sql);
  }

  /**
   * Function for global stats.
   *
   * @param array $server
   *
   * @return array
   */
  public function getGlobalStat(array $server): array {
    $data = [];
    $data['online'] = $this->getOnline();
    $data['characters'] = $this->getCharactersStats();
    $data['global'] = $this->getServerVariables();
    $data['epic'] = $this->getEpicCount($server);
    return $data;
  }

  /**
   * Just get online * STAT_ONLINE_X configuration.
   *
   * @return array
   */
  public function getOnline(): array {
    $content = [];
    $sql = 'SELECT count(obj_Id) as online FROM characters WHERE online > 0';
    $data = $this->getDataBySql($sql);
    foreach ($data as $row) {
      $content['data'] = $row['online'] * STAT_ONLINE_X;
    }
    return $content;
  }

  /**
   * Get SUM stats from characters table.
   *
   * @return array
   */
  public function getCharactersStats(): array {
    $content = [];
    $sql = 'SELECT SUM(pvpkills) as pvp, SUM(pkkills) as pk, SUM(onlinetime) as tt, SUM(noble) as noble  FROM characters;';
    $data = $this->getDataBySql($sql);

    foreach ($data as $row) {
      // TODO: Is onlinetime in minutes?
      $totaltime = $row['tt'] ?? 0;
      $seconds = floor($totaltime * 60);
      $dtF = new \DateTime('@0');
      $dtT = new \DateTime("@$seconds");
      $content['data'] = [
        'pvp' => $row['pvp'] ?? 0,
        'pk' => $row['pk'] ?? 0,
        'totaltime' => $totaltime,
        'totaltime_converted' => $dtF->diff($dtT)
          ->format('%a days, %h hours, %i minutes'),
        'noble' => $row['noble'],
      ];
    }
    return $content;
  }

  /**
   * Get Server variables from server_variables
   *
   * @return array
   */
  public function getServerVariables(): array {
    $content = [];
    $sql = 'SELECT name,value FROM server_variables';
    $data = $this->getDataBySql($sql);
    foreach ($data as $row) {
      $name = $row['name'] ?? '';
      $value = $row['value'] ?? '';
      if (empty($name)) {
        continue;
      }
      switch ($name) {
        case 'RecordOnline':
          $content['data'][strtolower($name)] = $value * STAT_ONLINE_X;
          break;
          // TODO: What do we need to use?
        case 'Olympiad_CurrentCycle':
        case 'Olympiad_Period':
        case 'Olympiad_End':
        case 'Olympiad_ValdationEnd':
        case 'Olympiad_NextWeeklyChange':
        case 'fishChampionshipEnd':
          $content['data'][strtolower($name)] = floor($value);
          break;
        default:
          break;
      }
    }
    return $content;
  }

  /**
   * Get count of epic items.
   *
   * @param $server
   *
   * @return array
   */
  public function getEpicCount($server): array {
    $content = [];
    $epic = $server['epic'] ?? [];
    $ids = implode(', ', array_keys($epic));
    $sql = "SELECT item_id, count(item_id) as counter FROM items WHERE item_id IN ($ids) GROUP BY item_id";
    $data = $this->getDataBySql($sql);
    foreach ($data as $row) {
      $tid = $row['item_id'];
      $content['data'][$tid] = array_merge($epic[$tid], ['count' => $row['counter']]);
    }
    return $content;
  }

  /**
   * Get epic detailed stats.
   *
   * @param array $epic
   *
   * @return array
   */
  public function getEpicStat(array $epic): array {
    $result = [];
    $ids = implode(', ', array_keys($epic));
    $sql = "SELECT items.item_id, characters.char_name, characters.online, clan_data.clan_name, clan_data.clan_id, items.enchant_level FROM `characters`
LEFT JOIN `clan_data` ON characters.clanid = clan_data.clan_id INNER JOIN `items` ON characters.obj_Id = items.owner_id and items.item_id IN ($ids)
ORDER BY items.item_id, items.enchant_level DESC";
    $data = $this->getDataBySql($sql);
    foreach ($data as $row) {
      $tid = $row['item_id'] ?? '';
      if (empty($tid)) {
        continue;
      }
      $result[$tid]['items'][] = [
        'id' => $tid,
        'name' => $row['char_name'],
        'status' => $row['online'],
        'clan' => $row['clan_name'],
        'enchant' => $row['enchant_level'],
      ];
    }
    foreach ($epic as $id => $item) {
      if (!isset($result[$id])) {
        continue;
      }
      $result[$id]['info'] = $item;
    }
    return $result;
  }

}