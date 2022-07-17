<?php

date_default_timezone_set("Asia/Kolkata");

class db {

	public function pconnect() {
		
		/*
		if($_SERVER['SERVER_NAME']=='::1') {
			$server = "localhost/memelok";
		}
		else {
			$server = $_SERVER['SERVER_NAME'];
		}*/

		$server = "localhost/memelok";

		try {
			$pdo = new PDO("mysql:host=localhost;dbname=people", "root", "");
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			return $pdo;
		}
		catch(PDOException $e) {
			return $e->getMessage();
		}

	}
	public function mconnect($db) {

		if($_SERVER['SERVER_NAME']=='::1') {
			$server = "localhost";
		}
		else {
			$server = $_SERVER['SERVER_NAME'];
		}

		try {
			$pdo = new PDO("mysql:host=localhost;dbname=$db", "root", "");
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			return $pdo;
		}
		catch(\Exception $e) {
			return $e->getMessage();
		}

	}

	public function connect() {

		if($_SERVER['SERVER_NAME']=='::1') {
			$server = "localhost";
		}
		else {
			$server = $_SERVER['SERVER_NAME'];
		}

		try {
			$pdo = new PDO("mysql:host=localhost", "root", "");
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			return $pdo;
		}
		catch(\Exception $e) {
			return $e->getMessage();
		}
	}

	public function pageconnect() {
		try {
			$pdo = new PDO("mysql:host=localhost;dbname=pages", "root", "");
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			return $pdo;
		}
		catch(\Exception $e) {
			return $e->getMessage();
		}
	}

	public function postsconnect() {
		try {
			$pdo = new PDO("mysql:host=localhost;dbname=posts", "root", "");
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			return $pdo;
		}
		catch(\Exception $e) {
			return $e->getMessage();
		} 
	}

}