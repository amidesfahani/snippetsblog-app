<?php

namespace App\Enums;

class SnippetLanguage
{
    const PHP = 'php';
    const JAVASCRIPT = 'javascript';
    const PYTHON = 'python';
    const JAVA = 'java';
    const CSHARP = 'csharp';
    const RUBY = 'ruby';
    const GO = 'go';
    const TYPESCRIPT = 'typescript';
    const SWIFT = 'swift';
    const KOTLIN = 'kotlin';
    const RUST = 'rust';
    const DART = 'dart';
    const SCALA = 'scala';
    const PERL = 'perl';
    const HASKELL = 'haskell';
    const LUA = 'lua';
    const R = 'r';
    const SHELL = 'shell';
    const SQL = 'sql';
    const HTML = 'html';
    const CSS = 'css';
    const C = 'c';
    const CPP = 'cpp';

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
            self::TYPESCRIPT,
            self::SWIFT,
            self::KOTLIN,
            self::RUST,
            self::DART,
            self::SCALA,
            self::PERL,
            self::HASKELL,
            self::LUA,
            self::R,
            self::SHELL,
            self::SQL,
            self::HTML,
            self::CSS,
            self::C,
            self::CPP,
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
            self::TYPESCRIPT => 'TypeScript',
            self::SWIFT => 'Swift',
            self::KOTLIN => 'Kotlin',
            self::RUST => 'Rust',
            self::DART => 'Dart',
            self::SCALA => 'Scala',
            self::PERL => 'Perl',
            self::HASKELL => 'Haskell',
            self::LUA => 'Lua',
            self::R => 'R',
            self::SHELL => 'Shell',
            self::SQL => 'SQL',
            self::HTML => 'HTML',
            self::CSS => 'CSS',
            self::C => 'C',
            self::CPP => 'C++',
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
            self::TYPESCRIPT => 'blue',
            self::SWIFT => 'orange',
            self::KOTLIN => 'purple',
            self::RUST => 'orange',
            self::DART => 'blue',
            self::SCALA => 'red',
            self::PERL => 'blue',
            self::HASKELL => 'purple',
            self::LUA => 'blue',
            self::R => 'blue',
            self::SHELL => 'green',
            self::SQL => 'gray',
            self::HTML => 'orange',
            self::CSS => 'blue',
            self::C => 'gray',
            self::CPP => 'blue',
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
            self::TYPESCRIPT => 'javascript',
            self::SWIFT => 'swift',
            self::KOTLIN => 'clike',
            self::RUST => 'rust',
            self::DART => 'dart',
            self::SCALA => 'clike',
            self::PERL => 'perl',
            self::HASKELL => 'haskell',
            self::LUA => 'lua',
            self::R => 'r',
            self::SHELL => 'shell',
            self::SQL => 'sql',
            self::HTML => 'htmlmixed',
            self::CSS => 'css',
            self::C => 'clike',
            self::CPP => 'clike',
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