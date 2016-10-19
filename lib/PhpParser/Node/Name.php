<?php

namespace PhpParser\Node;

use PhpParser\NodeAbstract;

class Name extends NodeAbstract
{
    /** @var string Name */
    public $name;

    /**
     * Constructs a name node.
     *
     * @param string|array $name       Name or parts of name
     * @param array        $attributes Additional attributes
     */
    public function __construct($name, array $attributes = array()) {
        parent::__construct($attributes);
        $this->name = self::prepareName($name);
    }

    public function getSubNodeNames() {
        return array('name');
    }

    /**
     * Gets the first part of the name, i.e. everything before the first namespace separator.
     *
     * @return string First part of the name
     */
    public function getFirst() {
        if (false !== $first = strpos($this->name, '\\')) {
            return substr($this->name, 0, $first);
        }
        return $this->name;
    }

    /**
     * Gets the last part of the name, i.e. everything after the last namespace separator.
     *
     * @return string Last part of the name
     */
    public function getLast() {
        if (false !== $last = strrpos($this->name, '\\')) {
            return substr($this->name, $last + 1);
        }
        return $this->name;
    }

    /**
     * Checks whether the name is unqualified. (E.g. Name)
     *
     * @return bool Whether the name is unqualified
     */
    public function isUnqualified() {
        return false === strpos($this->name, '\\');
    }

    /**
     * Checks whether the name is qualified. (E.g. Name\Name)
     *
     * @return bool Whether the name is qualified
     */
    public function isQualified() {
        return false !== strpos($this->name, '\\');
    }

    /**
     * Checks whether the name is fully qualified. (E.g. \Name)
     *
     * @return bool Whether the name is fully qualified
     */
    public function isFullyQualified() {
        return false;
    }

    /**
     * Checks whether the name is explicitly relative to the current namespace. (E.g. namespace\Name)
     *
     * @return bool Whether the name is relative
     */
    public function isRelative() {
        return false;
    }

    /**
     * Returns a string representation of the name by imploding the namespace parts with the
     * namespace separator.
     *
     * @return string String representation
     */
    public function toString() {
        return $this->name;
    }

    /**
     * Returns a string representation of the name by imploding the namespace parts with the
     * namespace separator.
     *
     * @return string String representation
     */
    public function __toString() {
        return $this->name;
    }

    /**
     * Gets a slice of a name (similar to array_slice).
     *
     * This method returns a new instance of the same type as the original and with the same
     * attributes.
     *
     * If the slice is empty, null is returned. The null value is handled correctly in conjunction
     * with concat().
     *
     * Offset and length have the same meaning as in array_slice().
     *
     * @param int      $offset Offset to start the slice at (may be negative)
     * @param int|null $length Length of the slice (may be negative)
     *
     * @return static|null Sliced name, or null for empty slices
     */
    public function slice($offset, $length = null) {
        if ($offset === 1 && $length === null) {
            // Short-circuit the common case
            if (false !== $first = strpos($this->name, '\\')) {
                return new static(substr($this->name, $first + 1));
            }
            return null;
        }

        $parts = explode('\\', $this->name);
        $numParts = count($parts);

        $realOffset = $offset < 0 ? $offset + $numParts : $offset;
        if ($realOffset < 0 || $realOffset > $numParts) {
            throw new \OutOfBoundsException(sprintf('Offset %d is out of bounds', $offset));
        }

        if (null === $length) {
            $realLength = $numParts - $realOffset;
        } else {
            $realLength = $length < 0 ? $length + $numParts - $realOffset : $length;
            if ($realLength < 0 || $realLength > $numParts) {
                throw new \OutOfBoundsException(sprintf('Length %d is out of bounds', $length));
            }
        }

        $slice = array_slice($parts, $realOffset, $realLength);
        if (empty($slice)) {
            return null;
        }
        return new static(implode('\\', $slice), $this->attributes);
    }

    /**
     * Concatenate two names, yielding a new Name instance.
     *
     * The type of the generated instance depends on which class this method is called on, for
     * example Name\FullyQualified::concat() will yield a Name\FullyQualified instance.
     *
     * Concatenation with null returns a new instance of the other name (or null if both are null).
     *
     * @param string|array|self|null $name1      The first name
     * @param string|array|self|null $name2      The second name
     * @param array                  $attributes Attributes to assign to concatenated name
     *
     * @return static Concatenated name
     */
    public static function concat($name1, $name2, array $attributes = []) {
        if (null === $name1 && null === $name2) {
            return null;
        } else if (null === $name1) {
            return new static(self::prepareName($name2), $attributes);
        } else if (null === $name2) {
            return new static(self::prepareName($name1), $attributes);
        }
        return new static(
            self::prepareName($name1) . '\\' . self::prepareName($name2), $attributes
        );
    }

    /**
     * Prepares a (string, array or Name node) name for use in name changing methods by converting
     * it into a string.
     *
     * @param string|array|self $name Name to prepare
     *
     * @return string Prepared name
     */
    private static function prepareName($name) {
        if (\is_string($name)) {
            return $name;
        } elseif ($name instanceof self) {
            return $name->name;
        } elseif (\is_array($name)) {
            if (empty($name)) {
                throw new \InvalidArgumentException('Name part array cannot be empty');
            }
            return implode('\\', $name);
        }

        throw new \InvalidArgumentException(
            'Expected string or Name node (or array -- deprecated)'
        );
    }
}
