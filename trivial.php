$cart = new Cart();
$cart->add('ab123');

$expected = [
    'ab123' => 1,
];

Assert::assertSame($expected, $cart->read());
