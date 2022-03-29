# QuicktDBTeigi

QuicktDBTeigiは、他のライブラリに一切依存せずにPHPのみでMySQLのテーブル定義を出力できるソフトウェアです。  
簡単な設定で、テーブル定義を単位のHTMLファイルとして出力します。


# 使い方

```
# ソースを取ってきます
git clone https://github.com/hoku/quickt-db-teigi.git

# 設定用ファイルを作成します
cp quickt-db-teigi/config.json.example quickt-db-teigi/config.json

# 設定ファイル内にDB接続情報を入力します
vi quickt-db-teigi/config.json

# 実行します
php quickt-db-teigi/make_db_teigi.php
```

上記を実行するだけで、「db_teigi.html」が出力されます。  
「db_teigi.html」内には全てのテーブルの定義が記載されているため、定義情報を他の人に共有したい場合はこのHTMLファイルのみを渡せばOKです。


# ライセンス

本ソフトは MIT License です。
