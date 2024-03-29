BEGIN TRANSACTION;
DROP TABLE IF EXISTS "chat";
CREATE TABLE IF NOT EXISTS "chat" (
	"id"	INTEGER NOT NULL UNIQUE,
	"text"	TEXT NOT NULL,
	"datetime"	TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	"username"	TEXT NOT NULL,
	"perm"	INTEGER DEFAULT 1,
	PRIMARY KEY("id" AUTOINCREMENT)
);
DROP TABLE IF EXISTS "counter";
CREATE TABLE IF NOT EXISTS "counter" (
	"datetime"	TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	"ip"	TEXT,
	"agent"	TEXT,
	"page"	TEXT,
	"username"	TEXT
);
DROP TABLE IF EXISTS "users";
CREATE TABLE IF NOT EXISTS "users" (
	"id"	INTEGER NOT NULL UNIQUE,
	"name"	TEXT NOT NULL,
	"username"	TEXT NOT NULL UNIQUE,
	"password"	TEXT NOT NULL,
	"notf"	TEXT,
	"perm"	INTEGER DEFAULT 1,
	PRIMARY KEY("id" AUTOINCREMENT)
);
COMMIT;
