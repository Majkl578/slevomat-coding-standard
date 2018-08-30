<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\TypeHints;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\AnnotationHelper;
use SlevomatCodingStandard\Helpers\DocCommentHelper;
use SlevomatCodingStandard\Helpers\NamespaceHelper;
use SlevomatCodingStandard\Helpers\PropertyHelper;
use SlevomatCodingStandard\Helpers\SniffSettingsHelper;
use SlevomatCodingStandard\Helpers\SuppressHelper;
use SlevomatCodingStandard\Helpers\TypeHintHelper;
use const T_VARIABLE;
use function array_map;
use function count;
use function preg_split;
use function sprintf;

class PropertyTypeSniff implements Sniff
{

	private const NAME = 'SlevomatCodingStandard.TypeHints.PropertyType';

	public const CODE_MISSING_TYPE_HINT = 'MissingTypeHint';

	public const CODE_MISSING_TRAVERSABLE_TYPE_HINT_SPECIFICATION = 'MissingTraversableTypeHintSpecification';

	/** @var string[] */
	public $traversableTypeHints = [];

	/**
	 * @return mixed[]
	 */
	public function register(): array
	{
		return [T_VARIABLE];
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @param int $pointer
	 */
	public function process(File $phpcsFile, $pointer): void
	{
		if (!PropertyHelper::isProperty($phpcsFile, $pointer)) {
			return;
		}

		if (SuppressHelper::isSniffSuppressed($phpcsFile, $pointer, self::NAME)) {
			return;
		}

		if (DocCommentHelper::hasInheritdocAnnotation($phpcsFile, $pointer)) {
			return;
		}

		$varAnnotations = AnnotationHelper::getAnnotationsByName($phpcsFile, $pointer, '@var');

		if (count($varAnnotations) === 0) {
			if (SuppressHelper::isSniffSuppressed($phpcsFile, $pointer, sprintf('%s.%s', self::NAME, self::CODE_MISSING_TYPE_HINT))) {
				return;
			}

			$phpcsFile->addError(
				sprintf(
					'Property %s does not have @var annotation.',
					PropertyHelper::getFullyQualifiedName($phpcsFile, $pointer)
				),
				$pointer,
				self::CODE_MISSING_TYPE_HINT
			);

			return;
		}

		if (SuppressHelper::isSniffSuppressed($phpcsFile, $pointer, sprintf('%s.%s', self::NAME, self::CODE_MISSING_TRAVERSABLE_TYPE_HINT_SPECIFICATION))) {
			return;
		}

		$propertyTypeHintDefinition = preg_split('~\\s+~', (string) $varAnnotations[0]->getContent())[0];

		if ((!TypeHintHelper::definitionContainsTraversableTypeHint($phpcsFile, $pointer, $propertyTypeHintDefinition, $this->getNormalizedTraversableTypeHints()) || TypeHintHelper::definitionContainsTraversableTypeHintSpeficication($propertyTypeHintDefinition))
			&& (
				!TypeHintHelper::definitionContainsTraversableTypeHintSpeficication($propertyTypeHintDefinition)
				|| TypeHintHelper::definitionContainsItemsSpecificationForTraversable($phpcsFile, $pointer, $propertyTypeHintDefinition, $this->getNormalizedTraversableTypeHints())
			)
		) {
			return;
		}

		$phpcsFile->addError(
			sprintf(
				'@var annotation of property %s does not specify type hint for its items.',
				PropertyHelper::getFullyQualifiedName($phpcsFile, $pointer)
			),
			$varAnnotations[0]->getStartPointer(),
			self::CODE_MISSING_TRAVERSABLE_TYPE_HINT_SPECIFICATION
		);
	}

	/**
	 * @return string[]
	 */
	private function getNormalizedTraversableTypeHints(): array
	{
		/** @var string[]|null $normalized */
		static $normalized = null;

		if ($normalized === null) {
			$normalized = array_map(
				function (string $typeHint): string {
					if (NamespaceHelper::isFullyQualifiedName($typeHint)) {
						return $typeHint;
					}

					return sprintf('%s%s', NamespaceHelper::NAMESPACE_SEPARATOR, $typeHint);
				},
				SniffSettingsHelper::normalizeArray($this->traversableTypeHints)
			);
		}

		return $normalized;
	}

}
