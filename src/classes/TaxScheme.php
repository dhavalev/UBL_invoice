<?php

namespace Levaral\UBL\Invoice;


use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;

class TaxScheme implements XmlSerializable {
	private $id;
    private $type = Schema::CAC;

    /**
     * @return mixed
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @param mixed $type
     * @return int
     */
    public function setType($type) {
        $this->type = $type;
        return $this;
    }

    /**
	 * @return mixed
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param mixed $id
	 * @return int
	 */
	public function setId($id) {
		$this->id = $id;
		return $this;
	}

	function xmlSerialize(Writer $writer) {
		$writer->write([
			$this->type.'ID' => $this->id
		]);
	}
}