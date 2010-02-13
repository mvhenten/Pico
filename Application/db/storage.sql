DROP TABLE post;
CREATE TABLE post(
    id INTEGER PRIMARY KEY,
    title,
    body,
    inserted DATETIME,
    categories
);
DROP TABLE comment;
CREATE TABLE comment(
    id INTEGER PRIMARY KEY,
    post_id INTEGER,
    name,
    comment,
    inserted DATETIME,
    ip
);
