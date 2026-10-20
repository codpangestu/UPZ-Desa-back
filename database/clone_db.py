import sqlalchemy

def main():
    engine = sqlalchemy.create_engine('mysql+pymysql://root:cauburuk123@127.0.0.1:3306/')
    with engine.connect() as conn:
        conn.execute(sqlalchemy.text('CREATE DATABASE IF NOT EXISTS upzdesa_revamp;'))
        conn.commit()

    src_engine = sqlalchemy.create_engine('mysql+pymysql://root:cauburuk123@127.0.0.1:3306/menara_masjid_revamp')
    dest_engine = sqlalchemy.create_engine('mysql+pymysql://root:cauburuk123@127.0.0.1:3306/upzdesa_revamp')

    with src_engine.connect() as s_conn:
        tables = [r[0] for r in s_conn.execute(sqlalchemy.text('SHOW TABLES')).fetchall()]

    with dest_engine.connect() as d_conn:
        d_conn.execute(sqlalchemy.text("SET GLOBAL sql_mode = '';"))
        d_conn.execute(sqlalchemy.text("SET SESSION sql_mode = '';"))
        d_conn.execute(sqlalchemy.text('SET FOREIGN_KEY_CHECKS = 0;'))
        for t in tables:
            with src_engine.connect() as s_conn:
                create_sql = s_conn.execute(sqlalchemy.text(f'SHOW CREATE TABLE `{t}`')).fetchone()[1]
            d_conn.execute(sqlalchemy.text(f'DROP TABLE IF EXISTS `{t}`'))
            d_conn.execute(sqlalchemy.text(create_sql))
            d_conn.execute(sqlalchemy.text(f'INSERT INTO `{t}` SELECT * FROM `menara_masjid_revamp`.`{t}`'))
        d_conn.execute(sqlalchemy.text('SET FOREIGN_KEY_CHECKS = 1;'))
        d_conn.commit()

    print('Database upzdesa_revamp successfully created and cloned from menara_masjid_revamp!')

if __name__ == '__main__':
    main()
