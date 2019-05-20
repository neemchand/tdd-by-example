<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Components\QueryBuilderComponent;

class ReviewTest extends TestCase
{
    protected $sql;
    public function setUp(): void
    {
       $this->sql = new QueryBuilderComponent;
    }

    public function testSelectAll()
    {
        $this->assertEquals('select * from reviews', $this->sql->select('reviews'));
    }

    public function testColumns()
    {
        $this->assertEquals('select id, review_subject from reviews', $this->sql->select('reviews', ['id', 'review_subject']));
    }

    public function testColumnOrder()
    {
        $this->assertEquals('select id, review_subject from reviews order by id desc', $this->sql->select('reviews', ['id', 'review_subject'], ['id', 'desc']));
    }

    public function testColumnSorting()
    {
        $this->assertEquals('select * from reviews order by review_subject asc, category asc', $this->sql->select('reviews', [['review_subject', 'asc'],['category','asc']]));
    }

    public function testCasing()
    {
        $this->assertEquals('SELECT id, review_subject FROM reviews ORDER BY id DESC', $this->sql->select('reviews', ['id', 'review_subject'], ['id', 'DESC']));
    }

    public function testLimit()
    {
        $this->assertEquals('select * from reviews limit 10', $this->sql->select('reviews', 10));
    }

    public function testOffset()
    {
        $this->assertEquals('select * from reviews limit 6 offset 5', $this->sql->select('reviews', [6, 5]));
    }

    public function testCount()
    {
        $this->assertEquals('select *, count("id") from reviews', $this->sql->select('reviews', ['count','id']));
    }

    public function testMax()
    {
        $this->assertEquals('select max(\'comment_count\') from reviews', $this->sql->select('reviews', ['max','comment_count']));
    }

    public function testGroupBy()
    {
        $this->assertEquals('select max(\'comment_count\') from reviews group by comment_count', $this->sql->select('reviews', ['group by','comment_count']));
    }

    public function testDistinct()
    {
        $this->assertEquals('select distinct review_subject from reviews', $this->sql->select('reviews', ['distinct','review_subject']));
    }

    public function testJoin()
    {
        $this->assertEquals('select * from reviews join users on reviews.user_id=users.id', $this->sql->selectJoin('reviews', 'users', ['user_id', 'id']));
    }

    public function testInsert()
    {
        $this->assertEquals('INSERT INTO reviews(id, name, cost, color) VALUES(1, "apple", 100, "red")', $this->sql->insert('reviews', ["id","name","cost","color"], [[1, "apple", 100, "red"]]));
    }

    public function testInsertMultiple()
    {
        $this->assertEquals('INSERT INTO reviews(id, review_subject, comment_count, color) VALUES(1, "apple", 100, "red"), (2, "orange", 50, "orange")', $this->sql->insert('reviews', ["id","review_subject","comment_count","color"], [[1, "apple", 100, "red"],[2, "orange", 50, "orange"]] ));
    }

    public function testInsertWithDefaut()
    {
        $this->assertEquals('INSERT INTO reviews(id, review_subject, comment_count, color) VALUES(1, "apple", 100, "DEFAULT")', $this->sql->insert('reviews', ["id","review_subject","comment_count","color"], [[1, "apple", 100, 'DEFAULT']]));
    }

    public function testUpdate()
    {
        $this->assertEquals('UPDATE reviews SET comment_count="200" WHERE review_subject = "apple"', $this->sql->update('reviews', ["comment_count",200], ["review_subject", "apple"]));
    }

    public function testUpdateWhere()
    {
        $this->assertEquals('UPDATE reviews SET color="black" WHERE color = "red"', $this->sql->update('reviews', ["color", "black"], ["color", "red"]));
    }

    public function testUpdateDefault()
    {
        $this->assertEquals('UPDATE reviews SET comment_count="DEFAULT" WHERE comment_count = "100"', $this->sql->update('reviews', ["comment_count", "DEFAULT"], ["comment_count", 100]));
    }

    public function testUpdateAll()
    {
        $this->assertEquals('UPDATE reviews SET comment_count="DEFAULT" WHERE comment_count = "100"', $this->sql->update('reviews', ["comment_count", "DEFAULT"], ["comment_count", 100]));
    }

    public function testDelete()
    {
        $this->assertEquals('DELETE FROM reviews WHERE review_subject="abc"', $this->sql->delete('reviews', ["review_subject", "abc"]));
    }

    public function testDeleteCalc()
    {
        $this->assertEquals('DELETE FROM reviews WHERE comment_count>500', $this->sql->delete('reviews', ["comment_count", ">", 100]));
    }

    public function testDeleteAll()
    {
        $this->assertEquals('DELETE FROM reviews', $this->sql->delete('reviews'));
    }
}