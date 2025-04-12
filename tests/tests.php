<?php

require_once __DIR__ . '/testframework.php';

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../modules/database.php';
require_once __DIR__ . '/../modules/page.php';

$tests = new TestFramework();

// test 1: check database connection
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

// test 2: test count method
function testDbCount()
{
    global $config;
    $db = new Database($config["db"]["path"]);
    $count = $db->Count("page");
    return assertExpression($count >= 3, "Count OK", "Count failed");
}

// test 3: test create method
function testDbCreate()
{
    global $config;
    $db = new Database($config["db"]["path"]);
    $id = $db->Create("page", ["title" => "Test page", "content" => "Test content"]);
    return assertExpression($id > 0, "Create OK", "Create failed");
}

// test 4: test read method
function testDbRead()
{
    global $config;
    $db = new Database($config["db"]["path"]);
    $id = $db->Create("page", ["title" => "Read test", "content" => "Read content"]);
    $record = $db->Read("page", $id);
    return assertExpression($record["title"] === "Read test", "Read OK", "Read failed");
}

// test 5: test update method
function testDbUpdate()
{
    global $config;
    $db = new Database($config["db"]["path"]);
    $id = $db->Create("page", ["title" => "Old title", "content" => "Old content"]);
    $db->Update("page", $id, ["title" => "New title"]);
    $record = $db->Read("page", $id);
    return assertExpression($record["title"] === "New title", "Update OK", "Update failed");
}

// test 6: test delete method
function testDbDelete()
{
    global $config;
    $db = new Database($config["db"]["path"]);
    $id = $db->Create("page", ["title" => "Delete test", "content" => "To delete"]);
    $db->Delete("page", $id);
    $record = $db->Read("page", $id);
    return assertExpression($record === null, "Delete OK", "Delete failed");
}

// test 7: test Page render
function testPageRender()
{
    $template = __DIR__ . '/../templates/index.tpl';
    file_put_contents($template, "<h1>{{title}}</h1><p>{{content}}</p>");
    $page = new Page($template);
    $output = $page->Render(["title" => "Hello", "content" => "World"]);
    return assertExpression(strpos($output, "Hello") !== false && strpos($output, "World") !== false, "Render OK", "Render failed");
}

// Adaugăm testele
$tests->add('Database connection', 'testDbConnection');
$tests->add('Table count', 'testDbCount');
$tests->add('Create record', 'testDbCreate');
$tests->add('Read record', 'testDbRead');
$tests->add('Update record', 'testDbUpdate');
$tests->add('Delete record', 'testDbDelete');
$tests->add('Page render', 'testPageRender');

// Rulăm testele
$tests->run();

echo $tests->getResult();
