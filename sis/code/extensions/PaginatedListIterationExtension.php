<?php

class PaginatedListIterationExtension extends Extension {

	public function currentIteration($position = null) {
		if($position) {
			return ($this->owner->FirstItem() + $position - 1);
		}
	}

}
