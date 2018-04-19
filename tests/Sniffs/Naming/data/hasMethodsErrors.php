<?php

abstract class Test
{

	public function hasA()
	{
	}

	protected function hasB(): ?bool
	{
	}

	private function hasC(): int
	{
	}

	/**
	 * @return
	 */
	abstract public function hasD();

	/**
	 * @return int
	 */
	public static function hasE()
	{
	}

	/**
	 * @return bool|null
	 */
	protected static function hasF()
	{
	}

	/**
	 * @return int|bool|null
	 */
	private static function hasG()
	{
	}

}
