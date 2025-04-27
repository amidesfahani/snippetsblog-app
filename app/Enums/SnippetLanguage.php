<?php

namespace App\Enums;

/** php 7 does not supp enum class so make a regular class */
class SnippetLanguage
{
	const PHP = 'php';
	const JAVASCRIPT = 'javascript';
	const PYTHON = 'python';
	const JAVA = 'java';
	const CSHARP = 'csharp';
	const RUBY = 'ruby';
	const GO = 'go';

	public static function all(): array
	{
		return [
			self::PHP,
			self::JAVASCRIPT,
			self::PYTHON,
			self::JAVA,
			self::CSHARP,
			self::RUBY,
			self::GO,
		];
	}

	public static function labels(): array
	{
		return [
			self::PHP => 'PHP',
			self::JAVASCRIPT => 'JavaScript',
			self::PYTHON => 'Python',
			self::JAVA => 'Java',
			self::CSHARP => 'C#',
			self::RUBY => 'Ruby',
			self::GO => 'Go',
		];
	}

	public static function colors(): array
	{
		return [
			self::PHP => 'purple',
			self::JAVASCRIPT => 'yellow',
			self::PYTHON => 'blue',
			self::JAVA => 'red',
			self::CSHARP => 'green',
			self::RUBY => 'pink',
			self::GO => 'cyan',
		];
	}

	public static function codeMirrorModes(): array
	{
		return [
			self::PHP => 'php',
			self::JAVASCRIPT => 'javascript',
			self::PYTHON => 'python',
			self::JAVA => 'clike',
			self::CSHARP => 'clike',
			self::RUBY => 'ruby',
			self::GO => 'go',
		];
	}

	public static function getLabel(string $value): string
	{
		return self::labels()[$value] ?? ucfirst($value);
	}

	public static function getColor(string $value): string
	{
		return self::colors()[$value] ?? 'gray';
	}

	public static function getCodeMirrorMode(string $value): string
	{
		return self::codeMirrorModes()[$value] ?? 'text/plain';
	}

	public static function isValid(string $value): bool
	{
		return in_array(strtolower($value), self::all());
	}
}
