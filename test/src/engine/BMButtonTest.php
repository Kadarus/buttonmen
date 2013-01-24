<?php

require_once 'engine/BMButton.php';

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2012-12-07 at 14:08:15.
 */
class BMButtonTest extends PHPUnit_Framework_TestCase {

    /**
     * @var BMButton
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new BMButton;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {

    }

    /**
     * @covers BMButton::load_from_recipe
     * @covers BMButton::__get
     * @covers BMButton::__set
     */
    public function test_load_from_recipe() {
        // empty button recipe
        $recipe = '';
        $this->object->load_from_recipe($recipe);
        $this->assertEquals($recipe, $this->object->recipe);
        $this->assertTrue(is_array($this->object->dieArray));

        // button recipes using dice with no special skills
        $recipe = '(4) (8) (20) (20)';
        $this->object->load_from_recipe($recipe);
        $this->assertEquals($recipe, $this->object->recipe);
        $this->assertEquals(4, count($this->object->dieArray));
        $dieSides = array(4, 8, 20, 20);
        for ($dieIdx = 0; $dieIdx <= (count($dieSides) - 1); $dieIdx++) {
          $this->assertTrue($this->object->dieArray[$dieIdx] instanceof BMDie);
          $this->assertEquals($dieSides[$dieIdx],
                              $this->object->dieArray[$dieIdx]->max);
        }

        $recipe = '(6) (10) (12)';
        $this->object->recipe = $recipe;
        $this->assertEquals(3, count($this->object->dieArray));
        $this->assertEquals($recipe, $this->object->recipe);
        $dieSides = array(6, 10, 12);
        for ($dieIdx = 0; $dieIdx <= (count($dieSides) - 1); $dieIdx++) {
          $this->assertTrue($this->object->dieArray[$dieIdx] instanceof BMDie);
          $this->assertEquals($dieSides[$dieIdx],
                              $this->object->dieArray[$dieIdx]->max);
        }

        // button recipe with dice with skills
        $recipe = 'p(4) s(10) ps(30) (8)';
        $this->object->load_from_recipe($recipe);
        $this->assertEquals(4, count($this->object->dieArray));
        $this->assertEquals($recipe, $this->object->recipe);
        $dieSides = array(4, 10, 30, 8);
        $dieSkills = array('p', 's', 'ps', '');
        for ($dieIdx = 0; $dieIdx <= (count($dieSides) - 1); $dieIdx++) {
          $this->assertTrue($this->object->dieArray[$dieIdx] instanceof BMDie);
          $this->assertEquals($dieSides[$dieIdx],
                              $this->object->dieArray[$dieIdx]->max);
//          $this->assertEquals($dieSkills[$dieIdx],
//                              $this->object->dieArray[$dieIdx]->mSkills);
        }

        // invalid button recipe with no die sides for one die
        try {
            $this->object->load_from_recipe('p(4) s(10) ps (8)');
            $this->fail('The number of sides must be specified for each die.');
        }
        catch (InvalidArgumentException $expected) {
        }

        // auxiliary dice
        $recipe = '(4) (10) (30) +(20)';
        $this->object->load_from_recipe($recipe);
        $this->assertEquals(4, count($this->object->dieArray));
        $this->assertEquals($recipe, $this->object->recipe);
        $dieSides = array(4, 10, 30, 20);
        $dieSkills = array('', '', '', '+');
        for ($dieIdx = 0; $dieIdx <= (count($dieSides) - 1); $dieIdx++) {
          $this->assertTrue($this->object->dieArray[$dieIdx] instanceof BMDie);
          $this->assertEquals($dieSides[$dieIdx],
                              $this->object->dieArray[$dieIdx]->max);
//          $this->assertEquals($dieSkills[$dieIdx],
//                              $this->object->dieArray[$dieIdx]->mSkills);
        }

        // twin dice, option dice

    }

    /**
     * @covers BMButton::reload
     */
    public function test_reload() {
        // button recipes using dice with no special skills
        $recipe = '(4) (8) (20) (20)';
        $this->object->load_from_recipe($recipe);
        $this->assertEquals(4, count($this->object->dieArray));
        // empty the array manually
        $this->object->dieArray = array();
        // force reload
        $this->object->reload();
        $this->assertEquals(4, count($this->object->dieArray));

        $dieSides = array(4, 8, 20, 20);
        for ($dieIdx = 0; $dieIdx <= (count($dieSides) - 1); $dieIdx++) {
          $this->assertTrue($this->object->dieArray[$dieIdx] instanceof BMDie);
          $this->assertEquals($dieSides[$dieIdx],
                              $this->object->dieArray[$dieIdx]->max);
        }

    }

    /**
     * @covers BMButton::load_from_name
     * @covers BMButton::__set
     */
    public function test_load_from_name() {
        $this->object->load_from_name('Bauer');
        $this->assertEquals('Bauer', $this->object->name);
        $this->assertEquals('(8) (10) (12) (20) (X)', $this->object->recipe);

        $this->object->name = 'Bauer';
        $this->assertEquals('Bauer', $this->object->name);
        $this->assertEquals('(8) (10) (12) (20) (X)', $this->object->recipe);

        $this->object->load_from_name('Stark');
        $this->assertEquals('Stark', $this->object->name);
        $this->assertEquals('(4) (6) (8) (X) (X)', $this->object->recipe);

        $this->object->name = 'Stark';
        $this->assertEquals('Stark', $this->object->name);
        $this->assertEquals('(4) (6) (8) (X) (X)', $this->object->recipe);

        $this->object->load_from_name('unknownTestName');
        $this->assertEquals('Default', $this->object->name);
        $this->assertEquals('(4) (8) (12) (20) (X)', $this->object->recipe);

        $this->object->name = 'unknownTestName';
        $this->assertEquals('Default', $this->object->name);
        $this->assertEquals('(4) (8) (12) (20) (X)', $this->object->recipe);
    }

    /**
     * @covers BMButton::load_values
     */
    public function test_load_values() {
        $this->object->load_from_recipe('(4) (8) (12) (20)');
        $dieValues = array(1, 2, 4, 9);
        $this->object->load_values($dieValues);
        for ($dieIdx = 0; $dieIdx < count($dieValues); $dieIdx++) {
            $this->assertEquals($dieValues[$dieIdx],
                                $this->object->dieArray[$dieIdx]->value);
        }

        // test for same number of values as dice
        $this->object->load_from_recipe('(4) (8) (12) (20)');
        try {
            $this->object->load_values(array(1, 2, 3));
            $this->fail('The number of values must match the number of dice.');
        }
        catch (InvalidArgumentException $expected) {
        }

        // test that value is within limits
        $this->object->load_from_recipe('(4) (8) (12) (20)');
        try {
            $this->object->load_values(array(5, 12, 20, 30));
            $this->fail('Invalid values.');
        }
        catch (InvalidArgumentException $expected) {
        }

        // test that a value cannot be set when the sides are not yet determined
        $this->object->load_from_recipe('(4) (8) (12) (X)');
        try {
            $this->object->load_values(array(1, 1, 1, 1));
            $this->fail('Cannot set value when sides are not yet determined.');
        }
        catch (InvalidArgumentException $expected) {
        }
    }

    /**
     * @covers BMButton::validate_recipe
     */
    public function test_validate_recipe() {
        $method = new ReflectionMethod('BMButton', 'validate_recipe');
        $method->setAccessible(TRUE);

        // empty button recipe
        $method->invoke(new BMButton, '');

        // single die recipe
        $method->invoke(new BMButton, '(99)');

        // valid button recipe
        $method->invoke(new BMButton, 'p(4) s(10) ps(30) (8)');

        // invalid button recipe with no die sides for one die
        try {
            $method->invoke(new BMButton, 'p(4) s(10) ps (8)');
            //$this->fail('The number of sides must be specified for each die.');
        }
        catch (InvalidArgumentException $expected) {
        }

        // twin dice, option dice

        // swing dice
    }

    /**
     * @covers BMButton::parse_recipe_for_sides
     */
    public function test_parse_recipe_for_sides() {
        $method = new ReflectionMethod('BMButton', 'parse_recipe_for_sides');
        $method->setAccessible(TRUE);

        $sides = $method->invoke(new BMButton, '(4) (8) (20) (20)');
        $this->assertEquals(array(4, 8, 20, 20), $sides);

        $sides = $method->invoke(new BMButton, 'p(4) s(10) ps(30) (8)');
        $this->assertEquals(array(4, 10, 30, 8), $sides);

        $sides = $method->invoke(new BMButton, '(8) (10) (12) (20) (X)');
        $this->assertEquals(array(8, 10, 12, 20, 'X'), $sides);
    }

    /**
     * @covers BMButton::parse_recipe_for_skills
     */
    public function test_parse_recipe_for_skills() {
        $method = new ReflectionMethod('BMButton', 'parse_recipe_for_skills');
        $method->setAccessible(TRUE);

        $skills = $method->invoke(new BMButton, '(4) (8) (20) (20)');
        $this->assertEquals(4, count($skills));
        $this->assertEquals(array('', '', '', ''), $skills);

        $skills = $method->invoke(new BMButton, 'p(4) s(10) ps(30) (8)');
        $this->assertEquals(4, count($skills));
        $this->assertEquals(array('p', 's', 'ps', ''), $skills);

        $skills = $method->invoke(new BMButton, '(8) (10) (12) (20) (X)');
        $this->assertEquals(5, count($skills));
        $this->assertEquals(array('', '', '', '', ''), $skills);
    }

    /**
     * @covers BMButton::__get
     */
    public function test__get() {
        $this->assertEquals(NULL, $this->object->fubar);
    }

    /**
     * @covers BMButton::__set
     */
    public function test__set() {
        $testString = 'testString xxx';
        $this->object->fubar = $testString;
        $this->assertEquals($testString, $this->object->fubar);
    }

    /**
     * @covers BMButton::__isset
     */
    public function test__isset() {
        $this->assertFalse(isset($this->object->name));

        $this->object->name = 'TestName';
        $this->assertTrue(isset($this->object->name));
    }

    /**
     * @covers BMButton::__unset
     */
    public function test__unset() {
        // check that a nonexistent property can be unset gracefully
        unset($this->object->rubbishVariable);

        $this->object->name = 'TestName';
        unset($this->object->name);
        $this->assertFalse(isset($this->object->name));
    }
}
