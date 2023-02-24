CREATE DATABASE taskify
	WITH
	OWNER = postgres;
	
CREATE TABLE task (
	id SERIAL PRIMARY KEY,
	name VARCHAR(255) NOT NULL,
	description TEXT NOT NULL,
	priority INTEGER NOT NULL,
	status INTEGER NOT NULL,
	created_at TIMESTAMP NOT NULL,
	updated_at TIMESTAMP
);
