<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Naming;

use SlevomatCodingStandard\Helpers\Annotation;
use SlevomatCodingStandard\Helpers\FunctionHelper;
use SlevomatCodingStandard\Helpers\ReturnTypeHint;

class FindMethodReturnsNullSniff implements \PHP_CodeSniffer\Sniffs\Sniff
{

	public const NULL_REQUIRED = 'NullRequired';

	/**
	 * @return mixed[]
	 */
	public function register(): array
	{
		return [T_FUNCTION];
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @param int $pointer
	 */
	public function process(\PHP_CodeSniffer\Files\File $phpcsFile, $pointer): void
	{
		if (!FunctionHelper::isMethod($phpcsFile, $pointer)) {
			return;
		}

		if (preg_match('~^find(?![a-z]).~', FunctionHelper::getName($phpcsFile, $pointer)) !== 1) {
			return;
		}

		$returnTypeHint = FunctionHelper::findReturnTypeHint($phpcsFile, $pointer);
		if ($returnTypeHint !== null) {
			$this->processTypeHint($returnTypeHint, $phpcsFile, $pointer);
			return;
		}

		$returnAnnotation = FunctionHelper::findReturnAnnotation($phpcsFile, $pointer);
		if ($returnAnnotation === null) {
			return;
		}

		$this->processAnnotation($returnAnnotation, $phpcsFile, $pointer);
	}

	private function processTypeHint(ReturnTypeHint $returnTypeHint, \PHP_CodeSniffer\Files\File $phpcsFile, int $pointer): void
	{
		if ($returnTypeHint->isNullable()) {
			return;
		}

		$this->report($phpcsFile, $pointer, self::NULL_REQUIRED, 'null not returned');
	}

	private function processAnnotation(Annotation $returnAnnotation, \PHP_CodeSniffer\Files\File $phpcsFile, int $pointer): void
	{
		if ($returnAnnotation->getContent() === null) {
			return;
		}

		$typeDeclaration = preg_split('~\s+~', $returnAnnotation->getContent())[0];

		$types = array_map('strtolower', explode('|', $typeDeclaration));

		if (in_array('null', $types, true)) {
			return;
		}

		$this->report($phpcsFile, $pointer, self::NULL_REQUIRED, 'null not returned');
	}

	private function report(\PHP_CodeSniffer\Files\File $phpcsFile, int $pointer, string $code, ?string $explanation): void
	{
		$phpcsFile->addError(
			sprintf(
				'Method %s must return nullable value%s%s.',
				FunctionHelper::getFullyQualifiedName($phpcsFile, $pointer),
				$explanation !== null ? ', ' : '',
				$explanation ?? ''
			),
			$pointer,
			$code
		);
	}

}
