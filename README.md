開発するにはコンテナに入ってやるのが楽

1. `docker compose up -d`でdockerコンテナを起動
2. `docker compose exec cake_scrape bash`でコンテナ内に入る

コンテナに入った後、以下の操作でスクレイピングが始まります

3. コンテナに入った場所（~/app）で`python patisserie_search.py`で実行

結果は appフォルダ内のcsv
