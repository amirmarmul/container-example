n<?php

use Example\Container;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    public function test_container_can_be_created()
    {
        $container = new Container;
        $this->assertInstanceOf('Example\Container', $container);
    }

    public function test_instance_can_be_found_and_resolved_from_the_container()
    {
        $instance = new Dummy;
        $container = new Container;
        $container->instance(Dummy::class, $instance);
        $this->assertSame($instance, $container->make(Dummy::class));
    }

    public function test_exception_is_thrown_when_instance_is_not_found()
    {
        $this->expectException(\Exception::class);

        $container = new Container;
        $container->make('FakeClass');
    }

    public function test_singleton_bindings_can_be_resolved()
    {
        $resolver = function() { return new Dummy; };
        $container = new Container;

        $container->bind(Dummy::class, $resolver);
        $this->assertInstanceOf('Dummy', $container->make(Dummy::class));

        $dummy = $container->make(Dummy::class);
        $this->assertSame($dummy, $container->make(Dummy::class));
    }

    public function test_resolve_class_instance_by_name_without_binding()
    {
        $container = new Container;
        $dummy = $container->make(Dummy::class);
        $this->assertInstanceOf('Dummy', $dummy);
    }

    public function test_we_can_resolve_dependencies_of_dependencies()
    {
        $container = new Container;
        $baz = $container->make(Baz::class);
        $this->assertInstanceOf('Baz', $baz);
    }
}

class Dummy {}
class Foo {}
class Bar { function __construct(Foo $foo) {} }
class Baz { function __construct(Bar $bar) {} }