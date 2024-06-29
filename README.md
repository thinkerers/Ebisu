# scrumfishers
Application de productivité pour pêcher les tâches.

[![Stargazers over time](https://starchart.cc/thinkerers/Ebisu.svg?variant=adaptive)](https://starchart.cc/thinkerers/Ebisu)

Database export

```sql
-- Adminer 4.8.4 SQLite 3 3.36.0 dump

DROP TABLE IF EXISTS "compendium";
CREATE TABLE "compendium" (
	"id" INTEGER NOT NULL UNIQUE,
	-- L'importance d'une tâche et son urgence détermine la rareté du poisson pêché une fois la tâche accomplie. Le système de point reprend le principe scrum - une tâche difficile et importante qui a pris beaucoup de temps vaudra beaucoup de points, pour un maximum de 20. Les poissons pouvant être pêchés iront jusqu'au niveau 20 (prises légendaires)
	"rank" INTEGER NOT NULL,
	"name" TEXT NOT NULL,
	"description" TEXT NOT NULL,
	"img" TEXT NOT NULL,
	PRIMARY KEY("id")	
);


DROP TABLE IF EXISTS "fishes";
CREATE TABLE "fishes" (
    "id" INTEGER PRIMARY KEY,
    "userId" INTEGER NOT NULL,
    "fishId" INTEGER NOT NULL,
    "caughtTime" DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY ("userId") REFERENCES "users"("id") ON DELETE CASCADE,
    FOREIGN KEY ("fishId") REFERENCES "compendium"("id") ON DELETE CASCADE
);

INSERT INTO "fishes" ("id", "userId", "fishId", "caughtTime") VALUES (1,	101,	18,	'2024-06-29 04:22:37');
INSERT INTO "fishes" ("id", "userId", "fishId", "caughtTime") VALUES (2,	101,	1,	'2024-06-29 04:22:38');
INSERT INTO "fishes" ("id", "userId", "fishId", "caughtTime") VALUES (3,	101,	3,	'2024-06-29 04:22:40');
INSERT INTO "fishes" ("id", "userId", "fishId", "caughtTime") VALUES (4,	101,	4,	'2024-06-29 04:22:41');
INSERT INTO "fishes" ("id", "userId", "fishId", "caughtTime") VALUES (5,	101,	3,	'2024-06-29 04:22:43');
INSERT INTO "fishes" ("id", "userId", "fishId", "caughtTime") VALUES (6,	101,	3,	'2024-06-29 04:22:45');
INSERT INTO "fishes" ("id", "userId", "fishId", "caughtTime") VALUES (7,	101,	2,	'2024-06-29 04:22:47');
INSERT INTO "fishes" ("id", "userId", "fishId", "caughtTime") VALUES (8,	101,	18,	'2024-06-29 04:22:48');
INSERT INTO "fishes" ("id", "userId", "fishId", "caughtTime") VALUES (9,	101,	2,	'2024-06-29 04:22:50');
INSERT INTO "fishes" ("id", "userId", "fishId", "caughtTime") VALUES (10,	101,	17,	'2024-06-29 04:22:52');
INSERT INTO "fishes" ("id", "userId", "fishId", "caughtTime") VALUES (11,	101,	12,	'2024-06-29 04:22:53');
INSERT INTO "fishes" ("id", "userId", "fishId", "caughtTime") VALUES (12,	101,	6,	'2024-06-29 04:22:55');
INSERT INTO "fishes" ("id", "userId", "fishId", "caughtTime") VALUES (13,	101,	7,	'2024-06-29 04:22:57');
INSERT INTO "fishes" ("id", "userId", "fishId", "caughtTime") VALUES (14,	101,	10,	'2024-06-29 04:22:58');
INSERT INTO "fishes" ("id", "userId", "fishId", "caughtTime") VALUES (15,	101,	22,	'2024-06-29 04:22:59');
INSERT INTO "fishes" ("id", "userId", "fishId", "caughtTime") VALUES (16,	101,	5,	'2024-06-29 04:23:01');
INSERT INTO "fishes" ("id", "userId", "fishId", "caughtTime") VALUES (17,	101,	5,	'2024-06-29 04:23:02');
INSERT INTO "fishes" ("id", "userId", "fishId", "caughtTime") VALUES (18,	101,	4,	'2024-06-29 04:23:04');
INSERT INTO "fishes" ("id", "userId", "fishId", "caughtTime") VALUES (19,	101,	7,	'2024-06-29 04:23:05');
INSERT INTO "fishes" ("id", "userId", "fishId", "caughtTime") VALUES (20,	101,	4,	'2024-06-29 04:23:06');
INSERT INTO "fishes" ("id", "userId", "fishId", "caughtTime") VALUES (21,	101,	3,	'2024-06-29 04:23:07');
INSERT INTO "fishes" ("id", "userId", "fishId", "caughtTime") VALUES (22,	101,	2,	'2024-06-29 04:23:08');
INSERT INTO "fishes" ("id", "userId", "fishId", "caughtTime") VALUES (23,	101,	13,	'2024-06-29 04:23:09');
INSERT INTO "fishes" ("id", "userId", "fishId", "caughtTime") VALUES (24,	101,	0,	'2024-06-29 04:23:10');
INSERT INTO "fishes" ("id", "userId", "fishId", "caughtTime") VALUES (25,	101,	4,	'2024-06-29 04:44:45');
INSERT INTO "fishes" ("id", "userId", "fishId", "caughtTime") VALUES (26,	101,	7,	'2024-06-29 04:44:47');
INSERT INTO "fishes" ("id", "userId", "fishId", "caughtTime") VALUES (27,	101,	8,	'2024-06-29 04:44:48');
INSERT INTO "fishes" ("id", "userId", "fishId", "caughtTime") VALUES (28,	101,	0,	'2024-06-29 04:44:49');
INSERT INTO "fishes" ("id", "userId", "fishId", "caughtTime") VALUES (29,	101,	8,	'2024-06-29 04:44:50');
INSERT INTO "fishes" ("id", "userId", "fishId", "caughtTime") VALUES (30,	101,	6,	'2024-06-29 04:44:51');
INSERT INTO "fishes" ("id", "userId", "fishId", "caughtTime") VALUES (31,	101,	8,	'2024-06-29 04:45:43');
INSERT INTO "fishes" ("id", "userId", "fishId", "caughtTime") VALUES (32,	101,	5,	'2024-06-29 04:45:44');
INSERT INTO "fishes" ("id", "userId", "fishId", "caughtTime") VALUES (33,	101,	2,	'2024-06-29 04:45:48');

DROP TABLE IF EXISTS "pomodoro";
CREATE TABLE "pomodoro" (
	"id" INTEGER NOT NULL UNIQUE,
	"userId" INTEGER,
	"focusTime" TEXT NOT NULL,
	"breakTime" TEXT NOT NULL,
	"startTime" TEXT,
	"endTime" TEXT,
	PRIMARY KEY("id"),
	FOREIGN KEY ("userId") REFERENCES "users"("id")
	ON UPDATE NO ACTION ON DELETE NO ACTION
);


DROP TABLE IF EXISTS "sqlite_sequence";
CREATE TABLE sqlite_sequence(name,seq);


DROP TABLE IF EXISTS "tasks";
CREATE TABLE "tasks" (
  "description" text NULL,
  "id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
  "userId" integer NULL,
  "name" text(65) NOT NULL,
  "urgency" integer NULL,
  "priority" integer NULL,
  "state" integer NULL,
  "endTime" text NULL,
  FOREIGN KEY ("userId") REFERENCES "users" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION
);


DROP TABLE IF EXISTS "users";
CREATE TABLE "users" (
  "id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
  "email" text NOT NULL,
  "hashedPassword" text NULL
);


-- 
```