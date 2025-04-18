-- This file is automatically generated using maintenance/generateSchemaSql.php.
-- Source: sql/tables.json
-- Do not modify this file directly.
-- See https://www.mediawiki.org/wiki/Manual:Schema_changes
CREATE TABLE /*_*/example_note (
  exnote_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  exnote_user INTEGER UNSIGNED NOT NULL,
  exnote_page INTEGER UNSIGNED NOT NULL,
  exnote_value BLOB DEFAULT NULL
);

CREATE INDEX exnote_user_page ON /*_*/example_note (exnote_user, exnote_page);

CREATE INDEX exnote_page_user ON /*_*/example_note (exnote_page, exnote_user);
