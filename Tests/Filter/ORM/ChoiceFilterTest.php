<?php

/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\AdminBundle\Tests\Filter\ORM;

use Sonata\AdminBundle\Filter\ORM\ChoiceFilter;
use Sonata\AdminBundle\Form\Type\Filter\ChoiceType;

class ChoiceFilterTest extends \PHPUnit_Framework_TestCase
{
    public function testFilterEmpty()
    {
        $filter = new ChoiceFilter;
        $filter->initialize('field_name', array('field_options' => array('class' => 'FooBar')));

        $builder = new QueryBuilder;

        $filter->filter($builder, 'alias', 'field', null);
        $filter->filter($builder, 'alias', 'field', 'all');
        $filter->filter($builder, 'alias', 'field', array());

        $this->assertEquals(array(), $builder->query);
    }

    public function testFilterArray()
    {
        $filter = new ChoiceFilter;
        $filter->initialize('field_name', array('field_options' => array('class' => 'FooBar')));

        $builder = new QueryBuilder;

        $filter->filter($builder, 'alias', 'field', array('type' => ChoiceType::TYPE_CONTAINS, 'value' => array('1', '2')));

        $this->assertEquals(array('in_alias.field', 'alias.field IN ("1,2")'), $builder->query);
    }

    public function testFilterScalar()
    {
        $filter = new ChoiceFilter;
        $filter->initialize('field_name', array('field_options' => array('class' => 'FooBar')));

        $builder = new QueryBuilder;

        $filter->filter($builder, 'alias', 'field', array('type' => ChoiceType::TYPE_CONTAINS, 'value' => '1'));

        $this->assertEquals(array('alias.field = :field_name'), $builder->query);
        $this->assertEquals(array('field_name' => '1'), $builder->parameters);

    }
}