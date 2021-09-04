<?php

use PHPUnit\Framework\TestCase;
use Framework\DBIO;

class DBIOTest extends TestCase
{
    public function testConfigure()
    {
        $config = [
            "connection" => [
                "default" => [
                    "host" => "localhost",
                    "port" => "3306",
                    "dbname" => "world",
                    "charset" => "utf8mb4",
                    "user" => "root",
                    "password" => "",
                ]
            ]
        ];
        DBIO::configure($config);
        $this->assertSame($config, DBIO::getConfigure());
    }

    /**
     * @return DBIO
     */
    public function getInstance(): DBIO
    {
        return new DBIO();
    }

    /**
     * @return void
     */
    public function testGetColumns(): void
    {
        $db = $this->getInstance();
        $this->assertSame([
            0 => 'ID',
            1 => 'Name',
            2 => 'CountryCode',
            3 => 'District',
            4 => 'Population',
        ], $db->getColumns("city"));
    }

    /**
     * @return void
     */
    public function testGetAll(): void
    {
        $db = $this->getInstance();
        $this->assertSame([
            [
                'ID' => '1',
                0 => '1',
                'Name' => 'Kabul',
                1 => 'Kabul',
                'CountryCode' => 'AFG',
                2 => 'AFG',
                'District' => 'Kabol',
                3 => 'Kabol',
                'Population' => '1780000',
                4 => '1780000',
            ]
        ], $db->getAll("select * from city where ID=?", [1]));
    }

    /**
     * @return void
     */
    public function testGetAllAssoc(): void
    {
        $db = $this->getInstance();
        $this->assertSame([
            [
                'ID' => '1',
                'Name' => 'Kabul',
                'CountryCode' => 'AFG',
                'District' => 'Kabol',
                'Population' => '1780000',
            ]
        ], $db->getAllAssoc("select * from city where ID=?", [1]));
    }

    /**
     * @return void
     */
    public function testIsConnected(): void
    {
        $db = $this->getInstance();
        $this->assertTrue($db->isConnected());
        $this->assertFalse($db->isConnected(true));
        $db->query("select * from city");
        $this->assertTrue($db->isConnected(true));
    }

    /**
     * @return void
     */
    public function testFetch(): void
    {
        $db = $this->getInstance();
        $db->query("select * from city");
        $this->assertNotFalse($db->fetch());
    }

    /**
     * @return void
     */
    public function testFetchAssoc(): void
    {
        $db = $this->getInstance();
        $db->query("select * from city");
        $this->assertNotFalse($db->fetchAssoc());
    }

    /**
     * @return void
     */
    public function testClose(): void
    {
        $db = $this->getInstance();
        $db->query("select * from city");
        $this->assertNotNull($db->get("dbh"));
        $this->assertNotNull($db->get("stmt"));
        $db->close();
        $this->assertNull($db->get("dbh"));
        $this->assertNull($db->get("stmt"));
    }
}