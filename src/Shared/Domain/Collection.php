<?php

declare(strict_types=1);

namespace App\Shared\Domain;

use App\Shared\Domain\Aggregate\AggregateRoot;
use App\Shared\Domain\Aggregate\AggregateSubModel;
use App\Shared\Domain\ValueObject\ValueObject;
use ArrayIterator;
use Closure;
use Countable;
use IteratorAggregate;

use function Lambdish\Phunctional\each;
use function Lambdish\Phunctional\map;

abstract class Collection implements Countable, IteratorAggregate
{
    public function __construct(protected array $items)
    {
        Assert::arrayOf($this->type(), $items);
    }

    abstract protected function type(): string;

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->items());
    }

    public function each(callable $fn): void
    {
        each($fn, $this->items);
    }

    public function count(): int
    {
        return count($this->items());
    }

    protected function items(): array
    {
        return $this->items;
    }

    public function toArrayPrimitive(): array
    {
        return map(
            fn ($item) => match (true) {
                $item instanceof AggregateSubModel,
                $item instanceof AggregateRoot => $item->toPrimitives(),
                $item instanceof ValueObject => $item->value(),
                $item instanceof Collection => $item->toArrayPrimitive(),
                default => $item,
            },
            $this->items
        );
    }

    public function toArray(): array
    {
        return $this->items;
    }

    public function first()
    {
        return $this->items[0];
    }

    public function all()
    {
        return $this->items();
    }

    public function push(mixed $item)
    {
        $this->items[] = $item;
    }

    public function add(mixed $element)
    {
        $this->items[] = $element;
    }

    public function clear()
    {
        $this->items = [];
    }

    public function remove(int|string $key)
    {
        unset($this->items[$key]);
    }

    public function removeElement(mixed $element)
    {
        $key = array_search($element, $this->items, true);

        if (false === $key) {
            return false;
        }

        unset($this->items[$key]);

        return true;
    }

    public function set(int|string $key, mixed $value)
    {
        $this->items[$key] = $value;
    }

    public function offsetExists(mixed $offset): bool
    {
        return $this->containsKey($offset);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->get($offset);
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->set($offset, $value);
    }

    public function offsetUnset(mixed $offset): void
    {
        $this->remove($offset);
    }

    public function contains(mixed $element)
    {
        return in_array($element, $this->items, true);
    }

    public function isEmpty()
    {
        return empty($this->items);
    }

    public function containsKey(int|string $key)
    {
        return array_key_exists($key, $this->items);
    }

    public function get(int|string $key)
    {
        return $this->items[$key];
    }

    public function getKeys()
    {
        return array_keys($this->items);
    }

    public function getValues()
    {
        return array_values($this->items);
    }

    public function last()
    {
        return end($this->items);
    }

    public function key()
    {
        return key($this->items);
    }

    public function current()
    {
        return current($this->items);
    }

    public function next()
    {
        return next($this->items);
    }

    public function slice(int $offset, ?int $length = null)
    {
        return array_slice($this->items, $offset, $length);
    }

    public function exists(Closure $p)
    {
        foreach ($this->items as $key => $element) {
            if ($p($key, $element)) {
                return true;
            }
        }

        return false;
    }

    public function filter(Closure $p)
    {
        return $this->createFrom(array_filter($this->items, $p, ARRAY_FILTER_USE_BOTH));
    }

    public function map(Closure $func)
    {
        return $this->createFrom(array_map($func, $this->items));
    }

    public function partition(Closure $p)
    {
        $matches = $noMatches = [];

        foreach ($this->items as $key => $element) {
            if ($p($key, $element)) {
                $matches[$key] = $element;
            } else {
                $noMatches[$key] = $element;
            }
        }

        return [$this->createFrom($matches), $this->createFrom($noMatches)];
    }

    public function forAll(Closure $p)
    {
        foreach ($this->items as $key => $element) {
            if (!$p($key, $element)) {
                return false;
            }
        }

        return true;
    }

    public function indexOf(mixed $element)
    {
        return array_search($element, $this->items, true);
    }

    public function findFirst(Closure $p)
    {
        foreach ($this->items as $key => $element) {
            if ($p($key, $element)) {
                return $element;
            }
        }

        return null;
    }

    public function reduce(Closure $func, mixed $initial = null)
    {
        return array_reduce($this->items, $func, $initial);
    }

    public function arrayMap(Closure $func)
    {
        return map($func, $this->items);
    }

    public static function from(array $items)
    {
        return new static($items);
    }

    protected function createFrom(array $array_filter)
    {
        return new static($array_filter);
    }
}
