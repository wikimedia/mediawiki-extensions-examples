--
-- Tables for the Example extension
--

-- Notes table
CREATE TABLE /*_*/example_note (
  -- Unique ID to identify each note
  exnote_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,

  -- Foreign key to user.user_id
  exnote_user int unsigned NOT NULL,

  -- Key to page.page_id.
  exnote_page int unsigned NOT NULL,

  -- Note value as a string.
  exnote_value blob

) /*$wgDBTableOptions*/;

-- For querying of all notes from a certain user
--  (e.g. Special:Notes).
-- For querying of all notes from a certain user on a certain page
--  (e.g. "My notes" on a page).
CREATE INDEX /*i*/exnote_user_page ON /*_*/example_note (exnote_user, exnote_page);

-- For querying of all notes from all users on a certain page
--  (e.g. "Notes by other users" on a certain page).
CREATE INDEX /*i*/exnote_page_user ON /*_*/example_note (exnote_page, exnote_user);
