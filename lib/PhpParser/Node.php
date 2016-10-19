<?php

namespace PhpParser;

/**
 * AST node.
 *
 * The following attributes are available depending on the 'usedAttributes' option passed to the
 * lexer.
 *
 * @property Comment[] $comments      Comments preceding this node
 * @property int       $startLine     Line the node starts at (1-based)
 * @property int       $endLine       Line the node end at (1-based)
 * @property int       $startFilePos  File offset the node starts at (0-based)
 * @property int       $endFilePos    File offset the node ends at (0-based)
 * @property int       $startTokenPos Token offset the node starts at (0-based)
 * @property int       $endTokenPos   Token offset the node ends at (0-based)
 */
interface Node
{
    /**
     * Gets the type of the node.
     *
     * @return string Type of the node
     */
    public function getType();

    /**
     * Gets the names of the sub nodes.
     *
     * @return array Names of sub nodes
     */
    public function getSubNodeNames();

    /**
     * Gets line the node started in.
     *
     * @return int Line
     */
    public function getLine();

    /**
     * Sets line the node started in.
     *
     * @param int $line Line
     */
    public function setLine($line);

    /**
     * Gets the doc comment of the node.
     *
     * The doc comment has to be the last comment associated with the node.
     *
     * @return null|Comment\Doc Doc comment object or null
     */
    public function getDocComment();

    /**
     * Sets the doc comment of the node.
     *
     * This will either replace an existing doc comment or add it to the comments array.
     *
     * @param Comment\Doc $docComment Doc comment to set
     */
    public function setDocComment(Comment\Doc $docComment);

    /**
     * Sets an attribute on a node.
     *
     * @param string $key
     * @param mixed  $value
     */
    public function setAttribute($key, $value);

    /**
     * Returns whether an attribute exists.
     *
     * @param string $key
     *
     * @return bool
     */
    public function hasAttribute($key);

    /**
     * Returns the value of an attribute.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function &getAttribute($key, $default = null);

    /**
     * Returns all attributes for the given node.
     *
     * @return array
     */
    public function getAttributes();

    /**
     * Get the value of an attribute.
     *
     * @param string $key Name of the attribute
     *
     * @return mixed Value of the attribute
     */
    public function &__get($key);

    /**
     * Sets the value of an attribute.
     *
     * @param string $key   Name of the attribute
     * @param mixed  $value Value to set
     */
    public function __set($key, $value);

    /**
     * Check whether an attribute exists and is non-null.
     *
     * @param string $key Name of the attribute
     *
     * @return bool Whether the attribute exists and is non-null
     */
    public function __isset($key);

    /**
     * Removes an attribute.
     *
     * @param string $key Name of the attribute.
     */
    public function __unset($key);
}