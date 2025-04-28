<?php

use App\Tag;
use App\User;
use App\Snippet;
use Faker\Generator as Faker;

$factory->define(Snippet::class, function (Faker $faker) {
	$languageCodeMap = [
		'PHP' => [
			'<?php echo "Hello World"; ?>',
			'<?php $array = [1, 2, 3]; print_r($array); ?>',
			'<?php function add($a, $b) { return $a + $b; } ?>',
			'<?php class Example { public function test() { return "test"; } } ?>'
		],
		'JavaScript' => [
			'console.log("Hello World");',
			'const sum = (a, b) => a + b;',
			'const arr = [1, 2, 3].map(x => x * 2);',
			'class Example { test() { return "test"; } }'
		],
		'Python' => [
			'print("Hello World")',
			implode(PHP_EOL, ['def add(a, b):', '    return a + b']),
			'list(map(lambda x: x*2, [1, 2, 3]))',
			implode(PHP_EOL, ['class Example:', '    def test(self):', '        return "test"'])
		],
		'Java' => [
			'System.out.println("Hello World");',
			'public int add(int a, int b) { return a + b; }',
			'Arrays.stream(new int[]{1, 2, 3}).map(x -> x * 2).toArray();',
			implode(PHP_EOL, ['public class Example {', '    public String test() {', '        return "test";', '    }', '}'])
		],
		'C#' => [
			'Console.WriteLine("Hello World");',
			'public int Add(int a, int b) => a + b;',
			'new int[] {1, 2, 3}.Select(x => x * 2).ToArray();',
			implode(PHP_EOL, ['public class Example', '{', '    public string Test() => "test";', '}'])
		],
		'Ruby' => [
			'puts "Hello World"',
			'def add(a, b) a + b end',
			'[1, 2, 3].map { |x| x * 2 }',
			implode(PHP_EOL, ['class Example', '  def test', '    "test"', '  end', 'end'])
		],
		'Go' => [
			'fmt.Println("Hello World")',
			'func add(a int, b int) int { return a + b }',
			implode(PHP_EOL, ['numbers := []int{1, 2, 3}', 'for i := range numbers { numbers[i] *= 2 }']),
			implode(PHP_EOL, ['type Example struct{}', 'func (e Example) Test() string {', '    return "test"', '}'])
		]
	];

	$language = $faker->randomElement(array_keys($languageCodeMap));
	$code = $faker->randomElement($languageCodeMap[$language]);

	return [
		'user_id' => function () {
			return factory(User::class)->create()->id;
		},
		'title' => $faker->sentence,
		'code' => $code,
		'language' => $language,
		'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
	];
});
