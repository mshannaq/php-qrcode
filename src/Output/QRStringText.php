<?php
/**
 * Class QRStringText
 *
 * @created      25.10.2023
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2023 smiley
 * @license      MIT
 */

namespace chillerlan\QRCode\Output;

use function implode;
use function is_string;
use function max;
use function min;
use function sprintf;

/**
 *
 */
class QRStringText extends QROutputAbstract{

	public const MIME_TYPE = 'text/plain';

	/**
	 * @inheritDoc
	 */
	public static function moduleValueIsValid($value):bool{
		return is_string($value);
	}

	/**
	 * @inheritDoc
	 */
	protected function prepareModuleValue($value):string{
		return $value;
	}

	/**
	 * @inheritDoc
	 */
	protected function getDefaultModuleValue(bool $isDark):string{
		return ($isDark) ? '██' : '░░';
	}

	/**
	 * @inheritDoc
	 */
	public function dump(string $file = null):string{
		$lines     = [];
		$linestart = $this->options->textLineStart;

		for($y = 0; $y < $this->moduleCount; $y++){
			$r = [];

			for($x = 0; $x < $this->moduleCount; $x++){
				$r[] = $this->getModuleValueAt($x, $y);
			}

			$lines[] = $linestart.implode('', $r);
		}

		$data = implode($this->eol, $lines);

		$this->saveToFile($data, $file);

		return $data;
	}

	/**
	 * a little helper to create a proper ANSI 8-bit color escape sequence
	 *
	 * @see https://en.wikipedia.org/wiki/ANSI_escape_code#8-bit
	 * @see https://en.wikipedia.org/wiki/Block_Elements
	 *
	 * @codeCoverageIgnore
	 */
	public static function ansi8(string $str, int $color, bool $background = null):string{
		$color      = max(0, min($color, 255));
		$background = ($background === true) ? 48 : 38;

		return sprintf("\x1b[%s;5;%sm%s\x1b[0m", $background, $color, $str);
	}

}