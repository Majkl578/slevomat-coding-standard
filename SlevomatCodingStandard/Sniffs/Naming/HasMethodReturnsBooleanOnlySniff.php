<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Naming;

use function preg_match;
use SlevomatCodingStandard\Helpers\Annotation;
use SlevomatCodingStandard\Helpers\FunctionHelper;
use SlevomatCodingStandard\Helpers\ReturnTypeHint;

class HasMethodReturnsBooleanOnlySniff implements \PHP_CodeSniffer\Sniffs\Sniff
{

	public const CODE_TYPE_NOT_SPECIFIED = 'TypeNotSpecified';
	public const CODE_NON_BOOLEAN_ALLOWED = 'NonBooleanNotAllowed';

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

		if (preg_match('~^((?i)has)(?![a-z]).~', FunctionHelper::getName($phpcsFile, $pointer)) !== 1) {
			return;
		}

		$returnTypeHint = FunctionHelper::findReturnTypeHint($phpcsFile, $pointer);
		if ($returnTypeHint !== null) {
			$this->processTypeHint($returnTypeHint, $phpcsFile, $pointer);
			return;
		}

		$returnAnnotation = FunctionHelper::findReturnAnnotation($phpcsFile, $pointer);
		if ($returnAnnotation !== null) {
			$this->processAnnotation($returnAnnotation, $phpcsFile, $pointer);
			return;
		}

		$phpcsFile->addError(
			sprintf('Method %s must return boolean value, but has no type specified.', FunctionHelper::getFullyQualifiedName($phpcsFile, $pointer)),
			$pointer,
			self::CODE_TYPE_NOT_SPECIFIED
		);
	}

	private function processTypeHint(ReturnTypeHint $returnTypeHint, \PHP_CodeSniffer\Files\File $phpcsFile, int $pointer): void
	{
		if ($returnTypeHint->isNullable()) {
			$this->report($phpcsFile, $pointer, self::CODE_NON_BOOLEAN_ALLOWED, 'null returned');
			return;
		}

		if (strtolower($returnTypeHint->getTypeHint()) === 'bool') {
			return;
		}

		$this->report($phpcsFile, $pointer, self::CODE_NON_BOOLEAN_ALLOWED, 'non-bool returned');
	}

	private function processAnnotation(Annotation $returnAnnotation, \PHP_CodeSniffer\Files\File $phpcsFile, int $pointer): void
	{
		if ($returnAnnotation->getContent() === null) {
			$this->report($phpcsFile, $pointer, self::CODE_TYPE_NOT_SPECIFIED, 'nothing specified');
			return;
		}

		$typeDeclaration = preg_split('~\s+~', $returnAnnotation->getContent())[0];

		$types = array_map('strtolower', explode('|', $typeDeclaration));

		if (in_array('null', $types, true)) {
			$this->report($phpcsFile, $pointer, self::CODE_NON_BOOLEAN_ALLOWED, 'null returned');
			return;
		}

		if (count(array_diff($types, ['bool', 'boolean', 'true', 'false'])) === 0) {
			return;
		}

		$this->report($phpcsFile, $pointer, self::CODE_NON_BOOLEAN_ALLOWED, 'non-bool returned');
	}

	private function report(\PHP_CodeSniffer\Files\File $phpcsFile, int $pointer, string $code, ?string $explanation): void
	{
		$phpcsFile->addError(
			sprintf(
				'Method %s must return non-nullable boolean value%s%s.',
				FunctionHelper::getFullyQualifiedName($phpcsFile, $pointer),
				$explanation !== null ? ', ' : '',
				$explanation ?? ''
			),
			$pointer,
			$code
		);
	}

}
