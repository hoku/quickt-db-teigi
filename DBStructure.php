<?php
/**
 * DB情報取得用クラス
 *
 * @license   MIT License
 * @author    hoku
 */

class DBStructure
{
    public static function load(string $dbHost, string $dbName, string $dbUser, string $dbPass)
    {
        // 入力値チェック
        if (!preg_match('/^[0-9a-zA-Z._-]+$/u', $dbHost.$dbName)) {
            echo "error: invalid params.\n";
            exit;
        }

        // DB接続
        $pdo = null;
        try {
            $dsn = 'mysql:dbname='.$dbName.';host='.$dbHost;
            $pdo = new PDO($dsn, $dbUser, $dbPass);
        } catch (PDOException $e) {
            echo "error: db connect.\n";
            echo $e->getMessage() . "\n";
            exit;
        }

        // DB情報を取得
        $stmt = $pdo->prepare('SELECT * FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME=:db');
        $stmt->bindValue(':db', $dbName, PDO::PARAM_STR);
        $stmt->execute();
        $dbInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);


        // 外部キー制約情報を取得
        $stmt = $pdo->prepare('SELECT * FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA=:db AND REFERENCED_TABLE_SCHEMA IS NOT NULL');
        $stmt->bindValue(':db', $dbName, PDO::PARAM_STR);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // テーブル毎に制約をまとめる
        $foreignKeysPerTable = [];
        foreach ($results as $result) {
            if (!array_key_exists($result['TABLE_NAME'], $foreignKeysPerTable)) {
                $foreignKeysPerTable[$result['TABLE_NAME']] = [];
            }
            $foreignKeysPerTable[$result['TABLE_NAME']][] = [
                'TABLE_NAME'             => $result['TABLE_NAME'],
                'COLUMN_NAME'            => $result['COLUMN_NAME'],
                'REFERENCED_TABLE_NAME'  => $result['REFERENCED_TABLE_NAME'],
                'REFERENCED_COLUMN_NAME' => $result['REFERENCED_COLUMN_NAME'],
            ];
        }

        // テーブル一覧を取得
        $stmt = $pdo->query('SHOW TABLES');
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $tableNames = array_column($results, 'Tables_in_'.$dbName);

        // テーブル毎の構造を取得
        $structuresPerTable = [];
        foreach ($tableNames as $tableName) {
            // テーブル情報を取得
            $stmt = $pdo->query('SHOW TABLE STATUS LIKE \''.$tableName.'\'');
            $tableStatus = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $tableStatus = self::withoutColumns($tableStatus, ['Rows', 'Avg_row_length', 'Data_length', 'Max_data_length', 'Index_length', 'Data_free', 'Auto_increment', 'Create_time', 'Update_time', 'Check_time', 'Checksum']);

            // キー情報を取得
            $stmt = $pdo->query('SHOW KEYS FROM '.$tableName);
            $tableKeys = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $tableKeys = self::withoutColumns($tableKeys, ['Table', 'Cardinality']);

            // フィールド情報を取得
            $stmt = $pdo->query('SHOW FULL COLUMNS FROM '.$tableName);
            $tableColumns = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $tableColumns = self::withoutColumns($tableColumns, ['Privileges']);

            $structuresPerTable[$tableName] = [
                'TABLE_STATUS'  => $tableStatus,
                'TABLE_KEYS'    => $tableKeys,
                'TABLE_COLUMNS' => $tableColumns,
            ];
        }

        return [$dbInfo, $structuresPerTable, $foreignKeysPerTable];
    }

    private static function withoutColumns(array $datas, array $keys)
    {
        $newDatas = [];
        foreach ($datas as $data) {
            foreach ($keys as $key) {
                unset($data[$key]);
            }
            $newDatas[] = $data;
        }
        return $newDatas;
    }

}
