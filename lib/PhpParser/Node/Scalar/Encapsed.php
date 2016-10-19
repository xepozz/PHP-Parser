<?php

namespace PhpParser\Node\Scalar;

use PhpParser\Node\Scalar;

/**
 * @property int    $kind     One of the String_::KIND_* class constants
 * @property string $docLabel Label of doc comment (only available if string defined as doc string)
 */
class Encapsed extends Scalar
{
    /** @var array Encaps list */
    public $parts;

    /**
     * Constructs an encapsed string node.
     *
     * @param array $parts      Encaps list
     * @param array $attributes Additional attributes
     */
    public function __construct(array $parts, array $attributes = array()) {
        parent::__construct($attributes);
        $this->parts = $parts;
    }

    public function getSubNodeNames() {
        return array('parts');
    }
}
