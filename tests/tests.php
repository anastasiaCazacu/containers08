<?php

require_once __DIR__ . '/testframework.php';
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../modules/database.php';
require_once __DIR__ . '/../modules/page.php';

$tests = new TestFramework();

// Test 1: verifică conexiunea la baza de date
function testDbConnection()
{
    global $config;
    try {
        $db = new Database($config["db"]["path"]);
        return assertExpression(true, "Connected successfully");
    } catch (Exception $e) {
        return assertExpression(false, "Connection failed: " . $e->getMessage());
    }
}

// Test 2: număr înregistrări în tabel
function testDbCount()
{
    global $config;
    $db = new Database($config["db"]["path"]);
    $count = $db->Count("page");
    return assertExpression(is_numeric($count), "Count OK", "Count failed");
}

// Test 3: creare înregistrare
function testDbCreate()
{
    global $config;
    $db = new Database($config["db"]["path"]);
    $id = $db->Create("page", ["title" => "Test page", "content" => "Test content"]);
    return assertExpression($id > 0, "Create OK", "Create failed");
}

// Test 4: citire înregistrare
function testDbRead()
{
    global $config;
    $db = new Database($config["db"]["path"]);
    $id = $db->Create("page", ["title" => "Read test", "content" => "Read content"]);
    $record = $db->Read("page", $id);
    return assertExpression($record["title"] === "Read test", "Read OK", "Read failed");
}

// Test 5: actualizare înregistrare
function testDbUpdate()
{
    global $config;
    $db = new Database($config["db"]["path"]);
    $id = $db->Create("page", ["title" => "Old title", "content" => "Old content"]);
    $db->Update("page", $id, ["title" => "New title"]);
    $record = $db->Read("page", $id);
    return assertExpression($record["title"] === "New title", "Update OK", "Update failed");
}

// Test 6: ștergere înregistrare
function testDbDelete()
{
    global $config;
    $db = new Database($config["db"]["path"]);
    $id = $db->Create("page", ["title" => "Delete test", "content" => "To delete"]);
    $db->Delete("page", $id);
    $record = $db->Read("page", $id);
    return assertExpression($record === null || empty($record), "Delete OK", "Delete failed");
}

// Test 7: randare pagină cu templating simplu
function testPageRender()
{
    $template = __DIR__ . '/../templates/test.tpl';
    file_put_contents($template, "<h1>{{title}}</h1><p>{{content}}</p>");

    $page = new Page($template);
    $output = $page->Render(["title" => "Hello", "content" => "World"]);

    unlink($template); // curăță

    return assertExpression(strpos($output, "Hello") !== false && strpos($output, "World") !== false, "Render OK", "Render failed");
}

// Adăugăm testele în sistem
$tests->add('Database connection', 'testDbConnection');
$tests->add('Table count', 'testDbCount');
$tests->add('Create record', 'testDbCreate');
$tests->add('Read record', 'testDbRead');
$tests->add('Update record', 'testDbUpdate');
$tests->add('Delete record', 'testDbDelete');
$tests->add('Page render', 'testPageRender');

// Rulăm testele
$tests->run();

// Afișăm rezultatul
echo $tests->getResult();
